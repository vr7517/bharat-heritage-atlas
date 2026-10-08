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

class SanchiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Location
        $location = Location::updateOrCreate(
            ['name' => 'Sanchi'],
            [
                'historical_names' => ['Kākanāya', 'Kākanāva', 'Kākanāda-bota', 'Botiya-śrī-parvata', 'Chetiyagiri'],
                'state' => 'Madhya Pradesh',
                'district' => 'Raisen',
                'taluk_tehsil' => 'Gairatganj / Sanchi',
                'latitude' => 23.4794,
                'longitude' => 77.7394,
                'altitude_meters' => 440,
                'uncertainty_radius_meters' => 30,
                'description' => 'Isolated sandstone hilltop settlement situated 9 km southwest of ancient Vidisha and 46 km northeast of Bhopal in Raisen District, Madhya Pradesh.',
            ]
        );

        // 2. Periods
        $mauryanPeriod = Period::updateOrCreate(
            ['slug' => 'mauryan-period'],
            [
                'name' => 'Mauryan Imperial Period',
                'dating_statement' => 'c. 322 BCE – 185 BCE',
                'start_year' => -322,
                'end_year' => -185,
                'start_era' => 'BCE',
                'end_era' => 'BCE',
                'description' => 'Pan-Indian imperial epoch established by Chandragupta Maurya and reached its monumental architectural height under Emperor Ashoka.',
            ]
        );

        $shungaPeriod = Period::updateOrCreate(
            ['slug' => 'shunga-period'],
            [
                'name' => 'Shunga Period',
                'dating_statement' => 'c. 185 BCE – 73 BCE',
                'start_year' => -185,
                'end_year' => -73,
                'start_era' => 'BCE',
                'end_era' => 'BCE',
                'description' => 'Post-Mauryan phase in Central and Northern India marked by the stone casing and balustrades of Buddhist stupas.',
            ]
        );

        $satavahanaPeriod = Period::updateOrCreate(
            ['slug' => 'satavahana-period'],
            [
                'name' => 'Satavahana / Early Historic Period',
                'dating_statement' => 'c. 1st century BCE – 2nd century CE',
                'start_year' => -100,
                'end_year' => 200,
                'start_era' => 'BCE',
                'end_era' => 'CE',
                'description' => 'Era of monumental gateway construction (toranas) and craft guild sponsorships in Central India and Deccan.',
            ]
        );

        // 3. Dynasties
        $maurya = Dynasty::updateOrCreate(
            ['slug' => 'maurya'],
            [
                'name' => 'Mauryan Empire',
                'dating_statement' => 'c. 322 BCE – 185 BCE',
                'start_year' => -322,
                'end_year' => -185,
                'region' => 'Pan-Indian Subcontinent',
                'capital' => 'Pataliputra (modern Patna)',
                'description' => 'First pan-Indian empire whose third emperor Ashoka adopted Buddhism, erected monolithic pillars with edicts, and founded the core stupa at Sanchi.',
            ]
        );

        $shunga = Dynasty::updateOrCreate(
            ['slug' => 'shunga'],
            [
                'name' => 'Shunga',
                'dating_statement' => 'c. 185 BCE – 73 BCE',
                'start_year' => -185,
                'end_year' => -73,
                'region' => 'Magadha and Central India (Malwa)',
                'capital' => 'Pataliputra, Vidisha (viceregal / secondary capital)',
                'description' => 'Imperial dynasty founded by Pushyamitra Shunga following the Mauryas; Vidisha served as a premier cultural and political center under Crown Prince Agnimitra and King Bhagabhadra.',
            ]
        );

        $satavahana = Dynasty::updateOrCreate(
            ['slug' => 'satavahana'],
            [
                'name' => 'Satavahana',
                'dating_statement' => 'c. 1st century BCE – 2nd century CE',
                'start_year' => -100,
                'end_year' => 200,
                'region' => 'Deccan and Central India',
                'capital' => 'Pratishthana (Paithan), Amaravati (Dhanyakataka)',
                'description' => 'Ancient dynasty ruling extensive regions of the Deccan and Malwa, under whose patronage the four monumental Sanchi toranas were completed.',
            ]
        );

        // 4. Heritage Site
        $site = HeritageSite::updateOrCreate(
            ['slug' => 'sanchi-archaeological-complex'],
            [
                'name' => 'Sanchi Buddhist Monument Complex & Great Stupa Compound',
                'location_id' => $location->id,
                'primary_period_id' => $mauryanPeriod->id,
                'primary_dynasty_id' => $maurya->id,
                'site_type' => 'Buddhist Monastic & Stupa Complex',
                'dating_statement' => 'c. 3rd century BCE – 12th century CE',
                'start_year' => -250,
                'end_year' => 1100,
                'is_dating_uncertain' => false,
                'protection_status' => 'UNESCO World Heritage Site (Ref: 524) & ASI Protected Monument of National Importance',
                'summary' => 'Hilltop Buddhist monastic and monumental complex comprising the Great Stupa (Stupa 1), Stupa 2, Stupa 3, Ashokan pillar, monumental toranas, monasteries, and early temples.',
                'historical_context' => 'Founded by Emperor Ashoka in the 3rd century BCE near Vidisha (home of his queen Devi). Expanded under the Shungas with stone casings and vedika, embellished with four carved toranas under the Satavahanas, and patronized through the Gupta period until the 12th century CE. Rediscovered by Henry Taylor in 1818, surveyed by Alexander Cunningham in 1851, and conserved by Sir John Marshall from 1912 to 1919.',
                'architectural_description' => 'Contains the Great Stupa (36.6 m diameter, 16.5 m height) with lower and upper circumambulatory paths, harmika, chhatravali, massive stone vedika railing, four 10.5-meter high carved torana gateways, companion Stupas 2 and 3, and Gupta Temple 17.',
                'is_published' => true,
            ]
        );

        // 5. Heritage Objects
        $greatStupa = HeritageObject::updateOrCreate(
            ['slug' => 'sanchi-great-stupa-1'],
            [
                'name' => 'Sanchi Great Stupa (Stupa 1)',
                'heritage_site_id' => $site->id,
                'location_id' => $location->id,
                'period_id' => $shungaPeriod->id,
                'dynasty_id' => $shunga->id,
                'object_type' => 'Stupa',
                'material' => 'Sandstone ashlar masonry encasing internal Mauryan burnt-brick core',
                'dimensions' => 'Diameter: 36.6 m (120 ft); Height: 16.5 m (54 ft)',
                'current_repository' => 'In situ at Sanchi Hill, Raisen District, Madhya Pradesh',
                'accession_number' => 'In situ UNESCO World Heritage Monument',
                'dating_statement' => 'Core: c. 250 BCE; Casing & Balustrade: c. 150 BCE; Gateways: c. 1st century BCE',
                'start_year' => -250,
                'end_year' => -20,
                'is_dating_uncertain' => false,
                'description' => 'Monumental hemispherical stone stupa with upper circumambulatory terrace (medhi), harmika balcony, stone umbrellas, lower stone balustrade (vedika), and four lavishly carved torana gateways facing the cardinal points.',
                'iconographic_notes' => 'Rich Jataka reliefs (Vessantara, Chaddanta, Mahakapi, Sama), scenes from the life of the Buddha, and strict aniconic representation using empty thrones, footprints, umbrellas, and wheels.',
                'is_published' => true,
            ]
        );

        $ashokanPillar = HeritageObject::updateOrCreate(
            ['slug' => 'sanchi-ashokan-pillar-25'],
            [
                'name' => 'Sanchi Ashokan Monolithic Pillar (Pillar 25)',
                'heritage_site_id' => $site->id,
                'location_id' => $location->id,
                'period_id' => $mauryanPeriod->id,
                'dynasty_id' => $maurya->id,
                'object_type' => 'Monolithic Pillar',
                'material' => 'Fine-grained buff Chunar sandstone with Mauryan mirror polish',
                'dimensions' => 'Original height: approx. 12.8 m (42 ft); Base diameter: 0.8 m',
                'current_repository' => 'In situ (shaft base beside Southern Gateway; capital fragment in Sanchi Archaeological Museum)',
                'accession_number' => 'In situ Monument / Sanchi Museum No. 25',
                'dating_statement' => 'c. 250 BCE (Reign of Emperor Ashoka)',
                'start_year' => -260,
                'end_year' => -232,
                'is_dating_uncertain' => false,
                'description' => 'Monolithic polished Chunar sandstone pillar erected by Ashoka carrying the Sanghabheda (Schism) Edict. Originally surmounted by an inverted bell lotus and four-lion capital.',
                'iconographic_notes' => 'Four-lion back-to-back group capital closely related to the Sarnath capital, with bell-shaped lotus and bird frieze abacus.',
                'is_published' => true,
            ]
        );

        $reliquaries = HeritageObject::updateOrCreate(
            ['slug' => 'sanchi-stupa-3-disciples-reliquaries'],
            [
                'name' => 'Stupa 3 Relic Caskets of Sariputra and Maha-Maudgalyayana',
                'heritage_site_id' => $site->id,
                'location_id' => $location->id,
                'period_id' => $shungaPeriod->id,
                'dynasty_id' => $shunga->id,
                'object_type' => 'Reliquary / Casket',
                'material' => 'Steatite / Grey Sandstone',
                'dimensions' => 'Caskets: 7.5 cm diameter; Stone box: 45 x 45 x 30 cm',
                'current_repository' => 'Chetiyagiri Vihara, Sanchi / Sanchi Archaeological Museum',
                'accession_number' => 'Sanchi Museum / Mahabodhi Society Relic Deposit',
                'dating_statement' => 'c. 2nd century BCE',
                'start_year' => -175,
                'end_year' => -125,
                'is_dating_uncertain' => false,
                'description' => 'Inscribed relic caskets discovered by Alexander Cunningham in 1851 inside Stupa 3, bearing the names of the Buddha\'s two chief disciples, Sariputta and Maha Moggallana.',
                'iconographic_notes' => 'Turned steatite caskets enclosed within an inscribed large stone relic box placed in a central masonry chamber.',
                'is_published' => true,
            ]
        );

        // 6. Inscriptions
        $inscrSchism = Inscription::updateOrCreate(
            ['slug' => 'sanchi-ashokan-schism-edict'],
            [
                'title' => 'Sanchi Ashokan Pillar Schism Edict (Sanghabheda Edict)',
                'heritage_site_id' => $site->id,
                'object_id' => $ashokanPillar->id,
                'epigraphic_reference' => 'CII Vol. I, pp. 160–161; Lüders List No. 161',
                'language' => 'Prakrit (Early Middle Indo-Aryan, Magadhi influence)',
                'script' => 'Brahmi',
                'dating_statement' => 'c. 250 BCE',
                'start_year' => -260,
                'end_year' => -232,
                'donor' => 'Devanampriya Priyadarsin (Emperor Ashoka)',
                'ruler_mentioned' => 'Emperor Ashoka',
                'raw_text' => "...saṃghe samage kate... Ye saṃghe bhokhati bhikhu vā bhikhuni vā se odātāni dusāni saṃnaṃdhāpayitu anāvāsasi nidhāpetaviye",
                'translation' => 'The Sangha is made whole and unified. Whosoever breaks the Sangha, monk or nun, shall be made to wear white garments and be expelled to a non-monastic residence.',
                'interpretation_notes' => 'Imperial rescript asserting royal protection of monastic unity and discipline within the Sanchi Buddhist community.',
                'is_published' => true,
            ]
        );

        $inscrIvoryCarvers = Inscription::updateOrCreate(
            ['slug' => 'sanchi-vidisha-ivory-carvers-inscription'],
            [
                'title' => 'Vidisha Ivory Carvers Guild Inscription (South Gateway)',
                'heritage_site_id' => $site->id,
                'object_id' => $greatStupa->id,
                'epigraphic_reference' => 'Lüders List No. 345; Marshall & Foucher No. 200',
                'language' => 'Prakrit (Middle Indo-Aryan)',
                'script' => 'Brahmi',
                'dating_statement' => 'c. 1st century BCE',
                'start_year' => -75,
                'end_year' => -25,
                'donor' => 'Guild of Ivory Carvers of Vidisha (Dantakāra)',
                'raw_text' => 'Vedisakehi damtakārehi rūpakammaṃ kataṃ',
                'translation' => 'Done by the ivory carvers of Vidisha.',
                'interpretation_notes' => 'Primary epigraphic evidence of guild artisans transferring micro-carving skills to monumental stone architecture.',
                'is_published' => true,
            ]
        );

        $inscrSatakarni = Inscription::updateOrCreate(
            ['slug' => 'sanchi-satakarni-south-gateway-inscription'],
            [
                'title' => 'Satavahana King Siri Satakarni Inscription (South Gateway)',
                'heritage_site_id' => $site->id,
                'object_id' => $greatStupa->id,
                'epigraphic_reference' => 'Lüders List No. 346; Marshall & Foucher No. 398',
                'language' => 'Prakrit (Middle Indo-Aryan)',
                'script' => 'Brahmi',
                'dating_statement' => 'c. 1st century BCE',
                'start_year' => -75,
                'end_year' => -25,
                'donor' => 'Ananda, son of Vasithi, foreman of artisans (avesanin)',
                'ruler_mentioned' => 'Rajan Siri Satakarni (Satavahana Dynasty)',
                'translation' => 'Gift of Ananda, son of Vasithi, the foreman of artisans of King Siri Satakarni.',
                'interpretation_notes' => 'Corroborates Satavahana royal sovereignty and artisan supervision over the construction of the South Gateway.',
                'is_published' => true,
            ]
        );

        $inscrReliquary = Inscription::updateOrCreate(
            ['slug' => 'sanchi-stupa-3-disciples-relic-inscriptions'],
            [
                'title' => 'Stupa 3 Disciples Relic Inscriptions (Sariputta and Maha Moggallana)',
                'heritage_site_id' => $site->id,
                'object_id' => $reliquaries->id,
                'epigraphic_reference' => 'Cunningham 1854, pp. 297–299; Lüders List Nos. 667 & 668',
                'language' => 'Prakrit',
                'script' => 'Brahmi',
                'dating_statement' => 'c. 2nd century BCE',
                'start_year' => -175,
                'end_year' => -125,
                'raw_text' => "Sāriputasa\nMahā-Mogalānasa",
                'translation' => '(Relics) of Sariputra / (Relics) of Maha-Maudgalyayana.',
                'interpretation_notes' => 'Primary inscriptional label identifying the corporeal bone relics of the Buddha\'s two foremost direct disciples.',
                'is_published' => true,
            ]
        );

        // 7. Sources (9 Verified Records)
        $srcCunningham1854 = Source::updateOrCreate(
            ['title' => 'The Bhilsa Topes; or, Buddhist Monuments of Central India'],
            [
                'authors' => 'Cunningham, Alexander',
                'publication_year' => 1854,
                'source_type' => 'EXCAVATION_REPORT',
                'publisher' => 'Smith, Elder and Co., London',
                'pages' => 'pp. 180–297, Plates XIX–XXXIII',
                'archival_location' => 'British Library, London; ASI Central Archaeological Library, New Delhi',
                'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
                'notes' => 'First systematic archaeological survey of Sanchi; documents opening of Stupas 2 and 3 and discovery of the Sariputra and Maudgalyayana relics.',
            ]
        );

        $srcMarshall1940 = Source::updateOrCreate(
            ['title' => 'The Monuments of Sāñchī (3 Volumes)'],
            [
                'authors' => 'Marshall, Sir John; Foucher, Alfred; Majumdar, N. G.',
                'publication_year' => 1940,
                'source_type' => 'EXCAVATION_REPORT',
                'publisher' => 'Government of India Press, Calcutta / Arthur Probsthain, London',
                'volume_issue' => 'Vols. I–III',
                'pages' => 'Vol. I: pp. 1–396; Vols. II & III: Plates 1–141',
                'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
                'notes' => 'Definitive comprehensive archaeological monograph on Sanchi excavations, architectural phases, relief sculptures, and epigraphy.',
            ]
        );

        $srcMarshall1918 = Source::updateOrCreate(
            ['title' => 'A Guide to Sanchi'],
            [
                'authors' => 'Marshall, Sir John',
                'publication_year' => 1918,
                'source_type' => 'EXCAVATION_REPORT',
                'publisher' => 'Superintendent Government Printing, India, Calcutta',
                'pages' => 'pp. 1–168, Plates I–XV',
                'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
                'notes' => 'Authoritative structural breakdown of the three building periods of the Great Stupa and monastic complex.',
            ]
        );

        $srcMitra1957 = Source::updateOrCreate(
            ['title' => 'Sanchi (ASI Site Guide)'],
            [
                'authors' => 'Mitra, Debala',
                'publication_year' => 1957,
                'source_type' => 'EXCAVATION_REPORT',
                'publisher' => 'Archaeological Survey of India, New Delhi',
                'pages' => 'pp. 1–74, Plates I–XII',
                'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
                'notes' => 'Archaeological guide to monument typology, chronologies, Gupta additions, and monastic layout.',
            ]
        );

        $srcBuhler1894 = Source::updateOrCreate(
            ['title' => 'The Inscriptions of Sanchi'],
            [
                'authors' => 'Bühler, Georg',
                'publication_year' => 1894,
                'source_type' => 'EPIGRAPHIC_CORPUS',
                'publisher' => 'Superintendent of Government Printing, Calcutta',
                'journal_or_series' => 'Epigraphia Indica',
                'volume_issue' => 'Volume II (1894)',
                'pages' => 'pp. 87–116, 366–408',
                'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
                'notes' => 'Critical edition and paleographic classification of early Brahmi donative inscriptions on Sanchi balustrades.',
            ]
        );

        $srcLuders1912Sanchi = Source::updateOrCreate(
            ['title' => 'A List of Brāhmī Inscriptions from the Earliest Times to about A.D. 400 (Sanchi Section)'],
            [
                'authors' => 'Lüders, Heinrich',
                'publication_year' => 1912,
                'source_type' => 'EPIGRAPHIC_CORPUS',
                'publisher' => 'Superintendent of Government Printing, Calcutta',
                'journal_or_series' => 'Epigraphia Indica',
                'volume_issue' => 'Volume X, Appendix',
                'pages' => 'pp. 26–63 (Nos. 161–668)',
                'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
                'notes' => 'Standardized catalog of early historic Brahmi inscriptions at Sanchi including the Vidisha ivory carvers inscription.',
            ]
        );

        $srcHultzsch1925 = Source::updateOrCreate(
            ['title' => 'Inscriptions of Asoka (CII Vol. I)'],
            [
                'authors' => 'Hultzsch, Eugen',
                'publication_year' => 1925,
                'source_type' => 'EPIGRAPHIC_CORPUS',
                'publisher' => 'The Clarendon Press for the Government of India, Oxford',
                'journal_or_series' => 'Corpus Inscriptionum Indicarum',
                'volume_issue' => 'Volume I (New Edition)',
                'pages' => 'pp. 160–161, Plate VIII',
                'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
                'notes' => 'Critical edition, transliteration, and English translation of the Sanchi Ashokan Pillar Schism Edict.',
            ]
        );

        $srcDehejia1997 = Source::updateOrCreate(
            ['title' => 'Discourse in Early Buddhist Art: Visual Narratives of India'],
            [
                'authors' => 'Dehejia, Vidya',
                'publication_year' => 1997,
                'source_type' => 'ACADEMIC_MONOGRAPH',
                'publisher' => 'Munshiram Manoharlal Publishers / Columbia University Press',
                'isbn_issn' => '978-81-215-0736-3',
                'pages' => 'pp. 91–138',
                'reliability_tier' => 'TIER_2_PEER_REVIEWED_ACADEMIC',
                'notes' => 'Art-historical and semiotic analysis of aniconic Buddha depictions and Jataka visual narratives on Sanchi toranas.',
            ]
        );

        $srcSchopen1997 = Source::updateOrCreate(
            ['title' => 'Bones, Stones, and Buddhist Monks: Collected Papers on the Archaeology, Epigraphy, and Texts of Monastic Buddhism in India'],
            [
                'authors' => 'Schopen, Gregory',
                'publication_year' => 1997,
                'source_type' => 'ACADEMIC_MONOGRAPH',
                'publisher' => 'University of Hawaii Press, Honolulu',
                'isbn_issn' => '978-0-8248-1870-8',
                'pages' => 'pp. 23–43, 86–98',
                'reliability_tier' => 'TIER_2_PEER_REVIEWED_ACADEMIC',
                'notes' => 'Analysis of donative epigraphy, monastic property rights, and the corporeal relic cult at Sanchi.',
            ]
        );

        // 8. Evidence Records
        $evBrickCore = Evidence::updateOrCreate(
            ['title' => 'Internal Mauryan Burnt-Brick Core Stratigraphy of Stupa 1'],
            [
                'evidence_type' => 'ARCHAEOLOGICAL',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Breaches into Stupa 1 and Marshall\'s conservation exposed an inner hemispherical brick tumulus of large Mauryan bricks (40 x 25 x 7.5 cm) measuring approx. 18 m in diameter, encased within later stone.',
                'stratigraphic_context' => 'Innermost core of Stupa 1 sealed beneath 2nd century BCE sandstone casing',
                'methodology_applied' => 'Internal cross-section excavation and brick metrology',
            ]
        );
        $evBrickCore->sources()->syncWithoutDetaching([
            $srcMarshall1940->id => [
                'specific_pages' => 'Vol. I, pp. 21–25',
                'direct_quotation_or_data' => 'The original stupa of Asoka was a brick structure of about half the diameter of the present stupa.',
                'citation_context' => 'Primary excavation and structural analysis of Stupa 1 core.',
            ],
            $srcCunningham1854->id => [
                'specific_pages' => 'pp. 180–190',
                'citation_context' => 'First recording of the internal brick masonry.',
            ],
        ]);

        $evSchismEdict = Evidence::updateOrCreate(
            ['title' => 'Brahmi Text of the Sanchi Pillar Schism Edict (Sanghabheda)'],
            [
                'evidence_type' => 'EPIGRAPHIC',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'A 20-line early monumental Brahmi inscription engraved on Pillar 25 ordering white garments and expulsion for any monk or nun who breaks the Sangha.',
                'stratigraphic_context' => 'Engraved on the polished monolithic sandstone shaft beside the Southern Gateway',
                'methodology_applied' => 'Paleographic and epigraphic analysis',
            ]
        );
        $evSchismEdict->sources()->syncWithoutDetaching([
            $srcHultzsch1925->id => [
                'specific_pages' => 'pp. 160–161, Plate VIII',
                'direct_quotation_or_data' => 'Ye samghe bhokhati bhikhu va bhikhuni va se odatani dusani samnamdhapayitu anavasasi nidhapetaviye',
                'citation_context' => 'Critical epigraphic edition and transcription.',
            ],
            $srcMarshall1940->id => [
                'specific_pages' => 'Vol. I, pp. 25–28, 373–375',
                'citation_context' => 'Archaeological documentation of Pillar 25.',
            ],
        ]);

        $evStoneJoinery = Evidence::updateOrCreate(
            ['title' => 'Carpentry-Derived Mortise-and-Tenon Stone Balustrade Joinery'],
            [
                'evidence_type' => 'ARCHAEOLOGICAL',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'The massive ground vedika balustrade consists of octagonal stone pillars (thaba), lenticular crossbars (suchi), and rounded coping stones (ushnisha) fitted without mortar using traditional wood-joinery techniques.',
                'methodology_applied' => 'Architectural technology and joinery analysis',
            ]
        );
        $evStoneJoinery->sources()->syncWithoutDetaching([
            $srcMarshall1940->id => [
                'specific_pages' => 'Vol. I, pp. 29–36; Vol. II, Plates 15–25',
                'citation_context' => 'Architectural description of the Shunga stone balustrades.',
            ],
            $srcMarshall1918->id => [
                'specific_pages' => 'pp. 35–40',
                'citation_context' => 'Structural engineering analysis of stone carpentry adaptation.',
            ],
        ]);

        $evRailingDonors = Evidence::updateOrCreate(
            ['title' => 'Middle Brahmi Donative Epigraphy on Vedika Balustrade Components'],
            [
                'evidence_type' => 'EPIGRAPHIC',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Over 600 short Brahmi inscriptions on the railing pillars and rails exhibit 2nd-century BCE paleography recording donations by monks, nuns, guilds, and lay devotees from all across India.',
                'methodology_applied' => 'Epigraphic paleography and demographic donor analysis',
            ]
        );
        $evRailingDonors->sources()->syncWithoutDetaching([
            $srcBuhler1894->id => [
                'specific_pages' => 'pp. 87–116',
                'citation_context' => 'Corpus publication of Sanchi balustrade inscriptions.',
            ],
            $srcLuders1912Sanchi->id => [
                'specific_pages' => 'pp. 26–63',
                'citation_context' => 'Standardized epigraphic list cataloging.',
            ],
            $srcSchopen1997->id => [
                'specific_pages' => 'pp. 23–43',
                'citation_context' => 'Socio-economic analysis of monastic and lay donations.',
            ],
        ]);

        $evIvoryCarvers = Evidence::updateOrCreate(
            ['title' => 'South Gateway Architrave Inscription of the Vidisha Ivory Carvers'],
            [
                'evidence_type' => 'EPIGRAPHIC',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Brahmi inscription on the middle architrave of the South Gateway: "Vedisakehi damtakarehi rupakammam katam" ("Done by the ivory carvers of Vidisha").',
                'stratigraphic_context' => 'In situ on the South Torana architrave of Stupa 1',
                'methodology_applied' => 'Epigraphic and art-historical craft-transmission analysis',
            ]
        );
        $evIvoryCarvers->sources()->syncWithoutDetaching([
            $srcLuders1912Sanchi->id => [
                'specific_pages' => 'p. 36 (No. 345)',
                'direct_quotation_or_data' => 'Vedisakehi damtakarehi rupakammam katam',
                'citation_context' => 'Lüders standard epigraphic list entry.',
            ],
            $srcMarshall1940->id => [
                'specific_pages' => 'Vol. I, p. 342; Vol. II, Plate 10',
                'citation_context' => 'Discovery and architectural placement on the South Torana.',
            ],
            $srcDehejia1997->id => [
                'specific_pages' => 'pp. 91–98',
                'citation_context' => 'Analysis of guild craftsmanship transfer from ivory to stone.',
            ],
        ]);

        $evSatakarniEpigraph = Evidence::updateOrCreate(
            ['title' => 'South Gateway Artisan Foreman Inscription of King Siri Satakarni'],
            [
                'evidence_type' => 'EPIGRAPHIC',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Inscription recording a donation by Ananda, foreman of artisans of King Siri Satakarni, dating the gateway to the 1st century BCE Satavahana hegemony in Malwa.',
                'methodology_applied' => 'Epigraphical dynastic synchronism',
            ]
        );
        $evSatakarniEpigraph->sources()->syncWithoutDetaching([
            $srcLuders1912Sanchi->id => [
                'specific_pages' => 'p. 37 (No. 346)',
                'citation_context' => 'Epigraphia Indica standard entry for Satakarni inscription.',
            ],
            $srcMarshall1940->id => [
                'specific_pages' => 'Vol. I, p. 342, 377',
                'citation_context' => 'Historical interpretation of Satavahana rule in Sanchi.',
            ],
        ]);

        $evAniconicReliefs = Evidence::updateOrCreate(
            ['title' => 'Strict Aniconic Symbolic Depiction of the Buddha in Torana Narrative Friezes'],
            [
                'evidence_type' => 'ART_HISTORICAL',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'In all gateway narrative panels (Great Departure, Enlightenment, First Sermon, Miracle at Sravasti), the Buddha\'s bodily form is never shown, but symbolized by the empty throne, footprints, parasol, wheel, or stupa.',
                'methodology_applied' => 'Comparative semiotic and iconographic relief analysis',
            ]
        );
        $evAniconicReliefs->sources()->syncWithoutDetaching([
            $srcMarshall1940->id => [
                'specific_pages' => 'Vol. I, pp. 181–220; Vols. II & III',
                'citation_context' => 'Complete iconographic catalog of gateway narrative panels.',
            ],
            $srcDehejia1997->id => [
                'specific_pages' => 'pp. 101–138',
                'citation_context' => 'Narrative structure and aniconism analysis.',
            ],
        ]);

        $evStupa3Relics = Evidence::updateOrCreate(
            ['title' => 'Inscribed Steatite Reliquaries of Sariputra and Maha-Maudgalyayana in Stupa 3'],
            [
                'evidence_type' => 'ARCHAEOLOGICAL',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Cunningham\'s 1851 excavation of Stupa 3 chamber recovered a stone chest containing two steatite caskets incised with the Brahmi names "Sariputasa" and "Maha-Mogalanasa" with bone relics.',
                'stratigraphic_context' => 'Central relic chamber of Stupa 3 beneath undisturbed stone masonry',
                'methodology_applied' => 'Relic chamber excavation and epigraphic paleography',
            ]
        );
        $evStupa3Relics->sources()->syncWithoutDetaching([
            $srcCunningham1854->id => [
                'specific_pages' => 'pp. 295–299, Plates XXI–XXII',
                'direct_quotation_or_data' => 'In the southern casket was inscribed "Sariputasa", in the northern "Maha-Mogalanasa".',
                'citation_context' => 'Primary excavation discovery account of Stupa 3 relics.',
            ],
            $srcSchopen1997->id => [
                'specific_pages' => 'pp. 23–43',
                'citation_context' => 'Archaeological analysis of disciple relics and mortuary ritual.',
            ],
        ]);

        // 9. Claims (Attached Polymorphically)
        $claim1 = Claim::updateOrCreate(
            [
                'statement' => 'The original core of the Great Stupa (Stupa 1) was founded by Mauryan Emperor Ashoka in the mid-3rd century BCE (c. 250 BCE) as a low hemispherical brick tumulus approximately half the diameter of the extant monument.',
            ],
            [
                'claimable_type' => HeritageObject::class,
                'claimable_id' => $greatStupa->id,
                'claim_type' => 'ARCHAEOLOGICAL_STRATIGRAPHY',
                'consensus_status' => 'SETTLED_CONSENSUS',
                'summary_justification' => 'Proved by internal cross-section examinations revealing the 18-meter core of large Mauryan bricks encased within later Shunga stone ashlar masonry.',
            ]
        );
        $claim1->evidence()->syncWithoutDetaching([
            $evBrickCore->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Sealed stratigraphic core confirms Mauryan brick construction.'],
        ]);

        $claim2 = Claim::updateOrCreate(
            [
                'statement' => 'Emperor Ashoka erected a 42-foot polished monolithic sandstone column (Pillar 25) crowned by a four-lion capital near the southern entrance of Stupa 1, bearing the imperial Sanghabheda (Schism) Edict ordering the expulsion of sectarian dissenters.',
            ],
            [
                'claimable_type' => HeritageObject::class,
                'claimable_id' => $ashokanPillar->id,
                'claim_type' => 'EPIGRAPHIC',
                'consensus_status' => 'SETTLED_CONSENSUS',
                'summary_justification' => 'Directly established by the 20-line Brahmi edict engraved on the polished Chunar sandstone shaft at Sanchi.',
            ]
        );
        $claim2->evidence()->syncWithoutDetaching([
            $evSchismEdict->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Verbatim imperial epigraph mandates monastic unity.'],
        ]);

        $claim3 = Claim::updateOrCreate(
            [
                'statement' => 'During the Shunga period in the mid-2nd century BCE (c. 150 BCE), the brick stupa was encased in sandstone masonry, doubling its diameter to 36.6 m, and was fitted with an elevated terrace, harmika, umbrellas, and a monumental stone balustrade (vedika).',
            ],
            [
                'claimable_type' => HeritageObject::class,
                'claimable_id' => $greatStupa->id,
                'claim_type' => 'ARCHITECTURAL_STRATIGRAPHY',
                'consensus_status' => 'SETTLED_CONSENSUS',
                'summary_justification' => 'Architectural analysis confirms stone joinery mimicking wood carpentry and over 600 donative Brahmi inscriptions on the balustrade datable to the 2nd century BCE.',
            ]
        );
        $claim3->evidence()->syncWithoutDetaching([
            $evStoneJoinery->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Joinery technology directly reproduces wooden architectural carpentry.'],
            $evRailingDonors->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'CORROBORATIVE', 'analysis_notes' => 'Railing paleography firmly dates construction to 2nd century BCE.'],
        ]);

        $claim4 = Claim::updateOrCreate(
            [
                'statement' => 'The four monumental carved stone gateways (toranas) were added in the 1st century BCE under Satavahana hegemony, with the earliest Southern Gateway carved by the Guild of Ivory Carvers of neighboring Vidisha.',
            ],
            [
                'claimable_type' => HeritageObject::class,
                'claimable_id' => $greatStupa->id,
                'claim_type' => 'EPIGRAPHIC',
                'consensus_status' => 'SETTLED_CONSENSUS',
                'summary_justification' => 'The architrave epigraphs on the South Gateway explicitly record the sponsorship of King Siri Satakarni\'s artisan foreman and the execution by the Vidisha ivory carvers guild.',
            ]
        );
        $claim4->evidence()->syncWithoutDetaching([
            $evIvoryCarvers->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Epigraph affirms ivory guild execution of stone reliefs.'],
            $evSatakarniEpigraph->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Synchronizes gate construction with Satavahana royal artisan supervision.'],
        ]);

        $claim5 = Claim::updateOrCreate(
            [
                'statement' => 'Throughout the rich narrative reliefs of all four gateways of Stupa 1, the Buddha is represented exclusively through sacred aniconic symbols and never in human anthropomorphic form.',
            ],
            [
                'claimable_type' => HeritageObject::class,
                'claimable_id' => $greatStupa->id,
                'claim_type' => 'ART_HISTORICAL',
                'consensus_status' => 'SETTLED_CONSENSUS',
                'summary_justification' => 'Universal scholarly consensus in Indian Buddhist art history; all narrative friezes employ an empty throne, footprints, parasol, wheel, or stupa to denote the Buddha.',
            ]
        );
        $claim5->evidence()->syncWithoutDetaching([
            $evAniconicReliefs->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Exhaustive iconographic survey confirms total absence of anthropomorphic Buddha.'],
        ]);

        $claim6 = Claim::updateOrCreate(
            [
                'statement' => 'Excavations by Alexander Cunningham inside Stupa 3 at Sanchi uncovered the authentic inscribed relic caskets of the Buddha\'s two chief disciples, Sariputra and Maha-Maudgalyayana.',
            ],
            [
                'claimable_type' => HeritageObject::class,
                'claimable_id' => $reliquaries->id,
                'claim_type' => 'RELIC_ARCHAEOLOGY',
                'consensus_status' => 'SETTLED_CONSENSUS',
                'summary_justification' => 'Undisturbed 1851 discovery of stone caskets incised with the Brahmi names "Sariputasa" and "Maha-Mogalanasa" within Stupa 3.',
            ]
        );
        $claim6->evidence()->syncWithoutDetaching([
            $evStupa3Relics->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Inscribed steatite reliquaries found in situ in Stupa 3 central chamber.'],
        ]);

        $claim7 = Claim::updateOrCreate(
            [
                'statement' => 'A significant historiographical debate exists regarding whether early Shunga ruler Pushyamitra Shunga damaged the Ashokan stupa before it was rebuilt, or whether the enlargement represents continuous peaceful Buddhist patronage under Shunga rule.',
            ],
            [
                'claimable_type' => HeritageSite::class,
                'claimable_id' => $site->id,
                'claim_type' => 'HISTORICAL_ATTRIBUTION',
                'consensus_status' => 'ONGOING_DEBATE',
                'summary_justification' => 'Marshall and later Buddhist texts propose Shunga vandalism followed by repair, while modern historians emphasize the total absence of archaeological destruction layers and abundant contemporary Buddhist donations under Shunga rule.',
            ]
        );
        $claim7->evidence()->syncWithoutDetaching([
            $evStoneJoinery->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Absence of destruction layer between brick core and stone casing complicates literary persecution narrative.'],
            $evRailingDonors->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Hundreds of prosperous private Buddhist donors flourished under Shunga rule.'],
        ]);
    }
}
