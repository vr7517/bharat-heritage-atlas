<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\Source;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Stream full or filtered Heritage Sites as RFC 7946 GeoJSON FeatureCollection.
     */
    public function geojson(Request $request): StreamedResponse
    {
        $query = HeritageSite::query()
            ->with(['location', 'primaryPeriod', 'primaryDynasty'])
            ->where('is_published', true)
            ->whereNotNull('location_id')
            ->whereHas('location', function ($q) {
                $q->whereNotNull('latitude')->whereNotNull('longitude');
            });

        if ($period = $request->query('period')) {
            $query->whereHas('primaryPeriod', fn ($pq) => $pq->where('slug', $period));
        }

        if ($dynasty = $request->query('dynasty')) {
            $query->whereHas('primaryDynasty', fn ($dq) => $dq->where('slug', $dynasty));
        }

        if ($state = $request->query('state')) {
            $query->whereHas('location', fn ($lq) => $lq->where('state', 'like', "%{$state}%"));
        }

        $sites = $query->get();

        $features = $sites->map(function (HeritageSite $site) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        (float) $site->location->longitude,
                        (float) $site->location->latitude,
                    ],
                ],
                'properties' => [
                    'id' => $site->id,
                    'name' => $site->name,
                    'slug' => $site->slug,
                    'site_type' => $site->site_type,
                    'architectural_style' => $site->architectural_style,
                    'dating_statement' => $site->dating_statement,
                    'start_year' => $site->start_year,
                    'end_year' => $site->end_year,
                    'protection_status' => $site->protection_status,
                    'location' => [
                        'name' => $site->location->name,
                        'district' => $site->location->district,
                        'state' => $site->location->state,
                    ],
                    'period' => $site->primaryPeriod?->name,
                    'dynasty' => $site->primaryDynasty?->name,
                    'url' => url('/sites/' . $site->slug),
                ],
            ];
        });

        $geojson = [
            'type' => 'FeatureCollection',
            'crs' => [
                'type' => 'name',
                'properties' => [
                    'name' => 'urn:ogc:def:crs:OGC:1.3:CRS84',
                ],
            ],
            'metadata' => [
                'generator' => 'Bharat Heritage Atlas Spatial Export Engine v1',
                'timestamp' => now()->toIso8601String(),
                'feature_count' => $features->count(),
                'license' => 'Government Open Data License - India (GODL) / CC BY 4.0',
            ],
            'features' => $features,
        ];

        return response()->stream(
            function () use ($geojson) {
                echo json_encode($geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            },
            200,
            [
                'Content-Type' => 'application/geo+json; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="bharat-heritage-sites-' . date('Ymd') . '.geojson"',
            ]
        );
    }

    /**
     * Stream peer-reviewed bibliographic sources in standard BibTeX (.bib) format.
     */
    public function bibtex(Request $request): StreamedResponse
    {
        $query = Source::query()->orderBy('authors')->orderBy('publication_year');

        if ($type = $request->query('type')) {
            $query->where('source_type', $type);
        }

        $sources = $query->get();

        return response()->stream(
            function () use ($sources) {
                echo "% Bharat Heritage Atlas — Archival Bibliographic Repository\n";
                echo "% Generated: " . now()->toIso8601String() . "\n";
                echo "% Total Records: " . $sources->count() . "\n\n";

                foreach ($sources as $source) {
                    $firstAuthor = explode(';', explode(',', $source->authors ?: 'Anon')[0])[0];
                    $cleanAuthor = preg_replace('/[^a-zA-Z]/', '', $firstAuthor) ?: 'BHA';
                    $key = strtoupper($cleanAuthor) . ($source->publication_year ?: $source->id);

                    $entryType = match ($source->source_type) {
                        'JOURNAL_ARTICLE' => 'article',
                        'ACADEMIC_MONOGRAPH', 'BOOK' => 'book',
                        'EXCAVATION_REPORT' => 'techreport',
                        'EPIGRAPHICAL_CORPUS' => 'incollection',
                        default => 'misc',
                    };

                    echo "@{$entryType}{{$key},\n";
                    echo "  title = {{" . addcslashes($source->title, '{}') . "}},\n";
                    if ($source->authors) {
                        echo "  author = {" . addcslashes($source->authors, '{}') . "},\n";
                    }
                    if ($source->publication_year) {
                        echo "  year = {" . $source->publication_year . "},\n";
                    }
                    if ($source->publisher) {
                        echo "  publisher = {" . addcslashes($source->publisher, '{}') . "},\n";
                    }
                    if ($source->volume_issue) {
                        echo "  volume = {" . addcslashes($source->volume_issue, '{}') . "},\n";
                    }
                    if ($source->pages) {
                        echo "  pages = {" . addcslashes($source->pages, '{}') . "},\n";
                    }
                    if ($source->url || $source->doi) {
                        echo "  url = {" . ($source->url ?: $source->doi) . "},\n";
                    }
                    if ($source->reliability_tier) {
                        echo "  note = {Reliability: " . $source->reliability_tier . "},\n";
                    }
                    echo "}\n\n";
                }
            },
            200,
            [
                'Content-Type' => 'application/x-bibtex; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="bharat-heritage-bibliography-' . date('Ymd') . '.bib"',
            ]
        );
    }

    /**
     * Stream tabular CSV export of published Heritage Sites.
     */
    public function sitesCsv(): StreamedResponse
    {
        $sites = HeritageSite::with(['location', 'primaryPeriod', 'primaryDynasty'])
            ->where('is_published', true)
            ->orderBy('name')
            ->get();

        return response()->stream(
            function () use ($sites) {
                $handle = fopen('php://output', 'w');
                // UTF-8 BOM for Excel compatibility
                fputs($handle, "\xEF\xBB\xBF");

                fputcsv($handle, [
                    'ID',
                    'Name',
                    'Slug',
                    'Site Type',
                    'Architectural Style',
                    'Dating Statement',
                    'Start Year',
                    'End Year',
                    'Protection Status',
                    'Location Name',
                    'District',
                    'State',
                    'Latitude',
                    'Longitude',
                    'Primary Period',
                    'Primary Dynasty',
                    'Public URL',
                ]);

                foreach ($sites as $site) {
                    fputcsv($handle, [
                        $site->id,
                        $site->name,
                        $site->slug,
                        $site->site_type,
                        $site->architectural_style,
                        $site->dating_statement,
                        $site->start_year,
                        $site->end_year,
                        $site->protection_status,
                        $site->location?->name,
                        $site->location?->district,
                        $site->location?->state,
                        $site->location?->latitude,
                        $site->location?->longitude,
                        $site->primaryPeriod?->name,
                        $site->primaryDynasty?->name,
                        url('/sites/' . $site->slug),
                    ]);
                }

                fclose($handle);
            },
            200,
            [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="bharat-heritage-sites-' . date('Ymd') . '.csv"',
            ]
        );
    }

    /**
     * Stream tabular CSV export of published Inscriptions.
     */
    public function inscriptionsCsv(): StreamedResponse
    {
        $inscriptions = Inscription::with(['heritageSite.primaryDynasty'])
            ->where('is_published', true)
            ->orderBy('title')
            ->get();

        return response()->stream(
            function () use ($inscriptions) {
                $handle = fopen('php://output', 'w');
                fputs($handle, "\xEF\xBB\xBF");

                fputcsv($handle, [
                    'ID',
                    'Title',
                    'Slug',
                    'Language',
                    'Script',
                    'Dating Statement',
                    'Associated Heritage Site',
                    'Associated Dynasty / Ruler',
                    'Epigraphic Reference',
                    'Translation Summary',
                    'Public URL',
                ]);

                foreach ($inscriptions as $insc) {
                    fputcsv($handle, [
                        $insc->id,
                        $insc->title,
                        $insc->slug,
                        $insc->language,
                        $insc->script,
                        $insc->dating_statement,
                        $insc->heritageSite?->name,
                        $insc->ruler_mentioned ?: $insc->heritageSite?->primaryDynasty?->name,
                        $insc->epigraphic_reference,
                        $insc->translation,
                        url('/inscriptions/' . $insc->slug),
                    ]);
                }

                fclose($handle);
            },
            200,
            [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="bharat-heritage-inscriptions-' . date('Ymd') . '.csv"',
            ]
        );
    }

    /**
     * Stream tabular CSV export of published Artifacts & Sculptures.
     */
    public function objectsCsv(): StreamedResponse
    {
        $objects = HeritageObject::with(['heritageSite', 'period', 'dynasty'])
            ->where('is_published', true)
            ->orderBy('name')
            ->get();

        return response()->stream(
            function () use ($objects) {
                $handle = fopen('php://output', 'w');
                fputs($handle, "\xEF\xBB\xBF");

                fputcsv($handle, [
                    'ID',
                    'Name',
                    'Slug',
                    'Object Type',
                    'Material',
                    'Dimensions',
                    'Dating Statement',
                    'Current Repository',
                    'Accession Number',
                    'Associated Site',
                    'Period',
                    'Dynasty',
                    'Public URL',
                ]);

                foreach ($objects as $obj) {
                    fputcsv($handle, [
                        $obj->id,
                        $obj->name,
                        $obj->slug,
                        $obj->object_type,
                        $obj->material,
                        $obj->dimensions,
                        $obj->dating_statement,
                        $obj->current_repository,
                        $obj->accession_number,
                        $obj->heritageSite?->name,
                        $obj->period?->name,
                        $obj->dynasty?->name,
                        url('/objects/' . $obj->slug),
                    ]);
                }

                fclose($handle);
            },
            200,
            [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="bharat-heritage-objects-' . date('Ymd') . '.csv"',
            ]
        );
    }
}
