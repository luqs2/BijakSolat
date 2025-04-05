<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\CloudinaryService;

class ProfileController extends Controller
{
    protected $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    public function edit(Request $request): Response
    {
        return Inertia::render('Tetapan', [
            'user' => Auth::user()
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
        ]);

        $request->user()->update($validated);

        return back();
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back();
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        try {
            Log::info('Starting avatar update process');

            $request->validate([
                'avatar' => 'required|image|max:5120'
            ]);

            $file = $request->file('avatar');
            if (!$file || !$file->isValid()) {
                throw new \Exception('Invalid file upload');
            }

            $user = auth()->user();
            if (!$user) {
                throw new \Exception('No authenticated user found');
            }

            Log::info('File validation passed', [
                'user_id' => $user->id,
                'file_name' => $file->getClientOriginalName()
            ]);

            $tempPath = $file->getPathname();
            $cloudinary = new CloudinaryService();

            $result = $cloudinary->upload($tempPath, [
                'folder' => 'bijak-solat/avatars',
                'public_id' => 'user_' . $user->id . '_' . time()
            ]);

            Log::info('Cloudinary upload successful', [
                'secure_url' => $result['secure_url'] ?? 'not set',
                'user_id' => $user->id
            ]);

            // Check if avatar is in fillable array
            Log::info('User model fillable attributes', [
                'fillable' => $user->getFillable()
            ]);

            // Update user avatar
            $user->update(['avatar' => $result['secure_url']]);

            return back()->with('success', 'Gambar profil berjaya dikemaskini.');
        } catch (\Exception $e) {
            Log::error('Avatar update failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id() ?? 'not set'
            ]);

            return back()->withErrors(['avatar' => 'Gagal mengemaskini gambar profil.']);
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
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
