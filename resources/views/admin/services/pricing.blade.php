@extends('layouts.admin')

@section('title', 'Services Price Management')
@section('page_title', 'Services Price Management')
@section('page_subtitle', 'Configure and update global catalog prices (token deduction) for all verification services')

@section('content')
<div class="container-fluid p-0">

    <!-- Header & Action Row -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.services') }}" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Back to Services Catalog">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="fw-bold text-dark mb-0">Global Service Pricing Matrix</h4>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('admin.services') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-grid-fill me-1"></i>Our Services Catalog
            </a>
            <a href="{{ route('admin.franchises') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-building-fill-gear me-1"></i>Franchise Accounts
            </a>
            <button type="button" class="btn btn-primary btn-sm" id="btnTopSubmit" onclick="document.getElementById('bulkPricingForm').submit()">
                <i class="bi bi-check2-circle me-1"></i>Save All Default Prices
            </button>
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
                    <div class="text-muted small fw-semibold">AVG SERVICE PRICE</div>
                    <h3 class="fw-bold mb-0 text-primary">₹{{ number_format($stats['avg_price'], 2) }}</h3>
                    <small class="text-muted">Tokens per successful hit</small>
                </div>
                <div class="p-3 rounded-3 bg-info-subtle text-info fs-3">
                    <i class="bi bi-calculator-fill"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100">
                <div>
                    <div class="text-muted small fw-semibold">PRICE RANGE</div>
                    <h3 class="fw-bold mb-0 text-dark">₹{{ number_format($stats['min_price'], 2) }} - ₹{{ number_format($stats['max_price'], 2) }}</h3>
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
                    <div class="text-muted small fw-semibold">FRANCHISE OVERRIDES</div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_overrides'] }}</h3>
                    <small class="text-info fw-medium">
                        <a href="{{ route('admin.franchises') }}" class="text-decoration-none">Manage Custom Overrides <i class="bi bi-arrow-right-short"></i></a>
                    </small>
                </div>
                <div class="p-3 rounded-3 bg-success-subtle text-success fs-3">
                    <i class="bi bi-sliders"></i>
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
                            <li><h6 class="dropdown-header">Uniform Price Adjustment</h6></li>
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
                    <i class="bi bi-tag-fill text-primary me-2"></i>Service Price Configuration Table
                </h5>
                <p class="text-muted small mb-0">Edit values below. You can save single rows instantly via the <span class="badge bg-light text-dark border">Save</span> button or save all changes at once.</p>
            </div>
            <div class="d-none d-md-flex align-items-center gap-2">
                <span id="unsavedBadge" class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle d-none">
                    <i class="bi bi-exclamation-circle me-1"></i>Unsaved changes present
                </span>
            </div>
        </div>

        <form action="{{ route('admin.services.pricing.update') }}" method="POST" id="bulkPricingForm">
            @csrf

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="pricingTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">#</th>
                            <th style="min-width: 220px;">Service Name</th>
                            <th style="min-width: 140px;">Category</th>
                            <th style="min-width: 140px;">Current Price</th>
                            <th style="min-width: 250px;">New Price (Tokens / ₹)</th>
                            <th style="min-width: 150px;">Franchise Overrides</th>
                            <th style="min-width: 100px;">Status</th>
                            <th class="text-end pe-4" style="min-width: 120px;">Quick Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $index => $service)
                            @php
                                $price = (float) ($service->default_franchise_price ?? $service->token_cost);
                                $icon = match($service->service_code) {
                                    'dl_verification_plus_srv2' => ['icon' => 'bi-truck-front-fill', 'color' => '#f59e0b', 'bg' => '#fffbeb'],
                                    'pan_details_v2' => ['icon' => 'bi-person-vcard-fill', 'color' => '#3b82f6', 'bg' => '#eff6ff'],
                                    'pan_details_v1' => ['icon' => 'bi-credit-card-2-front-fill', 'color' => '#0ea5e9', 'bg' => '#f0f9ff'],
                                    'aadhar_to_pan' => ['icon' => 'bi-fingerprint', 'color' => '#6366f1', 'bg' => '#eef2ff'],
                                    'rc_to_mobile_v3' => ['icon' => 'bi-car-front-fill', 'color' => '#10b981', 'bg' => '#ecfdf5'],
                                    'voter_id_verification_srv2' => ['icon' => 'bi-person-badge-fill', 'color' => '#8b5cf6', 'bg' => '#f5f3ff'],
                                    default => ['icon' => 'bi-gear-wide-connected', 'color' => '#64748b', 'bg' => '#f8fafc'],
                                };
                            @endphp
                            <tr id="service_row_{{ $service->id }}" class="service-table-row" data-category="{{ $service->category }}" data-search="{{ strtolower($service->name . ' ' . $service->service_code . ' ' . $service->endpoint_path . ' ' . $service->category) }}">
                                <!-- Index -->
                                <td class="ps-4 fw-semibold text-muted">{{ $index + 1 }}</td>

                                <!-- Service Details -->
                                <td>
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; background-color: {{ $icon['bg'] }}; color: {{ $icon['color'] }}; font-size: 1.15rem;">
                                            <i class="bi {{ $icon['icon'] }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark service-name">{{ $service->name }}</div>
                                            <div class="d-none align-items-center gap-2 mt-0.5">
                                                <code class="font-mono text-muted" style="font-size: 0.72rem;">{{ $service->service_code }}</code>
                                                <span class="text-secondary" style="font-size: 0.7rem;">&bull;</span>
                                                <span class="text-muted font-mono" style="font-size: 0.7rem;">{{ $service->endpoint_path }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Category -->
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1">
                                        {{ $service->category }}
                                    </span>
                                </td>

                                <!-- Current Catalog Price -->
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 fs-6 current-price-badge" id="current_price_{{ $service->id }}">
                                        ₹{{ number_format($price, 2) }}
                                    </span>
                                    <div class="text-muted" style="font-size: 0.72rem;">{{ number_format($price, 2) }} Tokens / hit</div>
                                </td>

                                <!-- New Price Input with Steppers -->
                                <td>
                                    <div class="d-flex align-items-center gap-1.5" style="max-width: 240px;">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white fw-bold text-muted">₹</span>
                                            <input type="number"
                                                   step="0.01"
                                                   min="0"
                                                   class="form-control form-control-sm text-end fw-bold price-input"
                                                   id="price_input_{{ $service->id }}"
                                                   name="prices[{{ $service->id }}]"
                                                   value="{{ number_format($price, 2, '.', '') }}"
                                                   data-service-id="{{ $service->id }}"
                                                   data-service-name="{{ $service->name }}"
                                                   data-original-price="{{ number_format($price, 2, '.', '') }}"
                                                   oninput="handlePriceInputChange({{ $service->id }})">
                                            <button type="button" class="btn btn-outline-secondary" onclick="stepPrice({{ $service->id }}, -0.50)" title="Decrease -₹0.50">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="stepPrice({{ $service->id }}, 0.50)" title="Increase +₹0.50">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Quick Preset Buttons -->
                                    <div class="d-flex align-items-center gap-1 mt-1">
                                        <button type="button" class="btn btn-outline-light border text-muted py-0 px-1" style="font-size: 0.68rem;" onclick="addPricePreset({{ $service->id }}, 0.50)">
                                            +0.50
                                        </button>
                                        <button type="button" class="btn btn-outline-light border text-muted py-0 px-1" style="font-size: 0.68rem;" onclick="addPricePreset({{ $service->id }}, 1.00)">
                                            +1.00
                                        </button>
                                        <button type="button" class="btn btn-outline-light border text-muted py-0 px-1" style="font-size: 0.68rem;" onclick="resetSinglePrice({{ $service->id }})">
                                            Reset
                                        </button>
                                    </div>
                                </td>

                                <!-- Franchise Overrides Info -->
                                <td>
                                    @if($service->franchise_prices_count > 0)
                                        <button type="button"
                                                class="btn btn-sm btn-outline-info py-0 px-2 d-flex align-items-center gap-1"
                                                style="font-size: 0.75rem;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#overridesModal"
                                                data-service-name="{{ $service->name }}"
                                                data-service-id="{{ $service->id }}"
                                                data-overrides='@json($service->franchisePrices->map(fn($fp) => ["franchise_id" => $fp->user_id, "franchise_name" => $fp->user?->name ?? "Unknown", "custom_price" => $fp->price]))'>
                                            <i class="bi bi-info-circle-fill"></i>
                                            <span>{{ $service->franchise_prices_count }} Overrides</span>
                                        </button>
                                    @else
                                        <span class="text-muted small">
                                            <i class="bi bi-check2 text-muted me-1"></i>Standard Rate
                                        </span>
                                    @endif
                                </td>

                                <!-- Active Status -->
                                <td>
                                    @if($service->is_active)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            <i class="bi bi-check-circle-fill me-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                            <i class="bi bi-x-circle-fill me-1"></i>Inactive
                                        </span>
                                    @endif
                                </td>

                                <!-- Action Button: Inline Save -->
                                <td class="text-end pe-4">
                                    <button type="button"
                                            class="btn btn-outline-primary btn-sm btn-save-single"
                                            id="btn_save_{{ $service->id }}"
                                            onclick="saveSingleServicePrice({{ $service->id }})"
                                            title="Save price for {{ $service->name }}">
                                        <i class="bi bi-check2 me-1"></i>Save
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No services found in database. Run <code>php artisan db:seed</code> to generate catalog.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Save Strip -->
            <div class="card-footer bg-white border-top p-3 p-md-4 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                <div class="text-muted small">
                    <i class="bi bi-shield-check text-success me-1"></i>
                    Prices update atomically. Franchise users without custom pricing will be charged these updated values.
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetAllPrices()">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Discard Changes
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-semibold" id="btnBottomSubmit">
                        <i class="bi bi-check2-circle me-1"></i>Save All Price Changes
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>

<!-- Franchise Overrides Breakdown Modal -->
<div class="modal fade" id="overridesModal" tabindex="-1" aria-labelledby="overridesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom bg-light">
                <div>
                    <h6 class="modal-title fw-bold text-dark" id="overridesModalLabel">Franchise Price Overrides</h6>
                    <small class="text-muted" id="modalServiceName"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <p class="small text-muted mb-3">
                    The following franchise accounts have custom price overrides configured that supersede the catalog base price:
                </p>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Franchise Name</th>
                                <th class="text-end">Custom Price</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="overridesModalTableBody">
                            <!-- Populated via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('admin.franchises') }}" class="btn btn-primary btn-sm">
                    Manage Franchises
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Floating Toast Container for Instant AJAX Notifications -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
    <div id="pricingToast" class="toast align-items-center text-white bg-dark border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill text-success fs-5" id="toastIcon"></i>
                <span id="toastMessage">Price updated successfully!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Live Search Filter
    const searchInput = document.getElementById('pricingSearch');
    const tableRows = document.querySelectorAll('.service-table-row');
    let activeCategory = 'ALL';

    if (searchInput) {
        searchInput.addEventListener('input', filterServices);
    }

    // Category Filter Buttons
    document.querySelectorAll('.category-filter-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.category-filter-btn').forEach(b => {
                b.classList.remove('btn-dark', 'active');
                b.classList.add('btn-outline-secondary');
            });
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-dark', 'active');
            activeCategory = this.dataset.category;
            filterServices();
        });
    });

    function filterServices() {
        const query = (searchInput ? searchInput.value.toLowerCase().trim() : '');
        tableRows.forEach(row => {
            const matchesSearch = !query || row.dataset.search.includes(query);
            const matchesCat = (activeCategory === 'ALL' || row.dataset.category === activeCategory);
            if (matchesSearch && matchesCat) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Stepper & Adjustment Handlers
    function stepPrice(serviceId, delta) {
        const input = document.getElementById('price_input_' + serviceId);
        if (!input) return;
        let current = parseFloat(input.value) || 0;
        let nextVal = Math.max(0, current + delta);
        input.value = nextVal.toFixed(2);
        handlePriceInputChange(serviceId);
    }

    function addPricePreset(serviceId, addAmount) {
        const input = document.getElementById('price_input_' + serviceId);
        if (!input) return;
        let current = parseFloat(input.value) || 0;
        input.value = (current + addAmount).toFixed(2);
        handlePriceInputChange(serviceId);
    }

    function resetSinglePrice(serviceId) {
        const input = document.getElementById('price_input_' + serviceId);
        if (!input) return;
        input.value = input.dataset.originalPrice;
        handlePriceInputChange(serviceId);
    }

    function resetAllPrices() {
        document.querySelectorAll('.price-input').forEach(input => {
            input.value = input.dataset.originalPrice;
            handlePriceInputChange(input.dataset.serviceId);
        });
        checkUnsavedChanges();
        showToast('All prices have been reset to current values.', 'info');
    }

    function quickAdjustAll(delta) {
        document.querySelectorAll('.price-input').forEach(input => {
            let current = parseFloat(input.value) || 0;
            let nextVal = Math.max(0, current + delta);
            input.value = nextVal.toFixed(2);
            handlePriceInputChange(input.dataset.serviceId);
        });
        checkUnsavedChanges();
        showToast(`Adjusted all prices by ${delta > 0 ? '+' : ''}₹${delta.toFixed(2)}. Click Save to commit.`, 'info');
    }

    function handlePriceInputChange(serviceId) {
        const input = document.getElementById('price_input_' + serviceId);
        const row = document.getElementById('service_row_' + serviceId);
        const saveBtn = document.getElementById('btn_save_' + serviceId);
        if (!input || !row) return;

        const original = parseFloat(input.dataset.originalPrice);
        const current = parseFloat(input.value) || 0;

        if (Math.abs(original - current) > 0.001) {
            row.classList.add('table-warning');
            if (saveBtn) {
                saveBtn.classList.remove('btn-outline-primary');
                saveBtn.classList.add('btn-primary');
            }
        } else {
            row.classList.remove('table-warning');
            if (saveBtn) {
                saveBtn.classList.remove('btn-primary');
                saveBtn.classList.add('btn-outline-primary');
            }
        }

        checkUnsavedChanges();
    }

    function checkUnsavedChanges() {
        let hasChanges = false;
        document.querySelectorAll('.price-input').forEach(input => {
            const original = parseFloat(input.dataset.originalPrice);
            const current = parseFloat(input.value) || 0;
            if (Math.abs(original - current) > 0.001) {
                hasChanges = true;
            }
        });

        const badge = document.getElementById('unsavedBadge');
        if (badge) {
            if (hasChanges) {
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }
        }
    }

    // Single Service Save via AJAX
    function saveSingleServicePrice(serviceId) {
        const input = document.getElementById('price_input_' + serviceId);
        const saveBtn = document.getElementById('btn_save_' + serviceId);
        const currentPriceBadge = document.getElementById('current_price_' + serviceId);
        const row = document.getElementById('service_row_' + serviceId);

        if (!input || !saveBtn) return;

        const newPrice = parseFloat(input.value);
        if (isNaN(newPrice) || newPrice < 0) {
            alert('Please enter a valid non-negative price amount.');
            return;
        }

        // Visual loading state
        const originalBtnHtml = saveBtn.innerHTML;
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';

        fetch("{{ url('/services/pricing') }}/" + serviceId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                token_cost: newPrice,
                default_franchise_price: newPrice
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="bi bi-check-lg text-success me-1"></i>Saved!';
            saveBtn.classList.remove('btn-primary');
            saveBtn.classList.add('btn-outline-success');

            // Update original dataset
            input.dataset.originalPrice = newPrice.toFixed(2);
            row.classList.remove('table-warning');
            row.classList.add('table-success');

            // Update current price badge
            if (currentPriceBadge) {
                currentPriceBadge.textContent = '₹' + newPrice.toFixed(2);
            }

            checkUnsavedChanges();
            showToast(data.message || `Price updated successfully to ₹${newPrice.toFixed(2)}`, 'success');

            setTimeout(() => {
                row.classList.remove('table-success');
                saveBtn.innerHTML = '<i class="bi bi-check2 me-1"></i>Save';
                saveBtn.classList.remove('btn-outline-success');
                saveBtn.classList.add('btn-outline-primary');
            }, 2500);
        })
        .catch(err => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalBtnHtml;
            const msg = err.message || (err.errors ? Object.values(err.errors).flat().join(', ') : 'Failed to update price.');
            showToast(msg, 'danger');
        });
    }

    // Overrides Modal Display
    const overridesModal = document.getElementById('overridesModal');
    if (overridesModal) {
        overridesModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const serviceName = button.getAttribute('data-service-name');
            const overridesRaw = button.getAttribute('data-overrides');
            const modalServiceName = document.getElementById('modalServiceName');
            const tbody = document.getElementById('overridesModalTableBody');

            if (modalServiceName) modalServiceName.textContent = serviceName;
            tbody.innerHTML = '';

            try {
                const overrides = JSON.parse(overridesRaw);
                if (overrides && overrides.length > 0) {
                    overrides.forEach(item => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>
                                <div class="fw-semibold text-dark">${item.franchise_name}</div>
                                <small class="text-muted">Account #${item.franchise_id}</small>
                            </td>
                            <td class="text-end fw-bold text-primary">₹${parseFloat(item.custom_price).toFixed(2)}</td>
                            <td class="text-end">
                                <a href="{{ url('/franchises') }}/${item.franchise_id}/services-cost" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.72rem;">
                                    Edit Override
                                </a>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-3">No custom overrides configured.</td></tr>';
                }
            } catch (e) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-danger py-2">Error parsing overrides data.</td></tr>';
            }
        });
    }

    // Toast Notification Helper
    function showToast(message, type = 'success') {
        const toastEl = document.getElementById('pricingToast');
        const msgEl = document.getElementById('toastMessage');
        const iconEl = document.getElementById('toastIcon');

        if (!toastEl || !msgEl) return;

        msgEl.textContent = message;

        if (type === 'success') {
            toastEl.className = 'toast align-items-center text-white bg-dark border-0 shadow';
            iconEl.className = 'bi bi-check-circle-fill text-success fs-5';
        } else if (type === 'danger') {
            toastEl.className = 'toast align-items-center text-white bg-danger border-0 shadow';
            iconEl.className = 'bi bi-x-circle-fill text-white fs-5';
        } else {
            toastEl.className = 'toast align-items-center text-white bg-dark border-0 shadow';
            iconEl.className = 'bi bi-info-circle-fill text-info fs-5';
        }

        const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
        toast.show();
    }
</script>
@endpush
@endsection
