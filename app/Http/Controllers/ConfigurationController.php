<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ConfigurationController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function settings(): JsonResponse
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
