<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Statistics Scan Details</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .page-header {
            background: linear-gradient(135deg,
                    #0d6efd,
                    #6610f2);

            color: white;
            border-radius: 15px;
        }

        .metric-card {
            border: 0;
            border-radius: 15px;
        }

        .json-container {
            max-height: 600px;
            overflow: auto;
            background: #111827;
            color: #e5e7eb;
            border-radius: 12px;
            padding: 20px;
        }
    </style>

</head>


<body>

    <div class="container-fluid py-4 px-lg-5">


        {{-- Header --}}

        <div class="page-header p-4 mb-4 shadow-sm">

            <div class="d-flex
                    flex-column
                    flex-lg-row
                    justify-content-between
                    align-items-lg-center">

                <div>

                    <h1 class="fw-bold mb-1">
                        🔍 Statistics Scan Details
                    </h1>

                    <p class="mb-0 opacity-75">
                        Complete information about this project scan
                    </p>

                </div>


                <div class="mt-3 mt-lg-0">

                    <a
                        href="{{ route('stats.history') }}"
                        class="btn btn-light">
                        ← Statistics History
                    </a>

                </div>

            </div>

        </div>


        {{-- Scan Information --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <strong>
                            Project:
                        </strong>

                        {{ $scan->project_name }}

                    </div>


                    <div class="col-md-6">

                        <strong>
                            Scan ID:
                        </strong>

                        #{{ $scan->id }}

                    </div>


                    <div class="col-md-6">

                        <strong>
                            Scanned At:
                        </strong>

                        {{ $scan->scanned_at->format(
                        'd M Y, h:i A'
                    ) }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Metrics --}}

        <div class="row g-4 mb-4">


            <div class="col-md-6 col-xl-3">

                <div class="card metric-card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Classes
                        </div>

                        <h2 class="fw-bold text-primary">

                            {{ number_format(
                            $scan->number_of_classes
                        ) }}

                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-6 col-xl-3">

                <div class="card metric-card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Methods
                        </div>

                        <h2 class="fw-bold text-success">

                            {{ number_format(
                            $scan->number_of_methods
                        ) }}

                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-6 col-xl-3">

                <div class="card metric-card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            LOC
                        </div>

                        <h2 class="fw-bold text-warning">

                            {{ number_format(
                            $scan->loc
                        ) }}

                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-6 col-xl-3">

                <div class="card metric-card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            LLOC
                        </div>

                        <h2 class="fw-bold text-danger">

                            {{ number_format(
                            $scan->lloc
                        ) }}

                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-6 col-xl-3">

                <div class="card metric-card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Routes
                        </div>

                        <h2 class="fw-bold text-info">

                            {{ number_format(
                            $scan->number_of_routes
                        ) }}

                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-6 col-xl-3">

                <div class="card metric-card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Code LLOC
                        </div>

                        <h2 class="fw-bold">

                            {{ number_format(
                            $scan->code_lloc
                        ) }}

                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-6 col-xl-3">

                <div class="card metric-card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Test LLOC
                        </div>

                        <h2 class="fw-bold text-success">

                            {{ number_format(
                            $scan->test_lloc
                        ) }}

                        </h2>

                    </div>

                </div>

            </div>


            <div class="col-md-6 col-xl-3">

                <div class="card metric-card shadow-sm h-100">

                    <div class="card-body">

                        <div class="text-muted">
                            Code/Test Ratio
                        </div>

                        <h2 class="fw-bold">

                            {{ number_format(
                            $scan->code_to_test_ratio,
                            2
                        ) }}

                        </h2>

                    </div>

                </div>

            </div>

        </div>


        {{-- Complete JSON --}}

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">

                <h5 class="fw-bold mb-0">
                    🧾 Complete Scan JSON
                </h5>

            </div>


            <div class="card-body">

                <div class="json-container">

                    <pre class="mb-0 text-light">{{ json_encode(
    $scan->statistics,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
) }}</pre>

                </div>

            </div>

        </div>


    </div>

</body>

</html>