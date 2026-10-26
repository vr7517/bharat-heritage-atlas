<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Evidence;
use App\Models\HeritageSite;
use App\Models\Source;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    /**
     * Store a newly created claim attached to a heritage entity.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'claimable_type' => ['required', 'string'],
            'claimable_id' => ['required', 'integer'],
            'claim_type' => ['required', 'string', 'max:50'],
            'statement' => ['required', 'string'],
            'consensus_status' => ['required', 'string', 'max:50'],
            'summary_justification' => ['nullable', 'string'],
        ]);

        $modelClass = match ($validated['claimable_type']) {
            'site', HeritageSite::class => HeritageSite::class,
            default => abort(400, 'Invalid entity type for claim attachment.'),
        };

        $entity = $modelClass::findOrFail($validated['claimable_id']);

        $entity->claims()->create([
            'claim_type' => $validated['claim_type'],
            'statement' => $validated['statement'],
            'consensus_status' => $validated['consensus_status'],
            'summary_justification' => $validated['summary_justification'] ?? null,
        ]);

        return back()->with('success', 'Scholarly claim added to heritage record.');
    }

    /**
     * Update an existing claim.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $claim = Claim::findOrFail($id);

        $validated = $request->validate([
            'claim_type' => ['required', 'string', 'max:50'],
            'statement' => ['required', 'string'],
            'consensus_status' => ['required', 'string', 'max:50'],
            'summary_justification' => ['nullable', 'string'],
        ]);

        $claim->update($validated);

        return back()->with('success', 'Scholarly claim updated successfully.');
    }

    /**
     * Remove a claim and its evidentiary graph associations.
     */
    public function destroy(int $id): RedirectResponse
    {
        $claim = Claim::findOrFail($id);
        $claim->evidence()->detach();
        $claim->delete();

        return back()->with('success', 'Scholarly claim removed from record.');
    }

    /**
     * Attach an evidence node (existing or newly created) to a claim.
     */
    public function attachEvidence(Request $request, int $claimId): RedirectResponse
    {
        $claim = Claim::findOrFail($claimId);

        $request->validate([
            'evidence_mode' => ['required', 'in:existing,new'],
            'relationship_type' => ['required', 'string', 'max:50'],
            'scholarly_weight' => ['required', 'string', 'max:50'],
            'analysis_notes' => ['nullable', 'string'],

            // Existing evidence
            'existing_evidence_id' => ['required_if:evidence_mode,existing', 'nullable', 'exists:evidence,id'],

            // New evidence
            'new_evidence_title' => ['required_if:evidence_mode,new', 'nullable', 'string', 'max:255'],
            'new_evidence_type' => ['required_if:evidence_mode,new', 'nullable', 'string', 'max:50'],
            'new_classification' => ['required_if:evidence_mode,new', 'nullable', 'string', 'max:50'],
            'new_description' => ['required_if:evidence_mode,new', 'nullable', 'string'],
            'new_stratigraphic_context' => ['nullable', 'string', 'max:255'],
            'new_methodology_applied' => ['nullable', 'string', 'max:255'],
            'new_uncertainty_notes' => ['nullable', 'string'],

            // Optional source linking
            'source_id' => ['nullable', 'exists:sources,id'],
            'specific_pages' => ['nullable', 'string', 'max:150'],
            'direct_quotation_or_data' => ['nullable', 'string'],
            'citation_context' => ['nullable', 'string'],
        ]);

        if ($request->input('evidence_mode') === 'new') {
            $evidence = Evidence::create([
                'title' => $request->input('new_evidence_title'),
                'evidence_type' => $request->input('new_evidence_type'),
                'classification' => $request->input('new_classification', 'STRONG_EVIDENCE'),
                'description' => $request->input('new_description'),
                'stratigraphic_context' => $request->input('new_stratigraphic_context'),
                'methodology_applied' => $request->input('new_methodology_applied'),
                'uncertainty_notes' => $request->input('new_uncertainty_notes'),
            ]);
        } else {
            $evidence = Evidence::findOrFail($request->input('existing_evidence_id'));
        }

        // Link Evidence to Claim
        $claim->evidence()->syncWithoutDetaching([
            $evidence->id => [
                'relationship_type' => $request->input('relationship_type', 'SUPPORTS'),
                'scholarly_weight' => $request->input('scholarly_weight', 'PRIMARY'),
                'analysis_notes' => $request->input('analysis_notes'),
            ],
        ]);

        // Link Source to Evidence if supplied
        if ($sourceId = $request->input('source_id')) {
            $evidence->sources()->syncWithoutDetaching([
                $sourceId => [
                    'specific_pages' => $request->input('specific_pages'),
                    'direct_quotation_or_data' => $request->input('direct_quotation_or_data'),
                    'citation_context' => $request->input('citation_context'),
                ],
            ]);
        }

        return back()->with('success', "Evidence node '{$evidence->title}' attached to claim successfully.");
    }

    /**
     * Detach an evidence node from a claim.
     */
    public function detachEvidence(int $claimId, int $evidenceId): RedirectResponse
    {
        $claim = Claim::findOrFail($claimId);
        $claim->evidence()->detach($evidenceId);

        return back()->with('success', 'Evidence node detached from claim.');
    }
}
