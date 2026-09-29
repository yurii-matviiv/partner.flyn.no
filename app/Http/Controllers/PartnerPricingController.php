<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Same-origin proxy for the CRM's canonical B2B pricing endpoint.
 * The browser stores no tariff values or formula.
 */
class PartnerPricingController
{
    public function calculateB2bRegularCleaning(Request $request): JsonResponse
    {
        $input = $request->validate([
            'plan' => ['required', 'string', 'in:maintenance,standard,extended'],
            'area' => ['required', 'numeric', 'min:50', 'max:3000'],
            'weekdays' => ['required', 'array', 'min:1', 'max:7'],
            'weekdays.*' => ['required', 'string', 'distinct', 'in:mon,tue,wed,thu,fri,sat,sun'],
        ]);

        try {
            $response = Http::acceptJson()->timeout(10)->post(config('services.crm_pricing.url'), [
                'calculator' => 'b2b_regular_cleaning',
                'input' => $input,
            ]);
        } catch (ConnectionException) {
            return response()->json(['message' => __('Pricing is temporarily unavailable. Please try again shortly.')], 503);
        }

        return response()->json(
            $response->json() ?: ['message' => __('Pricing is temporarily unavailable. Please try again shortly.')],
            $response->status(),
        );
    }
}
