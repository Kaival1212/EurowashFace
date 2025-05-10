<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaceController;
use App\Models\Device;


Route::post('/store-face', [FaceController::class, 'store']);

Route::post('/active'  , function(Request $request){

    $name = $request->header('X-DEVICE-NAME') ?? 'facepi-default';

    Device::updateOrCreate(
        ['name' => $name],
        ['last_seen' => now()]
    );

    return response()->json(['status' => 'ok']);

});
