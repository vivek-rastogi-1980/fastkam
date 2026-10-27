@extends('layouts.admin')

@section('title', 'Services Cost - ' . $franchise->name)
@section('page_title', 'Services Cost Management')
@section('page_subtitle', 'Configure and update custom service pricing / token deduction for franchise partner')

@section('content')
<div class="container-fluid p-0">

    <!-- Navigation & Header Breadcrumb -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.franchises') }}" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Back to Franchise List">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="fw-bold text-dark mb-0">Services Cost: {{ $franchise->name }}</h4>
            </div>
            <p class="text-muted small mb-0 ps-md-4 ms-md-2">
                Set custom prices against each service. When <strong>{{ $franchise->name }}</strong> logs in, these prices will be reflected on their portal and deducted on execution.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.franchises') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back to Franchise List
            </a>
        </div>
    </div>

    <!-- Franchise Summary Strip -->
    <div class="card glass-card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.25rem;">
                            {{ substr($franchise->name, 0, 1) }}
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">{{ $franchise->name }}</h6>
                            <div class="small text-muted">{{ $franchise->email }}</div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle mt-1" style="font-size: 0.68rem;">{{ ucfirst($franchise->status) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small text-muted">Operator / Owner:</div>
                    <div class="fw-bold text-dark">{{ $franchise->full_name ?? 'N/A' }}</div>
                    <div class="small text-muted"><i class="bi bi-telephone me-1"></i>{{ $franchise->phone ?? 'N/A' }}</div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="small text-muted">Current Wallet Balance:</div>
                    <h5 class="fw-bold text-primary mb-0">{{ number_format($franchise->wallet_balance, 2) }} Tokens</h5>
                    <small class="text-muted" style="font-size: 0.72rem;">1 Token = 1 INR</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Form Card -->
    <div class="card glass-card border-0 shadow-sm overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom p-3 p-md-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-tag-fill text-primary me-2"></i>Service Pricing Matrix</h5>
                <p class="text-muted small mb-0">Specify the price in Tokens (₹) for each service. Enter a custom price or leave blank to use the default catalog price.</p>
            </div>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnResetAllPrices">
                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset All to Default
            </button>
        </div>

        <form action="{{ route('admin.franchises.services_cost.update', $franchise->id) }}" method="POST">
            @csrf

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="min-width: 250px;">Service Name & Description</th>
                            <th>Category</th>
                            <th>Default Catalog Cost</th>
                            <th style="min-width: 200px;">Franchise Custom Price</th>
                            <th>Effective Cost</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            @php
                                $customPrice = $customPrices[$service->id] ?? null;
                                $effectiveCost = $customPrice !== null ? (float) $customPrice : (float) $service->token_cost;
                                $hasOverride = $customPrice !== null;
                            @endphp
                            <tr id="service_row_{{ $service->id }}">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark">{{ $service->name }}</h6>
                                            <code class="font-mono text-muted" style="font-size: 0.72rem;">{{ $service->service_code }}</code>
                                            <div class="text-muted small text-truncate" style="max-width: 320px;" title="{{ $service->description }}">
                                                {{ $service->description }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $service->category }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-secondary">₹{{ number_format($service->token_cost, 2) }}</span>
                                    <small class="text-muted d-block" style="font-size: 0.72rem;">{{ number_format($service->token_cost, 2) }} Tokens</small>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm" style="max-width: 170px;">
                                        <span class="input-group-text font-mono fw-bold">₹</span>
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               name="prices[{{ $service->id }}]"
                                               id="input_price_{{ $service->id }}"
                                               class="form-control font-mono fw-bold price-input"
                                               data-default="{{ number_format($service->token_cost, 2, '.', '') }}"
                                               data-service-id="{{ $service->id }}"
                                               value="{{ $hasOverride ? number_format($customPrice, 2, '.', '') : number_format($service->token_cost, 2, '.', '') }}"
                                               placeholder="{{ number_format($service->token_cost, 2, '.', '') }}">
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $hasOverride ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-light text-secondary border' }} fs-6 fw-bold px-2 py-1" id="badge_effective_{{ $service->id }}">
                                        ₹{{ number_format($effectiveCost, 2) }}
                                    </span>
                                    @if($hasOverride)
                                        <small class="text-primary d-block font-mono" style="font-size: 0.7rem;" id="override_label_{{ $service->id }}"><i class="bi bi-pencil-fill me-1"></i>Custom Rate</small>
                                    @else
                                        <small class="text-muted d-block font-mono" style="font-size: 0.7rem;" id="override_label_{{ $service->id }}">Standard Rate</small>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button"
                                            class="btn btn-outline-secondary btn-sm btn-reset-single"
                                            data-target="input_price_{{ $service->id }}"
                                            data-default="{{ number_format($service->token_cost, 2, '.', '') }}"
                                            data-service-id="{{ $service->id }}"
                                            title="Reset to default catalog cost">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Default
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No services found in database.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-light p-3 p-md-4 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                <div class="small text-muted">
                    <i class="bi bi-info-circle text-primary me-1"></i>
                    Changes will take effect immediately upon saving. The franchise will see these rates upon login.
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.franchises') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                        <i class="bi bi-check2-circle me-1"></i>Save & Update Services Cost
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Dynamic effective price preview
    document.querySelectorAll('.price-input').forEach(input => {
        input.addEventListener('input', function () {
            const serviceId = this.getAttribute('data-service-id');
            const defaultVal = parseFloat(this.getAttribute('data-default')) || 0;
            const enteredVal = parseFloat(this.value);
            const badge = document.getElementById('badge_effective_' + serviceId);
            const label = document.getElementById('override_label_' + serviceId);

            if (!isNaN(enteredVal) && enteredVal >= 0) {
                if (badge) badge.innerText = '₹' + enteredVal.toFixed(2);
                if (enteredVal !== defaultVal) {
                    if (badge) {
                        badge.className = 'badge bg-primary-subtle text-primary border border-primary-subtle fs-6 fw-bold px-2 py-1';
                    }
                    if (label) {
                        label.className = 'text-primary d-block font-mono';
                        label.innerHTML = '<i class="bi bi-pencil-fill me-1"></i>Custom Rate';
                    }
                } else {
                    if (badge) {
                        badge.className = 'badge bg-light text-secondary border fs-6 fw-bold px-2 py-1';
                    }
                    if (label) {
                        label.className = 'text-muted d-block font-mono';
                        label.innerText = 'Standard Rate';
                    }
                }
            }
        });
    });

    // Reset single input to default
    document.querySelectorAll('.btn-reset-single').forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const defaultVal = this.getAttribute('data-default');
            const input = document.getElementById(targetId);
            if (input) {
                input.value = defaultVal;
                input.dispatchEvent(new Event('input'));
            }
        });
    });

    // Reset all inputs to default
    const btnResetAll = document.getElementById('btnResetAllPrices');
    if (btnResetAll) {
        btnResetAll.addEventListener('click', function () {
            if (confirm('Are you sure you want to reset all service prices to their standard catalog defaults?')) {
                document.querySelectorAll('.price-input').forEach(input => {
                    const defaultVal = input.getAttribute('data-default');
                    input.value = defaultVal;
                    input.dispatchEvent(new Event('input'));
                });
            }
        });
    }
});
</script>
@endpush
