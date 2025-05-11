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
            // ✅ Validate input
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
    
            // ✅ Upload images to R2
            $path = $request->file('image')->store('faces', 'r2');
            $framePath = $request->hasFile('frame') ? $request->file('frame')->store('frames', 'r2') : null;
    
            if (!$path) {
                Log::error('[UPLOAD FAILED] Image not stored to R2');
            } else {
                Log::info('[UPLOAD SUCCESS] Image stored at: ' . $path);
            }
    
            if ($framePath && !$framePath) {
                Log::error('[UPLOAD FAILED] Frame not stored to R2');
            } elseif ($framePath) {
                Log::info('[UPLOAD SUCCESS] Frame stored at: ' . $framePath);
            }
    
            // ✅ Construct public URLs
            $fullImageUrl = $path ? rtrim(config('filesystems.disks.r2.url'), '/') . '/' . ltrim($path, '/') : null;
            $fullFrameUrl = $framePath ? rtrim(config('filesystems.disks.r2.url'), '/') . '/' . ltrim($framePath, '/') : null;
    
            // ✅ Save face record with strict boolean conversion
            $face = new Face([
                'image_path' => $fullImageUrl,
                'frame_path' => $fullFrameUrl,
                'face_covered' => $request->input('face_covered') === 'true' || $request->input('face_covered') === true,
                'eyes_visible' => $request->input('eyes_visible') === 'true' || $request->input('eyes_visible') === true,
                'mouth_visible' => $request->input('mouth_visible') === 'true' || $request->input('mouth_visible') === true,
                'confidence' => $request->input('confidence'),
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
    
    {
        try {
            // ✅ Validate input
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
    
            // ✅ Upload images to R2
            $path = $request->file('image')->store('faces', 'r2');
            $framePath = $request->hasFile('frame') ? $request->file('frame')->store('frames', 'r2') : null;
    
            if (!$path) {
                Log::error('[UPLOAD FAILED] Image not stored to R2');
            } else {
                Log::info('[UPLOAD SUCCESS] Image stored at: ' . $path);
            }
    
            if ($framePath && !$framePath) {
                Log::error('[UPLOAD FAILED] Frame not stored to R2');
            } elseif ($framePath) {
                Log::info('[UPLOAD SUCCESS] Frame stored at: ' . $framePath);
            }
    
            // ✅ Construct public URLs
            $fullImageUrl = $path ? rtrim(config('filesystems.disks.r2.url'), '/') . '/' . ltrim($path, '/') : null;
            $fullFrameUrl = $framePath ? rtrim(config('filesystems.disks.r2.url'), '/') . '/' . ltrim($framePath, '/') : null;
    
            // ✅ Save face record with strict boolean conversion
            $face = new Face([
                'image_path' => $fullImageUrl,
                'frame_path' => $fullFrameUrl,
                'face_covered' => $request->input('face_covered') === 'true' || $request->input('face_covered') === true,
                'eyes_visible' => $request->input('eyes_visible') === 'true' || $request->input('eyes_visible') === true,
                'mouth_visible' => $request->input('mouth_visible') === 'true' || $request->input('mouth_visible') === true,
                'confidence' => $request->input('confidence'),
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
