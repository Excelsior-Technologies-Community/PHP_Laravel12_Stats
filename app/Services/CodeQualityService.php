<?php

namespace App\Services;

use App\Models\StatisticScan;

class CodeQualityService
{
    /**
     * Analyze component code quality and calculate Maintainability Index.
     */
    public function analyzeCodeQuality(array $statistics): array
    {
        $components = $statistics['components'] ?? [];

        $analyzedComponents = [];
        $totalMi = 0;
        $count = 0;

        $highRiskCount = 0;
        $moderateRiskCount = 0;
        $lowRiskCount = 0;

        foreach ($components as $component) {
            $name = $component['name'] ?? 'Unknown';
            $classes = $component['number_of_classes'] ?? 0;
            $methods = $component['number_of_methods'] ?? 0;
            $methodsPerClass = $component['methods_per_class'] ?? 0;
            $loc = $component['loc'] ?? 0;
            $lloc = $component['lloc'] ?? 0;
            $llocPerMethod = $component['lloc_per_method'] ?? 0;

            // Maintainability Index (MI) formula estimation
            $locVal = max(1, $loc);
            $methodsPerClassVal = max(1, $methodsPerClass);
            $llocPerMethodVal = max(1, $llocPerMethod);

            $rawMi = 171 - (5.2 * log($locVal)) - (0.23 * $llocPerMethodVal) - (16.2 * log($methodsPerClassVal));
            $miScore = max(0, min(100, round(($rawMi / 171) * 100, 1)));

            // Cognitive & Cyclomatic Complexity Estimations
            $cyclomaticComplexity = round(($llocPerMethod * 0.4) + 1, 1);
            $cognitiveComplexity = round(($lloc * 0.15) + ($methodsPerClass * 1.2), 1);

            $riskLevel = 'Low Risk';
            $badgeColor = 'success';

            if ($miScore < 50 || $cyclomaticComplexity > 8 || $methodsPerClass > 15) {
                $riskLevel = 'High Risk';
                $badgeColor = 'danger';
                $highRiskCount++;
            } elseif ($miScore < 75 || $cyclomaticComplexity > 4) {
                $riskLevel = 'Moderate Risk';
                $badgeColor = 'warning';
                $moderateRiskCount++;
            } else {
                $lowRiskCount++;
            }

            $totalMi += $miScore;
            $count++;

            $analyzedComponents[] = [
                'name' => $name,
                'classes' => $classes,
                'methods' => $methods,
                'methods_per_class' => $methodsPerClass,
                'loc' => $loc,
                'lloc' => $lloc,
                'lloc_per_method' => $llocPerMethod,
                'maintainability_index' => $miScore,
                'cyclomatic_complexity' => $cyclomaticComplexity,
                'cognitive_complexity' => $cognitiveComplexity,
                'risk_level' => $riskLevel,
                'badge_color' => $badgeColor,
            ];
        }

        $overallMi = $count > 0 ? round($totalMi / $count, 1) : 100;

        return [
            'components' => $analyzedComponents,
            'overall_mi' => $overallMi,
            'high_risk_count' => $highRiskCount,
            'moderate_risk_count' => $moderateRiskCount,
            'low_risk_count' => $lowRiskCount,
        ];
    }

    /**
     * Run Rule-Engine for Architecture Health & Anti-Pattern Detection.
     */
    public function detectAntiPatterns(StatisticScan $scan): array
    {
        $statistics = $scan->statistics ?? [];
        $components = $statistics['components'] ?? [];
        $issues = [];

        // 1. Check Code to Test Ratio
        $codeToTestRatio = $scan->code_to_test_ratio;
        if ($codeToTestRatio < 0.5) {
            $issues[] = [
                'title' => '⚠️ Low Automated Test Coverage',
                'severity' => 'Critical',
                'badge' => 'danger',
                'component' => 'Project Infrastructure',
                'metric' => "Code/Test Ratio: {$codeToTestRatio}",
                'description' => 'The ratio of application code to automated unit/feature tests is below 0.5. System is vulnerable to regression bugs.',
                'recommendation' => 'Write Feature and Unit tests for Controllers and Service classes using PHPUnit / Pest PHP.'
            ];
        }

        // 2. Component Level Checks
        foreach ($components as $component) {
            $name = $component['name'] ?? '';
            $methods = $component['number_of_methods'] ?? 0;
            $methodsPerClass = $component['methods_per_class'] ?? 0;
            $lloc = $component['lloc'] ?? 0;
            $llocPerMethod = $component['lloc_per_method'] ?? 0;

            // Fat Controller Warning
            if (str_contains(strtolower($name), 'controller') && ($methodsPerClass > 10 || $lloc > 150)) {
                $issues[] = [
                    'title' => '🚨 Fat Controller Anti-Pattern Detected',
                    'severity' => 'High',
                    'badge' => 'danger',
                    'component' => $name,
                    'metric' => "Methods/Class: {$methodsPerClass}, LLOC: {$lloc}",
                    'description' => "The {$name} component carries excessive business logic and high method density.",
                    'recommendation' => 'Extract business logic into dedicated Service classes, Action classes, or Form Requests.'
                ];
            }

            // God Class Smell
            if ($methodsPerClass > 20 || $lloc > 300) {
                $issues[] = [
                    'title' => '⚠️ God Class Architectural Smell',
                    'severity' => 'Warning',
                    'badge' => 'warning',
                    'component' => $name,
                    'metric' => "Methods/Class: {$methodsPerClass}, LLOC: {$lloc}",
                    'description' => "Component {$name} handles too many responsibilities, violating Single Responsibility Principle (SRP).",
                    'recommendation' => 'Decompose component into single-purpose traits, sub-modules, or helper classes.'
                ];
            }

            // Large Method Smell
            if ($llocPerMethod > 15) {
                $issues[] = [
                    'title' => '⚡ Large Method Smell (High Method Complexity)',
                    'severity' => 'Info',
                    'badge' => 'info',
                    'component' => $name,
                    'metric' => "LLOC per Method: {$llocPerMethod}",
                    'description' => "Methods in {$name} are long and contain multiple nested control flows.",
                    'recommendation' => 'Break large methods into smaller private helper methods or single-action classes.'
                ];
            }
        }

        // Default issue if project is clean
        if (empty($issues)) {
            $issues[] = [
                'title' => '✅ Clean Architecture & High Code Health',
                'severity' => 'Clean',
                'badge' => 'success',
                'component' => 'All Components',
                'metric' => 'Optimal Metrics',
                'description' => 'No major anti-patterns or code smells detected across your codebase.',
                'recommendation' => 'Continue adhering to SOLID principles and maintaining high automated test coverage.'
            ];
        }

        return $issues;
    }

    /**
     * Get trend metrics across all historical scans.
     */
    public function getScanTrends(): array
    {
        $scans = StatisticScan::orderBy('scanned_at', 'asc')->get();

        $labels = [];
        $classes = [];
        $methods = [];
        $lloc = [];
        $routes = [];
        $testRatios = [];

        foreach ($scans as $scan) {
            $labels[] = $scan->scanned_at->format('M d, H:i');
            $classes[] = $scan->number_of_classes;
            $methods[] = $scan->number_of_methods;
            $lloc[] = $scan->lloc;
            $routes[] = $scan->number_of_routes;
            $testRatios[] = $scan->code_to_test_ratio;
        }

        return [
            'total_scans' => $scans->count(),
            'labels' => $labels,
            'classes' => $classes,
            'methods' => $methods,
            'lloc' => $lloc,
            'routes' => $routes,
            'test_ratios' => $testRatios,
            'scans' => $scans,
        ];
    }
}
