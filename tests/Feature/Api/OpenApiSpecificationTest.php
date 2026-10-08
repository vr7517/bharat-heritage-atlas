<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class OpenApiSpecificationTest extends TestCase
{
    public function test_openapi_specification_file_exists_and_has_valid_header(): void
    {
        $specPath = base_path('docs/api/openapi.yaml');
        $this->assertFileExists($specPath, 'OpenAPI specification file must exist at docs/api/openapi.yaml');

        $content = file_get_contents($specPath);
        $this->assertNotEmpty($content);

        // Verify OpenAPI version and info
        $this->assertMatchesRegularExpression('/^openapi:\s*3\.1\.0/m', $content);
        $this->assertMatchesRegularExpression('/title:\s*Bharat Heritage Atlas REST API/m', $content);
        $this->assertMatchesRegularExpression('/version:\s*0\.2\.0/m', $content);
    }

    public function test_openapi_specification_covers_all_registered_v1_endpoints(): void
    {
        $specPath = base_path('docs/api/openapi.yaml');
        $content = file_get_contents($specPath);

        // Extract all documented paths under paths:
        preg_match_all('/^  (\/[a-zA-Z0-9_\/\{\}]+):\s*$/m', $content, $matches);
        $documentedPaths = $matches[1] ?? [];

        $expectedPaths = [
            '/sites',
            '/sites/{slug}',
            '/objects',
            '/objects/{slug}',
            '/inscriptions',
            '/inscriptions/{slug}',
            '/sources',
            '/sources/{id}',
            '/claims/{id}',
            '/evidence/{id}',
            '/timeline',
            '/geo/sites',
        ];

        foreach ($expectedPaths as $expectedPath) {
            $this->assertContains(
                $expectedPath,
                $documentedPaths,
                "OpenAPI specification must document endpoint path '{$expectedPath}'"
            );
        }
    }

    public function test_openapi_specification_documents_core_schemas(): void
    {
        $specPath = base_path('docs/api/openapi.yaml');
        $content = file_get_contents($specPath);

        $expectedSchemas = [
            'HeritageSiteListItem',
            'HeritageSiteDetail',
            'HeritageObjectListItem',
            'HeritageObjectDetail',
            'Inscription',
            'Source',
            'Evidence',
            'Claim',
            'TimelineEvent',
            'GeoJsonFeatureCollection',
            'Location',
            'Period',
            'Dynasty',
        ];

        foreach ($expectedSchemas as $schema) {
            $this->assertMatchesRegularExpression(
                '/^\s{4}' . preg_quote($schema, '/') . ':\s*$/m',
                $content,
                "OpenAPI specification must contain schema definition for '{$schema}'"
            );
        }
    }

    public function test_api_markdown_documentation_guide_exists(): void
    {
        $guidePath = base_path('docs/api/README.md');
        $this->assertFileExists($guidePath);

        $content = file_get_contents($guidePath);
        $this->assertStringContainsString('Bharat Heritage Atlas', $content);
        $this->assertStringContainsString('/api/v1', $content);
        $this->assertStringContainsString('openapi.yaml', $content);
        $this->assertStringContainsString('/geo/sites', $content);
        $this->assertStringContainsString('/timeline', $content);
    }
}
