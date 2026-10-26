<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class DeveloperController extends Controller
{
    /**
     * Display the Public REST API & Developer Documentation Hub.
     */
    public function index(): View
    {
        $endpoints = [
            [
                'group' => 'Geospatial & Spatial GIS',
                'method' => 'GET',
                'path' => '/api/v1/geo/sites',
                'description' => 'Returns all geolocated monuments and archaeological complexes as RFC 7946 GeoJSON.',
                'params' => ['period', 'dynasty', 'state'],
            ],
            [
                'group' => 'Academic Data Exports',
                'method' => 'GET',
                'path' => '/api/v1/export/geojson',
                'description' => 'Direct download stream of complete spatial gazetteer in GeoJSON format.',
                'params' => ['period', 'dynasty', 'state'],
            ],
            [
                'group' => 'Academic Data Exports',
                'method' => 'GET',
                'path' => '/api/v1/export/bibtex',
                'description' => 'Direct download stream of complete peer-reviewed bibliographic sources in standard BibTeX (.bib) format.',
                'params' => ['type'],
            ],
            [
                'group' => 'Academic Data Exports',
                'method' => 'GET',
                'path' => '/api/v1/export/sites.csv',
                'description' => 'Tabular CSV dump of all published heritage monuments and archaeological coordinates.',
                'params' => [],
            ],
            [
                'group' => 'Cross-Corpus Search',
                'method' => 'GET',
                'path' => '/api/v1/search',
                'description' => 'Unified keyword and faceted filter search across sites, objects, inscriptions, sources, datasets, and claims.',
                'params' => ['q', 'type', 'period', 'dynasty', 'state', 'consensus', 'limit'],
            ],
            [
                'group' => 'Open Datasets',
                'method' => 'GET',
                'path' => '/api/v1/datasets',
                'description' => 'Paginated directory of open archaeological datasets, GeoJSON archives, and epigraphical packages.',
                'params' => ['type', 'license', 'page'],
            ],
            [
                'group' => 'Heritage Monuments',
                'method' => 'GET',
                'path' => '/api/v1/sites',
                'description' => 'Directory of archaeological monuments with pagination and architectural classification.',
                'params' => ['type', 'period', 'state', 'page'],
            ],
            [
                'group' => 'Epigraphical Corpus',
                'method' => 'GET',
                'path' => '/api/v1/inscriptions',
                'description' => 'Diplomatic inscriptions, Brahmi transliterations, and historical translation summaries.',
                'params' => ['language', 'script', 'dynasty'],
            ],
            [
                'group' => 'Artifacts & Relics',
                'method' => 'GET',
                'path' => '/api/v1/objects',
                'description' => 'Museum artifacts, sculptures, and portable relics with repository accession numbers.',
                'params' => ['type', 'material', 'site_id'],
            ],
            [
                'group' => 'Chronological Timeline',
                'method' => 'GET',
                'path' => '/api/v1/timeline',
                'description' => 'Calibrated chronological stream linking periods, dynastic reigns, and monument foundations.',
                'params' => ['start_year', 'end_year', 'limit'],
            ],
        ];

        return view('developers.index', compact('endpoints'));
    }
}
