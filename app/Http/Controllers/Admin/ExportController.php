<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Dataset;
use App\Models\Dynasty;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\Period;
use App\Models\Source;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Display the Admin Data Export Center.
     */
    public function index(): View
    {
        $entities = [
            'sites' => [
                'name' => 'Heritage Sites & Monuments',
                'description' => 'Architectural monuments, rock-cut shrines, archaeological mounds, and sacred sites.',
                'count' => HeritageSite::count(),
                'published' => HeritageSite::where('is_published', true)->count(),
                'icon' => '🏛️',
            ],
            'objects' => [
                'name' => 'Artifacts & Relics',
                'description' => 'Sculptures, coins, copper plates, terracotta figures, and museum relics.',
                'count' => HeritageObject::count(),
                'published' => HeritageObject::where('is_published', true)->count(),
                'icon' => '🏺',
            ],
            'inscriptions' => [
                'name' => 'Epigraphical Corpus',
                'description' => 'Pillar edicts, rock inscriptions, copper-plate grants, and Brahmi facsimiles.',
                'count' => Inscription::count(),
                'published' => Inscription::where('is_published', true)->count(),
                'icon' => '📜',
            ],
            'dynasties' => [
                'name' => 'Dynasties & Imperial Houses',
                'description' => 'Royal houses, regnal horizons, capitals, and imperial lineages.',
                'count' => Dynasty::count(),
                'published' => Dynasty::count(),
                'icon' => '👑',
            ],
            'periods' => [
                'name' => 'Historical Periods & Timeline',
                'description' => 'Calibrated chronological epochs and stratigraphical horizons.',
                'count' => Period::count(),
                'published' => Period::count(),
                'icon' => '⏳',
            ],
            'sources' => [
                'name' => 'Archival Sources & Bibliography',
                'description' => 'ASI publications, Epigraphia Indica volumes, monographs, and academic papers.',
                'count' => Source::count(),
                'published' => Source::count(),
                'icon' => '📚',
            ],
            'datasets' => [
                'name' => 'Open Research Datasets',
                'description' => 'GeoJSON coordinate archives, tabular CSVs, and epigraphical packages.',
                'count' => Dataset::count(),
                'published' => Dataset::where('is_published', true)->count(),
                'icon' => '📊',
            ],
            'claims' => [
                'name' => 'Evidentiary Claims Network',
                'description' => 'Peer-reviewed assertions, consensus ratings, and linked primary evidence.',
                'count' => Claim::count(),
                'published' => Claim::count(),
                'icon' => '🛡️',
            ],
        ];

        return view('admin.exports.index', compact('entities'));
    }

    /**
     * Download structured CSV or JSON table dump for curators.
     */
    public function download(Request $request, string $entity, string $format): StreamedResponse
    {
        $validEntities = ['sites', 'objects', 'inscriptions', 'dynasties', 'periods', 'sources', 'datasets', 'claims'];
        if (! in_array($entity, $validEntities)) {
            abort(404, "Unknown export entity '{$entity}'.");
        }

        $format = strtolower($format);
        if (! in_array($format, ['csv', 'json'])) {
            abort(400, "Unsupported export format '{$format}'. Choose 'csv' or 'json'.");
        }

        $data = match ($entity) {
            'sites' => HeritageSite::with(['location', 'primaryPeriod', 'primaryDynasty'])->get(),
            'objects' => HeritageObject::with(['heritageSite', 'period', 'dynasty'])->get(),
            'inscriptions' => Inscription::with(['heritageSite', 'dynasty'])->get(),
            'dynasties' => Dynasty::with('period')->get(),
            'periods' => Period::get(),
            'sources' => Source::get(),
            'datasets' => Dataset::get(),
            'claims' => Claim::with(['claimable', 'evidence.sources'])->get(),
        };

        $timestamp = date('Ymd_His');
        $filename = "bha_{$entity}_export_{$timestamp}.{$format}";

        if ($format === 'json') {
            return response()->stream(
                function () use ($data, $entity) {
                    echo json_encode([
                        'export_entity' => $entity,
                        'record_count' => $data->count(),
                        'generated_at' => now()->toIso8601String(),
                        'records' => $data,
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                },
                200,
                [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                ]
            );
        }

        // CSV generation
        return response()->stream(
            function () use ($data) {
                $handle = fopen('php://output', 'w');
                fputs($handle, "\xEF\xBB\xBF");

                if ($data->isNotEmpty()) {
                    $firstRow = $data->first()->toArray();
                    fputcsv($handle, array_keys($this->flattenArray($firstRow)));

                    foreach ($data as $item) {
                        fputcsv($handle, array_values($this->flattenArray($item->toArray())));
                    }
                }

                fclose($handle);
            },
            200,
            [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]
        );
    }

    /**
     * Flatten nested array values for tabular CSV representation.
     */
    protected function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix === '' ? $key : "{$prefix}_{$key}";
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey));
            } else {
                $result[$newKey] = is_bool($value) ? ($value ? '1' : '0') : $value;
            }
        }
        return $result;
    }
}
