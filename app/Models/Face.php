<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Face extends Model
{
    protected $fillable = ['image_path', 'face_covered', 'eyes_visible', 'mouth_visible', 'confidence', 'frame_path'];
}
