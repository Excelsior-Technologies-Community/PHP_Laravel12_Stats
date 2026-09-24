<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Code Churn & Multi-Scan Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: #f4f6f9; }
        .dashboard-header {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            color: white; border-radius: 15px;
        }
        .stat-card { border: 0; border-radius: 15px; transition: 0.2s; }
        .stat-card:hover { transform: translateY(-3px); }
        .stat-number { font-size: 32px; font-weight: 700; }
        .nav-pills .nav-link.active { background-color: #0d6efd; font-weight: 600; }
        .nav-pills .nav-link { color: #495057; font-weight: 500; }
    </style>
</head>
<body>
    <div class="container-fluid py-4 px-lg-5">

        {{-- Header Navigation --}}
        <div class="dashboard-header p-4 mb-4 shadow-sm">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
                <div>
                    <h1 class="fw-bold mb-1">📈 Code Churn & Multi-Scan Growth Analytics</h1>
                    <p class="mb-0 opacity-75">Historical Code Evolution, Volume Trends & Code-to-Test Ratios</p>
                </div>
                <div class="mt-3 mt-lg-0 d-flex gap-2">
                    <a href="{{ route('stats.analytics.json') }}" target="_blank" class="btn btn-warning fw-semibold">🧾 Analytics JSON API</a>
                    <a href="{{ route('stats.index') }}" class="btn btn-light fw-semibold">⬅️ Back to Dashboard</a>
                </div>
            </div>

            {{-- Top Navigation Pills --}}
            <ul class="nav nav-pills mt-4 bg-white p-2 rounded-3 shadow-sm">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('stats.index') }}">📊 Overview Stats</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('stats.quality') }}">🎯 Code Quality Radar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('stats.analytics') }}">📈 Trend Analytics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('stats.audit') }}">💡 Architecture Audit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('stats.history') }}">📜 Scan History</a>
                </li>
            </ul>
        </div>

        {{-- Summary Cards --}}
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted">Total Scans Recorded</div>
                        <div class="stat-number text-primary">{{ $trends['total_scans'] }}</div>
                        <small class="text-muted">Historical snapshots</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted">Latest Classes Count</div>
                        <div class="stat-number text-success">{{ end($trends['classes']) ?: 0 }}</div>
                        <small class="text-muted">Detected project classes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted">Latest LLOC</div>
                        <div class="stat-number text-warning">{{ end($trends['lloc']) ?: 0 }}</div>
                        <small class="text-muted">Logical lines of code</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted">Latest Code/Test Ratio</div>
                        <div class="stat-number text-info">{{ end($trends['test_ratios']) ?: 0 }}</div>
                        <small class="text-muted">Automated test density</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Interactive Trend Charts --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 fw-bold">📈 Code Volume & Growth Timeline (LLOC & Methods)</div>
                    <div class="card-body">
                        <canvas id="growthTrendChart" height="260"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 fw-bold">🧪 Code-to-Test Ratio Evolution</div>
                    <div class="card-body">
                        <canvas id="ratioTrendChart" height="260"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Historical Scans Data Table --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">📜 Scan Snapshots Log</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Scan ID</th>
                                <th>Project Name</th>
                                <th>Classes</th>
                                <th>Methods</th>
                                <th>LOC</th>
                                <th>LLOC</th>
                                <th>Routes</th>
                                <th>Code/Test Ratio</th>
                                <th>Scanned At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trends['scans'] as $scan)
                            <tr>
                                <td>#{{ $scan->id }}</td>
                                <td class="fw-bold">{{ $scan->project_name }}</td>
                                <td>{{ number_format($scan->number_of_classes) }}</td>
                                <td>{{ number_format($scan->number_of_methods) }}</td>
                                <td>{{ number_format($scan->loc) }}</td>
                                <td>{{ number_format($scan->lloc) }}</td>
                                <td>{{ number_format($scan->number_of_routes) }}</td>
                                <td><span class="badge bg-secondary fs-6">{{ $scan->code_to_test_ratio }}</span></td>
                                <td>{{ $scan->scanned_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const trendData = @json($trends);

        // 1. Growth Chart
        new Chart(document.getElementById('growthTrendChart'), {
            type: 'line',
            data: {
                labels: trendData.labels,
                datasets: [
                    {
                        label: 'LLOC (Logical Lines)',
                        data: trendData.lloc,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Methods',
                        data: trendData.methods,
                        borderColor: '#198754',
                        tension: 0.3,
                        fill: false
                    },
                    {
                        label: 'Classes',
                        data: trendData.classes,
                        borderColor: '#ffc107',
                        tension: 0.3,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });

        // 2. Test Ratio Chart
        new Chart(document.getElementById('ratioTrendChart'), {
            type: 'bar',
            data: {
                labels: trendData.labels,
                datasets: [{
                    label: 'Code to Test Ratio',
                    data: trendData.test_ratios,
                    backgroundColor: '#0dcaf0',
                    borderColor: '#0bacd0',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</body>
</html>
