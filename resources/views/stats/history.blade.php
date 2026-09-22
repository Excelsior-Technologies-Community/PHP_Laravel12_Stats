<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Statistics History</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .page-header {
            background: linear-gradient(
                135deg,
                #212529,
                #495057
            );

            color: white;
            border-radius: 15px;
        }

        .pagination {
            margin-bottom: 0;
            justify-content: center;
        }

        /*
        |--------------------------------------------------------------------------
        | Numeric Pagination Only
        |--------------------------------------------------------------------------
        */

        .pagination .page-item:first-child,
        .pagination .page-item:last-child {
            display: none;
        }

        .pagination .page-link {
            min-width: 40px;
            text-align: center;
            margin: 0 3px;
            border-radius: 8px !important;
        }

        .pagination .page-item.active .page-link {
            font-weight: 600;
        }
    </style>

</head>


<body>

    <div class="container-fluid py-4 px-lg-5">

        {{-- ========================================================= --}}
        {{-- Header --}}
        {{-- ========================================================= --}}

        <div class="page-header p-4 mb-4 shadow-sm">

            <div
                class="d-flex
                       flex-column
                       flex-lg-row
                       justify-content-between
                       align-items-lg-center">

                <div>

                    <h1 class="fw-bold mb-1">
                        📈 Statistics History
                    </h1>

                    <p class="mb-0 opacity-75">
                        Historical Laravel project statistics scans
                    </p>

                </div>


                <div class="mt-3 mt-lg-0">

                    <a
                        href="{{ route('stats.index') }}"
                        class="btn btn-light">

                        ← Dashboard

                    </a>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Success Message --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div
                class="alert
                       alert-success
                       alert-dismissible
                       fade
                       show">

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- Error Message --}}
        {{-- ========================================================= --}}

        @if(session('error'))

            <div
                class="alert
                       alert-danger
                       alert-dismissible
                       fade
                       show">

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- History Filters --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body">

                <h5 class="fw-bold mb-3">
                    🔎 Search & Filter History
                </h5>


                <form
                    method="GET"
                    action="{{ route('stats.history') }}">

                    <div class="row g-3">


                        {{-- ================================================= --}}
                        {{-- Project Search --}}
                        {{-- ================================================= --}}

                        <div class="col-lg-3">

                            <label class="form-label">
                                Project Search
                            </label>

                            <input
                                type="text"
                                name="search"
                                value="{{ $search }}"
                                class="form-control"
                                placeholder="Search project...">

                        </div>


                        {{-- ================================================= --}}
                        {{-- From Date --}}
                        {{-- ================================================= --}}

                        <div class="col-lg-2">

                            <label class="form-label">
                                From Date
                            </label>

                            <input
                                type="date"
                                name="from"
                                value="{{ $from }}"
                                class="form-control">

                        </div>


                        {{-- ================================================= --}}
                        {{-- To Date --}}
                        {{-- ================================================= --}}

                        <div class="col-lg-2">

                            <label class="form-label">
                                To Date
                            </label>

                            <input
                                type="date"
                                name="to"
                                value="{{ $to }}"
                                class="form-control">

                        </div>


                        {{-- ================================================= --}}
                        {{-- Sort --}}
                        {{-- ================================================= --}}

                        <div class="col-lg-3">

                            <label class="form-label">
                                Sort By
                            </label>

                            <select
                                name="sort"
                                class="form-select">

                                <option
                                    value="scanned_at"
                                    {{ $sort == 'scanned_at'
                                        ? 'selected'
                                        : '' }}>

                                    Scan Date

                                </option>

                                <option
                                    value="project_name"
                                    {{ $sort == 'project_name'
                                        ? 'selected'
                                        : '' }}>

                                    Project Name

                                </option>

                                <option
                                    value="number_of_classes"
                                    {{ $sort == 'number_of_classes'
                                        ? 'selected'
                                        : '' }}>

                                    Classes

                                </option>

                                <option
                                    value="number_of_methods"
                                    {{ $sort == 'number_of_methods'
                                        ? 'selected'
                                        : '' }}>

                                    Methods

                                </option>

                                <option
                                    value="loc"
                                    {{ $sort == 'loc'
                                        ? 'selected'
                                        : '' }}>

                                    LOC

                                </option>

                                <option
                                    value="lloc"
                                    {{ $sort == 'lloc'
                                        ? 'selected'
                                        : '' }}>

                                    LLOC

                                </option>

                                <option
                                    value="number_of_routes"
                                    {{ $sort == 'number_of_routes'
                                        ? 'selected'
                                        : '' }}>

                                    Routes

                                </option>

                            </select>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Direction --}}
                        {{-- ================================================= --}}

                        <div class="col-lg-2">

                            <label class="form-label">
                                Direction
                            </label>

                            <select
                                name="direction"
                                class="form-select">

                                <option
                                    value="desc"
                                    {{ $direction == 'desc'
                                        ? 'selected'
                                        : '' }}>

                                    Descending

                                </option>

                                <option
                                    value="asc"
                                    {{ $direction == 'asc'
                                        ? 'selected'
                                        : '' }}>

                                    Ascending

                                </option>

                            </select>

                        </div>


                        {{-- ================================================= --}}
                        {{-- Buttons --}}
                        {{-- ================================================= --}}

                        <div class="col-12">

                            <div class="d-flex gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-primary">

                                    🔎 Apply Filters

                                </button>


                                <a
                                    href="{{ route('stats.history') }}"
                                    class="btn btn-outline-secondary">

                                    Reset

                                </a>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- History Table --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm border-0">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table
                        class="table table-hover mb-0">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    ID
                                </th>

                                <th>
                                    Project
                                </th>

                                <th>
                                    Classes
                                </th>

                                <th>
                                    Methods
                                </th>

                                <th>
                                    LOC
                                </th>

                                <th>
                                    LLOC
                                </th>

                                <th>
                                    Routes
                                </th>

                                <th>
                                    Scanned At
                                </th>

                                <th>
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($scans as $scan)

                                <tr>

                                    {{-- ID --}}

                                    <td>

                                        #{{ $scan->id }}

                                    </td>


                                    {{-- Project --}}

                                    <td>

                                        <strong>
                                            {{ $scan->project_name }}
                                        </strong>

                                    </td>


                                    {{-- Classes --}}

                                    <td>

                                        {{ number_format(
                                            $scan->number_of_classes
                                        ) }}

                                    </td>


                                    {{-- Methods --}}

                                    <td>

                                        {{ number_format(
                                            $scan->number_of_methods
                                        ) }}

                                    </td>


                                    {{-- LOC --}}

                                    <td>

                                        {{ number_format(
                                            $scan->loc
                                        ) }}

                                    </td>


                                    {{-- LLOC --}}

                                    <td>

                                        {{ number_format(
                                            $scan->lloc
                                        ) }}

                                    </td>


                                    {{-- Routes --}}

                                    <td>

                                        {{ number_format(
                                            $scan->number_of_routes
                                        ) }}

                                    </td>


                                    {{-- Scanned At --}}

                                    <td>

                                        @if($scan->scanned_at)

                                            {{ $scan->scanned_at->format(
                                                'd M Y, h:i A'
                                            ) }}

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- Actions --}}
                                    {{-- ================================================= --}}

                                    <td>

                                        <div
                                            class="d-flex
                                                   gap-2
                                                   flex-wrap">


                                            {{-- View --}}

                                            <a
                                                href="{{ route(
                                                    'stats.show',
                                                    $scan
                                                ) }}"
                                                class="btn
                                                       btn-sm
                                                       btn-info">

                                                View

                                            </a>


                                            {{-- ================================================= --}}
                                            {{-- Compare --}}
                                            {{-- ================================================= --}}

                                            @if(!$loop->last)

                                                @php

                                                    $previousScan =
                                                        $scans
                                                        ->getCollection()
                                                        ->get(
                                                            $loop->index + 1
                                                        );

                                                @endphp


                                                @if($previousScan)

                                                    <a
                                                        href="{{ route(
                                                            'stats.compare',
                                                            [
                                                                'oldScan' =>
                                                                    $previousScan->id,

                                                                'newScan' =>
                                                                    $scan->id,
                                                            ]
                                                        ) }}"
                                                        class="btn
                                                               btn-sm
                                                               btn-primary">

                                                        Compare

                                                    </a>

                                                @endif

                                            @endif


                                            {{-- ================================================= --}}
                                            {{-- Delete --}}
                                            {{-- ================================================= --}}

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'stats.destroy',
                                                    $scan
                                                ) }}"
                                                onsubmit="return confirm(
                                                    'Delete this statistics scan?'
                                                )">

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn
                                                           btn-sm
                                                           btn-outline-danger">

                                                    Delete

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="9"
                                        class="text-center py-5">

                                        <div class="text-muted">

                                            No statistics history found.

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- Numeric Pagination Only --}}
            {{-- ========================================================= --}}

            @if($scans->hasPages())

                <div class="card-footer bg-white py-3">

                    <div class="d-flex justify-content-center">

                        {{ $scans->onEachSide(2)->links(
                            'pagination::bootstrap-5'
                        ) }}

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- Bootstrap JS --}}

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>