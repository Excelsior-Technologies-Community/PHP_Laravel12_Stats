<?php

namespace App\Http\Controllers;

use App\Models\StatisticScan;
use App\Services\ProjectStatsService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;
use Throwable;

use App\Services\CodeQualityService;

class StatsController extends Controller
{
    public function __construct(
        protected ProjectStatsService $statsService,
        protected CodeQualityService $qualityService
    ) {
    }

    /**
     * Statistics Dashboard.
     */
    public function index(Request $request)
    {
        try {
            $latestScan = StatisticScan::latest('scanned_at')->first();

            /*
            |--------------------------------------------------------------------------
            | Create First Scan Automatically
            |--------------------------------------------------------------------------
            */

            if (!$latestScan) {
                $latestScan = $this->statsService->createScan();
            }

            $statistics = $latestScan->statistics ?? [];

            /*
            |--------------------------------------------------------------------------
            | Search
            |--------------------------------------------------------------------------
            */

            $search = trim(
                (string) $request->query('search')
            );

            /*
            |--------------------------------------------------------------------------
            | Category Filter
            |--------------------------------------------------------------------------
            */

            $filter = $request->query(
                'filter',
                'all'
            );

            /*
            |--------------------------------------------------------------------------
            | Sorting
            |--------------------------------------------------------------------------
            */

            $sort = $request->query(
                'sort',
                'name'
            );

            $direction = $request->query(
                'direction',
                'asc'
            );

            if (!in_array($direction, ['asc', 'desc'])) {
                $direction = 'asc';
            }

            /*
            |--------------------------------------------------------------------------
            | Component Data
            |--------------------------------------------------------------------------
            */

            $components = $this->statsService->getComponents(
                $statistics
            );

            $components = $this->statsService->filterComponents(
                $components,
                $search,
                $filter
            );

            /*
            |--------------------------------------------------------------------------
            | Sort Components
            |--------------------------------------------------------------------------
            */

            $components = collect($components);

            $allowedSorts = [
                'name',
                'number_of_classes',
                'number_of_methods',
                'methods_per_class',
                'loc',
                'lloc',
                'lloc_per_method',
            ];

            if (!in_array($sort, $allowedSorts)) {
                $sort = 'name';
            }

            $components = $components->sortBy(
                function ($component) use ($sort) {
                    return $component[$sort] ?? 0;
                },
                SORT_NATURAL | SORT_FLAG_CASE,
                $direction === 'desc'
            )->values();

            /*
            |--------------------------------------------------------------------------
            | Component Pagination
            |--------------------------------------------------------------------------
            */

            $perPage = (int) $request->query(
                'per_page',
                5
            );

            $allowedPerPage = [
                5,
                10,
                20,
                50
            ];

            if (!in_array($perPage, $allowedPerPage)) {
                $perPage = 5;
            }

            $currentPage = LengthAwarePaginator::resolveCurrentPage();

            $paginatedComponents = new LengthAwarePaginator(
                $components->forPage(
                    $currentPage,
                    $perPage
                )->values(),
                $components->count(),
                $perPage,
                $currentPage,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'query' => $request->query(),
                ]
            );

            return view(
                'stats.index',
                [
                    'latestScan' => $latestScan,

                    'statistics' => $statistics,

                    'components' => $paginatedComponents,

                    'totalComponents' => $components->count(),

                    'search' => $search,

                    'filter' => $filter,

                    'sort' => $sort,

                    'direction' => $direction,

                    'perPage' => $perPage,
                ]
            );

        } catch (Throwable $e) {

            return view(
                'stats.index',
                [
                    'latestScan' => null,

                    'statistics' => [],

                    'components' => collect(),

                    'totalComponents' => 0,

                    'search' => '',

                    'filter' => 'all',

                    'sort' => 'name',

                    'direction' => 'asc',

                    'perPage' => 5,

                    'error' => $e->getMessage(),
                ]
            );
        }
    }


    /**
     * Generate a fresh statistics scan.
     */
    public function scan()
    {
        try {

            $this->statsService->createScan();

            return redirect()
                ->route('stats.index')
                ->with(
                    'success',
                    'New project statistics scan generated successfully.'
                );

        } catch (Throwable $e) {

            return redirect()
                ->route('stats.index')
                ->with(
                    'error',
                    'Statistics scan failed: ' .
                    $e->getMessage()
                );
        }
    }


    /**
     * Statistics history.
     *
     * Features:
     * - Search
     * - Sorting
     * - Date filtering
     * - Pagination
     *
     * Default:
     * - Sort by ID
     * - Ascending order
     */
    public function history(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        $search = trim(
            (string) $request->query('search')
        );


        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        |
        | Default is now:
        |
        | ID
        | Ascending
        |
        */

        $sort = $request->query(
            'sort',
            'id'
        );

        $direction = $request->query(
            'direction',
            'asc'
        );


        /*
        |--------------------------------------------------------------------------
        | Date Filters
        |--------------------------------------------------------------------------
        */

        $from = $request->query('from');

        $to = $request->query('to');


        /*
        |--------------------------------------------------------------------------
        | Allowed Sorting Columns
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'id',
            'project_name',
            'number_of_classes',
            'number_of_methods',
            'loc',
            'lloc',
            'number_of_routes',
            'scanned_at',
        ];


        /*
        |--------------------------------------------------------------------------
        | Validate Sort
        |--------------------------------------------------------------------------
        */

        if (!in_array($sort, $allowedSorts)) {

            $sort = 'id';
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Direction
        |--------------------------------------------------------------------------
        */

        if (!in_array($direction, ['asc', 'desc'])) {

            $direction = 'asc';
        }


        /*
        |--------------------------------------------------------------------------
        | Query
        |--------------------------------------------------------------------------
        */

        $query = StatisticScan::query();


        /*
        |--------------------------------------------------------------------------
        | Project Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                $q->where(
                    'project_name',
                    'like',
                    '%' . $search . '%'
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | From Date
        |--------------------------------------------------------------------------
        */

        if ($from) {

            $query->whereDate(
                'scanned_at',
                '>=',
                $from
            );
        }


        /*
        |--------------------------------------------------------------------------
        | To Date
        |--------------------------------------------------------------------------
        */

        if ($to) {

            $query->whereDate(
                'scanned_at',
                '<=',
                $to
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Apply Sorting
        |--------------------------------------------------------------------------
        */

        $query->orderBy(
            $sort,
            $direction
        );


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $scans = $query
            ->paginate(4)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | History View
        |--------------------------------------------------------------------------
        */

        return view(
            'stats.history',
            [
                'scans' => $scans,

                'search' => $search,

                'sort' => $sort,

                'direction' => $direction,

                'from' => $from,

                'to' => $to,
            ]
        );
    }


    /**
     * Compare two statistics scans.
     */
    public function compare(
        StatisticScan $oldScan,
        StatisticScan $newScan
    ) {
        $comparison =
            $this->statsService->compare(
                $oldScan,
                $newScan
            );

        return view(
            'stats.compare',
            [
                'oldScan' =>
                    $oldScan,

                'newScan' =>
                    $newScan,

                'comparison' =>
                    $comparison,
            ]
        );
    }


    /**
     * Display one complete scan.
     */
    public function show(
        StatisticScan $scan
    ) {
        return view(
            'stats.show',
            [
                'scan' => $scan,
            ]
        );
    }


    /**
     * Export filtered component statistics as CSV.
     */
    public function exportCsv(Request $request)
    {
        try {

            $latestScan =
                StatisticScan::latest(
                    'scanned_at'
                )->first();

            if (!$latestScan) {

                return redirect()
                    ->route('stats.index')
                    ->with(
                        'error',
                        'No statistics scan available for export.'
                    );
            }


            $statistics =
                $latestScan->statistics ?? [];


            $search = trim(
                (string) $request->query('search')
            );

            $filter = $request->query(
                'filter',
                'all'
            );


            $components =
                $this->statsService->getComponents(
                    $statistics
                );


            $components =
                $this->statsService->filterComponents(
                    $components,
                    $search,
                    $filter
                );


            $sort = $request->query(
                'sort',
                'name'
            );

            $direction = $request->query(
                'direction',
                'asc'
            );


            $allowedSorts = [
                'name',
                'number_of_classes',
                'number_of_methods',
                'methods_per_class',
                'loc',
                'lloc',
                'lloc_per_method',
            ];


            if (!in_array($sort, $allowedSorts)) {

                $sort = 'name';
            }


            if (!in_array($direction, ['asc', 'desc'])) {

                $direction = 'asc';
            }


            $components = collect($components)
                ->sortBy(
                    fn ($component) =>
                        $component[$sort] ?? 0,
                    SORT_NATURAL | SORT_FLAG_CASE,
                    $direction === 'desc'
                )
                ->values();


            $filename =
                'statistics-' .
                now()->format('Y-m-d-H-i-s') .
                '.csv';


            return Response::streamDownload(
                function () use ($components) {

                    $handle = fopen(
                        'php://output',
                        'w'
                    );


                    fputcsv(
                        $handle,
                        [
                            'Component',
                            'Classes',
                            'Methods',
                            'Methods/Class',
                            'LOC',
                            'LLOC',
                            'LLOC/Method',
                        ]
                    );


                    foreach ($components as $component) {

                        fputcsv(
                            $handle,
                            [
                                $component['name'] ?? '',

                                $component['number_of_classes'] ?? 0,

                                $component['number_of_methods'] ?? 0,

                                $component['methods_per_class'] ?? 0,

                                $component['loc'] ?? 0,

                                $component['lloc'] ?? 0,

                                $component['lloc_per_method'] ?? 0,
                            ]
                        );
                    }


                    fclose($handle);
                },
                $filename,
                [
                    'Content-Type' =>
                        'text/csv; charset=UTF-8',
                ]
            );

        } catch (Throwable $e) {

            return redirect()
                ->route('stats.index')
                ->with(
                    'error',
                    'CSV export failed: ' .
                    $e->getMessage()
                );
        }
    }


    /**
     * Export complete statistics as JSON.
     */
    public function exportJson()
    {
        try {

            $latestScan =
                StatisticScan::latest(
                    'scanned_at'
                )->first();


            if (!$latestScan) {

                return redirect()
                    ->route('stats.index')
                    ->with(
                        'error',
                        'No statistics scan available for export.'
                    );
            }


            $filename =
                'statistics-' .
                now()->format('Y-m-d-H-i-s') .
                '.json';


            return response()->json(
                [
                    'project_name' =>
                        $latestScan->project_name,

                    'scanned_at' =>
                        $latestScan->scanned_at,

                    'metrics' =>
                        [
                            'number_of_classes' =>
                                $latestScan->number_of_classes,

                            'number_of_methods' =>
                                $latestScan->number_of_methods,

                            'methods_per_class' =>
                                $latestScan->methods_per_class,

                            'loc' =>
                                $latestScan->loc,

                            'lloc' =>
                                $latestScan->lloc,

                            'lloc_per_method' =>
                                $latestScan->lloc_per_method,

                            'code_lloc' =>
                                $latestScan->code_lloc,

                            'test_lloc' =>
                                $latestScan->test_lloc,

                            'code_to_test_ratio' =>
                                $latestScan->code_to_test_ratio,

                            'number_of_routes' =>
                                $latestScan->number_of_routes,
                        ],

                    'statistics' =>
                        $latestScan->statistics,
                ],
                200,
                [
                    'Content-Disposition' =>
                        'attachment; filename="' .
                        $filename .
                        '"',
                ]
            );

        } catch (Throwable $e) {

            return redirect()
                ->route('stats.index')
                ->with(
                    'error',
                    'JSON export failed: ' .
                    $e->getMessage()
                );
        }
    }


    /**
     * Delete a historical scan.
     */
    public function destroy(
        StatisticScan $scan
    ) {
        $scan->delete();

        return redirect()
            ->route('stats.history')
            ->with(
                'success',
                'Statistics scan deleted successfully.'
            );
    }

    /**
     * Real-Time Code Quality Radar & Maintainability Index Inspector.
     */
    public function codeQuality(Request $request)
    {
        $latestScan = StatisticScan::latest('scanned_at')->first();

        if (!$latestScan) {
            $latestScan = $this->statsService->createScan();
        }

        $qualityAnalysis = $this->qualityService->analyzeCodeQuality($latestScan->statistics ?? []);

        return view('stats.quality', [
            'latestScan' => $latestScan,
            'components' => $qualityAnalysis['components'],
            'overallMi' => $qualityAnalysis['overall_mi'],
            'highRiskCount' => $qualityAnalysis['high_risk_count'],
            'moderateRiskCount' => $qualityAnalysis['moderate_risk_count'],
            'lowRiskCount' => $qualityAnalysis['low_risk_count'],
        ]);
    }

    /**
     * Interactive Code Churn & Multi-Scan Trend Analytics View.
     */
    public function analytics(Request $request)
    {
        $trends = $this->qualityService->getScanTrends();

        return view('stats.analytics', [
            'trends' => $trends,
        ]);
    }

    /**
     * Multi-Scan Trend Analytics JSON API Endpoint.
     */
    public function analyticsJson(Request $request)
    {
        $trends = $this->qualityService->getScanTrends();

        return response()->json([
            'status' => 'success',
            'scans_count' => $trends['total_scans'],
            'data' => [
                'labels' => $trends['labels'],
                'classes' => $trends['classes'],
                'methods' => $trends['methods'],
                'lloc' => $trends['lloc'],
                'routes' => $trends['routes'],
                'test_ratios' => $trends['test_ratios'],
            ]
        ]);
    }

    /**
     * Smart Architecture Health & Anti-Pattern Detector View.
     */
    public function architectureAudit(Request $request)
    {
        $latestScan = StatisticScan::latest('scanned_at')->first();

        if (!$latestScan) {
            $latestScan = $this->statsService->createScan();
        }

        $issues = $this->qualityService->detectAntiPatterns($latestScan);

        return view('stats.audit', [
            'latestScan' => $latestScan,
            'issues' => $issues,
        ]);
    }

    /**
     * Export Architecture Audit Report as CSV.
     */
    public function exportAuditCsv(Request $request)
    {
        $latestScan = StatisticScan::latest('scanned_at')->first();

        if (!$latestScan) {
            return redirect()->route('stats.audit')->with('error', 'No scan available for audit export.');
        }

        $issues = $this->qualityService->detectAntiPatterns($latestScan);
        $filename = 'architecture-audit-' . now()->format('Y-m-d-H-i-s') . '.csv';

        return Response::streamDownload(
            function () use ($issues) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Title', 'Severity', 'Component', 'Metric', 'Description', 'Recommendation']);

                foreach ($issues as $issue) {
                    fputcsv($handle, [
                        $issue['title'] ?? '',
                        $issue['severity'] ?? '',
                        $issue['component'] ?? '',
                        $issue['metric'] ?? '',
                        $issue['description'] ?? '',
                        $issue['recommendation'] ?? '',
                    ]);
                }

                fclose($handle);
            },
            $filename,
            ['Content-Type' => 'text/csv; charset=UTF-8']
        );
    }
}