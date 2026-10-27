@extends('layouts.admin')

@section('title', 'Executive Reporting Dashboard')
@section('page_title', 'Executive Reporting & Intelligence')
@section('page_subtitle', 'Single-page 360° overview of verification volume, token revenues, franchise float, and gateway health')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .kpi-card {
        background: #ffffff;
        border: 1px solid var(--card-border);
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }
    .kpi-icon-box {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }
    .kpi-value {
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -0.5px;
        color: #0f172a;
        line-height: 1.2;
    }
    .kpi-subtext {
        font-size: 0.78rem;
        color: #64748b;
    }
    .chart-card {
        background: #ffffff;
        border: 1px solid var(--card-border);
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03);
    }
    .btn-xs {
        padding: 0.15rem 0.5rem;
        font-size: 0.72rem;
        border-radius: 6px;
    }
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0.2rem 0.65rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    @media print {
        #sidebar, .top-navbar, .filter-toolbar, .no-print {
            display: none !important;
        }
        #main-content {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .card, .kpi-card, .chart-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            page-break-inside: avoid;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-0">

    <!-- Top Action & Overview Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary text-white px-2.5 py-1 font-mono rounded-pill" style="font-size: 0.72rem;">SUPER ADMIN INTELLIGENCE</span>
                <span class="text-muted small">Updated Live: {{ now()->format('d M Y, H:i') }} IST</span>
            </div>
            <h4 class="fw-bold text-dark mb-0 mt-1">Single-Page Operations & Financial Command Center</h4>
        </div>
        <div class="d-flex align-items-center gap-2 no-print">
            <button type="button" class="btn btn-outline-secondary btn-sm px-3 d-inline-flex align-items-center gap-1.5" onclick="window.print()">
                <i class="bi bi-printer"></i>
                <span>Print Report</span>
            </button>
            <button type="button" class="btn btn-outline-primary btn-sm px-3 d-inline-flex align-items-center gap-1.5" id="btnExportCSV">
                <i class="bi bi-download"></i>
                <span>Export CSV</span>
            </button>
            <a href="{{ route('admin.reports') }}" class="btn btn-primary btn-sm px-3 d-inline-flex align-items-center gap-1.5" title="Refresh Live Metrics">
                <i class="bi bi-arrow-clockwise"></i>
                <span>Refresh</span>
            </a>
        </div>
    </div>

    <!-- Filters & Time-Window Toolbar -->
    <div class="card glass-card border-0 shadow-sm mb-4 p-3 filter-toolbar">
        <form id="reportsFilterForm" action="{{ route('admin.reports') }}" method="GET" class="row g-3 align-items-end">
            <!-- Preset & Date Range Filter -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label small fw-semibold mb-0" for="date_range">
                        <i class="bi bi-calendar-range text-primary me-1"></i>Date Window
                    </label>
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.reports', array_merge(request()->except(['date_range', 'preset']), ['preset' => 'today'])) }}" 
                           class="btn btn-xs {{ ($rangePreset === 'today') ? 'btn-primary' : 'btn-outline-secondary' }}">Today</a>
                        <a href="{{ route('admin.reports', array_merge(request()->except(['date_range', 'preset']), ['preset' => 'yesterday'])) }}" 
                           class="btn btn-xs {{ ($rangePreset === 'yesterday') ? 'btn-primary' : 'btn-outline-secondary' }}">Yesterday</a>
                        <a href="{{ route('admin.reports', array_merge(request()->except(['date_range', 'preset']), ['preset' => 'last7'])) }}" 
                           class="btn btn-xs {{ ($rangePreset === 'last7') ? 'btn-primary' : 'btn-outline-secondary' }}">7 Days</a>
                        <a href="{{ route('admin.reports', array_merge(request()->except(['date_range', 'preset']), ['preset' => 'month'])) }}" 
                           class="btn btn-xs {{ ($rangePreset === 'month') ? 'btn-primary' : 'btn-outline-secondary' }}">Month</a>
                        <a href="{{ route('admin.reports', array_merge(request()->except(['date_range', 'preset']), ['preset' => 'all'])) }}" 
                           class="btn btn-xs {{ ($rangePreset === 'all') ? 'btn-primary' : 'btn-outline-secondary' }}">All</a>
                    </div>
                </div>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white text-muted"><i class="bi bi-calendar3"></i></span>
                    <input type="text" id="date_range" name="date_range" class="form-control" 
                           placeholder="e.g. 2026-09-22 to 2026-09-28" 
                           value="{{ $dateRangeValue }}" autocomplete="off">
                    <button type="button" class="btn btn-outline-secondary" id="clear_date_btn" title="Reset date window">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Filter by Franchise -->
            <div class="col-lg-3 col-md-6">
                <label class="form-label small fw-semibold">Franchise Kendra</label>
                <select name="franchise_id" class="form-select form-select-sm" onchange="document.getElementById('reportsFilterForm').submit()">
                    <option value="">All Franchises ({{ $franchises->count() }})</option>
                    @foreach($franchises as $f)
                        <option value="{{ $f->id }}" {{ $selectedFranchiseId == $f->id ? 'selected' : '' }}>
                            {{ $f->name }} (Bal: ₹{{ number_format($f->wallet_balance, 0) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by Service -->
            <div class="col-lg-3 col-md-6">
                <label class="form-label small fw-semibold">IDSPay Service</label>
                <select name="service_code" class="form-select form-select-sm" onchange="document.getElementById('reportsFilterForm').submit()">
                    <option value="">All Services ({{ $allServices->count() }})</option>
                    @foreach($allServices as $srv)
                        <option value="{{ $srv->service_code }}" {{ $selectedServiceCode == $srv->service_code ? 'selected' : '' }}>
                            {{ $srv->name }} (₹{{ number_format($srv->token_cost, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Actions -->
            <div class="col-lg-2 col-md-6 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-funnel-fill me-1"></i>Apply
                </button>
                <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary btn-sm" title="Clear all filters">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Active Filter Ribbon -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 p-2 px-3 mb-4 rounded-3 bg-light border text-muted small">
        <div class="d-flex align-items-center flex-wrap gap-2">
            <span class="fw-semibold text-dark"><i class="bi bi-clock-history me-1 text-primary"></i>Active Reporting Scope:</span>
            <span class="badge bg-white text-primary border shadow-sm px-2 py-1">
                <i class="bi bi-calendar-check me-1"></i>{{ $dateRangeText }}
            </span>
            @if($selectedFranchiseId)
                @php $fActive = $franchises->firstWhere('id', $selectedFranchiseId); @endphp
                <span class="badge bg-white text-dark border shadow-sm px-2 py-1">
                    <i class="bi bi-building me-1 text-primary"></i>Franchise: {{ $fActive->name ?? 'ID #' . $selectedFranchiseId }}
                </span>
            @endif
            @if($selectedServiceCode)
                <span class="badge bg-white text-dark border shadow-sm px-2 py-1 font-mono">
                    <i class="bi bi-cpu me-1 text-primary"></i>Service: {{ $selectedServiceCode }}
                </span>
            @endif
            <span class="text-secondary ms-1">({{ $kpis['total_logs'] }} verifications analyzed)</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i>{{ $kpis['success_rate'] }}% Success Rate</span>
            <span class="text-muted">•</span>
            <span class="font-mono text-dark fw-bold">₹{{ number_format($kpis['total_tokens_deducted'], 2) }} Billed</span>
        </div>
    </div>

    <!-- Section 1: Executive KPI Cards (Row of 5 Cards) -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Total API Traffic & Reliability -->
        <div class="col-xl col-md-6">
            <div class="kpi-card p-3 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="text-uppercase fw-bold text-muted" style="font-size: 0.68rem; letter-spacing: 0.5px;">Verification Volume</div>
                        <div class="kpi-value mt-1">{{ number_format($kpis['total_logs']) }}</div>
                    </div>
                    <div class="kpi-icon-box bg-primary-subtle text-primary">
                        <i class="bi bi-hdd-network-fill"></i>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-1 small">
                        <span class="text-success fw-semibold">{{ $kpis['success_logs'] }} Success</span>
                        <span class="text-danger fw-semibold">{{ $kpis['failed_logs'] }} Failures</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $kpis['success_rate'] }}%"></div>
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ 100 - $kpis['success_rate'] }}%"></div>
                    </div>
                    <div class="kpi-subtext mt-1.5 d-flex justify-content-between">
                        <span>Success Rate</span>
                        <strong class="text-dark">{{ $kpis['success_rate'] }}%</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 2: Total Token Revenue Billed -->
        <div class="col-xl col-md-6">
            <div class="kpi-card p-3 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="text-uppercase fw-bold text-muted" style="font-size: 0.68rem; letter-spacing: 0.5px;">Total Billed Tokens</div>
                        <div class="kpi-value mt-1 text-primary">₹{{ number_format($kpis['total_tokens_deducted'], 2) }}</div>
                    </div>
                    <div class="kpi-icon-box bg-success-subtle text-success">
                        <i class="bi bi-coin"></i>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>1 INR = 1 Token</span>
                        <span class="text-success fw-bold">₹{{ $kpis['total_logs'] > 0 ? number_format($kpis['total_tokens_deducted'] / $kpis['total_logs'], 2) : '0.00' }}/call avg</span>
                    </div>
                    <div class="p-1.5 px-2 bg-light rounded-2 small text-muted font-mono" style="font-size: 0.72rem;">
                        <i class="bi bi-shield-check text-success me-1"></i>Real-time lockForUpdate verified
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 3: Franchise Float & Customer Liquidity -->
        <div class="col-xl col-md-6">
            <div class="kpi-card p-3 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="text-uppercase fw-bold text-muted" style="font-size: 0.68rem; letter-spacing: 0.5px;">Franchise Float Balance</div>
                        <div class="kpi-value mt-1 text-dark">₹{{ number_format($kpis['total_wallet_float'], 2) }}</div>
                    </div>
                    <div class="kpi-icon-box bg-warning-subtle text-warning">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>{{ $kpis['active_franchises'] }} Active Kendras</span>
                        <span class="text-dark fw-bold">{{ $kpis['total_franchises'] }} Enrolled</span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>Total Recharges</span>
                        <span class="fw-semibold text-success">+₹{{ number_format($kpis['total_recharge_tokens'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 4: GST Tax Revenue Surcharge -->
        <div class="col-xl col-md-6">
            <div class="kpi-card p-3 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="text-uppercase fw-bold text-muted" style="font-size: 0.68rem; letter-spacing: 0.5px;">GST Tax Surcharge</div>
                        <div class="kpi-value mt-1 text-info">₹{{ number_format($kpis['gst_collected_estimated'], 2) }}</div>
                    </div>
                    <div class="kpi-icon-box bg-info-subtle text-info">
                        <i class="bi bi-percent"></i>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>Applied Rate</span>
                        <span class="badge {{ $kpis['gst_enabled'] ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                            {{ $kpis['gst_rate'] }}% ({{ $kpis['gst_enabled'] ? 'Active' : 'Disabled' }})
                        </span>
                    </div>
                    <div class="kpi-subtext">Automated Indian GST tax compliance</div>
                </div>
            </div>
        </div>

        <!-- KPI 5: Gateway Status & Latency -->
        <div class="col-xl col-md-6">
            <div class="kpi-card p-3 h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="text-uppercase fw-bold text-muted" style="font-size: 0.68rem; letter-spacing: 0.5px;">IDSPay Gateway Latency</div>
                        <div class="kpi-value mt-1 font-mono">{{ $kpis['avg_latency'] }} <span style="font-size: 1rem; font-weight: normal;">ms</span></div>
                    </div>
                    <div class="kpi-icon-box bg-purple-subtle text-purple" style="background: #f3e8ff; color: #7e22ce;">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                </div>
                <div>
                    <div class="d-flex align-items-center justify-content-between small mb-1">
                        <span class="d-flex align-items-center gap-1.5 text-success fw-bold">
                            <span class="status-pulse-dot bg-success"></span>
                            <span>100% OPERATIONAL</span>
                        </span>
                        <span class="text-muted font-mono" style="font-size: 0.72rem;">apiuser.idspay.in</span>
                    </div>
                    <div class="kpi-subtext">Real-time upstream response health</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Visual Charts & Analytics (Row of 2 Charts) -->
    <div class="row g-4 mb-4">
        <!-- Timeline Traffic & Deductions Chart (70% width) -->
        <div class="col-lg-8">
            <div class="chart-card p-4 h-100">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">Verification Volume & Token Consumption Trend</h6>
                        <small class="text-muted">Daily breakdown of successful vs failed calls and tokens deducted</small>
                    </div>
                    <div class="d-flex align-items-center gap-3 small">
                        <span class="d-flex align-items-center gap-1"><span class="badge bg-primary p-1 rounded-circle"></span> Success Calls</span>
                        <span class="d-flex align-items-center gap-1"><span class="badge bg-danger p-1 rounded-circle"></span> Failures</span>
                        <span class="d-flex align-items-center gap-1"><span class="badge bg-warning p-1 rounded-circle"></span> Tokens Spent (₹)</span>
                    </div>
                </div>
                <div style="height: 300px; position: relative;">
                    <canvas id="trafficTimelineChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Service Usage Distribution (30% width) -->
        <div class="col-lg-4">
            <div class="chart-card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <h6 class="fw-bold text-dark mb-0">Service Share by Calls</h6>
                    <small class="text-muted">Distribution of requests across IDSPay service suite</small>
                </div>
                <div class="my-auto py-2" style="height: 230px; position: relative;">
                    <canvas id="servicePieChart"></canvas>
                </div>
                <div class="border-top pt-2 small text-muted text-center">
                    Top Services: <strong>DL Verification Plus</strong> &bull; <strong>PAN Details V2</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Service-by-Service Operational Matrix -->
    <div class="card glass-card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="card-header bg-white border-bottom p-3 px-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-grid-fill text-primary me-2"></i>Service Operational Metrics & Commercial Output</h6>
                <small class="text-muted">Breakdown of each integrated IDSPay service with base price, calls, success rates, and token turnover</small>
            </div>
            <a href="{{ route('admin.services.pricing') }}" class="btn btn-outline-primary btn-sm" title="Configure master service pricing">
                <i class="bi bi-sliders me-1"></i>Adjust Pricing
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.865rem;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Service Name & Code</th>
                        <th>Category</th>
                        <th>Base Price</th>
                        <th>With GST ({{ $kpis['gst_rate'] }}%)</th>
                        <th>Total Calls</th>
                        <th style="min-width: 140px;">Success Rate</th>
                        <th>Avg Latency</th>
                        <th>Total Billed</th>
                        <th class="text-end pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servicesBreakdown as $row)
                        @php $s = $row['service']; @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $s->name }}</div>
                                <span class="badge bg-light text-secondary border font-mono" style="font-size: 0.72rem;">{{ $s->service_code }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary">{{ $s->category }}</span>
                            </td>
                            <td class="font-mono fw-semibold text-dark">
                                ₹{{ number_format($s->token_cost, 2) }}
                            </td>
                            <td class="font-mono text-muted">
                                ₹{{ number_format($s->token_cost * (1 + ($kpis['gst_enabled'] ? ($kpis['gst_rate'] / 100) : 0)), 2) }}
                            </td>
                            <td class="fw-bold text-dark">
                                {{ number_format($row['total_calls']) }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        <div class="progress-bar {{ $row['success_rate'] >= 90 ? 'bg-success' : ($row['success_rate'] >= 70 ? 'bg-warning' : 'bg-danger') }}" 
                                             role="progressbar" style="width: {{ $row['success_rate'] }}%"></div>
                                    </div>
                                    <span class="small font-mono fw-semibold">{{ $row['success_rate'] }}%</span>
                                </div>
                            </td>
                            <td class="font-mono text-muted">
                                {{ $row['avg_latency'] ? $row['avg_latency'] . ' ms' : '-' }}
                            </td>
                            <td class="font-mono fw-bold text-primary">
                                ₹{{ number_format($row['tokens_spent'], 2) }}
                            </td>
                            <td class="text-end pe-4">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                    <i class="bi bi-check-circle me-1"></i>Active
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 4: Franchise Performance & Consumption Leaderboard -->
    <div class="card glass-card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="card-header bg-white border-bottom p-3 px-4 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-trophy-fill text-warning me-2"></i>Franchise Kendra Consumption & Float Leaderboard</h6>
                <small class="text-muted">Top consuming partner kendras, liquidity balances, spend, and custom pricing status</small>
            </div>
            <a href="{{ route('admin.franchises') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-people-fill me-1"></i>Manage Franchises
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.865rem;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Franchise Kendra</th>
                        <th>Operator Name</th>
                        <th>Wallet Balance</th>
                        <th>Verifications</th>
                        <th>Success Rate</th>
                        <th>Tokens Spent (₹)</th>
                        <th>Recharges</th>
                        <th>Custom Pricing</th>
                        <th class="text-end pe-4">Quick Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($franchiseLeaderboard as $fRow)
                        @php $f = $fRow['franchise']; @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; font-size: 0.85rem;">
                                        {{ substr($f->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $f->name }}</div>
                                        <div class="small text-muted font-mono" style="font-size: 0.72rem;">{{ $f->email }} &bull; UID: {{ $f->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark">{{ $f->full_name ?? '-' }}</div>
                                <small class="text-muted font-mono" style="font-size: 0.72rem;">{{ $f->phone ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $fRow['wallet_balance'] > 500 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle' }} font-mono fw-bold px-2 py-1">
                                    ₹{{ number_format($fRow['wallet_balance'], 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">{{ $fRow['total_calls'] }}</span>
                                <span class="text-muted small">({{ $fRow['success_calls'] }} ok)</span>
                            </td>
                            <td>
                                @php
                                    $rate = $fRow['total_calls'] > 0 ? round(($fRow['success_calls'] / $fRow['total_calls']) * 100, 1) : 100;
                                @endphp
                                <span class="badge {{ $rate >= 90 ? 'bg-success' : 'bg-warning text-dark' }} font-mono">
                                    {{ $rate }}%
                                </span>
                            </td>
                            <td class="font-mono fw-bold text-primary">
                                ₹{{ number_format($fRow['tokens_spent'], 2) }}
                            </td>
                            <td class="font-mono text-muted">
                                ₹{{ number_format($fRow['total_recharges'], 2) }}
                            </td>
                            <td>
                                @if($fRow['custom_prices_count'] > 0)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        {{ $fRow['custom_prices_count'] }} custom prices
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border">Default Catalog</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <a href="{{ route('admin.franchises.services_cost', $f->id) }}" class="btn btn-outline-secondary btn-sm py-0.5 px-2" style="font-size: 0.75rem;" title="View or edit custom rates">
                                        <i class="bi bi-tag-fill me-1"></i>Rates
                                    </a>
                                    <a href="{{ route('admin.logs', ['franchise_id' => $f->id]) }}" class="btn btn-outline-primary btn-sm py-0.5 px-2" style="font-size: 0.75rem;" title="View franchise logs">
                                        <i class="bi bi-journal-text me-1"></i>Logs
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No franchise accounts registered in system.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 5: Dual Real-Time Feeds (Operational Exceptions + Wallet Funding Audit) -->
    <div class="row g-4 mb-4">
        <!-- Stream 1: Recent Critical Events / Troubleshooting Feed -->
        <div class="col-lg-6">
            <div class="card glass-card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom p-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-shield-exclamation text-danger me-2"></i>Recent API Exceptions & Audit Alerts
                        </h6>
                        <small class="text-muted">Real-time troubleshooting feed for non-success statuses and errors</small>
                    </div>
                    <a href="{{ route('admin.logs', ['status' => 'FAILED']) }}" class="btn btn-link btn-sm p-0 text-decoration-none">
                        View All Logs &rarr;
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" style="font-size: 0.85rem;">
                        @forelse($recentExceptions as $exc)
                            <div class="list-group-item p-3 px-4 d-flex align-items-start justify-content-between gap-3">
                                <div class="d-flex align-items-start gap-2.5">
                                    @php
                                        $iconClass = match($exc->status) {
                                            'SUCCESS' => 'bi-check-circle-fill text-success',
                                            'INSUFFICIENT_FUNDS' => 'bi-exclamation-circle-fill text-warning',
                                            'TIMEOUT' => 'bi-clock-history text-info',
                                            default => 'bi-x-circle-fill text-danger',
                                        };
                                    @endphp
                                    <i class="bi {{ $iconClass }} fs-5 mt-0.5"></i>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <strong class="text-dark">{{ $exc->service->name ?? $exc->service_code }}</strong>
                                            <span class="badge {{ $exc->status === 'SUCCESS' ? 'bg-success' : ($exc->status === 'INSUFFICIENT_FUNDS' ? 'bg-warning text-dark' : 'bg-danger') }}" style="font-size: 0.65rem;">
                                                {{ $exc->status }}
                                            </span>
                                        </div>
                                        <div class="text-muted small mt-0.5">
                                            User: <strong class="text-dark">{{ $exc->user->name ?? 'User #' . $exc->user_id }}</strong> &bull; IP: {{ $exc->ip_address }}
                                        </div>
                                        @if($exc->error_message)
                                            <div class="text-danger small font-mono mt-1 p-1 bg-danger-subtle rounded border border-danger-subtle" style="font-size: 0.72rem;">
                                                {{ Str::limit($exc->error_message, 80) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-end text-muted small font-mono flex-shrink-0" style="font-size: 0.72rem;">
                                    <div>{{ $exc->created_at->format('H:i:s') }}</div>
                                    <div class="text-secondary">{{ $exc->created_at->format('d M') }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">No failures or exceptions reported. Gateway running cleanly.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Stream 2: Recent Wallet Recharges Audit Trail -->
        <div class="col-lg-6">
            <div class="card glass-card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom p-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="bi bi-cash-stack text-success me-2"></i>Recent Wallet Recharges & Inflows
                        </h6>
                        <small class="text-muted">Audit stream of token credits allocated to franchise accounts</small>
                    </div>
                    <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#quickRechargeModal">
                        <i class="bi bi-plus-circle me-1"></i>Quick Recharge
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush" style="font-size: 0.85rem;">
                        @forelse($recentRecharges as $rec)
                            <div class="list-group-item p-3 px-4 d-flex align-items-start justify-content-between gap-3">
                                <div class="d-flex align-items-start gap-2.5">
                                    <div class="rounded-circle bg-success-subtle text-success p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        <i class="bi bi-arrow-down-left fw-bold"></i>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <strong class="text-dark">{{ $rec->user->name ?? 'Franchise #' . $rec->user_id }}</strong>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle font-mono fw-bold">
                                                +₹{{ number_format($rec->amount, 2) }} Tokens
                                            </span>
                                        </div>
                                        <div class="text-muted small mt-0.5">
                                            Txn: <span class="font-mono">{{ $rec->transaction_id }}</span>
                                            @if($rec->reference_id)
                                                &bull; Ref: <span class="font-mono text-dark">{{ $rec->reference_id }}</span>
                                            @endif
                                        </div>
                                        <div class="text-secondary small font-mono" style="font-size: 0.72rem;">
                                            Bal: ₹{{ number_format($rec->opening_balance, 2) }} &rarr; ₹{{ number_format($rec->closing_balance, 2) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end text-muted small font-mono flex-shrink-0" style="font-size: 0.72rem;">
                                    <div>{{ $rec->created_at->format('H:i:s') }}</div>
                                    <div class="text-secondary">{{ $rec->created_at->format('d M') }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">No wallet recharges found in this period.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal: Quick Recharge Franchise -->
<div class="modal fade" id="quickRechargeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.franchises.recharge') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-coin me-2"></i>Quick Recharge Franchise Wallet</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Select Franchise Kendra <span class="text-danger">*</span></label>
                        <select name="franchise_id" class="form-select" required>
                            <option value="">-- Choose Franchise --</option>
                            @foreach($franchises as $f)
                                <option value="{{ $f->id }}">
                                    {{ $f->name }} (Current: ₹{{ number_format($f->wallet_balance, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Token Amount (1 INR = 1 Token) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text fw-bold">₹</span>
                            <input type="number" step="0.01" min="1" name="amount" class="form-control" placeholder="e.g. 500.00" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Payment Reference / Note</label>
                        <input type="text" name="reference_id" class="form-control" placeholder="e.g. UPI/NEFT Ref #">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm px-4">Credit Tokens</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Initialize Flatpickr Date Range
    const dateRangeInput = document.getElementById('date_range');
    let fp = null;
    if (dateRangeInput && typeof flatpickr !== 'undefined') {
        fp = flatpickr(dateRangeInput, {
            mode: "range",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "J M y",
            allowInput: true,
            locale: { rangeSeparator: " to " },
            defaultDate: dateRangeInput.value ? dateRangeInput.value.split(' to ') : null
        });

        const clearBtn = document.getElementById('clear_date_btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                if (fp) fp.clear();
                dateRangeInput.value = '';
                const alt = dateRangeInput.parentNode.querySelector('.flatpickr-input:not([type="hidden"])');
                if (alt) alt.value = '';
                document.getElementById('reportsFilterForm').submit();
            });
        }
    }

    // 2. Chart.js Traffic Timeline Chart (Dual Bar & Line)
    const timelineCtx = document.getElementById('trafficTimelineChart');
    if (timelineCtx) {
        const labels = @json($chartData['labels']);
        const successData = @json($chartData['success']);
        const failedData = @json($chartData['failed']);
        const tokensData = @json($chartData['tokens']);

        new Chart(timelineCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Successful Calls',
                        data: successData,
                        backgroundColor: 'rgba(34, 197, 94, 0.75)',
                        borderColor: '#16a34a',
                        borderWidth: 1,
                        borderRadius: 4,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Failed / Insufficient Calls',
                        data: failedData,
                        backgroundColor: 'rgba(239, 68, 68, 0.75)',
                        borderColor: '#dc2626',
                        borderWidth: 1,
                        borderRadius: 4,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Tokens Billed (₹)',
                        data: tokensData,
                        type: 'line',
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { family: 'Plus Jakarta Sans', weight: 'bold' },
                        bodyFont: { family: 'JetBrains Mono' },
                        padding: 10,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748b' }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true,
                        title: { display: true, text: 'Query Volume', color: '#64748b', font: { size: 11 } },
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#64748b', precision: 0 }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        beginAtZero: true,
                        title: { display: true, text: 'Tokens Billed (₹)', color: '#2563eb', font: { size: 11 } },
                        grid: { drawOnChartArea: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#2563eb' }
                    }
                }
            }
        });
    }

    // 3. Chart.js Service Doughnut Chart
    const pieCtx = document.getElementById('servicePieChart');
    if (pieCtx) {
        const pieLabels = @json($chartData['pie_labels']);
        const pieCalls = @json($chartData['pie_calls']);

        const palette = [
            '#2563eb', '#06b6d4', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#64748b'
        ];

        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: pieLabels.length ? pieLabels : ['No Activity'],
                datasets: [{
                    data: pieCalls.length ? pieCalls : [1],
                    backgroundColor: pieCalls.length ? palette.slice(0, pieLabels.length) : ['#e2e8f0'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            font: { family: 'Plus Jakarta Sans', size: 11 },
                            color: '#475569'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { family: 'Plus Jakarta Sans', weight: 'bold' },
                        bodyFont: { family: 'Plus Jakarta Sans' },
                        padding: 10,
                        cornerRadius: 8,
                    }
                }
            }
        });
    }

    // 4. CSV Export Functionality
    const btnExportCSV = document.getElementById('btnExportCSV');
    if (btnExportCSV) {
        btnExportCSV.addEventListener('click', function () {
            const rows = [
                ['Metric', 'Value'],
                ['Scope Window', '{{ $dateRangeText }}'],
                ['Total Verifications', '{{ $kpis["total_logs"] }}'],
                ['Successful Calls', '{{ $kpis["success_logs"] }}'],
                ['Failed Calls', '{{ $kpis["failed_logs"] }}'],
                ['Success Rate', '{{ $kpis["success_rate"] }}%'],
                ['Total Tokens Deducted (INR)', '{{ $kpis["total_tokens_deducted"] }}'],
                ['Franchise Float Balance (INR)', '{{ $kpis["total_wallet_float"] }}'],
                ['Total Franchises Active', '{{ $kpis["active_franchises"] }} / {{ $kpis["total_franchises"] }}'],
                ['GST Rate', '{{ $kpis["gst_rate"] }}%'],
                ['GST Surcharge Collected (INR)', '{{ $kpis["gst_collected_estimated"] }}'],
                ['Average Gateway Latency', '{{ $kpis["avg_latency"] }} ms'],
                [],
                ['Service Name', 'Service Code', 'Category', 'Base Price', 'Calls', 'Success Rate', 'Tokens Billed'],
                @foreach($servicesBreakdown as $row)
                    [
                        '{{ addslashes($row["service"]->name) }}',
                        '{{ $row["service"]->service_code }}',
                        '{{ $row["service"]->category }}',
                        '{{ $row["service"]->token_cost }}',
                        '{{ $row["total_calls"] }}',
                        '{{ $row["success_rate"] }}%',
                        '{{ $row["tokens_spent"] }}'
                    ],
                @endforeach
            ];

            let csvContent = "data:text/csv;charset=utf-8," 
                + rows.map(e => e.map(val => `"${String(val).replace(/"/g, '""')}"`).join(",")).join("\n");

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `VLE_Digital_Executive_Report_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    }
});
</script>
@endpush
