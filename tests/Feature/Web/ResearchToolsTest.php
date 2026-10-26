<?php

namespace Tests\Feature\Web;

use App\Models\HeritageSite;
use App\Models\Inscription;
use App\Models\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResearchToolsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_can_access_research_tools_workbench(): void
    {
        $response = $this->get('/research-tools');

        $response->assertStatus(200);
        $response->assertSee('Academic Research Tools & Evidence Workbench', false);
        $response->assertSee('Academic Citation Generator', false);
        $response->assertSee('Chronological & Stratigraphical Synchronizer', false);
        $response->assertSee('Academic Evidence & Consensus Matrix', false);
    }

    public function test_citation_engine_generates_apa_chicago_harvard_bibtex_for_site(): void
    {
        $site = HeritageSite::create([
            'name' => 'Sanchi Stupa 1',
            'slug' => 'sanchi-stupa-1',
            'site_type' => 'Buddhist Stupa',
            'dating_statement' => 'c. 3rd century BCE',
            'protection_status' => 'ASI Protected Monument',
            'summary' => 'Principal Great Stupa at Sanchi.',
            'is_published' => true,
        ]);

        $response = $this->getJson("/api/v1/research/citation?type=site&id={$site->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'entity' => 'site',
            'name' => 'Sanchi Stupa 1',
        ]);

        $data = $response->json('citations');
        $this->assertArrayHasKey('apa', $data);
        $this->assertArrayHasKey('chicago', $data);
        $this->assertArrayHasKey('harvard', $data);
        $this->assertArrayHasKey('bibtex', $data);
        $this->assertArrayHasKey('wikipedia', $data);

        $this->assertStringContainsString('Sanchi Stupa 1', $data['apa']);
        $this->assertStringContainsString('@misc{bha_site_', $data['bibtex']);
    }

    public function test_citation_engine_generates_citations_for_inscription(): void
    {
        $insc = Inscription::create([
            'title' => 'Heliodorus Garuda Pillar Edict',
            'slug' => 'heliodorus-garuda-pillar-edict',
            'language' => 'Prakrit',
            'script' => 'Brahmi',
            'dating_statement' => 'c. 113 BCE',
            'translation' => 'Commemorates the erection of the Garuda-pillar of Vasudeva.',
            'is_published' => true,
        ]);

        $response = $this->getJson("/api/v1/research/citation?type=inscription&id={$insc->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'entity' => 'inscription',
        ]);

        $data = $response->json('citations');
        $this->assertStringContainsString('Heliodorus Garuda Pillar Edict', $data['apa']);
        $this->assertStringContainsString('@misc{bha_inscription_', $data['bibtex']);
    }

    public function test_citation_engine_generates_citations_for_source(): void
    {
        $source = Source::create([
            'title' => 'Epigraphia Indica Volume VIII',
            'authors' => 'Hultzsch, E.',
            'publication_year' => 1905,
            'publisher' => 'Superintendent of Government Printing, Calcutta',
            'source_type' => 'BOOK',
            'citation_key' => 'HULTZSCH1905',
        ]);

        $response = $this->getJson("/api/v1/research/citation?type=source&id={$source->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'entity' => 'source',
        ]);

        $data = $response->json('citations');
        $this->assertStringContainsString('Hultzsch, E.', $data['apa']);
        $this->assertStringContainsString('@book{HULTZSCH1905', $data['bibtex']);
    }

    public function test_public_user_can_access_developer_api_hub(): void
    {
        $response = $this->get('/developers');

        $response->assertStatus(200);
        $response->assertSee('REST API v1 & Developer Documentation', false);
        $response->assertSee('Base URL', false);
        $response->assertSee('Quick Start Examples', false);
        $response->assertSee('API v1 Endpoints Directory', false);
    }
}
