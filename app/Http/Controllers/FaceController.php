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
            Log::info('Received face detection request', [
                'has_image' => $request->hasFile('image'),
                'has_frame' => $request->hasFile('frame'),
                'face_covered' => $request->input('face_covered'),
                'eyes_visible' => $request->input('eyes_visible'),
                'mouth_visible' => $request->input('mouth_visible'),
                'confidence' => $request->input('confidence'),
            ]);

            $request->validate([
                'image' => 'required|image',
                'face_covered' => ['required', function (
                    $attribute, $value, $fail
                ) {
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

            $path = $request->file('image')->store('faces', 'public');
            $framePath = null;
            if ($request->hasFile('frame')) {
                $framePath = $request->file('frame')->store('frames', 'public');
                Log::info('Frame stored at: ' . $framePath);
            }

            Log::info('Image stored at: ' . $path);

            // Check last record for state change
            $lastFace = Face::latest()->first();
            $newState = filter_var($request->input('face_covered'), FILTER_VALIDATE_BOOLEAN);
            if ($lastFace && $lastFace->face_covered === $newState) {
                Log::info('No state change detected, skipping save.');
                return response()->json(['message' => 'No state change, not saved.'], 200);
            }

            $face = new Face([
                'image_path' => $path,
                'face_covered' => filter_var($request->input('face_covered'), FILTER_VALIDATE_BOOLEAN),
                'eyes_visible' => filter_var($request->input('eyes_visible'), FILTER_VALIDATE_BOOLEAN),
                'mouth_visible' => filter_var($request->input('mouth_visible'), FILTER_VALIDATE_BOOLEAN),
                'confidence' => $request->input('confidence'),
                'frame_path' => $framePath,
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
            Log::error('Error in face detection: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
