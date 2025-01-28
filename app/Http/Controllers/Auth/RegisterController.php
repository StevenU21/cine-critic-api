<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(Request $request, ImageService $imageService): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:60'],
            'username' => ['required', 'string', 'min:3', 'max:20', Rule::unique('users')],
            'image' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg,webp'],
            'email' => ['required', 'string', 'email', 'max:20', Rule::unique('users')->ignore($request->user())],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->hasFile('image')) {
            $user->image = $imageService->storeImage($request->file('image'), $request->username, $user->id, 'profile_images');
            $user->save();
        }

        $user->assignRole('reviewer');

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
