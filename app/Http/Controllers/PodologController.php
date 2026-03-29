<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PodologController extends Controller
{
    public function dashboard(Request $request): View
    {
        return view('fullcalendar', [
            'canManage' => true,
            'pageHeading' => __('messages.podolog.calendar_title'),
            'pageText' => __('messages.podolog.calendar_text'),
            'isManagementCalendar' => true,
        ]);
    }

    public function updateReservation(Request $request, Event $event): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:booked,done,no_show,cancelled'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $event->update($data);

        return back()->with('status', __('messages.podolog.updated'));
    }
}
