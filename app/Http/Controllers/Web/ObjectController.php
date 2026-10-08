<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\HeritageObject;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ObjectController extends Controller
{
    /**
     * Display a paginated listing of verified physical heritage objects and sculptures.
     */
    public function index(Request $request): View
    {
        $query = HeritageObject::query()
            ->with(['heritageSite', 'period', 'dynasty'])
            ->withCount(['inscriptions', 'claims'])
            ->where('is_published', true);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('object_type', 'like', "%{$search}%")
                    ->orWhere('material', 'like', "%{$search}%")
                    ->orWhere('current_repository', 'like', "%{$search}%");
            });
        }

        if ($objectType = $request->query('object_type')) {
            $query->where('object_type', 'like', "%{$objectType}%");
        }

        $objects = $query->orderBy('start_year', 'asc')->paginate(12)->withQueryString();

        $objectTypes = HeritageObject::where('is_published', true)
            ->distinct()
            ->orderBy('object_type')
            ->pluck('object_type');

        return view('objects.index', compact('objects', 'objectTypes'));
    }

    /**
     * Display the detailed record of a specific heritage object.
     */
    public function show(string $slug): View
    {
        $object = HeritageObject::with([
            'heritageSite.location',
            'location',
            'period',
            'dynasty',
            'inscriptions',
            'claims.evidence.sources',
        ])
            ->where('is_published', true)
            ->where('slug', $slug)
            ->firstOrFail();

        return view('objects.show', compact('object'));
    }
}
