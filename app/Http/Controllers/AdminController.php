<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Event;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'users' => User::orderBy('id')->get(),
            'deletedUsers' => User::onlyTrashed()->orderByDesc('deleted_at')->limit(50)->get(),
            'events' => Event::orderByDesc('start_at')->limit(50)->get(),
            'deletedEvents' => Event::onlyTrashed()->orderByDesc('deleted_at')->limit(50)->get(),
            'promotions' => Promotion::orderBy('sort_order')->orderByDesc('id')->get(),
            'deletedPromotions' => Promotion::onlyTrashed()->orderByDesc('deleted_at')->limit(50)->get(),
            'auditLogs' => AuditLog::with('admin:id,name,surname,email')->orderByDesc('id')->limit(80)->get(),
        ]);
    }

    public function updateUserRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', 'in:user,podolog,admin'],
        ]);

        $user->update($data);
        $this->logAdminAction($request, 'user.role.updated', 'user', $user->id, __('messages.admin.user_role_updated'), [
            'role' => $data['role'],
        ]);

        return back()->with('status', __('messages.admin.user_role_updated'));
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'surname' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'string', 'max:32'],
            'role' => ['required', 'in:user,podolog,admin'],
        ]);

        // Pirms saglabāšanas iztīrām teksta laukus no HTML atzīmēm.
        $user->update([
            'name' => trim(strip_tags($data['name'])),
            'surname' => trim(strip_tags($data['surname'])),
            'email' => trim(strtolower($data['email'])),
            'phone' => trim($data['phone']),
            'role' => $data['role'],
        ]);
        $this->logAdminAction($request, 'user.updated', 'user', $user->id, __('messages.admin.user_updated'), [
            'fields' => array_keys($data),
        ]);

        return back()->with('status', __('messages.admin.user_updated'));
    }

    public function deleteUser(Request $request, User $user): RedirectResponse
    {
        if ((int) $request->user()->id === (int) $user->id) {
            return back()->withErrors(['user' => __('messages.admin.cannot_delete_self')]);
        }

        $user->delete();
        $this->logAdminAction($request, 'user.deleted', 'user', $user->id, __('messages.admin.user_deleted'));

        return back()->with('status', __('messages.admin.user_deleted'));
    }

    public function restoreUser(Request $request, int $userId): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($userId);
        $user->restore();
        $this->logAdminAction($request, 'user.restored', 'user', $user->id, __('messages.admin.user_restored'));

        return back()->with('status', __('messages.admin.user_restored'));
    }

    public function updateReservation(Request $request, Event $event): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:booked,done,no_show,cancelled'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $event->update([
            'status' => $data['status'],
            'notes' => isset($data['notes']) ? trim(strip_tags((string) $data['notes'])) : null,
        ]);
        $this->logAdminAction($request, 'reservation.updated', 'event', $event->id, __('messages.admin.reservation_updated'), [
            'status' => $data['status'],
        ]);

        return back()->with('status', __('messages.admin.reservation_updated'));
    }

    public function deleteReservation(Event $event): RedirectResponse
    {
        $event->delete();
        $this->logAdminAction(request(), 'reservation.deleted', 'event', $event->id, __('messages.admin.reservation_deleted'));

        return back()->with('status', __('messages.admin.reservation_deleted'));
    }

    public function restoreReservation(Request $request, int $eventId): RedirectResponse
    {
        $event = Event::withTrashed()->findOrFail($eventId);
        $event->restore();
        $this->logAdminAction($request, 'reservation.restored', 'event', $event->id, __('messages.admin.reservation_restored'));

        return back()->with('status', __('messages.admin.reservation_restored'));
    }

    public function createPromotion(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'badge' => ['nullable', 'string', 'max:60'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        Promotion::create([
            'badge' => isset($data['badge']) ? trim(strip_tags((string) $data['badge'])) : null,
            'title' => trim(strip_tags($data['title'])),
            'description' => isset($data['description']) ? trim(strip_tags((string) $data['description'])) : null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
        $promotion = Promotion::latest('id')->first();
        $this->logAdminAction($request, 'promotion.created', 'promotion', $promotion?->id, __('messages.admin.promotion_created'));

        return back()->with('status', __('messages.admin.promotion_created'));
    }

    public function updatePromotion(Request $request, Promotion $promotion): RedirectResponse
    {
        $data = $request->validate([
            'badge' => ['nullable', 'string', 'max:60'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $promotion->update([
            'badge' => isset($data['badge']) ? trim(strip_tags((string) $data['badge'])) : null,
            'title' => trim(strip_tags($data['title'])),
            'description' => isset($data['description']) ? trim(strip_tags((string) $data['description'])) : null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
        $this->logAdminAction($request, 'promotion.updated', 'promotion', $promotion->id, __('messages.admin.promotion_updated'));

        return back()->with('status', __('messages.admin.promotion_updated'));
    }

    public function deletePromotion(Promotion $promotion): RedirectResponse
    {
        $promotion->delete();
        $this->logAdminAction(request(), 'promotion.deleted', 'promotion', $promotion->id, __('messages.admin.promotion_deleted'));

        return back()->with('status', __('messages.admin.promotion_deleted'));
    }

    public function restorePromotion(Request $request, int $promotionId): RedirectResponse
    {
        $promotion = Promotion::withTrashed()->findOrFail($promotionId);
        $promotion->restore();
        $this->logAdminAction($request, 'promotion.restored', 'promotion', $promotion->id, __('messages.admin.promotion_restored'));

        return back()->with('status', __('messages.admin.promotion_restored'));
    }

    private function logAdminAction(Request $request, string $action, string $targetType, ?int $targetId, string $description, array $context = []): void
    {
        AuditLog::create([
            'admin_user_id' => $request->user()?->id,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'description' => $description,
            'context' => $context,
        ]);
    }
}
