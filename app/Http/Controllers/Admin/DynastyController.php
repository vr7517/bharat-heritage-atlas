<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dynasty;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DynastyController extends Controller
{
    /**
     * Display a listing of dynastic realms and imperial houses.
     */
    public function index(Request $request): View
    {
        $query = Dynasty::query()
            ->withCount(['heritageSites', 'objects']);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('region', 'like', "%{$search}%")
                    ->orWhere('capital', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $dynasties = $query->orderBy('start_year', 'asc')->paginate(15)->withQueryString();

        $counts = [
            'total' => Dynasty::count(),
        ];

        return view('admin.dynasties.index', compact('dynasties', 'counts'));
    }

    /**
     * Show the form for creating a new dynasty record.
     */
    public function create(): View
    {
        return view('admin.dynasties.create');
    }

    /**
     * Store a newly created dynasty in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:dynasties,slug'],
            'dating_statement' => ['required', 'string', 'max:255'],
            'start_year' => ['nullable', 'integer'],
            'end_year' => ['nullable', 'integer'],
            'region' => ['nullable', 'string', 'max:255'],
            'capital' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $slug = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);

        $baseSlug = $slug;
        $counter = 1;
        while (Dynasty::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        $dynasty = Dynasty::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'dating_statement' => $validated['dating_statement'],
            'start_year' => $validated['start_year'] ?? null,
            'end_year' => $validated['end_year'] ?? null,
            'region' => $validated['region'] ?? null,
            'capital' => $validated['capital'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.dynasties.index')
            ->with('success', "Dynasty '{$dynasty->name}' registered successfully.");
    }

    /**
     * Show the form for editing the specified dynasty.
     */
    public function edit(int $id): View
    {
        $dynasty = Dynasty::withCount(['heritageSites', 'objects'])->findOrFail($id);

        return view('admin.dynasties.edit', compact('dynasty'));
    }

    /**
     * Update the specified dynasty in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $dynasty = Dynasty::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:dynasties,slug,' . $id],
            'dating_statement' => ['required', 'string', 'max:255'],
            'start_year' => ['nullable', 'integer'],
            'end_year' => ['nullable', 'integer'],
            'region' => ['nullable', 'string', 'max:255'],
            'capital' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $dynasty->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['slug']),
            'dating_statement' => $validated['dating_statement'],
            'start_year' => $validated['start_year'] ?? null,
            'end_year' => $validated['end_year'] ?? null,
            'region' => $validated['region'] ?? null,
            'capital' => $validated['capital'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.dynasties.index')
            ->with('success', "Dynasty '{$dynasty->name}' updated successfully.");
    }

    /**
     * Remove the specified dynasty from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $dynasty = Dynasty::findOrFail($id);
        $name = $dynasty->name;
        $dynasty->delete();

        return redirect()->route('admin.dynasties.index')
            ->with('success', "Dynasty '{$name}' deleted successfully.");
    }
}
