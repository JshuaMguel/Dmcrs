<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            try {
                // Delete old profile image if exists
                if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }
                
                // Store new profile image
                $imagePath = $request->file('profile_image')->store('profile_images', 'public');
                $data['profile_image'] = $imagePath;
                
                // Log successful upload
                \Illuminate\Support\Facades\Log::info('Profile image uploaded', [
                    'user_id' => $user->id,
                    'image_path' => $imagePath,
                    'file_exists' => Storage::disk('public')->exists($imagePath)
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Profile image upload failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                
                return Redirect::route('profile.edit')
                    ->with('error', 'Failed to upload profile image. Please try again.');
            }
        } else {
            // Preserve existing profile image if no new image is uploaded
            // Remove profile_image from data array to prevent overwriting
            unset($data['profile_image']);
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
