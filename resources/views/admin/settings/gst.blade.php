@extends('layouts.admin')

@section('title', 'GST Tax Rate Configuration | Super Administrator - a4Digital')

@section('content')
<div class="container-fluid px-0">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 text-muted" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="{{ route('admin.services') }}" class="text-decoration-none text-muted"><i class="bi bi-house"></i> Portal</a></li>
                    <li class="breadcrumb-item text-secondary">Settings</li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">GST Rate Configuration</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <span class="p-2 rounded-3 bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-percent fs-5"></i>
                </span>
                GST Rate & Wallet Tax Settings
            </h1>
            <p class="text-muted mb-0 small">
                Define the applicable Goods & Services Tax (GST) rate percentage. <strong>Note:</strong> Franchise accounts and the franchise panel are 0% GST exempt (no GST charged) as per system policy.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2 rounded-pill fs-7">
                <i class="bi bi-shield-check me-1"></i><strong>Franchise Panel: 0% GST Exempt</strong>
            </span>

            @if($gstEnabled && $gstRate > 0)
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fs-7 d-flex align-items-center gap-2">
                    <span class="spinner-grow spinner-grow-sm text-success" role="status" style="width: 8px; height: 8px;"></span>
                    <strong>GST Active: {{ number_format($gstRate, 2) }}%</strong>
                </span>
            @elseif(!$gstEnabled)
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill fs-7 d-flex align-items-center gap-2">
                    <i class="bi bi-pause-circle-fill"></i>
                    <strong>GST Surcharge Disabled (0%)</strong>
                </span>
            @else
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 rounded-pill fs-7">
                    <strong>Zero-Rated (0.00%)</strong>
                </span>
            @endif

            <a href="{{ route('admin.services.pricing') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
                <i class="bi bi-tags-fill me-1"></i> Base Pricing
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                <div class="fw-medium">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                <span class="fw-bold">Validation Error</span>
            </div>
            <ul class="mb-0 small ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <!-- Settings Form Column -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="card-title fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-sliders text-primary"></i>
                        GST Rate Definition
                    </h5>
                    <small class="text-muted">Configure the tax percentage applied to service triggers</small>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.settings.gst.update') }}" method="POST" id="gst-settings-form">
                        @csrf

                        <!-- Enable/Disable Master Toggle -->
                        <div class="p-3 rounded-3 border bg-light-subtle mb-4">
                            <div class="form-check form-switch d-flex align-items-center justify-content-between p-0 m-0">
                                <div>
                                    <label class="form-check-label fw-bold text-dark d-block" for="gst_enabled">
                                        Enable GST Deduction
                                    </label>
                                    <span class="text-muted small">
                                        When active, the defined percentage is added to each service execution charge.
                                    </span>
                                </div>
                                <input class="form-check-input ms-3" type="checkbox" role="switch" id="gst_enabled" name="gst_enabled" value="1" {{ $gstEnabled ? 'checked' : '' }} style="width: 2.8em; height: 1.4em; cursor: pointer;">
                            </div>
                        </div>

                        <!-- GST Rate Input Field -->
                        <div class="mb-3">
                            <label for="gst_rate_percent" class="form-label fw-bold text-dark d-flex justify-content-between">
                                <span>GST Rate Percentage (%)</span>
                                <span class="text-primary fw-semibold" id="current-rate-display">{{ number_format($rawGstRate, 2) }}%</span>
                            </label>
                            <div class="input-group input-group-lg shadow-sm">
                                <span class="input-group-text bg-white border-end-0 text-muted">
                                    <i class="bi bi-percent fs-5"></i>
                                </span>
                                <input type="number" 
                                       step="0.01" 
                                       min="0" 
                                       max="100" 
                                       class="form-control border-start-0 ps-0 fw-bold fs-4 text-dark @error('gst_rate_percent') is-invalid @enderror" 
                                       id="gst_rate_percent" 
                                       name="gst_rate_percent" 
                                       value="{{ old('gst_rate_percent', number_format($rawGstRate, 2, '.', '')) }}" 
                                       required>
                                <span class="input-group-text bg-light text-muted fw-semibold">%</span>
                            </div>
                            <small class="text-muted d-block mt-1">
                                Enter a value between 0.00% and 100.00% (e.g. 18.00 for standard Indian GST).
                            </small>
                            @error('gst_rate_percent')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Presets Selector -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small text-uppercase letter-spacing-1 mb-2">
                                Standard GST Slabs
                            </label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 preset-btn" data-rate="0">
                                    0% (Exempt)
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 preset-btn" data-rate="5">
                                    5%
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 preset-btn" data-rate="12">
                                    12%
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 preset-btn fw-bold" data-rate="18">
                                    18% (Standard)
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 preset-btn" data-rate="28">
                                    28%
                                </button>
                            </div>
                        </div>

                        <!-- Formula Note Box -->
                        <div class="card bg-primary-subtle border-primary-subtle p-3 rounded-3 mb-4">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-info-circle-fill text-primary mt-1"></i>
                                <div class="small text-dark">
                                    <strong>Wallet Deduction Formula:</strong><br>
                                    <code>Total Deducted = Service Price + (Service Price × GST%)</code><br>
                                    <span class="text-muted">Example: For a service priced at 30.00 Tokens with 18% GST: 30 + (30 × 0.18) = <strong>35.40 Tokens</strong>.</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold shadow-sm" id="btn-save-gst">
                                <i class="bi bi-check2-circle me-1"></i> Save GST Configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Live Cost Simulation Column -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-calculator text-success"></i>
                            Live Wallet Deduction Preview
                        </h5>
                        <small class="text-muted">Interactive preview of how franchise wallets will be charged</small>
                    </div>
                    <span class="badge bg-light text-dark border px-2 py-1 rounded">Interactive</span>
                </div>
                <div class="card-body p-4">
                    <!-- Interactive Test Calculator -->
                    <div class="p-3 bg-light rounded-3 border mb-4">
                        <label class="form-label fw-bold text-dark small mb-2">Test Service Price (Tokens / ₹)</label>
                        <div class="row g-3 align-items-center">
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-white">₹</span>
                                    <input type="number" step="0.5" min="0" value="30.00" id="sim-input-price" class="form-control fw-bold text-primary">
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="p-2 bg-white rounded border d-flex justify-content-between align-items-center">
                                    <span class="small text-muted">Deduction:</span>
                                    <div class="text-end">
                                        <span class="text-muted small">₹<span id="sim-base">30.00</span> + ₹<span id="sim-tax">5.40</span> GST</span>
                                        <div class="fw-bold text-success fs-6">= ₹<span id="sim-total">35.40</span> Tokens</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Services Table Simulation -->
                    <h6 class="fw-bold text-secondary small text-uppercase letter-spacing-1 mb-3">
                        Catalog Services with Current GST Breakdown
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="simulation-table">
                            <thead class="table-light">
                                <tr class="text-secondary small">
                                    <th>Service Name</th>
                                    <th>Code</th>
                                    <th class="text-end">Base Price</th>
                                    <th class="text-end">+ GST (<span class="table-gst-rate">{{ number_format($gstRate, 2) }}</span>%)</th>
                                    <th class="text-end fw-bold text-dark">Wallet Deduct</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($simulation as $item)
                                <tr>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $item['name'] }}</span>
                                        <div class="text-muted small">{{ $item['category'] }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary font-mono">{{ $item['service_code'] }}</span>
                                    </td>
                                    <td class="text-end font-mono text-muted">
                                        ₹{{ number_format($item['base_cost'], 2) }}
                                    </td>
                                    <td class="text-end font-mono text-danger">
                                        +₹{{ number_format($item['gst_amount'], 2) }}
                                    </td>
                                    <td class="text-end font-mono fw-bold text-success fs-6">
                                        ₹{{ number_format($item['total_cost'], 2) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No services found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tax Deductions Log -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="card-title fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-secondary"></i>
                Recent Service Wallet Deductions with GST Breakdown
            </h5>
            <a href="{{ route('admin.logs') }}" class="btn btn-sm btn-outline-primary">
                View All Verification Logs <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-secondary small">
                            <th>Transaction ID</th>
                            <th>Account / Franchise</th>
                            <th>Description & Tax Breakdown</th>
                            <th class="text-end">Deducted Amount</th>
                            <th class="text-end">Closing Balance</th>
                            <th class="text-end">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $txn)
                        <tr>
                            <td>
                                <span class="font-mono text-muted small">{{ $txn->transaction_id }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $txn->user->name ?? 'User #' . $txn->user_id }}</div>
                                <small class="text-muted">{{ $txn->user->email ?? '' }}</small>
                            </td>
                            <td>
                                <div class="text-dark small">{{ $txn->description }}</div>
                                @if(is_array($txn->metadata) && isset($txn->metadata['gst_amount']))
                                    <span class="badge bg-light text-secondary border font-mono small mt-1">
                                        Base: ₹{{ number_format($txn->metadata['service_price'] ?? 0, 2) }} | 
                                        GST ({{ $txn->metadata['gst_rate_percent'] ?? 0 }}%): ₹{{ number_format($txn->metadata['gst_amount'], 2) }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-end font-mono fw-bold text-danger">
                                -₹{{ number_format($txn->amount, 2) }}
                            </td>
                            <td class="text-end font-mono text-muted">
                                ₹{{ number_format($txn->closing_balance, 2) }}
                            </td>
                            <td class="text-end text-muted small">
                                {{ $txn->created_at->format('d M Y, h:i A') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-1 text-secondary opacity-50"></i>
                                No recent tax transactions recorded yet. When a franchise triggers a service, wallet deductions with GST details will appear here.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rateInput = document.getElementById('gst_rate_percent');
    const enabledSwitch = document.getElementById('gst_enabled');
    const rateDisplay = document.getElementById('current-rate-display');
    const presetButtons = document.querySelectorAll('.preset-btn');

    const simInput = document.getElementById('sim-input-price');
    const simBase = document.getElementById('sim-base');
    const simTax = document.getElementById('sim-tax');
    const simTotal = document.getElementById('sim-total');

    function updateSimulation() {
        const isEnabled = enabledSwitch.checked;
        const rate = isEnabled ? parseFloat(rateInput.value || 0) : 0;
        const base = parseFloat(simInput.value || 0);

        rateDisplay.textContent = (rateInput.value || 0) + '%';

        const tax = isEnabled && rate > 0 ? (base * (rate / 100)) : 0;
        const total = base + tax;

        simBase.textContent = base.toFixed(2);
        simTax.textContent = tax.toFixed(2);
        simTotal.textContent = total.toFixed(2);

        // Update table GST rates display
        document.querySelectorAll('.table-gst-rate').forEach(el => {
            el.textContent = rate.toFixed(2);
        });

        // Update each table row live
        const rows = document.querySelectorAll('#simulation-table tbody tr');
        rows.forEach(row => {
            const baseTd = row.children[2];
            const taxTd = row.children[3];
            const totalTd = row.children[4];

            if (baseTd && taxTd && totalTd) {
                const baseVal = parseFloat(baseTd.textContent.replace(/[^0-9.]/g, '')) || 0;
                const taxVal = isEnabled && rate > 0 ? (baseVal * (rate / 100)) : 0;
                const totalVal = baseVal + taxVal;

                taxTd.textContent = '+₹' + taxVal.toFixed(2);
                totalTd.textContent = '₹' + totalVal.toFixed(2);
            }
        });
    }

    // Preset button click
    presetButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const rate = this.getAttribute('data-rate');
            rateInput.value = parseFloat(rate).toFixed(2);
            enabledSwitch.checked = true;
            updateSimulation();

            presetButtons.forEach(b => b.classList.remove('btn-primary', 'text-white'));
            this.classList.add('btn-primary', 'text-white');
        });
    });

    rateInput.addEventListener('input', updateSimulation);
    enabledSwitch.addEventListener('change', updateSimulation);
    simInput.addEventListener('input', updateSimulation);

    // Initial calculation run
    updateSimulation();
});
</script>
@endpush
