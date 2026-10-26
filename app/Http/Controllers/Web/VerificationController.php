<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Evidence;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Display a listing of verified claims categorized by consensus status.
     */
    public function index(Request $request): View
    {
        $query = Claim::query()->with([
            'claimable',
            'evidence.sources',
        ]);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('statement', 'like', "%{$search}%")
                    ->orWhere('summary_justification', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('consensus_status')) {
            $query->where('consensus_status', $status);
        }

        if ($claimType = $request->query('claim_type')) {
            $query->where('claim_type', $claimType);
        }

        $claims = $query->orderBy('id', 'asc')->paginate(12)->withQueryString();

        $totalClaims = Claim::count();
        $settledCount = Claim::whereIn('consensus_status', ['SETTLED_CONSENSUS', 'STRONG_CONSENSUS'])->count();
        $debatedCount = Claim::where('consensus_status', 'ONGOING_DEBATE')->count();
        $totalEvidence = Evidence::count();

        $consensusStatuses = Claim::distinct()->orderBy('consensus_status')->pluck('consensus_status');
        $claimTypes = Claim::distinct()->orderBy('claim_type')->pluck('claim_type');

        return view('verification.index', compact(
            'claims',
            'totalClaims',
            'settledCount',
            'debatedCount',
            'totalEvidence',
            'consensusStatuses',
            'claimTypes'
        ));
    }

    /**
     * Display the detailed evidence breakdown and verification graph for a specific claim.
     */
    public function show(int $id): View
    {
        $claim = Claim::with([
            'claimable',
            'evidence.sources',
        ])->findOrFail($id);

        $supportingEvidence = $claim->evidence->filter(function ($ev) {
            return $ev->pivot->relationship_type === 'SUPPORTS';
        });

        $divergentEvidence = $claim->evidence->filter(function ($ev) {
            return $ev->pivot->relationship_type !== 'SUPPORTS';
        });

        return view('verification.show', compact('claim', 'supportingEvidence', 'divergentEvidence'));
    }
}
