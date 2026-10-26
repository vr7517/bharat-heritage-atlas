<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Period;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PeriodController extends Controller
{
    /**
     * Display a listing of chronological periods and archaeological horizons.
     */
    public function index(Request $request): View
    {
        $query = Period::query()
            ->withCount(['heritageSites', 'objects']);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('dating_statement', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $periods = $query->orderBy('start_year', 'asc')->paginate(15)->withQueryString();

        $counts = [
            'total' => Period::count(),
        ];

        return view('admin.periods.index', compact('periods', 'counts'));
    }

    /**
     * Show the form for creating a new period record.
     */
    public function create(): View
    {
        return view('admin.periods.create');
    }

    /**
     * Store a newly created period in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:periods,slug'],
            'dating_statement' => ['required', 'string', 'max:255'],
            'start_year' => ['required', 'integer'],
            'end_year' => ['required', 'integer'],
            'start_era' => ['required', 'string', 'max:10'],
            'end_era' => ['required', 'string', 'max:10'],
            'description' => ['nullable', 'string'],
        ]);

        $slug = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);

        $baseSlug = $slug;
        $counter = 1;
        while (Period::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $period = Period::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'dating_statement' => $validated['dating_statement'],
            'start_year' => $validated['start_year'],
            'end_year' => $validated['end_year'],
            'start_era' => $validated['start_era'],
            'end_era' => $validated['end_era'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.periods.index')
            ->with('success', "Historical period '{$period->name}' registered successfully.");
    }

    /**
     * Show the form for editing the specified period.
     */
    public function edit(int $id): View
    {
        $period = Period::withCount(['heritageSites', 'objects'])->findOrFail($id);

        return view('admin.periods.edit', compact('period'));
    }

    /**
     * Update the specified period in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $period = Period::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:periods,slug,' . $id],
            'dating_statement' => ['required', 'string', 'max:255'],
            'start_year' => ['required', 'integer'],
            'end_year' => ['required', 'integer'],
            'start_era' => ['required', 'string', 'max:10'],
            'end_era' => ['required', 'string', 'max:10'],
            'description' => ['nullable', 'string'],
        ]);

        $period->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['slug']),
            'dating_statement' => $validated['dating_statement'],
            'start_year' => $validated['start_year'],
            'end_year' => $validated['end_year'],
            'start_era' => $validated['start_era'],
            'end_era' => $validated['end_era'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.periods.index')
            ->with('success', "Historical period '{$period->name}' updated successfully.");
    }

    /**
     * Remove the specified period from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $period = Period::findOrFail($id);
        $name = $period->name;
        $period->delete();

        return redirect()->route('admin.periods.index')
            ->with('success', "Historical period '{$name}' deleted successfully.");
    }
}
