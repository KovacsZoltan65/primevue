<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response AS InertiaResponse;
use App\Traits\Functions;
use App\Repositories\ProfileRepository;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProfileController extends Controller
{
    use AuthorizesRequests,
        Functions;

    protected ProfileRepository $profileRepository;

    public function __construct(ProfileRepository $repository)
    {
        $this->profileRepository = $repository;

        //$this->middleware('can:profiles list', ['only' => ['index', 'applySearch', 'getProfiles', 'getProfile', 'getProfileByName']]);
        //$this->middleware('can:profiles create', ['only' => ['createProfile']]);
        //$this->middleware('can:profiles edit', ['only' => ['updateProfile']]);
        //$this->middleware('can:profiles delete', ['only' => ['deleteProfile', 'deleteProfiles']]);
        //$this->middleware('can:profiles restore', ['only' => ['restoreProfile']]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): InertiaResponse
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->profileRepository->updateProfile($request->user(), $request->validated());

        /*
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();
        */
        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        //$user->delete();
        $this->profileRepository->deleteUserAccount($user);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
