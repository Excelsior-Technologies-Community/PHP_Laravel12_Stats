<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Architecture Health & Anti-Pattern Detector</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .dashboard-header {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            color: white; border-radius: 15px;
        }
        .issue-card { border: 0; border-radius: 15px; transition: 0.2s; border-left: 6px solid #0d6efd; }
        .issue-card.danger { border-left-color: #dc3545; }
        .issue-card.warning { border-left-color: #ffc107; }
        .issue-card.info { border-left-color: #0dcaf0; }
        .issue-card.success { border-left-color: #198754; }
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
                    <h1 class="fw-bold mb-1">💡 Architecture Health & Anti-Pattern Detector</h1>
                    <p class="mb-0 opacity-75">Automated Code Smells, Fat Controllers & Refactoring Engine</p>
                </div>
                <div class="mt-3 mt-lg-0 d-flex gap-2">
                    <a href="{{ route('stats.audit.export') }}" class="btn btn-success fw-semibold">📥 Export Audit CSV</a>
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
                    <a class="nav-link" href="{{ route('stats.analytics') }}">📈 Trend Analytics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('stats.audit') }}">💡 Architecture Audit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('stats.history') }}">📜 Scan History</a>
                </li>
            </ul>
        </div>

        {{-- Summary Cards --}}
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-xl-4">
                <div class="card issue-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted">Total Issues Detected</div>
                        <div class="fs-2 fw-bold text-dark">{{ count($issues) }}</div>
                        <small class="text-muted">Flagged by Rule-Engine</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card issue-card danger shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted">Critical / High Severity</div>
                        <div class="fs-2 fw-bold text-danger">
                            {{ collect($issues)->whereIn('severity', ['Critical', 'High'])->count() }}
                        </div>
                        <small class="text-muted">High priority refactoring required</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card issue-card warning shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted">Warnings & Code Smells</div>
                        <div class="fs-2 fw-bold text-warning">
                            {{ collect($issues)->whereIn('severity', ['Warning', 'Info'])->count() }}
                        </div>
                        <small class="text-muted">Maintainability improvements</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Flagged Architectural Issues & Recommendations --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">🚨 Architectural Audit Findings & Refactoring Recommendations</h5>
                <span class="badge bg-primary fs-6">{{ count($issues) }} Rules Evaluated</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    @foreach($issues as $issue)
                    <div class="col-12">
                        <div class="card issue-card {{ strtolower($issue['badge']) }} shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="fw-bold mb-0 text-dark">{{ $issue['title'] }}</h5>
                                    <span class="badge bg-{{ $issue['badge'] }} px-3 py-2 fs-6">{{ $issue['severity'] }} Severity</span>
                                </div>
                                <div class="mb-2 text-muted">
                                    <strong>Target Component:</strong> <span class="badge bg-light text-dark border">{{ $issue['component'] }}</span> |
                                    <strong>Metric Trigger:</strong> <span class="font-monospace text-primary">{{ $issue['metric'] }}</span>
                                </div>
                                <p class="mb-3 text-secondary">{{ $issue['description'] }}</p>
                                <div class="p-3 bg-light rounded-3 border-start border-4 border-primary">
                                    <strong>💡 Actionable Refactoring Suggestion:</strong>
                                    <p class="mb-0 text-dark mt-1">{{ $issue['recommendation'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html>
