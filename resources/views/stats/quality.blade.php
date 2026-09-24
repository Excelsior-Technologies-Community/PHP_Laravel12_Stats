<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Code Quality Radar & Maintainability Index</title>
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
                    <h1 class="fw-bold mb-1">🎯 Code Quality Radar & Maintainability Index</h1>
                    <p class="mb-0 opacity-75">Component Complexity, Risk Metrics & Refactoring Radar</p>
                </div>
                <div class="mt-3 mt-lg-0">
                    <a href="{{ route('stats.index') }}" class="btn btn-light fw-semibold">⬅️ Back to Dashboard</a>
                </div>
            </div>

            {{-- Top Navigation Pills --}}
            <ul class="nav nav-pills mt-4 bg-white p-2 rounded-3 shadow-sm">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('stats.index') }}">📊 Overview Stats</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('stats.quality') }}">🎯 Code Quality Radar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('stats.analytics') }}">📈 Trend Analytics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('stats.audit') }}">💡 Architecture Audit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('stats.history') }}">📜 Scan History</a>
                </li>
            </ul>
        </div>

        {{-- Overall Health Summary --}}
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted">Overall Maintainability Index</div>
                        <div class="stat-number text-primary">{{ $overallMi }} / 100</div>
                        <small class="text-muted">Weighted MI score across project</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted">Low Risk (Clean Code)</div>
                        <div class="stat-number text-success">{{ $lowRiskCount }} Components</div>
                        <small class="text-muted">High maintainability</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted">Moderate Risk</div>
                        <div class="stat-number text-warning">{{ $moderateRiskCount }} Components</div>
                        <small class="text-muted">Needs attention</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted">High Risk (Spaghetti/God)</div>
                        <div class="stat-number text-danger">{{ $highRiskCount }} Components</div>
                        <small class="text-muted">Refactor urgently</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Visual Radar & Complexity Chart --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 fw-bold">🕸️ Maintainability Radar Chart</div>
                    <div class="card-body">
                        <canvas id="qualityRadarChart" height="260"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 fw-bold">📊 Cyclomatic vs Cognitive Complexity</div>
                    <div class="card-body">
                        <canvas id="complexityBarChart" height="260"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Component Quality Table --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">🔍 Component Quality Inspector Table</h5>
                <span class="badge bg-primary fs-6">{{ count($components) }} Components Analyzed</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Component Name</th>
                                <th>Classes</th>
                                <th>Methods</th>
                                <th>Methods / Class</th>
                                <th>LLOC / Method</th>
                                <th>Maintainability Index</th>
                                <th>Cyclomatic Complexity</th>
                                <th>Risk Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($components as $comp)
                            <tr>
                                <td class="fw-bold">{{ $comp['name'] }}</td>
                                <td>{{ $comp['classes'] }}</td>
                                <td>{{ $comp['methods'] }}</td>
                                <td>{{ $comp['methods_per_class'] }}</td>
                                <td>{{ $comp['lloc_per_method'] }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar bg-{{ $comp['badge_color'] }}" style="width: {{ $comp['maintainability_index'] }}%"></div>
                                        </div>
                                        <span class="fw-bold">{{ $comp['maintainability_index'] }}</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-secondary">~{{ $comp['cyclomatic_complexity'] }}</span></td>
                                <td>
                                    <span class="badge bg-{{ $comp['badge_color'] }} px-3 py-2 fs-6">
                                        {{ $comp['risk_level'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const componentsData = @json($components);
        const labels = componentsData.map(c => c.name);
        const miData = componentsData.map(c => c.maintainability_index);
        const cycloData = componentsData.map(c => c.cyclomatic_complexity);
        const cogData = componentsData.map(c => c.cognitive_complexity);

        // 1. Radar Chart
        new Chart(document.getElementById('qualityRadarChart'), {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Maintainability Index (0-100)',
                    data: miData,
                    backgroundColor: 'rgba(13, 110, 253, 0.2)',
                    borderColor: '#0d6efd',
                    pointBackgroundColor: '#0d6efd',
                }]
            },
            options: {
                responsive: true,
                scales: { r: { min: 0, max: 100 } }
            }
        });

        // 2. Bar Chart
        new Chart(document.getElementById('complexityBarChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Cyclomatic Complexity',
                        data: cycloData,
                        backgroundColor: '#ffc107',
                    },
                    {
                        label: 'Cognitive Complexity',
                        data: cogData,
                        backgroundColor: '#dc3545',
                    }
                ]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</body>
</html>
