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

class HeliodorusPillarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Location
        $location = Location::updateOrCreate(
            ['name' => 'Besnagar'],
            [
                'historical_names' => ['Besnagar', 'Vidiśā', 'Vaidiśa'],
                'state' => 'Madhya Pradesh',
                'district' => 'Vidisha',
                'taluk_tehsil' => 'Vidisha',
                'latitude' => 23.5497,
                'longitude' => 77.8233,
                'altitude_meters' => 425,
                'uncertainty_radius_meters' => 25,
                'description' => 'Ancient fortified city and sacred archaeological complex situated at the confluence of the Betwa (ancient Vetravati) and Bes (ancient Vidisa) rivers, 3 km north of modern Vidisha and 9 km northeast of Sanchi.',
            ]
        );

        // 2. Period
        $period = Period::updateOrCreate(
            ['slug' => 'shunga-indo-greek-period'],
            [
                'name' => 'Shunga / Indo-Greek Synchronism Period',
                'dating_statement' => 'c. 2nd century BCE (c. 185 BCE – 73 BCE)',
                'start_year' => -185,
                'end_year' => -73,
                'start_era' => 'BCE',
                'end_era' => 'BCE',
                'description' => 'Post-Mauryan imperial and regional era characterized by the revival of Vedic rituals, Bhagavata Vaishnavism, and diplomatic engagement with the Indo-Greek kingdoms.',
            ]
        );

        // 3. Dynasties
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

        $indoGreek = Dynasty::updateOrCreate(
            ['slug' => 'indo-greek'],
            [
                'name' => 'Indo-Greek Kingdom',
                'dating_statement' => 'c. 200 BCE – 10 CE',
                'start_year' => -200,
                'end_year' => 10,
                'region' => 'Gandhara, Punjab, Taxila',
                'capital' => 'Taxila (Takshashila), Sagala (Sialkot)',
                'description' => 'Hellenistic realm established across northwestern South Asia, known for bilingual coinage, philosophical engagement with Indian religions, and diplomatic relations with Gangetic/Malwa courts.',
            ]
        );

        // 4. Heritage Site
        $site = HeritageSite::updateOrCreate(
            ['slug' => 'besnagar-archaeological-complex'],
            [
                'name' => 'Besnagar Archaeological Complex & Heliodorus Pillar Compound',
                'location_id' => $location->id,
                'primary_period_id' => $period->id,
                'primary_dynasty_id' => $shunga->id,
                'site_type' => 'Archaeological Complex & Temple Compound',
                'dating_statement' => 'c. 3rd century BCE – 2nd century BCE',
                'start_year' => -300,
                'end_year' => -100,
                'is_dating_uncertain' => false,
                'protection_status' => 'ASI Protected Monument of National Importance (Bhopal Circle)',
                'summary' => 'Monolithic pillar compound and ancient sacred complex at Besnagar featuring the subterranean remains of an elliptical Vasudeva temple, a foundation avenue of eight dhvaja-stambhas, and the in situ Heliodorus Garuda column.',
                'historical_context' => 'Surveyed by Alexander Cunningham in 1877 as Khamb Baba; inscriptions revealed by H.H. Lake in 1909 and deciphered by J.Ph. Vogel; excavated by D.R. Bhandarkar (1913–15) and M.D. Khare (1963–65).',
                'architectural_description' => 'Archaeological complex containing an elliptical sanctum (11.0 m x 8.1 m) with stone plinth grooves for timber posts, fronted by an avenue of eight stone-supported dhvaja-stambhas including the Heliodorus column.',
                'is_published' => true,
            ]
        );

        // 5. Heritage Object
        $object = HeritageObject::updateOrCreate(
            ['slug' => 'heliodorus-garuda-pillar'],
            [
                'name' => 'Heliodorus Monolithic Garuda Pillar (Khamb Baba)',
                'heritage_site_id' => $site->id,
                'location_id' => $location->id,
                'period_id' => $period->id,
                'dynasty_id' => $shunga->id,
                'object_type' => 'Dhvaja-Stambha',
                'material' => 'Fine-grained buff Chunar/local sandstone with polished surface',
                'dimensions' => 'Height: 5.4 m above ground (6.8 m total with subterranean base); Base diameter: 0.6 m',
                'current_repository' => 'In situ at Besnagar, Vidisha, Madhya Pradesh',
                'accession_number' => 'In situ ASI National Monument',
                'dating_statement' => 'c. 113 BCE (14th regnal year of King Bhagabhadra)',
                'start_year' => -115,
                'end_year' => -110,
                'is_dating_uncertain' => false,
                'description' => 'Monolithic sandstone column transitioning from an 8-sided base to 16-sided middle and 32-sided upper shaft, crowned by an inverted bell lotus capital with an abacus holding a socket for a lost Garuda figure. Features two Prakrit Brahmi inscriptions recording an Indo-Greek ambassador\'s dedication to Vasudeva.',
                'iconographic_notes' => 'Shaft exhibits bell capital with inverted lotus petals and an abacus with bead-and-reel molding and carved geese (hamsa), surmounted by a mortise socket for a lost stone Garuda finial.',
                'is_published' => true,
            ]
        );

        // 6. Inscriptions
        $inscrA = Inscription::updateOrCreate(
            ['slug' => 'heliodorus-pillar-inscription-a-dedicatory'],
            [
                'title' => 'Heliodorus Pillar Inscription A (Dedicatory & Historical Synchronism)',
                'heritage_site_id' => $site->id,
                'object_id' => $object->id,
                'epigraphic_reference' => 'Lüders List No. 669; ASI AR 1908–09, pp. 126–129',
                'language' => 'Prakrit (Early Middle Indo-Aryan with Sanskritisms)',
                'script' => 'Brahmi',
                'dating_statement' => 'c. 113 BCE (14th regnal year of King Bhagabhadra)',
                'start_year' => -115,
                'end_year' => -110,
                'donor' => 'Heliodoros, son of Dion (Resident of Taxila, Ambassador of King Antialkidas)',
                'ruler_mentioned' => 'Maharaja Antialkidas (Taxila) and King Kasiputra Bhagabhadra (Vidisha)',
                'raw_text' => "Devadevasa Vā[sude]vasa Garuḍadhvaje ayaṃ\nkārite i[a] Heliodoreṇa bhāga-\nvatena Diyasa putreṇa Takṣaśilākena\nYonadūtena āgatena mahārājasa\nAṃtalikitasa upā[ṃ]tā sakāsaṃ raño\nKāsīputa[sa] [Bhā]gabhadrasa trātārasa\nvasena [chatu]dasena rājena vadhamānasa",
                'translation' => 'This Garuda-standard of Vasudeva, the God of gods, was erected here by Heliodoros, a Bhagavata, son of Dion, an inhabitant of Taxila, who came as a Greek ambassador (Yona-duta) from the Great King Antialkidas to King Kasiputra Bhagabhadra, the Savior, who was prospering in the fourteenth regnal year of his reign.',
                'interpretation_notes' => 'Primary epigraphic anchor synchronizing Indo-Greek King Antialkidas with King Bhagabhadra, and providing the earliest inscriptional evidence of Vasudeva as Devadeva and foreign adoption of Bhagavata faith.',
                'is_published' => true,
            ]
        );

        $inscrB = Inscription::updateOrCreate(
            ['slug' => 'heliodorus-pillar-inscription-b-ethical'],
            [
                'title' => 'Heliodorus Pillar Inscription B (Mahabharata Ethical Triad)',
                'heritage_site_id' => $site->id,
                'object_id' => $object->id,
                'epigraphic_reference' => 'Lüders List No. 669; ASI AR 1908–09, p. 128',
                'language' => 'Prakrit (Early Middle Indo-Aryan)',
                'script' => 'Brahmi',
                'dating_statement' => 'c. 113 BCE',
                'start_year' => -115,
                'end_year' => -110,
                'donor' => 'Heliodoros, son of Dion',
                'ruler_mentioned' => 'King Kasiputra Bhagabhadra',
                'raw_text' => "Trini amutapadāni [iā] su-anuṭhitāna\nneyamti [svagaṃ] dama cāga apramāda",
                'translation' => 'Three immortal steps, when well practiced, lead to heaven: self-control (dama), renunciation/generosity (caga/tyaga), and vigilance/conscientiousness (apramada).',
                'interpretation_notes' => 'Direct parallel to Mahabharata Udyoga Parva 43.22 and Stri Parva 7.23, demonstrating ethical and literary diffusion in the 2nd century BCE.',
                'is_published' => true,
            ]
        );

        // 7. Sources (9 Verified Bibliographic Records)
        $srcCunningham1880 = Source::updateOrCreate(
            ['title' => 'Report of Tours in Bundelkhand and Malwa in 1874–75 and 1876–77'],
            [
                'authors' => 'Cunningham, Alexander',
                'publication_year' => 1880,
                'source_type' => 'EXCAVATION_REPORT',
                'publisher' => 'Office of the Superintendent of Government Printing, Calcutta',
                'journal_or_series' => 'Archaeological Survey of India Reports, Old Series',
                'volume_issue' => 'Volume X',
                'pages' => 'pp. 41–44, Plate XIV',
                'archival_location' => 'ASI Central Archaeological Library, New Delhi',
                'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
                'notes' => 'First systematic survey and dimension recording of the pillar when locally venerated as Khamb Baba prior to inscription discovery.',
            ]
        );

        $srcVogel1909 = Source::updateOrCreate(
            ['title' => 'The Garuda Pillar of Besnagar'],
            [
                'authors' => 'Vogel, Jean Philippe',
                'publication_year' => 1912,
                'source_type' => 'EXCAVATION_REPORT',
                'publisher' => 'Superintendent Government Printing, Calcutta',
                'journal_or_series' => 'Archaeological Survey of India Annual Report',
                'volume_issue' => '1908–09',
                'pages' => 'pp. 126–129, Plates XLV & XLVI',
                'url' => 'https://asi.nic.in/wp-content/uploads/2021/08/Annual-Report-of-the-Director-General-of-Archaeology-in-India-1908-09.pdf',
                'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
                'notes' => 'Primary academic publication, facsimile reproduction, transcription, and decipherment of Inscriptions A and B discovered by H.H. Lake.',
            ]
        );

        $srcBhandarkar1914 = Source::updateOrCreate(
            ['title' => 'Excavations at Besnagar (ASI AR 1913–14 & 1914–15)'],
            [
                'authors' => 'Bhandarkar, Devadatta Ramakrishna',
                'publication_year' => 1917,
                'source_type' => 'EXCAVATION_REPORT',
                'publisher' => 'Superintendent Government Printing, Calcutta',
                'journal_or_series' => 'Archaeological Survey of India Annual Report',
                'volume_issue' => '1913–14 (pp. 186–226); 1914–15 (pp. 66–88)',
                'pages' => 'pp. 186–226, 66–88',
                'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
                'notes' => 'Documents pillar foundation slabs, 2nd-century BCE iron/steel support wedge, and unearthing of Kalpadruma, Tala, and Makara capitals.',
            ]
        );

        $srcKhare1967 = Source::updateOrCreate(
            ['title' => 'Discovery of a Vishnu Temple near the Heliodoros Pillar, Besnagar'],
            [
                'authors' => 'Khare, M. D.',
                'publication_year' => 1967,
                'source_type' => 'EXCAVATION_REPORT',
                'publisher' => 'Lalit Kalā Akademi, New Delhi',
                'journal_or_series' => 'Lalit Kalā',
                'volume_issue' => 'No. 13 (1967), pp. 21–27; Puratattva No. 8 (1975–76), pp. 179–180',
                'pages' => 'pp. 21–27, 179–180',
                'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
                'notes' => 'Controlled ASI stratigraphic excavations revealing subterranean elliptical temple of Vasudeva (3rd–2nd c. BCE) and row of 7 companion pillars.',
            ]
        );

        $srcLuders1912 = Source::updateOrCreate(
            ['title' => 'A List of Brāhmī Inscriptions from the Earliest Times to about A.D. 400'],
            [
                'authors' => 'Lüders, Heinrich',
                'publication_year' => 1912,
                'source_type' => 'EPIGRAPHIC_CORPUS',
                'publisher' => 'Superintendent of Government Printing, Calcutta',
                'journal_or_series' => 'Epigraphia Indica',
                'volume_issue' => 'Volume X (1909–10), Appendix',
                'pages' => 'pp. 63–64 (No. 669)',
                'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
                'notes' => 'Authoritative epigraphical list entry standardizing the Besnagar Heliodorus inscription citation in Indian epigraphy.',
            ]
        );

        $srcSircar1965 = Source::updateOrCreate(
            ['title' => 'Select Inscriptions Bearing on Indian History and Civilization'],
            [
                'authors' => 'Sircar, Dines Chandra',
                'publication_year' => 1965,
                'source_type' => 'EPIGRAPHIC_CORPUS',
                'publisher' => 'University of Calcutta, Calcutta',
                'volume_issue' => 'Volume I (3rd Edition)',
                'pages' => 'pp. 88–90 (Book I, No. 2)',
                'reliability_tier' => 'TIER_1_PRIMARY_EXCAVATION_EPIGRAPHY',
                'notes' => 'Critical edition of Prakrit Brahmi text, Sanskrit chhaya, and historical epigraphical commentary.',
            ]
        );

        $srcSalomon1998 = Source::updateOrCreate(
            ['title' => 'Indian Epigraphy: A Guide to the Study of Inscriptions in Sanskrit, Prakrit, and the Other Indo-Aryan Languages'],
            [
                'authors' => 'Salomon, Richard',
                'publication_year' => 1998,
                'source_type' => 'ACADEMIC_MONOGRAPH',
                'publisher' => 'Oxford University Press, New York & Oxford',
                'isbn_issn' => '978-0-19-509984-3',
                'pages' => 'pp. 134, 265–267',
                'reliability_tier' => 'TIER_2_PEER_REVIEWED_ACADEMIC',
                'notes' => 'Linguistic and epigraphical analysis of Indo-Greek Prakrit epigraphy and diplomatic embassies.',
            ]
        );

        $srcJaiswal1967 = Source::updateOrCreate(
            ['title' => 'The Origin and Development of Vaiṣṇavism'],
            [
                'authors' => 'Jaiswal, Suvira',
                'publication_year' => 1967,
                'source_type' => 'ACADEMIC_MONOGRAPH',
                'publisher' => 'Munshiram Manoharlal Publishers, New Delhi',
                'pages' => 'pp. 116–120',
                'reliability_tier' => 'TIER_2_PEER_REVIEWED_ACADEMIC',
                'notes' => 'Historiographical analysis of early Bhagavata theology, Vasudeva-Krishna veneration, and the integration of non-Indian converts.',
            ]
        );

        $srcMarshall1909 = Source::updateOrCreate(
            ['title' => 'The Besnagar Inscription of Heliodoros'],
            [
                'authors' => 'Marshall, John H.',
                'publication_year' => 1909,
                'source_type' => 'PEER_REVIEWED_JOURNAL',
                'journal_or_series' => 'Journal of the Royal Asiatic Society of Great Britain and Ireland',
                'volume_issue' => 'October 1909',
                'pages' => 'pp. 1053–1056',
                'reliability_tier' => 'TIER_2_PEER_REVIEWED_ACADEMIC',
                'notes' => 'First scholarly analysis establishing the historical synchronism between Indo-Greek King Antialkidas and King Bhagabhadra.',
            ]
        );

        // 8. Evidence Records
        $evSynchronism = Evidence::updateOrCreate(
            ['title' => 'Inscriptional Synchronism of Antialkidas and Bhagabhadra'],
            [
                'evidence_type' => 'EPIGRAPHIC',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Inscription A lines 4–7 explicitly couples Indo-Greek King Antialkidas of Taxila with King Kasiputra Bhagabhadra in his 14th regnal year.',
                'stratigraphic_context' => 'Engraved in situ on the octagonal lower shaft of the pillar',
                'methodology_applied' => 'Epigraphic textual analysis and political synchronism',
            ]
        );
        $evSynchronism->sources()->syncWithoutDetaching([
            $srcVogel1909->id => [
                'specific_pages' => 'pp. 126–129, Plate XLV',
                'direct_quotation_or_data' => 'Heliodorena bhagavatena Diyasa putrena Takhasilakena Yonadutena agatena maharajasa Amtalikitasa upamta...',
                'citation_context' => 'Original decipherment and translation.',
            ],
            $srcLuders1912->id => [
                'specific_pages' => 'pp. 63–64 (No. 669)',
                'citation_context' => 'Epigraphia Indica standard corpus cataloging.',
            ],
            $srcSircar1965->id => [
                'specific_pages' => 'pp. 88–89',
                'citation_context' => 'Critical epigraphic edition and grammatical commentary.',
            ],
            $srcMarshall1909->id => [
                'specific_pages' => 'pp. 1053–1056',
                'citation_context' => 'Royal Asiatic Society synchronism demonstration.',
            ],
        ]);

        $evNumismatics = Evidence::updateOrCreate(
            ['title' => 'Numismatic Drachms of Indo-Greek King Antialkidas Nikephoros'],
            [
                'evidence_type' => 'NUMISMATIC',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Bilingual silver and bronze coins of Antialkidas recovered across Taxila and the Punjab establish his absolute reign at c. 115–95 BCE.',
                'methodology_applied' => 'Numismatic hoard analysis and stylistic seriation',
            ]
        );
        $evNumismatics->sources()->syncWithoutDetaching([
            $srcMarshall1909->id => [
                'specific_pages' => 'pp. 1054–1055',
                'citation_context' => 'Coinage correlation of Antialkidas with Taxila mint issues.',
            ],
        ]);

        $evVasudevaFormula = Evidence::updateOrCreate(
            ['title' => 'Dedicatory Brahmi Formula: Devadevasa Vasudevasa Garudadhvaje'],
            [
                'evidence_type' => 'EPIGRAPHIC',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Inscription A line 1 preserves the explicit dedication of the Garuda-standard to Vasudeva designated as Devadeva (God of gods).',
                'stratigraphic_context' => 'Engraved on facet 1 of the octagonal shaft',
                'methodology_applied' => 'Epigraphic formulaic analysis',
            ]
        );
        $evVasudevaFormula->sources()->syncWithoutDetaching([
            $srcVogel1909->id => [
                'specific_pages' => 'pp. 126–127',
                'direct_quotation_or_data' => 'Devadevasa Vasudevasa Garudadhvaje ayam karite...',
                'citation_context' => 'Reading of the dedication line.',
            ],
            $srcJaiswal1967->id => [
                'specific_pages' => 'pp. 116–120',
                'citation_context' => 'Analysis of the supreme divinity title Devadeva applied to Vasudeva.',
            ],
        ]);

        $evCapitalSocket = Evidence::updateOrCreate(
            ['title' => 'Lotus Bell Capital Abacus Socket for Crowning Garuda Figure'],
            [
                'evidence_type' => 'ARCHAEOLOGICAL',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Upper abacus of the bell capital features a rectangular mortise socket carved to secure the crowning zoomorphic Garuda sculpture.',
                'methodology_applied' => 'Architectural metrology and comparative capital analysis',
            ]
        );
        $evCapitalSocket->sources()->syncWithoutDetaching([
            $srcCunningham1880->id => [
                'specific_pages' => 'pp. 41–42, Plate XIV',
                'citation_context' => 'Architectural measurements of capital and abacus.',
            ],
            $srcBhandarkar1914->id => [
                'specific_pages' => 'pp. 188–190',
                'citation_context' => 'Identification of mortise joint for the lost Garudadhvaja finial.',
            ],
        ]);

        $evGreekDevotee = Evidence::updateOrCreate(
            ['title' => 'Epigraphic Self-Identification as Bhagavata and Yonaduta'],
            [
                'evidence_type' => 'EPIGRAPHIC',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Heliodoros declares himself both a Bhagavata (devotee of Vasudeva) and a Yonaduta (Greek ambassador) from Taxila, demonstrating early intercultural religious integration.',
                'methodology_applied' => 'Epigraphic socio-religious analysis',
            ]
        );
        $evGreekDevotee->sources()->syncWithoutDetaching([
            $srcSalomon1998->id => [
                'specific_pages' => 'pp. 134, 265–267',
                'citation_context' => 'Linguistic examination of the loanword Yona and Bhagavata identity.',
            ],
            $srcJaiswal1967->id => [
                'specific_pages' => 'pp. 117–119',
                'citation_context' => 'Religious integration of foreigners in early Vaishnavism.',
            ],
        ]);

        $evEthicalVerse = Evidence::updateOrCreate(
            ['title' => 'Brahmi Text of Inscription B Ethical Triad'],
            [
                'evidence_type' => 'EPIGRAPHIC',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Inscription B records the three immortal steps (amutapadani) leading to heaven: dama (self-restraint), caga (renunciation), and apramada (vigilance).',
                'stratigraphic_context' => 'Engraved on facet 3 of the octagonal shaft',
                'methodology_applied' => 'Epigraphic transcription and textual analysis',
            ]
        );
        $evEthicalVerse->sources()->syncWithoutDetaching([
            $srcVogel1909->id => [
                'specific_pages' => 'p. 128',
                'direct_quotation_or_data' => 'Trini amutapadani [ia] su-anuthitana neyamti [svagam] dama caga apramada',
                'citation_context' => 'First academic transcription of Inscription B.',
            ],
            $srcSircar1965->id => [
                'specific_pages' => 'p. 90',
                'citation_context' => 'Epigraphic commentary on the moral verse.',
            ],
        ]);

        $evMahabharataParallel = Evidence::updateOrCreate(
            ['title' => 'Philological Parallels with Mahabharata Sanatsujatiya Verses'],
            [
                'evidence_type' => 'LITERARY',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Direct verbal match with Mahabharata Udyoga Parva 43.22 (Sanatsujatiya) and Stri Parva 7.23: "Damas tyago \'pramadas ca etesv amrtam ahitam".',
                'methodology_applied' => 'Comparative philology and epic intertextuality',
            ]
        );
        $evMahabharataParallel->sources()->syncWithoutDetaching([
            $srcSircar1965->id => [
                'specific_pages' => 'p. 90',
                'citation_context' => 'Philological identification of the Mahabharata verse parallel.',
            ],
            $srcJaiswal1967->id => [
                'specific_pages' => 'p. 119',
                'citation_context' => 'Textual diffusion of epic ethical concepts in 2nd-century BCE Central India.',
            ],
        ]);

        $evKhareTemple = Evidence::updateOrCreate(
            ['title' => 'M.D. Khare Stratigraphic Excavation of Elliptical Vasudeva Temple'],
            [
                'evidence_type' => 'ARCHAEOLOGICAL',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Excavation revealed the subterranean stone plinth with grooves for wooden uprights of an elliptical sanctum (11.0 m x 8.1 m) datable to the 3rd–2nd c. BCE facing the pillar.',
                'stratigraphic_context' => 'Phase I timber-and-stone temple plinth sealed below early historic flood silt layers',
                'methodology_applied' => 'Controlled stratigraphic excavation and ceramic typology',
            ]
        );
        $evKhareTemple->sources()->syncWithoutDetaching([
            $srcKhare1967->id => [
                'specific_pages' => 'pp. 21–27; Puratattva pp. 179–180',
                'direct_quotation_or_data' => 'The elliptical plan is delineated by grooves cut into stone slabs... datable to the 3rd-2nd century B.C.',
                'citation_context' => 'Excavation report of the Vasudeva temple.',
            ],
        ]);

        $evPillarAvenue = Evidence::updateOrCreate(
            ['title' => 'Linear Alignment of Seven Companion Pillar Foundation Pits'],
            [
                'evidence_type' => 'ARCHAEOLOGICAL',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Excavation revealed stone foundation pockets of seven other columns aligned north-south facing the temple, confirming the pillar was part of a sacred avenue.',
                'methodology_applied' => 'Spatial spatial-plan archaeological analysis',
            ]
        );
        $evPillarAvenue->sources()->syncWithoutDetaching([
            $srcKhare1967->id => [
                'specific_pages' => 'pp. 24–26',
                'citation_context' => 'Discovery of the processional column alignment.',
            ],
            $srcBhandarkar1914->id => [
                'specific_pages' => 'pp. 188–205',
                'citation_context' => 'Discovery of companion capitals (Kalpadruma, Tala, Makara) belonging to the sacred complex.',
            ],
        ]);

        $evSteelWedge = Evidence::updateOrCreate(
            ['title' => 'D.R. Bhandarkar Excavation of Steel Wedge and Deep Foundation Slabs'],
            [
                'evidence_type' => 'ARCHAEOLOGICAL',
                'classification' => 'STRONG_EVIDENCE',
                'description' => 'Pillar base rests 1.4 m deep on two massive foundation stones supported by wooden pegs and a high-carbon steel wedge (20 cm x 8 cm), demonstrating 2nd-century BCE metallurgy.',
                'stratigraphic_context' => 'Subterranean foundation bed beneath column base',
                'methodology_applied' => 'Excavation clearance and archaeometallurgical analysis',
            ]
        );
        $evSteelWedge->sources()->syncWithoutDetaching([
            $srcBhandarkar1914->id => [
                'specific_pages' => 'pp. 187–192',
                'direct_quotation_or_data' => 'Under the base of the pillar was found an iron wedge... metallurgical analysis confirmed steel of fine quality.',
                'citation_context' => 'Excavation report on pillar engineering and metallurgy.',
            ],
        ]);

        // 9. Claims (Attached Polymorphically to Object and Site)
        $claim1 = Claim::updateOrCreate(
            [
                'statement' => 'Inscription A on the Heliodorus Pillar records the diplomatic embassy of Heliodoros, son of Dion, from Indo-Greek King Antialkidas of Taxila to King Kasiputra Bhagabhadra in his 14th regnal year, establishing an epigraphically anchored date of c. 113 BCE (+/- 3 years).',
            ],
            [
                'claimable_type' => HeritageObject::class,
                'claimable_id' => $object->id,
                'claim_type' => 'CHRONOLOGY',
                'consensus_status' => 'SETTLED_CONSENSUS',
                'summary_justification' => 'Directly established by Inscription A lines 4–7 and corroborated by numismatic drachms of Antialkidas dating to c. 115–95 BCE.',
            ]
        );
        $claim1->evidence()->syncWithoutDetaching([
            $evSynchronism->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Inscriptional synchronism provides the absolute historical anchor.'],
            $evNumismatics->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'CORROBORATIVE', 'analysis_notes' => 'Taxila and Punjab coin hoards confirm Antialkidas reign dates.'],
        ]);

        $claim2 = Claim::updateOrCreate(
            [
                'statement' => 'The Heliodorus Pillar is the earliest securely dated epigraphic and structural monument in South Asia explicitly designating Vasudeva as Devadeva ("God of gods") and recording the erection of a Garuda-standard (Garudadhvaja).',
            ],
            [
                'claimable_type' => HeritageObject::class,
                'claimable_id' => $object->id,
                'claim_type' => 'RELIGIOUS_HISTORY',
                'consensus_status' => 'SETTLED_CONSENSUS',
                'summary_justification' => 'Line 1 of Inscription A and the capital socket designed for a Garuda figure establish indisputable material proof of Bhagavata Vasudeva worship in the late 2nd century BCE.',
            ]
        );
        $claim2->evidence()->syncWithoutDetaching([
            $evVasudevaFormula->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Epigraphic formula explicitly names Vasudeva Devadeva.'],
            $evCapitalSocket->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'CORROBORATIVE', 'analysis_notes' => 'Abacus mortise confirms mounting of a zoomorphic Garuda sculpture.'],
        ]);

        $claim3 = Claim::updateOrCreate(
            [
                'statement' => 'Heliodoros, an ethnic Greek (Yona) ambassador from Taxila, explicitly identifies himself as a Bhagavata (lay devotee of Vasudeva), demonstrating that foreigners were assimilated into early Vaishnava religious life in the 2nd century BCE.',
            ],
            [
                'claimable_type' => HeritageObject::class,
                'claimable_id' => $object->id,
                'claim_type' => 'CULTURAL_TRANSMISSION',
                'consensus_status' => 'SETTLED_CONSENSUS',
                'summary_justification' => 'Inscription A lines 2–4 records the official self-designation Heliodorena bhagavatena... Yonadutena, disproving that early Indian religion rejected foreign converts.',
            ]
        );
        $claim3->evidence()->syncWithoutDetaching([
            $evGreekDevotee->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Self-identification as Bhagavata confirms personal devotion.'],
            $evSynchronism->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'CORROBORATIVE', 'analysis_notes' => 'Embassy context proves ambassadorial status from Taxila.'],
        ]);

        $claim4 = Claim::updateOrCreate(
            [
                'statement' => 'Inscription B on the pillar preserves an ethical triad of virtues leading to heaven—dama (self-restraint), caga (tyaga, renunciation/charity), and apramada (vigilance)—which directly mirrors moral verses in the Mahabharata.',
            ],
            [
                'claimable_type' => HeritageObject::class,
                'claimable_id' => $object->id,
                'claim_type' => 'PHILOLOGICAL',
                'consensus_status' => 'SETTLED_CONSENSUS',
                'summary_justification' => 'Verbatim correlation with Mahabharata Udyoga Parva 43.22 and Stri Parva 7.23 proves the public epigraphic diffusion of epic philosophical ethics.',
            ]
        );
        $claim4->evidence()->syncWithoutDetaching([
            $evEthicalVerse->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Inscription B Brahmi text records the triad.'],
            $evMahabharataParallel->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Philological identity with Sanatsujatiya epic verses.'],
        ]);

        $claim5 = Claim::updateOrCreate(
            [
                'statement' => 'The Heliodorus Pillar did not stand in isolation, but was erected within a monumental sacred compound immediately facing a timber-and-brick elliptical temple of Vasudeva founded in the 3rd–2nd century BCE and aligned with a row of seven other contemporary dhvaja-stambhas.',
            ],
            [
                'claimable_type' => HeritageSite::class,
                'claimable_id' => $site->id,
                'claim_type' => 'ARCHAEOLOGICAL_STRATIGRAPHY',
                'consensus_status' => 'STRONG_CONSENSUS',
                'summary_justification' => 'M.D. Khare\'s ASI excavations uncovered the subterranean elliptical temple plinth and seven companion column foundations in direct alignment with the Heliodorus column.',
            ]
        );
        $claim5->evidence()->syncWithoutDetaching([
            $evKhareTemple->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'Stratigraphically sealed elliptical temple plinth dated to 3rd–2nd c. BCE.'],
            $evPillarAvenue->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'CORROBORATIVE', 'analysis_notes' => 'Seven aligned pillar foundations confirm monumental processional avenue.'],
            $evSteelWedge->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'CORROBORATIVE', 'analysis_notes' => 'Subterranean foundation slabs and steel wedge confirm engineered stability.'],
        ]);

        $claim6 = Claim::updateOrCreate(
            [
                'statement' => 'A historiographical debate exists regarding whether King Kasiputra Bhagabhadra of the inscription is the fifth Shunga emperor (Bhadraka / Bhagavata in Puranic lists) ruling from Vidisha or Pataliputra, or an independent local ruler of eastern Malwa.',
            ],
            [
                'claimable_type' => HeritageObject::class,
                'claimable_id' => $object->id,
                'claim_type' => 'HISTORICAL_ATTRIBUTION',
                'consensus_status' => 'ONGOING_DEBATE',
                'summary_justification' => 'Bhandarkar, Raychaudhuri, and Sircar identify Bhagabhadra with Shunga Bhagavata based on Puranic 32-year reign lengths and Vidisha as secondary capital; Tarn and Narain argue the lack of imperial titles indicates a local Malwa monarch.',
            ]
        );
        $claim6->evidence()->syncWithoutDetaching([
            $evSynchronism->id => ['relationship_type' => 'SUPPORTS', 'scholarly_weight' => 'PRIMARY', 'analysis_notes' => 'King name Kasiputra Bhagabhadra without imperial titles creates dynastic debate.'],
        ]);
    }
}
