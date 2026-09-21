<?php

namespace App\Http\Controllers;

use App\Models\StatisticScan;
use App\Services\ProjectStatsService;
use Illuminate\Http\Request;
use Throwable;

class StatsController extends Controller
{
    public function __construct(
        protected ProjectStatsService $statsService
    ) {
    }


    /**
     * Statistics Dashboard.
     */
    public function index(Request $request)
    {
        try {

            $latestScan = StatisticScan::latest(
                'scanned_at'
            )->first();


            /*
            |--------------------------------------------------------------------------
            | Create First Scan Automatically
            |--------------------------------------------------------------------------
            */

            if (!$latestScan) {

                $latestScan =
                    $this->statsService->createScan();
            }


            $statistics =
                $latestScan->statistics;


            /*
            |--------------------------------------------------------------------------
            | Search & Filter
            |--------------------------------------------------------------------------
            */

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


            return view(
                'stats.index',
                [
                    'latestScan' =>
                        $latestScan,

                    'statistics' =>
                        $statistics,

                    'components' =>
                        $components,

                    'search' =>
                        $search,

                    'filter' =>
                        $filter,
                ]
            );

        } catch (Throwable $e) {

            return view(
                'stats.index',
                [
                    'latestScan' =>
                        null,

                    'statistics' =>
                        [],

                    'components' =>
                        [],

                    'search' =>
                        '',

                    'filter' =>
                        'all',

                    'error' =>
                        $e->getMessage(),
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
                    'Statistics scan failed: '
                    . $e->getMessage()
                );
        }
    }


    /**
     * Statistics history.
     */
    public function history()
    {
        $scans = StatisticScan::latest(
            'scanned_at'
        )->paginate(10);

        return view(
            'stats.history',
            compact('scans')
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
}