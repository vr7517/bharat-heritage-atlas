<?php

namespace Database\Seeders;

use App\Models\Claim;
use App\Models\Dynasty;
use App\Models\Evidence;
use App\Models\HeritageObject;
use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\Location;
use App\Models\Period;
use App\Models\Source;
use Illuminate\Database\Seeder;

class GudimallamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Location
        $location = Location::updateOrCreate(
            ['name' => 'Gudimallam'],
            [
                'historical_names' => ['Gudimallam', 'Tiru-vippirambedu'],
                'state' => 'Andhra Pradesh',
                'district' => 'Tirupati',
                'taluk_tehsil' => 'Yerpedu',
                'latitude' => 13.5822,
                'longitude' => 79.5847,
                'altitude_meters' => 110,
                'uncertainty_radius_meters' => 50,
                'description' => 'Village situated on the plains near the Suvarnamukhi river in Tirupati district, Andhra Pradesh, 13 km southeast of Renigunta.',
            ]
        );

        // 2. Period
        $period = Period::updateOrCreate(
            ['slug' => 'early-historic'],
            [
                'name' => 'Early Historic / Mauryan-Satavahana Transition',
                'dating_statement' => 'c. 3rd century BCE to 2nd century CE',
                'start_year' => -300,
                'end_year' => 200,
                'start_era' => 'BCE',
                'end_era' => 'CE',
                'description' => 'Formative phase of early historic stone architecture, sculpture, and epigraphy in peninsular India.',
            ]
        );

        // 3. Dynasties
        $satavahana = Dynasty::updateOrCreate(
            ['slug' => 'satavahana'],
            [
                'name' => 'Satavahana',
                'dating_statement' => 'c. 1st century BCE – 2nd century CE',
                'start_year' => -100,
                'end_year' => 200,
                'region' => 'Deccan and Coastal Andhra',
                'capital' => 'Pratishthana (Paithan), Amaravati (Dhanyakataka)',
                'description' => 'Ancient imperial dynasty ruling extensive regions of the Deccan and Andhra.',
            ]
        );

        $bana = Dynasty::updateOrCreate(
            ['slug' => 'bana'],
            [
                'name' => 'Bana',
                'dating_statement' => 'c. 4th – 9th century CE',
                'start_year' => 350,
                'end_year' => 900,
                'region' => 'Perumbanappadi (Rayalaseema / Northern Tamil Nadu)',
                'description' => 'Medieval feudatory dynasty associated with Shaiva patronage in Andhra-Tamil borderlands.',
            ]
        );

        $chola = Dynasty::updateOrCreate(
            ['slug' => 'chola'],
            [
                'name' => 'Chola',
                'dating_statement' => 'c. 9th – 13th century CE',
                'start_year' => 848,
                'end_year' => 1279,
                'region' => 'Tamil Nadu and Southern Andhra',
                'capital' => 'Thanjavur, Gangaikonda Cholapuram',
                'description' => 'Imperial maritime dynasty famed for monumental temple architecture in stone.',
            ]
        );

        // 4. Heritage Site
        $site = HeritageSite::updateOrCreate(
            ['slug' => 'parasurameshwara-temple-gudimallam'],
            [
                'name' => 'Parasurameshwara Temple Complex, Gudimallam',
                'location_id' => $location->id,
                'primary_period_id' => $period->id,
                'primary_dynasty_id' => $satavahana->id,
                'site_type' => 'Temple Complex',
                'dating_statement' => 'Phase I: c. 3rd–2nd c. BCE; Phase II: c. 1st–3rd c. CE; Phase III: c. 9th–12th c. CE',
                'start_year' => -300,
                'end_year' => 1200,
                'is_dating_uncertain' => true,
                'protection_status' => 'ASI Protected Monument of National Importance',
                'summary' => 'Site of the earliest extant sculptured anthropomorphic Shiva linga in India, preserving continuous worship across 1,500 years of structural evolution.',
                'historical_context' => 'First brought to scholarly notice by T.A. Gopinatha Rao in 1903. Systematic stratigraphic excavation was conducted in 1973–74 by Dr. I.K. Sarma of the Archaeological Survey of India (ASI), revealing three distinct architectural phases starting with an open-air hypaethral stone railing from the 3rd–2nd century BCE.',
                'architectural_description' => 'The extant temple features an apsidal (hastiprishta or elephant-back) stone sanctum rebuilt in granite during the Bana and Chola periods, directly enclosing the ancient Phase I open-air stone railing and linga foundation.',
                'is_published' => true,
            ]
        );

        // 5. Heritage Object (The Linga)
        $object = HeritageObject::updateOrCreate(
            ['slug' => 'gudimallam-anthropomorphic-shiva-linga'],
            [
                'name' => 'Gudimallam Anthropomorphic Shiva Linga',
                'heritage_site_id' => $site->id,
                'location_id' => $location->id,
                'period_id' => $period->id,
                'dynasty_id' => $satavahana->id,
                'object_type' => 'Linga',
                'material' => 'Hard dark-brown igneous stone (dolerite)',
                'dimensions' => 'Visible height above floor: 1.52 m (5 ft); Total length with buried shaft: 2.45 m (8 ft); Glans diameter: 34 cm',
                'current_repository' => 'In situ (Garbhagriha of Parasurameshwara Temple, Gudimallam)',
                'accession_number' => 'In situ Sacred Cultural Asset',
                'dating_statement' => 'Phase I: c. 3rd–2nd century BCE (I.K. Sarma) / c. 1st century BCE (A.K. Coomaraswamy)',
                'start_year' => -300,
                'end_year' => -50,
                'is_dating_uncertain' => true,
                'description' => 'Monolithic five-foot-tall linga realistically carved as an erect phallus (urdhva-linga), bearing on its anterior face a high-relief standing two-armed anthropomorphic male deity.',
                'iconographic_notes' => 'The deity stands upon the shoulders of a crouching grotesque dwarf-like Yaksha with pointed animal ears. The deity holds a dead sacrificial ram/goat by the hind legs in his right hand, a small round water vessel (kamandalu) in his left hand, and balances a battle-axe (parashu) over his left shoulder. Wears a transparent dhoti, patra-kundalas, and finely plaited coiled locks.',
                'is_published' => true,
            ]
        );

        // 6. Inscriptions
        $inscrBana = Inscription::updateOrCreate(
            ['slug' => 'gudimallam-bana-inscription-vijayaditya'],
            [
                'title' => 'Gudimallam Inscription of Bana King Vijayaditya II',
                'heritage_site_id' => $site->id,
                'language' => 'Tamil',
                'script' => 'Grantha and Tamil',
                'dating_statement' => 'c. 9th century CE (Bana Period)',
                'start_year' => 850,
                'end_year' => 900,
                'donor' => 'Bana King Vijayaditya II (Prabhumeruvardhana)',
                'ruler_mentioned' => 'Vijayaditya II',
                'epigraphic_reference' => 'Epigraphia Indica, Vol. XI, pp. 222–228',
                'translation' => 'Records land grants and perpetual maintenance endowments dedicated to the god of the holy shrine of Tiru-vippirambedu (Gudimallam).',
                'interpretation_notes' => 'Provides historical documentation of the temple name and medieval patron dynasty before Chola annexation.',
                'is_published' => true,
            ]
        );

        $inscrChola = Inscription::updateOrCreate(
            ['slug' => 'gudimallam-chola-inscription-parantaka-i'],
            [
                'title' => 'Gudimallam Inscription of Chola King Parantaka I',
                'heritage_site_id' => $site->id,
                'language' => 'Tamil',
                'script' => 'Tamil',
                'dating_statement' => '23rd regnal year of Parantaka I (c. 930 CE)',
                'start_year' => 930,
                'end_year' => 930,
                'donor' => 'Royal officers of Parantaka I',
                'ruler_mentioned' => 'Madirai-konda Ko-Parakesarivarman (Parantaka I)',
                'epigraphic_reference' => 'Epigraphia Indica, Vol. XVII, pp. 1–7',
                'translation' => 'Records endowment of 90 sheep for maintaining a perpetual lamp in the sacred temple of Parasurameshwara.',
                'interpretation_notes' => 'Confirms imperial Chola patronage and architectural renovations.',
                'is_published' => true,
            ]
        );

        // 7. Sources (8 Verified Bibliographic Records)
        $srcSarma1982 = Source::updateOrCreate(
            ['title' => 'The Development of Early Saiva Art and Architecture: (With Special Reference to Andhradesa)'],
            [
                'authors' => 'Sarma, Inguva Karthikeya',
                'publication_year' => 1982,
                'source_type' => 'ACADEMIC_MONOGRAPH',
                'publisher' => 'Sundeep Prakashan, Delhi',
                'pages' => 'pp. 37–85, Plates 10–24',
                'archival_location' => 'ASI Central Archaeological Library, New Delhi',
                'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
                'notes' => 'Primary authoritative monograph documenting the 1973–74 ASI stratigraphic excavation inside the Gudimallam sanctum.',
            ]
        );

        $srcAsiReview = Source::updateOrCreate(
            ['title' => 'Indian Archaeology 1973–74: A Review'],
            [
                'authors' => 'Deshpande, M. N. (ed.), Archaeological Survey of India',
                'publication_year' => 1979,
                'source_type' => 'EXCAVATION_REPORT',
                'publisher' => 'Archaeological Survey of India, Government of India',
                'journal_or_series' => 'Indian Archaeology — A Review',
                'volume_issue' => '1973–74',
                'pages' => 'pp. 4–5, Plates II–III',
                'url' => 'https://asi.nic.in/wp-content/uploads/2021/08/Indian-Archaeology-1973-74-A-Review.pdf',
                'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
                'notes' => 'Official administrative and excavation report of the Gudimallam sanctum conservation project.',
            ]
        );

        $srcPuratattva = Source::updateOrCreate(
            ['title' => 'Gudimallam: A Unique Siva Temple of Andhra Pradesh'],
            [
                'authors' => 'Sarma, Inguva Karthikeya',
                'publication_year' => 1974,
                'source_type' => 'PEER_REVIEWED_JOURNAL',
                'journal_or_series' => 'Puratattva: Bulletin of the Indian Archaeological Society',
                'volume_issue' => 'No. 7 (1974)',
                'pages' => 'pp. 97–100',
                'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
                'notes' => 'Preliminary report announcing discovery of the buried Phase I Mauryan-Satavahana railing.',
            ]
        );

        $srcEpigraphia = Source::updateOrCreate(
            ['title' => 'Epigraphia Indica: Inscriptions of Gudimallam'],
            [
                'authors' => 'Hultzsch, E.; Venkayya, V.',
                'publication_year' => 1912,
                'source_type' => 'EPIGRAPHIC_CORPUS',
                'publisher' => 'Government of India, Calcutta',
                'journal_or_series' => 'Epigraphia Indica',
                'volume_issue' => 'Vol. XI, pp. 222–228; Vol. XVII, pp. 1–7',
                'pages' => 'pp. 222–228, 1–7',
                'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
                'notes' => 'Records Bana and Chola donor epigraphs carved onto the stone sanctum walls.',
            ]
        );

        $srcGopinathaRao = Source::updateOrCreate(
            ['title' => 'Elements of Hindu Iconography'],
            [
                'authors' => 'Gopinatha Rao, T. A.',
                'publication_year' => 1916,
                'source_type' => 'ACADEMIC_MONOGRAPH',
                'publisher' => 'The Law Printing House, Madras',
                'volume_issue' => 'Vol. II, Part I',
                'pages' => 'pp. 65–68, Plate I',
                'reliability_tier' => 'TIER_2_PEER_REVIEWED_ACADEMIC',
                'notes' => 'First scholarly publication and iconographic identification of the Gudimallam linga.',
            ]
        );

        $srcCoomaraswamy = Source::updateOrCreate(
            ['title' => 'History of Indian and Indonesian Art'],
            [
                'authors' => 'Coomaraswamy, Ananda K.',
                'publication_year' => 1927,
                'source_type' => 'ACADEMIC_MONOGRAPH',
                'publisher' => 'Edward Goldston, London',
                'pages' => 'pp. 67–68, Fig. 66',
                'reliability_tier' => 'TIER_2_PEER_REVIEWED_ACADEMIC',
                'notes' => 'Foundational art-historical stylistic survey dating the sculpture to the 1st century BCE based on Bharhut comparisons.',
            ]
        );

        $srcKramrisch = Source::updateOrCreate(
            ['title' => 'The Presence of Siva'],
            [
                'authors' => 'Kramrisch, Stella',
                'publication_year' => 1981,
                'source_type' => 'ACADEMIC_MONOGRAPH',
                'publisher' => 'Princeton University Press',
                'isbn_issn' => '978-0-691-01930-7',
                'pages' => 'pp. 117–124',
                'reliability_tier' => 'TIER_2_PEER_REVIEWED_ACADEMIC',
                'notes' => 'Theological analysis of the Vedic Agni-Rudra synthesis in the anthropomorphic linga.',
            ]
        );

        $srcSivaramamurti = Source::updateOrCreate(
            ['title' => 'Satarudriya: Vibhuti of Siva\'s Iconography'],
            [
                'authors' => 'Sivaramamurti, C.',
                'publication_year' => 1976,
                'source_type' => 'ACADEMIC_MONOGRAPH',
                'publisher' => 'Abhinav Publications, New Delhi',
                'pages' => 'pp. 31–34',
                'reliability_tier' => 'TIER_2_PEER_REVIEWED_ACADEMIC',
                'notes' => 'Textual correlation of the Gudimallam attributes with Vedic Rudra hymns.',
            ]
        );

        // 8. Evidence Records
        $evStratigraphy = Evidence::updateOrCreate(
            ['title' => 'ASI Stratigraphic Excavation in Gudimallam Sanctum (1973–74)'],
            [
                'evidence_type' => 'ARCHAEOLOGICAL',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Excavation revealed that the linga was founded in Phase I (c. 3rd–2nd c. BCE) on virgin soil within an open-air enclosure surrounded by a two-tiered square stone railing and burnt-brick floor.',
                'stratigraphic_context' => 'Period I, Floor level 1, sealed by Phase II lime concrete floor',
                'methodology_applied' => 'Controlled stratigraphic excavation with ceramic typological analysis',
            ]
        );
        $evStratigraphy->sources()->syncWithoutDetaching([
            $srcSarma1982->id => [
                'specific_pages' => 'pp. 43–58, Plates 10–18',
                'direct_quotation_or_data' => 'The linga was in situ on a square floor of burnt bricks datable to Phase I.',
                'citation_context' => 'Primary excavation report detailing the sanctum foundation.',
            ],
            $srcAsiReview->id => [
                'specific_pages' => 'pp. 4–5, Plate II',
                'direct_quotation_or_data' => 'Excavation inside the garbhagriha brought to light a square brick enclosure and two-tiered railing around the linga.',
                'citation_context' => 'Official ASI report.',
            ],
        ]);

        $evBrickMetrology = Evidence::updateOrCreate(
            ['title' => 'Burnt-Brick Metrology and Associated Early Historic Ceramics'],
            [
                'evidence_type' => 'ARCHAEOLOGICAL',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Phase I brick floor consists of large burnt bricks measuring 42 x 21 x 7 cm, accompanied by Megalithic Black-and-Red Ware and early historic black-slipped shards.',
                'stratigraphic_context' => 'Foundation trench Layer 4 directly above virgin natural soil',
                'methodology_applied' => 'Metrological comparison of Mauryan/early Satavahana brick sizes and ceramic typology',
            ]
        );
        $evBrickMetrology->sources()->syncWithoutDetaching([
            $srcSarma1982->id => [
                'specific_pages' => 'pp. 48–52',
                'direct_quotation_or_data' => 'Bricks of 42 x 21 x 7 cm correlate with standard Mauryan-Satavahana brick dimensions found at Amaravati and Yeleswaram.',
                'citation_context' => 'Metrological ceramic analysis.',
            ],
        ]);

        $evComparativeArt = Evidence::updateOrCreate(
            ['title' => 'Art-Historical Antiquity Relative to Mathura Mukhalingas'],
            [
                'evidence_type' => 'ART_HISTORICAL',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Comparative iconographic survey proves Gudimallam is the earliest extant anthropomorphic linga, preceding the Mathura mukhalingas of the Kushan and Shunga-Mitra eras.',
                'methodology_applied' => 'Pan-Indian sculptural iconographic survey',
            ]
        );
        $evComparativeArt->sources()->syncWithoutDetaching([
            $srcGopinathaRao->id => [
                'specific_pages' => 'pp. 65–68',
                'citation_context' => 'Initial comparative survey of phallic sculptural forms.',
            ],
            $srcKramrisch->id => [
                'specific_pages' => 'pp. 117–120',
                'citation_context' => 'Theological and stylistic comparison with northern Indian Shaiva sculptures.',
            ],
        ]);

        $evStylisticDebate = Evidence::updateOrCreate(
            ['title' => 'Stylistic Affinities with Bharhut and Bodh Gaya Yakshas'],
            [
                'evidence_type' => 'ART_HISTORICAL',
                'classification' => 'SCHOLARLY_DEBATE',
                'description' => 'The standing figure features heavy ear ornaments, coiled headgear, and stands on a crouching dwarf with attributes comparable to 1st century BCE Bharhut reliefs.',
                'methodology_applied' => 'Comparative visual and stylistic analysis',
                'uncertainty_notes' => 'Purely stylistic dating can have a margin of error of 100–150 years and is challenged by physical stratigraphic excavation dates.',
            ]
        );
        $evStylisticDebate->sources()->syncWithoutDetaching([
            $srcCoomaraswamy->id => [
                'specific_pages' => 'pp. 67–68, Fig. 66',
                'direct_quotation_or_data' => 'The figure exhibits strong stylistic affinities with late Sunga sculptures, dating to the first century B.C.',
                'citation_context' => 'Classic stylistic dating proposition.',
            ],
        ]);

        $evVedicParallels = Evidence::updateOrCreate(
            ['title' => 'Vedic Literary Epithets Correlating with Gudimallam Attributes'],
            [
                'evidence_type' => 'LITERARY',
                'classification' => 'GOOD_EVIDENCE',
                'description' => 'Attributes of the figure (ram/goat, parashu, kamandalu) match descriptions of Rudra in the Vedic Satarudriya as a hunter, lord of forests, and associated with sacrificial animal offerings.',
                'methodology_applied' => 'Textual-iconographic synthesis with Vedic Samhitas',
            ]
        );
        $evVedicParallels->sources()->syncWithoutDetaching([
            $srcSivaramamurti->id => [
                'specific_pages' => 'pp. 31–34',
                'citation_context' => 'Correlating iconography with the Yajurvedic Satarudriya text.',
            ],
            $srcKramrisch->id => [
                'specific_pages' => 'pp. 120–124',
                'citation_context' => 'Analysis of the Agni-Rudra Vedic fusion.',
            ],
        ]);

        $evEpigraphicBanaChola = Evidence::updateOrCreate(
            ['title' => 'Bana and Chola Architectural Renovation Inscriptions'],
            [
                'evidence_type' => 'EPIGRAPHIC',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Granite wall inscriptions document the stone renovation of the sanctum in Phase III between the 9th and 12th centuries CE under Bana and Chola kings.',
                'methodology_applied' => 'Epigraphic paleography and royal regnal dating',
            ]
        );
        $evEpigraphicBanaChola->sources()->syncWithoutDetaching([
            $srcEpigraphia->id => [
                'specific_pages' => 'Vol. XI, pp. 222–228; Vol. XVII, pp. 1–7',
                'citation_context' => 'Published readings of medieval Tamil/Grantha inscriptions from Gudimallam.',
            ],
        ]);

        // 9. Claims (Attached Polymorphically to Site and Object)
        $claim1 = Claim::updateOrCreate(
            [
                'statement' => 'The Gudimallam Linga was originally erected in situ on virgin soil within an open-air hypaethral enclosure surrounded by a square brick floor and stone railing during Phase I (c. 3rd–2nd century BCE).',
            ],
            [
                'claimable_type' => HeritageSite::class,
                'claimable_id' => $site->id,
                'claim_type' => 'ARCHAEOLOGICAL_STRATIGRAPHY',
                'consensus_status' => 'STRONG_CONSENSUS',
                'summary_justification' => 'Proved definitively by the 1973–74 ASI sanctum excavation led by Dr. I.K. Sarma, uncovering the sealed brick floor and railing beneath subsequent floor layers.',
            ]
        );
        $claim1->evidence()->syncWithoutDetaching([
            $evStratigraphy->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Undisturbed in situ stratigraphy confirms Phase I foundation.'],
            $evBrickMetrology->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'CORROBORATIVE', 'analysis_notes' => 'Large brick sizes match early historic Mauryan-Satavahana metrology.'],
        ]);

        $claim2 = Claim::updateOrCreate(
            [
                'statement' => 'The Gudimallam Linga is the earliest extant stone sculpture in the Indian subcontinent combining a realistic phallic shaft with a two-armed standing anthropomorphic deity.',
            ],
            [
                'claimable_type' => HeritageObject::class,
                'claimable_id' => $object->id,
                'claim_type' => 'ICONOGRAPHIC_IDENTITY',
                'consensus_status' => 'SETTLED_CONSENSUS',
                'summary_justification' => 'Unanimously accepted across Indian art history; no earlier sculptured anthropomorphic linga has been discovered in South Asia.',
            ]
        );
        $claim2->evidence()->syncWithoutDetaching([
            $evComparativeArt->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Chronological priority verified across pan-Indian museum collections.'],
        ]);

        $claim3 = Claim::updateOrCreate(
            [
                'statement' => 'Scholarly debate persists regarding whether the sculpture was carved in the 3rd–2nd century BCE (Mauryan/early Satavahana period) or the 1st century BCE (late Shunga period).',
            ],
            [
                'claimable_type' => HeritageObject::class,
                'claimable_id' => $object->id,
                'claim_type' => 'CHRONOLOGY',
                'consensus_status' => 'ONGOING_DEBATE',
                'summary_justification' => 'Archaeologists rely on sealed in situ stratigraphic evidence favoring 3rd–2nd c. BCE, whereas some art historians rely on stylistic parallels with Bharhut Yakshas favoring late 1st c. BCE.',
            ]
        );
        $claim3->evidence()->syncWithoutDetaching([
            $evStratigraphy->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Physical excavation strata favor 3rd–2nd c. BCE.'],
            $evStylisticDebate->id => ['relationship_type' => 'COMPLICATES', 'scholarly_weight' => 'HISTORICAL_ALTERNATIVE', 'analysis_notes' => 'Art-historical stylistic analysis proposes 1st c. BCE.'],
        ]);

        $claim4 = Claim::updateOrCreate(
            [
                'statement' => 'The anthropomorphic deity represents an archaic Vedic conception of Rudra / Agni-Rudra, holding a sacrificial ram/goat, a water vessel, and a hunter\'s battle-axe.',
            ],
            [
                'claimable_type' => HeritageObject::class,
                'claimable_id' => $object->id,
                'claim_type' => 'ICONOGRAPHIC_IDENTITY',
                'consensus_status' => 'STRONG_CONSENSUS',
                'summary_justification' => 'Iconographic attributes directly parallel the Vedic Satarudriya hymns rather than later classical Puranic Shiva depictions.',
            ]
        );
        $claim4->evidence()->syncWithoutDetaching([
            $evVedicParallels->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Textual correlation with Yajurvedic hymns.'],
        ]);

        $claim5 = Claim::updateOrCreate(
            [
                'statement' => 'The extant granite stone temple with its apsidal sanctum was constructed in Phase III between the 9th and 12th centuries CE under Bana and Chola patronage over the Phase II brick temple.',
            ],
            [
                'claimable_type' => HeritageSite::class,
                'claimable_id' => $site->id,
                'claim_type' => 'ARCHAEOLOGICAL_STRATIGRAPHY',
                'consensus_status' => 'SETTLED_CONSENSUS',
                'summary_justification' => 'Corroborated by donor inscriptions carved into the temple pilasters and basement walls.',
            ]
        );
        $claim5->evidence()->syncWithoutDetaching([
            $evEpigraphicBanaChola->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Epigraphs of Bana Vijayaditya and Chola Parantaka I confirm medieval renovation dates.'],
        ]);
    }
}

