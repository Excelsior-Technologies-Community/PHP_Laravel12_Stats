<?php

namespace App\Services;

use App\Models\StatisticScan;
use Illuminate\Support\Carbon;
use RuntimeException;
use Symfony\Component\Process\Process;

class ProjectStatsService
{
    /**
     * Run Wnx Laravel Stats and return the JSON result.
     */
    public function generateStats(): array
    {
        $process = new Process([
            PHP_BINARY,
            base_path('artisan'),
            'stats',
            '--json',
        ]);

        $process->setWorkingDirectory(
            base_path()
        );

        $process->setTimeout(120);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException(
                trim($process->getErrorOutput())
                ?: 'Unable to generate Laravel statistics.'
            );
        }

        $output = trim(
            $process->getOutput()
        );

        if ($output === '') {
            throw new RuntimeException(
                'Laravel Stats returned an empty response.'
            );
        }

        $statistics = json_decode(
            $output,
            true
        );

        if (
            json_last_error() !== JSON_ERROR_NONE
        ) {
            throw new RuntimeException(
                'Laravel Stats returned invalid JSON: '
                . json_last_error_msg()
            );
        }

        if (
            !isset($statistics['components']) ||
            !isset($statistics['total']) ||
            !isset($statistics['meta'])
        ) {
            throw new RuntimeException(
                'Unexpected Laravel Stats JSON structure.'
            );
        }

        return $statistics;
    }


    /**
     * Create and store a new statistics scan.
     */
    public function createScan(): StatisticScan
    {
        $statistics = $this->generateStats();

        $total = $statistics['total'];

        $meta = $statistics['meta'];

        return StatisticScan::create([
            'project_name' => basename(
                base_path()
            ),

            /*
            |--------------------------------------------------------------------------
            | Total Statistics
            |--------------------------------------------------------------------------
            */

            'number_of_classes' =>
                $total['number_of_classes'] ?? 0,

            'number_of_methods' =>
                $total['number_of_methods'] ?? 0,

            'methods_per_class' =>
                $total['methods_per_class'] ?? 0,

            'loc' =>
                $total['loc'] ?? 0,

            'lloc' =>
                $total['lloc'] ?? 0,

            'lloc_per_method' =>
                $total['lloc_per_method'] ?? 0,


            /*
            |--------------------------------------------------------------------------
            | Meta Statistics
            |--------------------------------------------------------------------------
            */

            'code_lloc' =>
                $meta['code_lloc'] ?? 0,

            'test_lloc' =>
                $meta['test_lloc'] ?? 0,

            'code_to_test_ratio' =>
                $meta['code_to_test_ratio'] ?? 0,

            'number_of_routes' =>
                $meta['number_of_routes'] ?? 0,


            /*
            |--------------------------------------------------------------------------
            | Complete JSON
            |--------------------------------------------------------------------------
            */

            'statistics' => $statistics,

            'scanned_at' => Carbon::now(),
        ]);
    }


    /**
     * Get component statistics.
     */
    public function getComponents(
        array $statistics
    ): array {
        return $statistics['components'] ?? [];
    }


    /**
     * Search and filter component statistics.
     */
    public function filterComponents(
        array $components,
        string $search = '',
        string $filter = 'all'
    ): array {
        return array_values(
            array_filter(
                $components,
                function (array $component) use (
                    $search,
                    $filter
                ) {
                    $name = $component['name'] ?? '';

                    /*
                    |--------------------------------------------------------------------------
                    | Category Filter
                    |--------------------------------------------------------------------------
                    */

                    if ($filter !== 'all') {

                        if (
                            strtolower($name)
                            !== strtolower($filter)
                        ) {
                            return false;
                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Search Filter
                    |--------------------------------------------------------------------------
                    */

                    if ($search !== '') {

                        return str_contains(
                            strtolower($name),
                            strtolower($search)
                        );
                    }

                    return true;
                }
            )
        );
    }


    /**
     * Compare two statistics scans.
     */
    public function compare(
        StatisticScan $oldScan,
        StatisticScan $newScan
    ): array {
        return [
            'number_of_classes' => [
                'old' =>
                    $oldScan->number_of_classes,

                'new' =>
                    $newScan->number_of_classes,

                'difference' =>
                    $newScan->number_of_classes
                    -
                    $oldScan->number_of_classes,
            ],

            'number_of_methods' => [
                'old' =>
                    $oldScan->number_of_methods,

                'new' =>
                    $newScan->number_of_methods,

                'difference' =>
                    $newScan->number_of_methods
                    -
                    $oldScan->number_of_methods,
            ],

            'methods_per_class' => [
                'old' =>
                    $oldScan->methods_per_class,

                'new' =>
                    $newScan->methods_per_class,

                'difference' =>
                    $newScan->methods_per_class
                    -
                    $oldScan->methods_per_class,
            ],

            'loc' => [
                'old' =>
                    $oldScan->loc,

                'new' =>
                    $newScan->loc,

                'difference' =>
                    $newScan->loc
                    -
                    $oldScan->loc,
            ],

            'lloc' => [
                'old' =>
                    $oldScan->lloc,

                'new' =>
                    $newScan->lloc,

                'difference' =>
                    $newScan->lloc
                    -
                    $oldScan->lloc,
            ],

            'lloc_per_method' => [
                'old' =>
                    $oldScan->lloc_per_method,

                'new' =>
                    $newScan->lloc_per_method,

                'difference' =>
                    $newScan->lloc_per_method
                    -
                    $oldScan->lloc_per_method,
            ],

            'code_lloc' => [
                'old' =>
                    $oldScan->code_lloc,

                'new' =>
                    $newScan->code_lloc,

                'difference' =>
                    $newScan->code_lloc
                    -
                    $oldScan->code_lloc,
            ],

            'test_lloc' => [
                'old' =>
                    $oldScan->test_lloc,

                'new' =>
                    $newScan->test_lloc,

                'difference' =>
                    $newScan->test_lloc
                    -
                    $oldScan->test_lloc,
            ],

            'code_to_test_ratio' => [
                'old' =>
                    $oldScan->code_to_test_ratio,

                'new' =>
                    $newScan->code_to_test_ratio,

                'difference' =>
                    $newScan->code_to_test_ratio
                    -
                    $oldScan->code_to_test_ratio,
            ],

            'number_of_routes' => [
                'old' =>
                    $oldScan->number_of_routes,

                'new' =>
                    $newScan->number_of_routes,

                'difference' =>
                    $newScan->number_of_routes
                    -
                    $oldScan->number_of_routes,
            ],
        ];
    }
}