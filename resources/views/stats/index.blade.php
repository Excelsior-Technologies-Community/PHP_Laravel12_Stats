<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Laravel Project Statistics</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f4f6f9;
        }

        .dashboard-header {
            background: linear-gradient(
                135deg,
                #0d6efd,
                #6610f2
            );

            color: white;
            border-radius: 15px;
        }

        .stat-card {
            border: 0;
            border-radius: 15px;
            transition: 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
        }

        .json-container {
            max-height: 500px;
            overflow: auto;
            background: #111827;
            color: #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            font-size: 13px;
        }

        .component-table {
            min-width: 1000px;
        }

    </style>

</head>

<body>

<div class="container-fluid py-4 px-lg-5">


    {{-- Header --}}

    <div class="dashboard-header p-4 mb-4 shadow-sm">

        <div class="d-flex flex-column flex-lg-row
                    justify-content-between
                    align-items-lg-center">

            <div>

                <h1 class="fw-bold mb-1">
                    📊 Laravel Project Statistics
                </h1>

                <p class="mb-0 opacity-75">
                    Project analysis powered by Wnx Laravel Stats
                </p>

            </div>


            <div class="mt-3 mt-lg-0">

                <form
                    method="POST"
                    action="{{ route('stats.scan') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-light fw-semibold"
                    >
                        🔄 Run New Scan
                    </button>

                </form>

            </div>

        </div>

    </div>


    {{-- Success Message --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Error Message --}}

    @if(session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

    @endif


    {{-- Exception Error --}}

    @isset($error)

        <div class="alert alert-danger">

            <strong>
                Statistics Error:
            </strong>

            <div class="mt-2">
                {{ $error }}
            </div>

        </div>

    @endisset


    @if($latestScan)


        {{-- ========================================================= --}}
        {{-- Main Statistics --}}
        {{-- ========================================================= --}}

        <div class="row g-4 mb-4">


            {{-- Classes --}}

            <div class="col-md-6 col-xl-3">

                <div class="card stat-card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Classes
                        </div>

                        <div class="stat-number text-primary">

                            {{ number_format(
                                $latestScan->number_of_classes
                            ) }}

                        </div>

                        <small class="text-muted">
                            Detected project classes
                        </small>

                    </div>

                </div>

            </div>


            {{-- Methods --}}

            <div class="col-md-6 col-xl-3">

                <div class="card stat-card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Methods
                        </div>

                        <div class="stat-number text-success">

                            {{ number_format(
                                $latestScan->number_of_methods
                            ) }}

                        </div>

                        <small class="text-muted">
                            Detected methods
                        </small>

                    </div>

                </div>

            </div>


            {{-- Lines --}}

            <div class="col-md-6 col-xl-3">

                <div class="card stat-card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Lines
                        </div>

                        <div class="stat-number text-warning">

                            {{ number_format(
                                $latestScan->loc
                            ) }}

                        </div>

                        <small class="text-muted">
                            Total source lines
                        </small>

                    </div>

                </div>

            </div>


            {{-- Logical Lines --}}

            <div class="col-md-6 col-xl-3">

                <div class="card stat-card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Logical Lines
                        </div>

                        <div class="stat-number text-danger">

                            {{ number_format(
                                $latestScan->lloc
                            ) }}

                        </div>

                        <small class="text-muted">
                            Logical lines of code
                        </small>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Additional Statistics --}}
        {{-- ========================================================= --}}

        <div class="row g-4 mb-4">


            {{-- Routes --}}

            <div class="col-md-6 col-xl-3">

                <div class="card stat-card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Routes
                        </div>

                        <div class="stat-number text-info">

                            {{ number_format(
                                $latestScan->number_of_routes
                            ) }}

                        </div>

                        <small class="text-muted">
                            Registered application routes
                        </small>

                    </div>

                </div>

            </div>


            {{-- Code LLOC --}}

            <div class="col-md-6 col-xl-3">

                <div class="card stat-card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Code LLOC
                        </div>

                        <div class="stat-number text-primary">

                            {{ number_format(
                                $latestScan->code_lloc
                            ) }}

                        </div>

                        <small class="text-muted">
                            Logical lines of application code
                        </small>

                    </div>

                </div>

            </div>


            {{-- Test LLOC --}}

            <div class="col-md-6 col-xl-3">

                <div class="card stat-card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Test LLOC
                        </div>

                        <div class="stat-number text-success">

                            {{ number_format(
                                $latestScan->test_lloc
                            ) }}

                        </div>

                        <small class="text-muted">
                            Logical lines in tests
                        </small>

                    </div>

                </div>

            </div>


            {{-- Code/Test Ratio --}}

            <div class="col-md-6 col-xl-3">

                <div class="card stat-card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Code/Test Ratio
                        </div>

                        <div class="stat-number text-dark">

                            {{ number_format(
                                $latestScan->code_to_test_ratio,
                                2
                            ) }}

                        </div>

                        <small class="text-muted">
                            Code to test ratio
                        </small>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Scan Information --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">

                        <strong>
                            Project:
                        </strong>

                        {{ $latestScan->project_name }}

                    </div>

                    <div class="col-md-6 text-md-end">

                        <strong>
                            Last Scan:
                        </strong>

                        {{ $latestScan->scanned_at->format(
                            'd M Y, h:i A'
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Search & Filter --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body">

                <h5 class="fw-bold mb-3">
                    🔎 Search & Filter Statistics
                </h5>


                <form
                    method="GET"
                    action="{{ route('stats.index') }}"
                >

                    <div class="row g-3">


                        {{-- Search --}}

                        <div class="col-md-7">

                            <label class="form-label">
                                Search
                            </label>

                            <input
                                type="text"
                                name="search"
                                value="{{ $search }}"
                                class="form-control"
                                placeholder="Search component..."
                            >

                        </div>


                        {{-- Category --}}

                        <div class="col-md-3">

                            <label class="form-label">
                                Category
                            </label>

                            <select
                                name="filter"
                                class="form-select"
                            >

                                <option
                                    value="all"
                                    {{ $filter === 'all'
                                        ? 'selected'
                                        : '' }}
                                >
                                    All Statistics
                                </option>


                                <option
                                    value="Commands"
                                    {{ $filter === 'Commands'
                                        ? 'selected'
                                        : '' }}
                                >
                                    Commands
                                </option>


                                <option
                                    value="Controllers"
                                    {{ $filter === 'Controllers'
                                        ? 'selected'
                                        : '' }}
                                >
                                    Controllers
                                </option>


                                <option
                                    value="Database Factories"
                                    {{ $filter === 'Database Factories'
                                        ? 'selected'
                                        : '' }}
                                >
                                    Database Factories
                                </option>


                                <option
                                    value="Migrations"
                                    {{ $filter === 'Migrations'
                                        ? 'selected'
                                        : '' }}
                                >
                                    Migrations
                                </option>


                                <option
                                    value="Models"
                                    {{ $filter === 'Models'
                                        ? 'selected'
                                        : '' }}
                                >
                                    Models
                                </option>


                                <option
                                    value="Other"
                                    {{ $filter === 'Other'
                                        ? 'selected'
                                        : '' }}
                                >
                                    Other
                                </option>


                                <option
                                    value="PHPUnit Tests"
                                    {{ $filter === 'PHPUnit Tests'
                                        ? 'selected'
                                        : '' }}
                                >
                                    PHPUnit Tests
                                </option>


                                <option
                                    value="Seeders"
                                    {{ $filter === 'Seeders'
                                        ? 'selected'
                                        : '' }}
                                >
                                    Seeders
                                </option>


                                <option
                                    value="Service Providers"
                                    {{ $filter === 'Service Providers'
                                        ? 'selected'
                                        : '' }}
                                >
                                    Service Providers
                                </option>

                            </select>

                        </div>


                        {{-- Search Button --}}

                        <div class="col-md-2 d-flex align-items-end">

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >
                                Search
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Component Statistics --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <div class="d-flex justify-content-between">

                    <h5 class="fw-bold mb-0">
                        📋 Component Statistics
                    </h5>

                    <span class="badge bg-primary">

                        {{ count($components) }}

                        Results

                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table
                        class="table table-hover mb-0 component-table"
                    >

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Component
                                </th>

                                <th>
                                    Classes
                                </th>

                                <th>
                                    Methods
                                </th>

                                <th>
                                    Methods/Class
                                </th>

                                <th>
                                    LOC
                                </th>

                                <th>
                                    LLOC
                                </th>

                                <th>
                                    LLOC/Method
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        @forelse(
                            $components
                            as $index => $component
                        )

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>


                                <td>

                                    <span class="badge bg-secondary">

                                        {{ $component['name'] }}

                                    </span>

                                </td>


                                <td>

                                    {{ number_format(
                                        $component['number_of_classes']
                                    ) }}

                                </td>


                                <td>

                                    {{ number_format(
                                        $component['number_of_methods']
                                    ) }}

                                </td>


                                <td>

                                    {{ $component['methods_per_class'] }}

                                </td>


                                <td>

                                    {{ number_format(
                                        $component['loc']
                                    ) }}

                                </td>


                                <td>

                                    {{ number_format(
                                        $component['lloc']
                                    ) }}

                                </td>


                                <td>

                                    {{ $component['lloc_per_method'] }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5"
                                >

                                    <div class="text-muted">

                                        No statistics found.

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Raw JSON --}}
        {{-- ========================================================= --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">

                <h5 class="fw-bold mb-0">
                    🧾 Raw JSON Statistics
                </h5>

            </div>


            <div class="card-body">

                <div class="json-container">

<pre class="mb-0 text-light">{{ json_encode(
    $statistics,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
) }}</pre>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Navigation --}}
        {{-- ========================================================= --}}

        <div class="d-flex gap-2 flex-wrap mb-5">

            <a
                href="{{ route('stats.history') }}"
                class="btn btn-dark"
            >
                📈 Statistics History
            </a>

        </div>

    @endif

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>