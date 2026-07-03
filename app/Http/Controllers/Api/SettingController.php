<?php

namespace App\Http\Controllers\Api;

use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends Controller
{
    /**
     * Update a single setting (AJAX / API).
     */
    public function __invoke(Request $request, SettingsService $settings): JsonResponse
    {
        $validated = $request->validate([
            'key' => ['required', 'string'],
            'value' => ['required'],
        ]);

        try {
            $settings->set($validated['key'], $validated['value']);

            return response()->json([
                'success' => true,
                'message' => __('messages.updated'),
                'data' => [
                    'key' => $validated['key'],
                    'value' => $validated['value'],
                ],
                'errors' => null,
            ], Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => __('messages.update_failed'),
                'data' => null,
                'errors' => null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
