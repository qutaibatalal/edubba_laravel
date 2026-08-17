<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FacultyResource;
use App\Http\Resources\ParentResource;
use App\Http\Resources\StudentResource;
use App\Models\ApiUser;
use App\Models\Faculty;
use App\Models\ParentModel;
use App\Models\Student;
use App\Support\UploadPolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * POST /login
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = ApiUser::where('username', $request->username)->first();

        if (! $user || ! $user->active || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role,
                ],
            ],
        ]);
    }

    /**
     * POST /logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out',
        ]);
    }

    /**
     * GET /profile
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = [
            'id' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
        ];

        if ($user->role === ApiUser::ROLE_STUDENT && $user->student) {
            $data['student'] = new StudentResource($user->student->load('batch', 'program', 'academicYear'));
        } elseif ($user->role === ApiUser::ROLE_PARENT && $user->parent) {
            $user->parent->load(['children' => fn ($q) => $q->with('batch')]);
            $data['parent'] = new ParentResource($user->parent);
        } elseif ($user->role === ApiUser::ROLE_FACULTY && $user->faculty) {
            $data['faculty'] = new FacultyResource($user->faculty->load('department'));
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    /**
     * POST /change-password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Current password is incorrect',
            ], 422);
        }

        $user->password = $request->new_password;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password changed successfully',
        ]);
    }

    /**
     * POST /upload-photo
     * Uploads a profile photo (jpg/png) and generates a 150x150 thumbnail.
     */
    public function uploadPhoto(Request $request): JsonResponse
    {
        $request->validate([
            'photo' => ['required', 'file'],
        ]);

        UploadPolicy::validate($request->file('photo'), 'image');

        $user = $request->user();
        $photo = $request->file('photo');

        $name = Str::random(24).'.'.$photo->getClientOriginalExtension();
        $path = 'photos/'.$name;
        $thumbName = 'thumbs/'.pathinfo($name, PATHINFO_FILENAME).'_thumb.jpg';
        $stored = $photo->storeAs('photos', $name, 'public');

        if (! $stored) {
            return response()->json(['status' => 'error', 'message' => 'Upload failed'], 500);
        }

        $this->makeThumbnail($photo->getRealPath(), storage_path('app/public/'.$thumbName));

        $model = match ($user->role) {
            ApiUser::ROLE_STUDENT => $user->student,
            ApiUser::ROLE_FACULTY => $user->faculty,
            ApiUser::ROLE_PARENT => $user->parent,
            default => null,
        };

        $url = asset('storage/'.$stored);

        if ($model) {
            if ($model instanceof Student || $model instanceof ParentModel) {
                $model->photo = $url;
            } elseif ($model instanceof Faculty) {
                $model->photo = $url;
            }
            $model->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Photo uploaded',
            'data' => ['photo_url' => $url],
        ]);
    }

    protected function makeThumbnail(string $sourcePath, string $thumbPath): void
    {
        $dir = dirname($thumbPath);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        [$width, $height, $type] = @getimagesize($sourcePath);
        if (! $width || ! $height) {
            return;
        }

        $src = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => imagecreatefrompng($sourcePath),
            default => null,
        };

        if (! $src) {
            return;
        }

        $size = min($width, $height);
        $offsetX = (int) (($width - $size) / 2);
        $offsetY = (int) (($height - $size) / 2);

        $thumb = imagecreatetruecolor(150, 150);
        imagecopyresampled($thumb, $src, 0, 0, $offsetX, $offsetY, 150, 150, $size, $size);
        imagejpeg($thumb, $thumbPath, 85);
        imagedestroy($src);
        imagedestroy($thumb);
    }
}
