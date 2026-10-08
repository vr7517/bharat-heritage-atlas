<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ClaimResource;
use App\Http\Resources\V1\EvidenceResource;
use App\Models\Claim;
use App\Models\Evidence;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
    /**
     * Display a single claim with its complete evidence graph and verification breakdown.
     */
    public function showClaim(int $id): JsonResponse
    {
        $claim = Claim::with([
            'claimable',
            'evidence.sources',
        ])->find($id);

        if (! $claim) {
            return response()->json([
                'success' => false,
                'message' => "Historical claim with ID {$id} not found.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ClaimResource($claim),
        ]);
    }

    /**
     * Display a single evidence node with methodology, classification, and primary citations.
     */
    public function showEvidence(int $id): JsonResponse
    {
        $evidence = Evidence::with([
            'claims.claimable',
            'sources',
        ])->find($id);

        if (! $evidence) {
            return response()->json([
                'success' => false,
                'message' => "Evidence record with ID {$id} not found.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new EvidenceResource($evidence),
        ]);
    }
}
