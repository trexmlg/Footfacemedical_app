<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.card', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'surname' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:32'],
        ]);

        // Normalizējam ievadi, lai datubāzē nesaglabātos HTML atzīmes.
        $request->user()->update([
            'name' => trim(strip_tags($data['name'])),
            'surname' => trim(strip_tags($data['surname'])),
            'phone' => trim($data['phone']),
        ]);

        return back()->with('status', __('messages.profile.updated'));
    }

    public function showForManager(User $user): View
    {
        return view('profile.show', ['user' => $user]);
    }
}
