<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LicenseVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LicenseController extends Controller
{
    protected LicenseVerificationService $verificationService;

    public function __construct(LicenseVerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    /**
     * Handle incoming license verification requests.
     */
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'purchase_code' => 'required|string|min:10',
            'product_id' => 'nullable|string',
            'domain' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'error' => 'Validation error.',
                'messages' => $validator->errors()
            ], 422);
        }

        $result = $this->verificationService->verifyLicense(
            $request->input('purchase_code'),
            $request->input('product_id'),
            $request->input('domain'),
            $request->ip()
        );

        if (!$result['valid']) {
            return response()->json($result, 400);
        }

        return response()->json($result, 200);
    }
}
