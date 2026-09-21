<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Statistics Comparison</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f4f6f9;
        }

        .page-header {
            background: linear-gradient(
                135deg,
                #0d6efd,
                #6610f2
            );

            color: white;
            border-radius: 15px;
        }

        .metric-card {
            border: 0;
            border-radius: 15px;
        }

        .difference-positive {
            color: #198754;
            font-weight: 700;
        }

        .difference-negative {
            color: #dc3545;
            font-weight: 700;
        }

        .difference-neutral {
            color: #6c757d;
            font-weight: 700;
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
                    📊 Statistics Comparison
                </h1>

                <p class="mb-0 opacity-75">
                    Compare two Laravel project statistics scans
                </p>

            </div>


            <div class="mt-3 mt-lg-0">

                <a
                    href="{{ route('stats.history') }}"
                    class="btn btn-light"
                >
                    ← Statistics History
                </a>

            </div>

        </div>

    </div>


    {{-- Scan Information --}}

    <div class="row g-4 mb-4">


        <div class="col-md-6">

            <div class="card metric-card shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Previous Scan
                    </h5>

                    <hr>

                    <p class="mb-1">

                        <strong>
                            Project:
                        </strong>

                        {{ $oldScan->project_name }}

                    </p>

                    <p class="mb-0">

                        <strong>
                            Scanned:
                        </strong>

                        {{ $oldScan->scanned_at->format(
                            'd M Y, h:i A'
                        ) }}

                    </p>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card metric-card shadow-sm h-100">

                <div class="card-body">

                    <h5 class="fw-bold">
                        Current Scan
                    </h5>

                    <hr>

                    <p class="mb-1">

                        <strong>
                            Project:
                        </strong>

                        {{ $newScan->project_name }}

                    </p>

                    <p class="mb-0">

                        <strong>
                            Scanned:
                        </strong>

                        {{ $newScan->scanned_at->format(
                            'd M Y, h:i A'
                        ) }}

                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- Comparison Table --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h5 class="fw-bold mb-0">
                📈 Metric Comparison
            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Metric
                            </th>

                            <th>
                                Previous
                            </th>

                            <th>
                                Current
                            </th>

                            <th>
                                Difference
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @foreach($comparison as $metric => $values)

                        @php

                            $difference =
                                $values['difference'];

                        @endphp

                        <tr>

                            <td>

                                <strong>

                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $metric
                                        )
                                    ) }}

                                </strong>

                            </td>


                            <td>

                                {{ number_format(
                                    $values['old'],
                                    2
                                ) }}

                            </td>


                            <td>

                                {{ number_format(
                                    $values['new'],
                                    2
                                ) }}

                            </td>


                            <td>

                                @if($difference > 0)

                                    <span
                                        class="difference-positive"
                                    >
                                        +{{ number_format(
                                            $difference,
                                            2
                                        ) }}
                                    </span>

                                @elseif($difference < 0)

                                    <span
                                        class="difference-negative"
                                    >
                                        {{ number_format(
                                            $difference,
                                            2
                                        ) }}
                                    </span>

                                @else

                                    <span
                                        class="difference-neutral"
                                    >
                                        0.00
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>


</div>

</body>

</html>