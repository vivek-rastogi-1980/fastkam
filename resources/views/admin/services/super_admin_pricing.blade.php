@extends('layouts.admin')

@section('title', 'Super Admin Price Management')
@section('page_title', 'Super Admin Price Management')
@section('page_subtitle', 'Configure and manage base purchase cost / token deduction for Super Administrator account')

@section('content')
<div class="container-fluid p-0">

    <!-- Header & Action Row -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.services') }}" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Back to Services Catalog">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="fw-bold text-dark mb-0">Super Admin Service Pricing</h4>
                <span class="badge bg-primary text-white rounded-pill px-2.5 py-1">Super Admin Dedicated Pricing</span>
                <span class="badge bg-dark text-white rounded-pill px-2.5 py-1 font-mono">Base Wallet Deduction</span>
            </div>
            <p class="text-muted small mb-0 ps-md-4 ms-md-2">
                This section manages the base purchase cost (token deduction) for the <strong>Super Administrator</strong>. When you execute services directly from the Super Admin portal, or when any franchise partner triggers an upstream verification, your Super Admin wallet is debited by this price.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('admin.services') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-grid-fill me-1"></i>Our Services
            </a>
            <a href="{{ route('admin.services.pricing') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-tags-fill me-1"></i>Default Price (New Franchise)
            </a>
            <button type="button" class="btn btn-primary btn-sm shadow-sm" id="btnTopSubmit" onclick="document.getElementById('bulkPricingForm').submit()">
                <i class="bi bi-check2-circle me-1"></i>Save All Super Admin Prices
            </button>
        </div>
    </div>

    <!-- Explanatory Banner -->
    <div class="alert alert-info border-info-subtle bg-info-subtle text-dark p-3 rounded-3 shadow-sm mb-4">
        <div class="d-flex align-items-start gap-3">
            <div class="p-2 rounded-2 bg-info text-white d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="bi bi-shield-lock-fill fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-1 text-dark">Super Admin Price vs Default Franchise Price Separation</h6>
                <p class="mb-0 small text-secondary">
                    <strong>Super Admin Price</strong> is strictly what the Super Admin pays (debited from Super Admin wallet). 
                    <a href="{{ route('admin.services.pricing') }}" class="fw-bold text-decoration-none text-primary">Default Price</a> applies <strong>only to new franchises</strong> upon registration. Existing franchises maintain their own negotiated rates set under <a href="{{ route('admin.franchises') }}" class="fw-semibold text-decoration-none">Franchises &rarr; Services Cost</a>.
                </p>
            </div>
        </div>
    </div>

    <!-- KPI Summary Row -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100">
                <div>
                    <div class="text-muted small fw-semibold">TOTAL SERVICES</div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_services'] }}</h3>
                    <small class="text-success fw-medium">
                        <i class="bi bi-check-circle-fill me-1"></i>{{ $stats['active_services'] }} Active in Catalog
                    </small>
                </div>
                <div class="p-3 rounded-3 bg-primary-subtle text-primary fs-3">
                    <i class="bi bi-collection-fill"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100">
                <div>
                    <div class="text-muted small fw-semibold">AVG ADMIN COST</div>
                    <h3 class="fw-bold mb-0 text-primary">₹{{ number_format($stats['avg_admin_cost'], 2) }}</h3>
                    <small class="text-muted">Tokens base cost per hit</small>
                </div>
                <div class="p-3 rounded-3 bg-info-subtle text-info fs-3">
                    <i class="bi bi-calculator-fill"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100">
                <div>
                    <div class="text-muted small fw-semibold">ADMIN COST RANGE</div>
                    <h3 class="fw-bold mb-0 text-dark">₹{{ number_format($stats['min_admin_cost'], 2) }} - ₹{{ number_format($stats['max_admin_cost'], 2) }}</h3>
                    <small class="text-muted">Min to Max base cost</small>
                </div>
                <div class="p-3 rounded-3 bg-warning-subtle text-warning-emphasis fs-3">
                    <i class="bi bi-currency-rupee"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100">
                <div>
                    <div class="text-muted small fw-semibold">ADMIN WALLET BALANCE</div>
                    <h3 class="fw-bold mb-0 text-success font-mono">{{ number_format($stats['admin_wallet_balance'], 2) }}</h3>
                    <small class="text-muted">Available Tokens for execution</small>
                </div>
                <div class="p-3 rounded-3 bg-success-subtle text-success fs-3">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Bulk Adjust Toolbar -->
    <div class="card glass-card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">
                <!-- Search Filter -->
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" id="pricingSearch" placeholder="Search service name, code, or path...">
                    </div>
                </div>

                <!-- Category Filters -->
                <div class="col-md-5">
                    <div class="d-flex gap-1 flex-wrap" id="categoryFilterContainer">
                        <button type="button" class="btn btn-sm btn-dark category-filter-btn active" data-category="ALL">All Categories</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary category-filter-btn" data-category="Identity & Tax">Identity & Tax</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary category-filter-btn" data-category="Transport & Vahan">Transport</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary category-filter-btn" data-category="Election Commission">Election</button>
                    </div>
                </div>

                <!-- Quick Bulk Adjust Tool -->
                <div class="col-md-3 text-md-end">
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-lightning-charge-fill text-warning me-1"></i>Quick Adjust All
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><h6 class="dropdown-header">Uniform Admin Cost Adjustment</h6></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="quickAdjustAll(0.50)">Add +₹0.50 to All</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="quickAdjustAll(1.00)">Add +₹1.00 to All</a></li>
                            <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="quickAdjustAll(-0.50)">Subtract -₹0.50 from All</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="resetAllPrices()">Reset All to Initial</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Pricing Table Card -->
    <div class="card glass-card border-0 shadow-sm overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom p-3 p-md-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold text-dark mb-1">
                    <i class="bi bi-shield-shaded text-primary me-2"></i>Super Admin Price Matrix
                </h5>
                <p class="text-muted small mb-0">Specify the base cost in Tokens (₹) debited from Super Admin wallet when services are executed.</p>
            </div>
            <div class="d-none d-md-flex align-items-center gap-2">
                <span id="unsavedBadge" class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle d-none">
                    <i class="bi bi-exclamation-circle me-1"></i>Unsaved Changes
                </span>
                <button type="submit" form="bulkPricingForm" class="btn btn-primary btn-sm px-3 shadow-sm">
                    <i class="bi bi-save me-1"></i>Save All Changes
                </button>
            </div>
        </div>

        <form action="{{ route('admin.services.super_admin_pricing.update') }}" method="POST" id="bulkPricingForm">
            @csrf

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="pricingTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="min-width: 260px;">Service Name &amp; Code</th>
                            <th>Category</th>
                            <th>API Route / Endpoint</th>
                            <th style="min-width: 160px;">New Franchise Default</th>
                            <th style="min-width: 200px;">Super Admin Cost (₹)</th>
                            <th class="text-end pe-4" style="min-width: 140px;">Quick Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            <tr class="service-row" data-category="{{ $service->category }}" data-name="{{ strtolower($service->name) }}" data-code="{{ strtolower($service->service_code) }}" id="row_{{ $service->id }}">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center p-2" style="width: 40px; height: 40px;">
                                            @if(str_contains($service->service_code, 'pan'))
                                                <i class="bi bi-credit-card-2-front-fill fs-5"></i>
                                            @elseif(str_contains($service->service_code, 'dl'))
                                                <i class="bi bi-person-vcard-fill fs-5"></i>
                                            @elseif(str_contains($service->service_code, 'rc') || str_contains($service->service_code, 'vehicle'))
                                                <i class="bi bi-car-front-fill fs-5"></i>
                                            @elseif(str_contains($service->service_code, 'voter'))
                                                <i class="bi bi-person-badge-fill fs-5"></i>
                                            @else
                                                <i class="bi bi-cpu-fill fs-5"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-6">{{ $service->name }}</div>
                                            <code class="font-mono text-muted small">{{ $service->service_code }}</code>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $service->category }}</span>
                                </td>
                                <td>
                                    <span class="font-mono small text-secondary text-truncate d-inline-block" style="max-width: 220px;" title="{{ $service->endpoint_path }}">
                                        {{ $service->endpoint_path }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-mono fw-bold text-secondary">₹{{ number_format($service->default_franchise_price, 2) }}</span>
                                    <small class="text-muted d-block" style="font-size: 0.72rem;">New franchise rate</small>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm" style="max-width: 180px;">
                                        <span class="input-group-text font-mono fw-bold bg-light">₹</span>
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               name="prices[{{ $service->id }}]"
                                               id="price_input_{{ $service->id }}"
                                               class="form-control font-mono fw-bold price-input"
                                               data-original="{{ number_format($service->super_admin_cost, 2, '.', '') }}"
                                               data-service-id="{{ $service->id }}"
                                               value="{{ number_format($service->super_admin_cost, 2, '.', '') }}"
                                               required>
                                        <span class="input-group-text text-muted font-mono small">Tokens</span>
                                    </div>
                                    <span class="text-success small d-none" id="save_success_{{ $service->id }}">
                                        <i class="bi bi-check2"></i> Saved
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button"
                                            class="btn btn-outline-primary btn-sm btn-save-single"
                                            id="btn_save_{{ $service->id }}"
                                            onclick="saveSinglePrice({{ $service->id }}, '{{ addslashes($service->name) }}')">
                                        <i class="bi bi-check2"></i> Save
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No services configured in catalog.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-top p-3 p-md-4 d-flex justify-content-between align-items-center">
                <span class="text-muted small">
                    Showing <strong id="visibleCount">{{ $services->count() }}</strong> of {{ $services->count() }} services in Super Admin price catalog.
                </span>
                <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">
                    <i class="bi bi-check2-circle me-1"></i>Save All Super Admin Prices
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Toast Container for Notifications -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
    <div id="priceToast" class="toast align-items-center text-white bg-dark border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill text-success fs-5" id="toastIcon"></i>
                <span id="toastMessage">Changes saved successfully.</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('pricingSearch');
    const categoryBtns = document.querySelectorAll('.category-filter-btn');
    const priceInputs = document.querySelectorAll('.price-input');
    const unsavedBadge = document.getElementById('unsavedBadge');
    const visibleCountElem = document.getElementById('visibleCount');
    const rows = document.querySelectorAll('.service-row');
    const toastElem = document.getElementById('priceToast');
    const toast = new bootstrap.Toast(toastElem, { delay: 3500 });

    let activeCategory = 'ALL';

    // Track unsaved changes
    function checkUnsavedChanges() {
        let hasChanges = false;
        priceInputs.forEach(input => {
            const original = parseFloat(input.getAttribute('data-original'));
            const current = parseFloat(input.value);
            if (!isNaN(current) && Math.abs(original - current) > 0.001) {
                hasChanges = true;
                input.classList.add('border-warning', 'bg-warning-subtle');
            } else {
                input.classList.remove('border-warning', 'bg-warning-subtle');
            }
        });

        if (hasChanges) {
            unsavedBadge.classList.remove('d-none');
        } else {
            unsavedBadge.classList.add('d-none');
        }
    }

    priceInputs.forEach(input => {
        input.addEventListener('input', checkUnsavedChanges);
    });

    // Search filter
    searchInput.addEventListener('input', function () {
        applyFilters();
    });

    // Category filter
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            categoryBtns.forEach(b => {
                b.classList.remove('btn-dark', 'active');
                b.classList.add('btn-outline-secondary');
            });
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-dark', 'active');
            activeCategory = this.getAttribute('data-category');
            applyFilters();
        });
    });

    function applyFilters() {
        const query = (searchInput.value || '').toLowerCase().trim();
        let visible = 0;

        rows.forEach(row => {
            const rowCat = row.getAttribute('data-category');
            const rowName = row.getAttribute('data-name');
            const rowCode = row.getAttribute('data-code');

            const matchesCategory = (activeCategory === 'ALL') || (rowCat === activeCategory);
            const matchesQuery = !query || rowName.includes(query) || rowCode.includes(query);

            if (matchesCategory && matchesQuery) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        if (visibleCountElem) {
            visibleCountElem.innerText = visible;
        }
    }

    // Quick Uniform Adjust
    window.quickAdjustAll = function (delta) {
        priceInputs.forEach(input => {
            let current = parseFloat(input.value) || 0;
            let updated = Math.max(0, current + delta);
            input.value = updated.toFixed(2);
        });
        checkUnsavedChanges();
    };

    // Reset to Original
    window.resetAllPrices = function () {
        priceInputs.forEach(input => {
            input.value = input.getAttribute('data-original');
            input.classList.remove('border-warning', 'bg-warning-subtle');
        });
        checkUnsavedChanges();
    };

    // Single Row Save via AJAX
    window.saveSinglePrice = function (serviceId, serviceName) {
        const input = document.getElementById('price_input_' + serviceId);
        const btn = document.getElementById('btn_save_' + serviceId);
        const successIndicator = document.getElementById('save_success_' + serviceId);
        if (!input || !btn) return;

        const price = parseFloat(input.value);
        if (isNaN(price) || price < 0) {
            alert('Please enter a valid non-negative token price.');
            return;
        }

        const origHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...';

        fetch(`/services/super-admin-pricing/${serviceId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                super_admin_cost: price
            })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = origHtml;

            if (data.status === 'SUCCESS') {
                input.setAttribute('data-original', price.toFixed(2));
                input.classList.remove('border-warning', 'bg-warning-subtle');
                checkUnsavedChanges();

                if (successIndicator) {
                    successIndicator.classList.remove('d-none');
                    setTimeout(() => { successIndicator.classList.add('d-none'); }, 3000);
                }

                document.getElementById('toastIcon').className = 'bi bi-check-circle-fill text-success fs-5';
                document.getElementById('toastMessage').innerText = data.message || `Price updated for ${serviceName}.`;
                toast.show();
            } else {
                alert(data.message || 'Error updating price.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = origHtml;
            alert('Network error while updating price.');
        });
    };
});
</script>
@endpush
