<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Statistics History</title>

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
                #212529,
                #495057
            );

            color: white;
            border-radius: 15px;
        }

    </style>

</head>

<body>

<div class="container-fluid py-4 px-lg-5">


    <div class="page-header p-4 mb-4 shadow-sm">

        <div class="d-flex
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
                    class="btn btn-light"
                >
                    ← Dashboard
                </a>

            </div>

        </div>

    </div>


    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif


    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

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

                            <td>
                                #{{ $scan->id }}
                            </td>


                            <td>

                                <strong>
                                    {{ $scan->project_name }}
                                </strong>

                            </td>


                            <td>

                                {{ number_format(
                                    $scan->number_of_classes
                                ) }}

                            </td>


                            <td>

                                {{ number_format(
                                    $scan->number_of_methods
                                ) }}

                            </td>


                            <td>

                                {{ number_format(
                                    $scan->loc
                                ) }}

                            </td>


                            <td>

                                {{ number_format(
                                    $scan->lloc
                                ) }}

                            </td>


                            <td>

                                {{ number_format(
                                    $scan->number_of_routes
                                ) }}

                            </td>


                            <td>

                                {{ $scan->scanned_at->format(
                                    'd M Y, h:i A'
                                ) }}

                            </td>


                            <td>

                                <div class="d-flex gap-2">

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
                                                class="btn btn-sm btn-primary"
                                            >
                                                Compare
                                            </a>

                                        @endif

                                    @endif


                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'stats.destroy',
                                            $scan
                                        ) }}"
                                        onsubmit="return confirm(
                                            'Delete this statistics scan?'
                                        )"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                        >
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
                                class="text-center py-5"
                            >

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

    </div>


    @if($scans->hasPages())

        <div class="mt-4">

            {{ $scans->links() }}

        </div>

    @endif


</div>

</body>

</html>