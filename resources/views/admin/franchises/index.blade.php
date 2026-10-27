@extends('layouts.admin')

@section('title', 'Franchise Management')
@section('page_title', 'Franchise Accounts & Child Systems')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Franchise Directory</h4>
            <p class="text-muted small mb-0">Master accounts can manage child franchise systems with verified credentials and token allocations.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.services.pricing') }}" class="btn btn-outline-secondary fw-semibold d-flex align-items-center gap-2">
                <i class="bi bi-tags-fill text-primary"></i>
                <span>Manage Default Prices</span>
            </a>
            <a href="{{ route('admin.franchises.invite') }}" class="btn btn-info text-white fw-semibold d-flex align-items-center gap-2">
                <i class="bi bi-link-45deg"></i>
                <span>Generate Invite URL</span>
            </a>
        </div>
    </div>

    @if(session('invite_url'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <h5 class="alert-heading"><i class="bi bi-check-circle-fill me-2"></i>Invite URL Generated!</h5>
        <p class="mb-2">Share this unique, encrypted URL with your new franchise partner. It will allow them to register and pay the ₹100 registration fee.</p>
        <div class="input-group">
            <input type="text" class="form-control font-mono" id="inviteUrlInput" value="{{ session('invite_url') }}" readonly>
            <button class="btn btn-primary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('inviteUrlInput').value); alert('Copied to clipboard!')">Copy Link</button>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Active / Inactive Status Filter Tabs -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <ul class="nav nav-pills gap-1 p-1 bg-white border rounded-pill shadow-xs">
            <li class="nav-item">
                <a class="nav-link py-1 px-3 rounded-pill fw-semibold {{ ($statusFilter ?? 'all') === 'all' ? 'active' : 'text-secondary' }}" 
                   href="{{ route('admin.franchises', ['status' => 'all']) }}">
                    All <span class="badge {{ ($statusFilter ?? 'all') === 'all' ? 'bg-white text-primary' : 'bg-secondary-subtle text-secondary' }} ms-1">{{ $counts['all'] ?? $franchises->total() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1 px-3 rounded-pill fw-semibold {{ ($statusFilter ?? '') === 'active' ? 'active bg-success text-white' : 'text-success' }}" 
                   href="{{ route('admin.franchises', ['status' => 'active']) }}">
                    <i class="bi bi-check-circle-fill me-1"></i>Active <span class="badge {{ ($statusFilter ?? '') === 'active' ? 'bg-white text-success' : 'bg-success-subtle text-success' }} ms-1">{{ $counts['active'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-1 px-3 rounded-pill fw-semibold {{ ($statusFilter ?? '') === 'inactive' ? 'active bg-danger text-white' : 'text-danger' }}" 
                   href="{{ route('admin.franchises', ['status' => 'inactive']) }}">
                    <i class="bi bi-x-circle-fill me-1"></i>Inactive <span class="badge {{ ($statusFilter ?? '') === 'inactive' ? 'bg-white text-danger' : 'bg-danger-subtle text-danger' }} ms-1">{{ $counts['inactive'] ?? 0 }}</span>
                </a>
            </li>
        </ul>
        <span class="text-muted small">
            <i class="bi bi-info-circle me-1"></i>Switch toggle to instantly activate or deactivate franchise
        </span>
    </div>

    <!-- Franchises Table Card -->
    <div class="card glass-card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Franchise Partner</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th class="pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($franchises as $item)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center shadow-xs" style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($item->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">{{ $item->name }}</h6>
                                        @if($item->full_name)
                                            <div class="small text-secondary fw-semibold"><i class="bi bi-person me-1"></i>{{ $item->full_name }}</div>
                                        @endif
                                        @if(!$item->gov_id_number && !$item->gov_id_type && !$item->dob && !$item->address)
                                            <span class="d-none">Not provided</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-envelope text-muted"></i>
                                    <span class="text-dark">{{ $item->email }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-telephone text-muted"></i>
                                    <span class="text-dark">{{ $item->phone ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="pe-4">
                                <div class="d-inline-flex align-items-center gap-2">
                                    <div class="form-check form-switch m-0 p-0" style="min-height: auto;">
                                        <input class="form-check-input status-toggle-switch" 
                                               type="checkbox" 
                                               role="switch" 
                                               id="toggle_status_{{ $item->id }}" 
                                               data-id="{{ $item->id }}" 
                                               data-name="{{ $item->name }}"
                                               data-url="{{ route('admin.franchises.toggle_status', $item->id) }}"
                                               {{ $item->status === 'active' ? 'checked' : '' }} 
                                               style="cursor: pointer; width: 2.3em; height: 1.2em;"
                                               title="Click to toggle Active / Inactive">
                                    </div>
                                    <span id="badge_status_{{ $item->id }}" class="badge {{ $item->status === 'active' ? 'bg-success-subtle text-success border border-success-subtle' : ($item->status === 'pending_payment' ? 'bg-warning-subtle text-warning border border-warning-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle') }} px-2.5 py-1">
                                        {{ $item->status === 'active' ? 'Active' : ($item->status === 'pending_payment' ? 'Pending Payment' : 'Inactive') }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                @if(($statusFilter ?? 'all') === 'active')
                                    No active franchise accounts found.
                                @elseif(($statusFilter ?? 'all') === 'inactive')
                                    No inactive franchise accounts found.
                                @else
                                    No franchise accounts found.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-top px-4 py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="text-muted small">
                Showing <span class="fw-semibold text-dark">{{ $franchises->firstItem() ?? 0 }}</span> to <span class="fw-semibold text-dark">{{ $franchises->lastItem() ?? 0 }}</span> of <span class="fw-semibold text-dark">{{ $franchises->total() }}</span> franchises
            </div>
            @if($franchises->hasPages())
                <div>
                    {{ $franchises->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal: Create Franchise -->
<div class="modal fade" id="createFranchiseModal" tabindex="-1" aria-labelledby="createFranchiseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <form class="modal-content border-0 shadow" action="{{ route('admin.franchises.store') }}" method="POST">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="createFranchiseModalLabel"><i class="bi bi-person-plus-fill me-2"></i>Onboard New Franchise Kendra</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="overflow-y: auto;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Franchise Name / Kendra <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Om Digital Seva Kendra" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Full Name (Owner / Operator) <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control" placeholder="e.g. Rajesh Kumar Sharma" value="{{ old('full_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Login Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="franchise@example.com" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Contact Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" placeholder="10-digit mobile number" value="{{ old('phone') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Initial Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Min 8 characters" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Password Reconfirm <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter password" required>
                    </div>

                    <!-- Default Service Prices Applicable at Creation -->
                    <div class="col-12 mt-4">
                        <div class="card border border-primary-subtle shadow-none rounded-3 overflow-hidden">
                            <div class="card-header bg-primary-subtle border-bottom border-primary-subtle d-flex justify-content-between align-items-center py-2.5 px-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-tags-fill text-primary"></i>
                                    <span class="fw-bold text-dark small">Default Service Pricing Matrix (Applicable at Creation)</span>
                                </div>
                                <a href="{{ route('admin.services.pricing') }}" target="_blank" class="small text-decoration-none fw-semibold">
                                    <i class="bi bi-pencil-square me-1"></i>Manage Catalog Defaults
                                </a>
                            </div>
                            <div class="card-body p-3 bg-white">
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-info-circle text-primary me-1"></i>
                                    When this franchise is created, it is automatically assigned the default prices configured in the <strong>Main Administrator Panel</strong>. You can customize any price specifically for this franchise below or keep the default rates.
                                </p>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Service Name</th>
                                                <th>Category</th>
                                                <th>Default Franchise Price</th>
                                                <th style="width: 200px;">Assigned Price (Tokens)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($services ?? [] as $srv)
                                                <tr>
                                                    <td>
                                                        <div class="fw-semibold text-dark">{{ $srv->name }}</div>
                                                        <code class="text-muted small">{{ $srv->service_code }}</code>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-secondary border">{{ $srv->category }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary-subtle text-secondary font-mono">₹{{ number_format($srv->default_franchise_price, 2) }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text">₹</span>
                                                            <input type="number" step="0.01" min="0" 
                                                                   name="prices[{{ $srv->id }}]" 
                                                                   class="form-control font-mono fw-bold text-primary" 
                                                                   value="{{ number_format($srv->default_franchise_price, 2, '.', '') }}" 
                                                                   required>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-2 text-muted small">No services available in catalog.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-secondary px-4 fw-semibold" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Close
                </button>
                <button type="submit" class="btn btn-primary fw-semibold px-4" id="btnSubmitFranchise">
                    <i class="bi bi-check-circle-fill me-1"></i>Submit
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Recharge Tokens -->
<div class="modal fade" id="rechargeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.franchises.recharge') }}" method="POST">
                @csrf
                <input type="hidden" name="franchise_id" id="rechargeFranchiseId">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Credit Wallet Tokens</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Franchise Kendra</label>
                        <input type="text" id="rechargeFranchiseName" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Recharge Token Amount (1 INR = 1 Token)</label>
                        <div class="input-group">
                            <span class="input-group-text">₹ / Tokens</span>
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="100.00" required min="1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success fw-semibold">Confirm Token Recharge</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal: Services Cost Pricing -->
<div class="modal fade" id="servicesCostModal" tabindex="-1" aria-labelledby="servicesCostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-dark text-white px-4 py-3 border-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded-3 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="bi bi-tag-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="servicesCostModalLabel">Services Cost Management</h5>
                        <small class="text-white-50" id="scModalSubtitle">Configure custom service pricing for franchise partner</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="servicesCostForm" method="POST" action="" class="d-flex flex-column flex-grow-1 overflow-hidden" style="min-height: 0;">
                @csrf
                <div class="modal-body p-4" style="overflow-y: auto;">

                    <!-- Franchise Quick Info Strip -->
                    <div class="p-3 rounded-3 bg-light border mb-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" id="scFranchiseAvatar" style="width: 40px; height: 40px;">
                                F
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark" id="scFranchiseName">Franchise Kendra</h6>
                                <span class="text-muted small" id="scFranchiseEmail">franchise@example.com</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary text-white px-2.5 py-1.5 font-mono" style="font-size: 0.75rem;">1 Token = 1 INR</span>
                            <a href="#" id="scDedicatedPageLink" class="btn btn-outline-secondary btn-sm" target="_blank" title="Open full dedicated page in new tab">
                                <i class="bi bi-box-arrow-up-right me-1"></i>Full Screen Editor
                            </a>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div id="scLoadingSpinner" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="text-muted small mt-2 mb-0">Loading services and current price configurations...</p>
                    </div>

                    <!-- Services Table Container -->
                    <div id="scTableContainer" class="d-none">
                        <div class="table-responsive border rounded-3 overflow-hidden">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3" style="min-width: 220px;">Service Name & Code</th>
                                        <th>Category</th>
                                        <th>Default Catalog Price</th>
                                        <th style="min-width: 170px;">Franchise Price (₹ / Tokens)</th>
                                        <th class="text-end pe-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="scServicesTableBody">
                                    <!-- Populated via AJAX -->
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-light border small text-muted d-flex align-items-center gap-2 mt-3 mb-0">
                            <i class="bi bi-info-circle text-primary fs-5 flex-shrink-0"></i>
                            <div>
                                When this franchise partner logs in, these prices will be displayed on their <strong>Our Services</strong> portal and deducted from their wallet upon execution.
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light px-4 py-3 border-0 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary fw-semibold px-4" id="btnSaveServicesCost">
                        <span id="scSaveSpinner" class="spinner-border spinner-border-sm d-none me-1" role="status"></span>
                        <i class="bi bi-check2-circle me-1" id="scSaveIcon"></i>
                        <span>Save & Update Services Cost</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Status Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1090;">
        <div id="statusToast" class="toast align-items-center text-white bg-dark border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i id="statusToastIcon" class="bi bi-check-circle-fill text-success fs-5"></i>
                    <span id="statusToastMessage">Status updated successfully.</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Active / Inactive Status Switch Handler
    const statusToggles = document.querySelectorAll('.status-toggle-switch');
    statusToggles.forEach(toggle => {
        toggle.addEventListener('change', async function () {
            const franchiseId = this.getAttribute('data-id');
            const franchiseName = this.getAttribute('data-name');
            const url = this.getAttribute('data-url');
            const newStatus = this.checked ? 'active' : 'inactive';
            const badge = document.getElementById('badge_status_' + franchiseId);
            const originalChecked = !this.checked;

            this.disabled = true;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                if (!response.ok) {
                    throw new Error('Server returned ' + response.status);
                }

                const result = await response.json();
                if (result.success) {
                    if (result.status === 'active') {
                        badge.className = 'badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1';
                        badge.textContent = 'Active';
                    } else {
                        badge.className = 'badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1';
                        badge.textContent = 'Inactive';
                    }
                    showStatusToast(result.message || `Franchise '${franchiseName}' is now ${result.status_label}.`, 'success');
                } else {
                    throw new Error(result.message || 'Failed to update status');
                }
            } catch (err) {
                this.checked = originalChecked;
                showStatusToast(`Error updating status for '${franchiseName}': ${err.message}`, 'danger');
            } finally {
                this.disabled = false;
            }
        });
    });

    function showStatusToast(message, type = 'success') {
        const toastEl = document.getElementById('statusToast');
        const toastMsg = document.getElementById('statusToastMessage');
        const toastIcon = document.getElementById('statusToastIcon');
        if (!toastEl) return;

        toastMsg.textContent = message;
        if (type === 'success') {
            toastIcon.className = 'bi bi-check-circle-fill text-success fs-5';
        } else {
            toastIcon.className = 'bi bi-exclamation-triangle-fill text-danger fs-5';
        }

        const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
        toast.show();
    }
    // Recharge Modal Setup
    const rechargeModal = document.getElementById('rechargeModal');
    if (rechargeModal) {
        rechargeModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('rechargeFranchiseId').value = button.getAttribute('data-id');
            document.getElementById('rechargeFranchiseName').value = button.getAttribute('data-name');
        });
    }

    // Services Cost Modal Setup
    const servicesCostModal = document.getElementById('servicesCostModal');
    const scForm = document.getElementById('servicesCostForm');
    const scLoadingSpinner = document.getElementById('scLoadingSpinner');
    const scTableContainer = document.getElementById('scTableContainer');
    const scServicesTableBody = document.getElementById('scServicesTableBody');
    const scFranchiseName = document.getElementById('scFranchiseName');
    const scFranchiseEmail = document.getElementById('scFranchiseEmail');
    const scFranchiseAvatar = document.getElementById('scFranchiseAvatar');
    const scDedicatedPageLink = document.getElementById('scDedicatedPageLink');

    if (servicesCostModal) {
        servicesCostModal.addEventListener('show.bs.modal', async function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const franchiseId = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            const url = button.getAttribute('data-url');
            const updateUrl = button.getAttribute('data-update-url');

            // Set Header and metadata
            scFranchiseName.innerText = name;
            scFranchiseEmail.innerText = email || '';
            scFranchiseAvatar.innerText = (name || 'F').charAt(0).toUpperCase();
            scDedicatedPageLink.href = url;
            scForm.action = updateUrl;

            // Reset UI states
            scLoadingSpinner.classList.remove('d-none');
            scTableContainer.classList.add('d-none');
            scServicesTableBody.innerHTML = '';

            try {
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to load services cost details');
                }

                const data = await response.json();
                scServicesTableBody.innerHTML = '';

                data.services.forEach(service => {
                    const hasCustom = service.custom_price !== null;
                    const displayVal = hasCustom ? parseFloat(service.custom_price).toFixed(2) : parseFloat(service.default_price).toFixed(2);
                    const defaultVal = parseFloat(service.default_price).toFixed(2);

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="ps-3">
                            <div class="fw-bold text-dark">${service.name}</div>
                            <code class="font-mono text-muted" style="font-size: 0.72rem;">${service.service_code}</code>
                            <div class="text-muted small text-truncate" style="max-width: 280px;" title="${service.description || ''}">
                                ${service.description || ''}
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">${service.category}</span>
                        </td>
                        <td>
                            <span class="fw-semibold text-secondary">₹${defaultVal}</span>
                            <small class="text-muted d-block" style="font-size: 0.72rem;">${defaultVal} Tokens</small>
                        </td>
                        <td>
                            <div class="input-group input-group-sm" style="max-width: 170px;">
                                <span class="input-group-text font-mono fw-bold">₹</span>
                                <input type="number"
                                       step="0.01"
                                       min="0"
                                       name="prices[${service.id}]"
                                       id="modal_price_${service.id}"
                                       class="form-control font-mono fw-bold sc-modal-price-input"
                                       data-default="${defaultVal}"
                                       data-service-id="${service.id}"
                                       value="${displayVal}"
                                       placeholder="${defaultVal}">
                            </div>
                            <span class="badge ${hasCustom ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-light text-secondary border'} font-mono mt-1" style="font-size: 0.68rem;" id="modal_badge_status_${service.id}">
                                ${hasCustom ? 'Custom Rate' : 'Standard Rate'}
                            </span>
                        </td>
                        <td class="text-end pe-3">
                            <button type="button"
                                    class="btn btn-outline-secondary btn-sm sc-btn-reset-default"
                                    data-target="modal_price_${service.id}"
                                    data-default="${defaultVal}"
                                    data-service-id="${service.id}"
                                    title="Reset to standard catalog price">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </td>
                    `;
                    scServicesTableBody.appendChild(tr);
                });

                // Attach change listeners to price inputs inside modal
                scServicesTableBody.querySelectorAll('.sc-modal-price-input').forEach(input => {
                    input.addEventListener('input', function () {
                        const sid = this.getAttribute('data-service-id');
                        const def = parseFloat(this.getAttribute('data-default')) || 0;
                        const entered = parseFloat(this.value);
                        const statusBadge = document.getElementById('modal_badge_status_' + sid);

                        if (!isNaN(entered) && statusBadge) {
                            if (entered !== def) {
                                statusBadge.className = 'badge bg-primary-subtle text-primary border border-primary-subtle font-mono mt-1';
                                statusBadge.innerText = 'Custom Rate';
                            } else {
                                statusBadge.className = 'badge bg-light text-secondary border font-mono mt-1';
                                statusBadge.innerText = 'Standard Rate';
                            }
                        }
                    });
                });

                // Attach reset buttons inside modal
                scServicesTableBody.querySelectorAll('.sc-btn-reset-default').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const targetId = this.getAttribute('data-target');
                        const def = this.getAttribute('data-default');
                        const input = document.getElementById(targetId);
                        if (input) {
                            input.value = def;
                            input.dispatchEvent(new Event('input'));
                        }
                    });
                });

                scLoadingSpinner.classList.add('d-none');
                scTableContainer.classList.remove('d-none');

            } catch (err) {
                scLoadingSpinner.innerHTML = `
                    <div class="alert alert-danger mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        Failed to load services: ${err.message}.
                        <div class="mt-2">
                            <a href="${url}" class="btn btn-sm btn-primary">Open Dedicated Page</a>
                        </div>
                    </div>
                `;
            }
        });
    }

    // Auto re-open Create Franchise modal if validation errors exist
    @if($errors->any())
    const createModalEl = document.getElementById('createFranchiseModal');
    if (createModalEl) {
        const createModal = new bootstrap.Modal(createModalEl);
        createModal.show();
    }
    @endif
});
</script>
@endpush
