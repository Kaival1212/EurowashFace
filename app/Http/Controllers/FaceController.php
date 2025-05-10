<?php

namespace App\Http\Controllers;

use App\Models\Face;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FaceController extends Controller
{
    public function store(Request $request)
    {
        try {
            // ✅ Validate incoming data
            $request->validate([
                'image' => 'required|image',
                'face_covered' => ['required', function ($attribute, $value, $fail) {
                    if (!in_array($value, ['true', 'false', true, false], true)) {
                        $fail('The face covered field must be true or false.');
                    }
                }],
                'eyes_visible' => ['nullable', function ($attribute, $value, $fail) {
                    if (!in_array($value, ['true', 'false', true, false, null], true)) {
                        $fail('The eyes visible field must be true or false.');
                    }
                }],
                'mouth_visible' => ['nullable', function ($attribute, $value, $fail) {
                    if (!in_array($value, ['true', 'false', true, false, null], true)) {
                        $fail('The mouth visible field must be true or false.');
                    }
                }],
                'confidence' => 'nullable|numeric',
                'frame' => 'nullable|image',
            ]);

            $path = null;
            $framePath = null;

            // ✅ Try to upload image to R2
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('faces', 'r2');
                if (!$path) {
                    Log::error('[UPLOAD FAILED] Image not stored to R2');
                } else {
                    Log::info('[UPLOAD SUCCESS] Image stored at: ' . $path);
                }
            }

            if ($request->hasFile('frame')) {
                $framePath = $request->file('frame')->store('frames', 'r2');
                if (!$framePath) {
                    Log::error('[UPLOAD FAILED] Frame not stored to R2');
                } else {
                    Log::info('[UPLOAD SUCCESS] Frame stored at: ' . $framePath);
                }
            }

            $lastFace = Face::latest()->first();
            $newState = filter_var($request->input('face_covered'), FILTER_VALIDATE_BOOLEAN);


            $fullImageUrl = $path ? rtrim(env('R2_URL'), '/') . '/' . ltrim($path, '/') : null;
            $fullFrameUrl = $framePath ? rtrim(env('R2_URL'), '/') . '/' . ltrim($framePath, '/') : null;
            
            // ✅ Save the Face record
            $face = new Face([
                'image_path' =>  $fullImageUrl,
                'face_covered' => $newState,
                'eyes_visible' => filter_var($request->input('eyes_visible'), FILTER_VALIDATE_BOOLEAN),
                'mouth_visible' => filter_var($request->input('mouth_visible'), FILTER_VALIDATE_BOOLEAN),
                'confidence' => $request->input('confidence'),
                'frame_path' => $fullFrameUrl,
            ]);

            $face->save();

            Log::info('Face record saved', [
                'id' => $face->id,
                'path' => $face->image_path,
                'frame_path' => $face->frame_path,
                'face_covered' => $face->face_covered,
                'eyes_visible' => $face->eyes_visible,
                'mouth_visible' => $face->mouth_visible,
                'confidence' => $face->confidence,
            ]);

            return response()->json(['message' => 'Saved', 'id' => $face->id], 200);

        } catch (\Exception $e) {
            Log::error('[EXCEPTION] Error in face detection upload: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
