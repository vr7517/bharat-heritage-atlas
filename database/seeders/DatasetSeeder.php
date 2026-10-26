<?php

namespace Database\Seeders;

use App\Models\Dataset;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatasetSeeder extends Seeder
{
    /**
     * Run the dataset seeds.
     */
    public function run(): void
    {
        $storageDir = storage_path('app/public/datasets');
        if (! File::exists($storageDir)) {
            File::makeDirectory($storageDir, 0755, true);
        }

        // 1. ASI Centrally Protected Monuments GeoJSON
        $geojsonPath = 'datasets/asi_centrally_protected_monuments.geojson';
        $geojsonData = [
            'type' => 'FeatureCollection',
            'crs' => [
                'type' => 'name',
                'properties' => ['name' => 'urn:ogc:def:crs:OGC:1.3:CRS84'],
            ],
            'features' => [
                [
                    'type' => 'Feature',
                    'geometry' => ['type' => 'Point', 'coordinates' => [77.7397, 23.4794]],
                    'properties' => [
                        'name' => 'Sanchi Buddhist Monuments & Stupas',
                        'state' => 'Madhya Pradesh',
                        'district' => 'Raisen',
                        'asi_circle' => 'Bhopal Circle',
                        'protection_status' => 'Centrally Protected ASI Monument',
                    ],
                ],
                [
                    'type' => 'Feature',
                    'geometry' => ['type' => 'Point', 'coordinates' => [77.7958, 23.5350]],
                    'properties' => [
                        'name' => 'Heliodorus Garuda Pillar',
                        'state' => 'Madhya Pradesh',
                        'district' => 'Vidisha',
                        'asi_circle' => 'Bhopal Circle',
                        'protection_status' => 'Centrally Protected ASI Monument',
                    ],
                ],
                [
                    'type' => 'Feature',
                    'geometry' => ['type' => 'Point', 'coordinates' => [79.5878, 13.5855]],
                    'properties' => [
                        'name' => 'Parasurameswara Temple & Linga',
                        'state' => 'Andhra Pradesh',
                        'district' => 'Tirupati',
                        'asi_circle' => 'Hyderabad Circle',
                        'protection_status' => 'Centrally Protected ASI Monument',
                    ],
                ],
            ],
        ];

        File::put(storage_path('app/public/' . $geojsonPath), json_encode($geojsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        Dataset::updateOrCreate(
            ['slug' => 'asi-centrally-protected-monuments-geojson'],
            [
                'name' => 'Archaeological Survey of India Centrally Protected Monuments Spatial Gazetteer',
                'source_organization' => 'Archaeological Survey of India (ASI)',
                'source_url' => 'https://asi.nic.in/open-data',
                'dataset_type' => 'GEOJSON',
                'version' => '2026.1',
                'release_date' => '2026-01-15',
                'license' => 'Government Open Data License - India (GODL)',
                'record_count' => 3693,
                'file_path' => $geojsonPath,
                'file_name' => 'asi_centrally_protected_monuments.geojson',
                'file_size_bytes' => filesize(storage_path('app/public/' . $geojsonPath)),
                'file_mime_type' => 'application/geo+json',
                'description' => 'Comprehensive spatial GIS coordinate dataset of Centrally Protected Monuments of India, including stupa complexes, cave temples, monolithic pillars, and fortresses under national protection.',
                'notes' => 'Coordinate reference system: EPSG:4326 (WGS84). Standardized geographic points with ASI Circle and district designations.',
                'is_published' => true,
                'is_featured' => true,
            ]
        );

        // 2. Epigraphia Indica Transcriptions CSV
        $csvPath = 'datasets/epigraphia_indica_corpus.csv';
        $csvContent = "\xEF\xBB\xBF" .
            "ID,Title,Language,Script,Dating,Epigraphic_Reference,Associated_Site,Translation_Summary\n" .
            "1,\"Heliodorus Garuda Pillar Edict\",\"Prakrit\",\"Brahmi\",\"c. 113 BCE\",\"Epigraphia Indica Vol. II, p. 87\",\"Besnagar, Vidisha\",\"Commemorates the erection of the Garuda-pillar in honour of Devadeva Vasudeva by Heliodorus, ambassador of king Antialkidas.\"\n" .
            "2,\"Sanchi Ashokan Schism Pillar Edict\",\"Prakrit\",\"Ashokan Brahmi\",\"c. 250 BCE\",\"CII Vol. I, Inscriptions of Asoka, pp. 160-161\",\"Sanchi Stupa 1\",\"Injunction by Devanampriya forbidding schism in the Buddhist Sangha and ordering monks and nuns to remain united.\"\n" .
            "3,\"Gudimallam Foundation Inscription of Vijayaditya II\",\"Tamil-Grantha\",\"Grantha\",\"c. 898 CE\",\"Epigraphia Indica Vol. XI, pp. 222-228\",\"Parasurameswara Temple, Gudimallam\",\"Bana dynasty donation recording perpetual lamp endowments for the shrine of Parasurameswara.\"\n";

        File::put(storage_path('app/public/' . $csvPath), $csvContent);

        Dataset::updateOrCreate(
            ['slug' => 'epigraphia-indica-diplomatic-transcriptions'],
            [
                'name' => 'Corpus Inscriptionum Indicarum (CII) Diplomatic Transcriptions & Translations',
                'source_organization' => 'Epigraphia Indica Digital Archive & BHA Epigraphy Cell',
                'source_url' => 'https://epigraphia.indica.in/corpus',
                'dataset_type' => 'CSV',
                'version' => '1.2',
                'release_date' => '2025-11-20',
                'license' => 'Open Data Commons Open Database License (ODbL)',
                'record_count' => 840,
                'file_path' => $csvPath,
                'file_name' => 'epigraphia_indica_corpus.csv',
                'file_size_bytes' => filesize(storage_path('app/public/' . $csvPath)),
                'file_mime_type' => 'text/csv',
                'description' => 'Diplomatic Brahmi transliterations, palaeographical commentary, and English academic translations of ancient Indian royal inscriptions from CII volumes.',
                'notes' => 'Transliterated using ISO 15919 Indic romanization standard. Includes dating calculations and cross-referenced historical rulers.',
                'is_published' => true,
                'is_featured' => true,
            ]
        );

        // 3. Sanchi Archaeological Environs Stratigraphy JSON
        $jsonPath = 'datasets/sanchi_survey_stratigraphy.json';
        $jsonData = [
            'project' => 'Sanchi Archaeological Project & Landscape Survey',
            'directors' => 'Dr. Julia Shaw; Archaeological Survey of India',
            'calibrated_dates' => [
                ['trench' => 'SG-1', 'depth_cm' => 120, 'c14_bp' => 2240, 'cal_bce' => '390–200 BCE', 'stratum' => 'Early Historic Horizon (Maurya Phase)'],
                ['trench' => 'SG-2', 'depth_cm' => 210, 'c14_bp' => 2410, 'cal_bce' => '540–400 BCE', 'stratum' => 'Pre-Mauryan Habitation Strata'],
                ['trench' => 'SG-3', 'depth_cm' => 60, 'c14_bp' => 1890, 'cal_ce' => '60–220 CE', 'stratum' => 'Kushana-Satavahana Phase'],
            ],
            'methodology' => 'AMS radiocarbon determinations calibrated against IntCal20 calibration curve; 2-sigma confidence intervals.',
        ];

        File::put(storage_path('app/public/' . $jsonPath), json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        Dataset::updateOrCreate(
            ['slug' => 'sanchi-landscape-survey-stratigraphy'],
            [
                'name' => 'Sanchi Environs Landscape Survey Stratigraphy & Radiocarbon Calibrations',
                'source_organization' => 'British Academy & Archaeological Survey of India',
                'source_url' => 'https://example.org/sanchi-landscape-project',
                'dataset_type' => 'STRATIGRAPHY_CHRONOLOGY',
                'version' => '2.0',
                'release_date' => '2026-02-10',
                'license' => 'Creative Commons Attribution-ShareAlike 4.0 (CC BY-SA 4.0)',
                'record_count' => 125,
                'file_path' => $jsonPath,
                'file_name' => 'sanchi_survey_stratigraphy.json',
                'file_size_bytes' => filesize(storage_path('app/public/' . $jsonPath)),
                'file_mime_type' => 'application/json',
                'description' => 'Calibrated AMS radiocarbon determination metrics, stratigraphical trench horizons, dam catchment surveys, and ceramic seriation data from the Sanchi archaeological hinterland.',
                'notes' => 'IntCal20 2-sigma calibration curve determinations with associated ceramic seriation matrices.',
                'is_published' => true,
                'is_featured' => false,
            ]
        );
    }
}
