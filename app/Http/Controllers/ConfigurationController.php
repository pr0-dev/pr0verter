<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function limits(): JsonResponse
    {
        return response()->json(config('pr0verter'));
    }

    /**
     * @return JsonResponse
     */
    public function disabledComponents(): JsonResponse
    {
        return response()->json(null);
    }
}
