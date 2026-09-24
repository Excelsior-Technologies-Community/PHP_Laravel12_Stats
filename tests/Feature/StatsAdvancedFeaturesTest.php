<?php

namespace Tests\Feature;

use App\Models\StatisticScan;
use App\Services\CodeQualityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatsAdvancedFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a dummy scan for testing
        StatisticScan::create([
            'project_name' => 'TestProject',
            'number_of_classes' => 20,
            'number_of_methods' => 80,
            'methods_per_class' => 4.0,
            'loc' => 1200,
            'lloc' => 600,
            'lloc_per_method' => 7.5,
            'code_lloc' => 500,
            'test_lloc' => 100,
            'code_to_test_ratio' => 0.2,
            'number_of_routes' => 15,
            'statistics' => [
                'meta' => [
                    'code_lloc' => 500,
                    'test_lloc' => 100,
                    'code_to_test_ratio' => 0.2,
                    'number_of_routes' => 15,
                ],
                'total' => [
                    'number_of_classes' => 20,
                    'number_of_methods' => 80,
                    'methods_per_class' => 4.0,
                    'loc' => 1200,
                    'lloc' => 600,
                    'lloc_per_method' => 7.5,
                ],
                'components' => [
                    [
                        'name' => 'Controllers',
                        'number_of_classes' => 5,
                        'number_of_methods' => 30,
                        'methods_per_class' => 6.0,
                        'loc' => 400,
                        'lloc' => 250,
                        'lloc_per_method' => 8.3,
                    ],
                    [
                        'name' => 'Models',
                        'number_of_classes' => 8,
                        'number_of_methods' => 24,
                        'methods_per_class' => 3.0,
                        'loc' => 300,
                        'lloc' => 150,
                        'lloc_per_method' => 6.25,
                    ],
                ]
            ],
            'scanned_at' => now(),
        ]);
    }

    /**
     * Test CodeQualityService maintainability index calculation
     */
    public function test_service_calculates_maintainability_index(): void
    {
        $service = new CodeQualityService();
        $scan = StatisticScan::first();

        $analysis = $service->analyzeCodeQuality($scan->statistics);

        $this->assertArrayHasKey('overall_mi', $analysis);
        $this->assertArrayHasKey('components', $analysis);
        $this->assertGreaterThan(0, $analysis['overall_mi']);
        $this->assertCount(2, $analysis['components']);
    }

    /**
     * Test CodeQualityService anti-pattern rule engine
     */
    public function test_service_detects_architecture_anti_patterns(): void
    {
        $service = new CodeQualityService();
        $scan = StatisticScan::first();

        $issues = $service->detectAntiPatterns($scan);

        $this->assertNotEmpty($issues);
        $this->assertEquals('⚠️ Low Automated Test Coverage', $issues[0]['title']);
    }

    /**
     * Test Code Quality Radar Page Route
     */
    public function test_code_quality_radar_page_loads_successfully(): void
    {
        $response = $this->get('/stats/code-quality');

        $response->assertStatus(200);
        $response->assertSee('Code Quality Radar');
        $response->assertSee('Maintainability Index');
    }

    /**
     * Test Multi-Scan Trend Analytics Page Route
     */
    public function test_analytics_page_loads_successfully(): void
    {
        $response = $this->get('/stats/analytics');

        $response->assertStatus(200);
        $response->assertSee('Code Churn');
        $response->assertSee('Growth Analytics');
    }

    /**
     * Test Multi-Scan Trend Analytics JSON API Endpoint
     */
    public function test_analytics_json_api_returns_valid_trend_metrics(): void
    {
        $response = $this->getJson('/stats/analytics-json');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'scans_count',
            'data' => [
                'labels',
                'classes',
                'methods',
                'lloc',
                'routes',
                'test_ratios',
            ]
        ]);
    }

    /**
     * Test Architecture Audit Page Route
     */
    public function test_architecture_audit_page_loads_successfully(): void
    {
        $response = $this->get('/stats/architecture-audit');

        $response->assertStatus(200);
        $response->assertSee('Architecture Health');
        $response->assertSee('Anti-Pattern Detector');
    }

    /**
     * Test Architecture Audit CSV Export Endpoint
     */
    public function test_architecture_audit_export_streams_csv(): void
    {
        $response = $this->get('/stats/architecture-audit/export');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}
