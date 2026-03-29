<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FullCalendarController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        if ($request->ajax()) {
            $range = $request->validate([
                'start' => ['required', 'date'],
                'end' => ['required', 'date', 'after:start'],
            ]);

            $canManage = $this->canManageCalendar($request);

            $data = Event::with($canManage ? ['user:id,name,surname,email,phone'] : [])
                ->where('status', '!=', 'cancelled')
                ->where('start_at', '<', $range['end'])
                ->where('end_at', '>', $range['start'])
                ->orderBy('start_at')
                ->get()
                ->map(fn (Event $event) => $this->serializeEvent($event, $canManage));

            return response()->json($data);
        }

        return view('fullcalendar', [
            'canManage' => $request->user()?->isPodolog() || $request->user()?->isAdmin(),
        ]);
    }

    public function ajax(Request $request): JsonResponse
    {
        switch ($request->type) {
            case 'add':
                $data = $request->validate([
                    'title' => ['required', 'string', 'max:190'],
                    'start' => ['required', 'date'],
                    'end' => ['required', 'date', 'after:start'],
                ]);

                if (! $this->isValidSlot($data['start'], $data['end'])) {
                    return response()->json(['message' => __('messages.calendar.slot_rule')], 422);
                }

                if ($this->hasConflict($data['start'], $data['end'])) {
                    return response()->json(['message' => __('messages.calendar.slot_taken')], 422);
                }

                $user = $request->user();
                $safeTitle = trim(strip_tags($data['title']));

                $event = Event::create([
                    'title' => $safeTitle,
                    'start' => substr($data['start'], 0, 10),
                    'end' => substr($data['end'], 0, 10),
                    'start_at' => $data['start'],
                    'end_at' => $data['end'],
                    'status' => 'booked',
                    'user_id' => $user->id,
                    'patient_name' => trim($user->name . ' ' . ($user->surname ?? '')),
                    'patient_phone' => $user->phone,
                ]);

                return response()->json($this->serializeEvent($event->load('user'), $this->canManageCalendar($request)));

            case 'update':
                if (! ($request->user()->isPodolog() || $request->user()->isAdmin())) {
                    return response()->json(['message' => __('messages.calendar.only_manage_update')], 403);
                }

                $event = Event::find($request->id);

                if (! $event) {
                    return response()->json(['message' => __('messages.calendar.event_not_found')], 404);
                }

                $data = $request->validate([
                    'title' => ['required', 'string', 'max:190'],
                    'start' => ['required', 'date'],
                    'end' => ['required', 'date', 'after:start'],
                    'status' => ['required', 'in:booked,done,no_show,cancelled'],
                    'notes' => ['nullable', 'string', 'max:1000'],
                ]);

                if (! $this->isValidSlot($data['start'], $data['end'])) {
                    return response()->json(['message' => __('messages.calendar.slot_rule')], 422);
                }

                $safeTitle = trim(strip_tags($data['title']));
                $safeNotes = isset($data['notes']) ? trim(strip_tags((string) $data['notes'])) : null;

                if ($data['status'] !== 'cancelled' && $this->hasConflict($data['start'], $data['end'], $event)) {
                    return response()->json(['message' => __('messages.calendar.slot_taken')], 422);
                }

                $event->update([
                    'title' => $safeTitle,
                    'start' => substr($data['start'], 0, 10),
                    'end' => substr($data['end'], 0, 10),
                    'start_at' => $data['start'],
                    'end_at' => $data['end'],
                    'status' => $data['status'],
                    'notes' => $safeNotes ?: null,
                ]);

                return response()->json($this->serializeEvent($event->fresh()->load('user'), true));

            case 'delete':
                if (! ($request->user()->isPodolog() || $request->user()->isAdmin())) {
                    return response()->json(['message' => __('messages.calendar.only_manage_delete')], 403);
                }

                $event = Event::find($request->id);

                if (! $event) {
                    return response()->json(['message' => __('messages.calendar.event_not_found')], 404);
                }

                $event->delete();

                return response()->json(['message' => __('messages.calendar.event_deleted')]);

            default:
                return response()->json(['message' => __('messages.calendar.invalid_action')], 422);
        }
    }

    private function isValidSlot(string $start, string $end): bool
    {
        try {
            $startDate = CarbonImmutable::parse($start);
            $endDate = CarbonImmutable::parse($end);
        } catch (\Exception) {
            return false;
        }

        if ($startDate->greaterThanOrEqualTo($endDate)) {
            return false;
        }

        if ((int) $startDate->format('i') !== 0 || (int) $endDate->format('i') !== 0) {
            return false;
        }

        $startHour = (int) $startDate->format('H');

        return $startHour >= 8 && $startHour <= 19 && $startDate->addHour()->equalTo($endDate);
    }

    private function hasConflict(string $start, string $end, ?Event $ignoreEvent = null): bool
    {
        $query = Event::query()
            ->where('status', '!=', 'cancelled')
            ->where('start_at', '<', $end)
            ->where('end_at', '>', $start);

        if ($ignoreEvent) {
            $query->whereKeyNot($ignoreEvent->getKey());
        }

        return $query->exists();
    }

    private function canManageCalendar(Request $request): bool
    {
        return $request->user()?->isPodolog() || $request->user()?->isAdmin();
    }

    private function serializeEvent(Event $event, bool $withDetails): array
    {
        $payload = [
            'id' => $event->id,
            'title' => $event->title,
            'start' => optional($event->start_at)->toIso8601String(),
            'end' => optional($event->end_at)->toIso8601String(),
            'slot_date' => optional($event->start_at)->format('Y-m-d'),
            'slot_time' => optional($event->start_at)->format('H:i'),
            'slot_end_time' => optional($event->end_at)->format('H:i'),
            'status' => $event->status,
        ];

        if (! $withDetails) {
            return $payload;
        }

        return $payload + [
            'notes' => $event->notes,
            'patient_name' => $event->patient_name,
            'patient_phone' => $event->patient_phone,
            'user' => $event->user ? [
                'id' => $event->user->id,
                'name' => $event->user->name,
                'surname' => $event->user->surname,
                'email' => $event->user->email,
                'phone' => $event->user->phone,
            ] : null,
        ];
    }
}
