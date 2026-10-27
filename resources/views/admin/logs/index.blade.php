@extends('layouts.admin')

@section('title', Auth::user()->role === 'franchise' ? 'My Verification Logs' : 'Verification Audit Logs')
@section('page_title', Auth::user()->role === 'franchise' ? 'My Verification Logs' : 'Verification Audit Logs')
@section('page_subtitle', Auth::user()->role === 'franchise' ? 'Real-time trace of your verification queries, token deductions, and gateway responses' : '')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-calendar {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        font-family: inherit !important;
    }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange {
        background: #2563eb !important;
        border-color: #2563eb !important;
    }
    .flatpickr-day.inRange {
        background: #dbeafe !important;
        border-color: #dbeafe !important;
        box-shadow: -5px 0 0 #dbeafe, 5px 0 0 #dbeafe !important;
    }
    .btn-xs {
        padding: 0.15rem 0.45rem;
        font-size: 0.72rem;
        border-radius: 6px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-0">

    <!-- Filters Bar -->
    <div class="card glass-card border-0 shadow-sm mb-4 p-3">
        <form id="logsFilterForm" action="{{ route('admin.logs') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-lg-3 col-md-6">
                <label class="form-label small fw-semibold">Filter by Service</label>
                <select name="service_code" class="form-select form-select-sm">
                    <option value="">All Services</option>
                    @foreach($services as $srv)
                        <option value="{{ $srv->service_code }}" {{ request('service_code') == $srv->service_code ? 'selected' : '' }}>
                            {{ $srv->name }} ({{ $srv->service_code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label small fw-semibold">Filter by Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="SUCCESS" {{ request('status') == 'SUCCESS' ? 'selected' : '' }}>SUCCESS</option>
                    <option value="FAILED" {{ request('status') == 'FAILED' ? 'selected' : '' }}>FAILED</option>
                    <option value="TIMEOUT" {{ request('status') == 'TIMEOUT' ? 'selected' : '' }}>TIMEOUT</option>
                    <option value="INSUFFICIENT_FUNDS" {{ request('status') == 'INSUFFICIENT_FUNDS' ? 'selected' : '' }}>INSUFFICIENT_FUNDS</option>
                    <option value="ERROR" {{ request('status') == 'ERROR' ? 'selected' : '' }}>ERROR</option>
                </select>
            </div>

            <!-- Date Range Filter (e.g. 2nd Nov 26 to 5 Nov 26) -->
            <div class="col-lg-4 col-md-8">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label small fw-semibold mb-0" for="date_range">
                        <i class="bi bi-calendar3 me-1 text-primary"></i>Date Filter
                    </label>
                    <div class="d-flex gap-1">
                        <button type="button" class="btn btn-xs py-0 px-1.5 btn-outline-primary btn-date-preset" data-preset="today" title="Set to Today">Today</button>
                        <button type="button" class="btn btn-xs py-0 px-1.5 btn-outline-secondary btn-date-preset" data-preset="yesterday" title="Set to Yesterday">Yesterday</button>
                        <button type="button" class="btn btn-xs py-0 px-1.5 btn-outline-secondary btn-date-preset" data-preset="last7" title="Set to Last 7 Days">7 Days</button>
                        <button type="button" class="btn btn-xs py-0 px-1.5 btn-outline-secondary btn-date-preset" data-preset="month" title="Set to This Month">Month</button>
                    </div>
                </div>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-calendar-range text-primary"></i>
                    </span>
                    <input type="text" 
                           id="date_range" 
                           name="date_range" 
                           class="form-control form-control-sm border-start-0" 
                           placeholder="e.g. 2nd Nov 26 to 5 Nov 26" 
                           value="{{ $dateRangeValue ?? request('date_range') }}" 
                           autocomplete="off">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="clear_date_btn" title="Clear Date Filter">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <div class="col-lg-3 col-md-4 d-flex flex-wrap align-items-end gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-3 flex-grow-1 flex-md-grow-0">
                    <i class="bi bi-funnel-fill me-1"></i>Filter
                </button>
                <a href="{{ route('admin.logs') }}" class="btn btn-outline-secondary btn-sm px-3 flex-grow-1 flex-md-grow-0" title="Clear all filters">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Active Filters Badge Summary Bar -->
    @if($dateRangeText || request('status') || request('service_code'))
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 p-2 px-3 mb-3 rounded-3 bg-primary-subtle border border-primary-subtle text-primary-emphasis small">
        <div class="d-flex align-items-center flex-wrap gap-2">
            <span class="fw-semibold"><i class="bi bi-funnel me-1"></i>Active Filters:</span>
            @if($dateRangeText)
                <span class="badge bg-white text-primary border shadow-sm d-inline-flex align-items-center gap-1.5 py-1 px-2">
                    <i class="bi bi-calendar-event text-primary"></i>
                    <span>Date: <strong>{{ $dateRangeText }}</strong></span>
                    <a href="{{ route('admin.logs', request()->except(['date_range', 'today', 'from_date', 'to_date'])) }}" class="text-danger ms-1 text-decoration-none fw-bold" title="Remove date filter">&times;</a>
                </span>
            @endif
            @if(request('status'))
                <span class="badge bg-white text-primary border shadow-sm d-inline-flex align-items-center gap-1.5 py-1 px-2">
                    <span>Status: <strong>{{ request('status') }}</strong></span>
                    <a href="{{ route('admin.logs', request()->except('status')) }}" class="text-danger ms-1 text-decoration-none fw-bold" title="Remove status filter">&times;</a>
                </span>
            @endif
            @if(request('service_code'))
                <span class="badge bg-white text-primary border shadow-sm d-inline-flex align-items-center gap-1.5 py-1 px-2">
                    <span>Service: <strong>{{ request('service_code') }}</strong></span>
                    <a href="{{ route('admin.logs', request()->except('service_code')) }}" class="text-danger ms-1 text-decoration-none fw-bold" title="Remove service filter">&times;</a>
                </span>
            @endif
            <span class="text-muted ms-2">({{ $logs->total() }} record{{ $logs->total() == 1 ? '' : 's' }} found)</span>
        </div>
        <a href="{{ route('admin.logs') }}" class="btn btn-link btn-sm p-0 text-decoration-none text-danger small fw-medium">
            <i class="bi bi-x-circle me-1"></i>Clear All Filters
        </a>
    </div>
    @endif

    <!-- Logs Table -->
    <div class="card glass-card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Timestamp</th>
                        @if(Auth::user()->role === 'admin')
                            <th>Franchise / User</th>
                        @endif
                        <th>Service Name</th>
                        <th>Tokens Deducted</th>
                        <th class="pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr data-uid="UID: {{ $log->user_id }}" data-ip="{{ $log->ip_address }}">
                            <td class="ps-4 text-muted small">
                                <div class="fw-medium text-dark">{{ $log->created_at->format('jS M Y') }}</div>
                                <div class="font-mono text-muted" style="font-size: 0.75rem;">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            @if(Auth::user()->role === 'admin')
                                <td>
                                    <div class="fw-semibold text-dark">{{ $log->user->name ?? 'User #' . $log->user_id }}</div>
                                    <small class="text-muted font-mono" style="font-size: 0.75rem;">UID: {{ $log->user_id }}</small>
                                </td>
                            @endif
                            <td>
                                <div class="fw-semibold text-dark">{{ $log->service->name ?? ($log->service_code ? ucwords(str_replace('_', ' ', $log->service_code)) : 'Verification Service') }}</div>
                                @if(!empty($log->service?->category))
                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $log->service->category }}</small>
                                @endif
                            </td>

                            <td>
                                @if($log->tokens_deducted > 0)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-bold">
                                        -{{ number_format($log->tokens_deducted, 2) }} Tokens
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">0.00 (No deduction)</span>
                                @endif
                            </td>
                            <td class="pe-4">
                                @php
                                    $badgeClass = match($log->status) {
                                        'SUCCESS' => 'bg-success text-white',
                                        'INSUFFICIENT_FUNDS' => 'bg-warning text-dark',
                                        'TIMEOUT' => 'bg-info text-white',
                                        'FAILED' => 'bg-danger text-white',
                                        default => 'bg-secondary text-white',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $log->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">No verification logs found matching criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-top px-4 py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="text-muted small">
                Showing <span class="fw-semibold text-dark">{{ $logs->firstItem() ?? 0 }}</span> to <span class="fw-semibold text-dark">{{ $logs->lastItem() ?? 0 }}</span> of <span class="fw-semibold text-dark">{{ $logs->total() }}</span> audit log entries
            </div>
            @if($logs->hasPages())
                <div>
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal: User-Friendly Payload & Response Inspector -->
<div class="modal fade" id="payloadModal" tabindex="-1" aria-labelledby="payloadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-dark text-white px-4 py-3 border-0">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="p-2 rounded-3 bg-primary text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                        <i class="bi bi-layout-text-window-reverse fs-5"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="modal-title fw-bold mb-0">Verification Record Inspector</h5>
                            <span class="badge" id="modalStatusBadge">SUCCESS</span>
                        </div>
                        <small class="text-white-50" id="modalInspectorSubtitle">Gateway verification response trace</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Transaction Summary Strip -->
            <div class="px-4 py-2.5 bg-light border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2 text-muted small">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-secondary-subtle text-dark border font-mono" id="modalServiceBadge">service_code</span>
                    <span class="text-dark fw-semibold" id="modalServiceName">Service Name</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span><i class="bi bi-stopwatch text-muted me-1"></i><span id="modalLatency">-</span></span>
                    <span><i class="bi bi-coin text-warning me-1"></i><span id="modalTokens" class="fw-bold text-dark">-</span></span>
                    <span><i class="bi bi-clock text-muted me-1"></i><span id="modalTimestamp">-</span></span>
                </div>
            </div>

            <!-- Nav Tabs -->
            <div class="px-4 pt-3 pb-0 bg-white border-bottom">
                <ul class="nav nav-tabs border-0" id="inspectorTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-semibold d-flex align-items-center gap-1.5" id="friendly-tab" data-bs-toggle="tab" data-bs-target="#friendlyTabPane" type="button" role="tab">
                            <i class="bi bi-card-checklist text-primary"></i> Friendly Response
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold d-flex align-items-center gap-1.5" id="request-tab" data-bs-toggle="tab" data-bs-target="#requestTabPane" type="button" role="tab">
                            <i class="bi bi-box-arrow-in-right text-success"></i> Request Inputs
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold d-flex align-items-center gap-1.5" id="raw-tab" data-bs-toggle="tab" data-bs-target="#rawTabPane" type="button" role="tab">
                            <i class="bi bi-code-slash text-dark"></i> Raw JSON
                        </button>
                    </li>
                </ul>
            </div>

            <div class="modal-body p-4 bg-light" style="min-height: 320px;">
                <div id="modalErrorNotice" class="alert alert-danger d-none d-flex align-items-start gap-2 shadow-sm rounded-3 mb-3 p-3">
                    <i class="bi bi-exclamation-triangle-fill fs-5 text-danger flex-shrink-0 mt-0.5"></i>
                    <div>
                        <strong class="d-block">Upstream Error / Failure Reported:</strong>
                        <span id="modalErrorText" class="small"></span>
                    </div>
                </div>

                <div class="tab-content" id="inspectorTabContent">
                    <!-- Tab 1: Friendly Response View -->
                    <div class="tab-pane fade show active" id="friendlyTabPane" role="tabpanel">
                        <div id="friendlyResponseContainer">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                    <!-- Tab 2: Request Parameters View -->
                    <div class="tab-pane fade" id="requestTabPane" role="tabpanel">
                        <div id="friendlyRequestContainer">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>

                    <!-- Tab 3: Raw JSON View -->
                    <div class="tab-pane fade" id="rawTabPane" role="tabpanel">
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                <label class="form-label small fw-bold text-dark mb-0">Upstream Response JSON</label>
                                <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" style="font-size: 0.72rem;" id="btnCopyRawResponse">
                                    <i class="bi bi-clipboard me-1"></i>Copy Response JSON
                                </button>
                            </div>
                            <pre id="modalResponsePayload" class="p-3 bg-dark text-light rounded-3 font-mono border" style="font-size: 0.78rem; max-height: 220px; overflow-y: auto;"></pre>
                        </div>
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                <label class="form-label small fw-bold text-dark mb-0">Request Payload Sent</label>
                                <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" style="font-size: 0.72rem;" id="btnCopyRawRequest">
                                    <i class="bi bi-clipboard me-1"></i>Copy Request JSON
                                </button>
                            </div>
                            <pre id="modalRequestPayload" class="p-3 bg-white text-dark rounded-3 font-mono border" style="font-size: 0.78rem; max-height: 180px; overflow-y: auto;"></pre>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-white border-top px-4 py-2.5">
                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Flatpickr Date Range Initialization
    const dateRangeInput = document.getElementById('date_range');
    let fpInstance = null;

    if (dateRangeInput && typeof flatpickr !== 'undefined') {
        fpInstance = flatpickr(dateRangeInput, {
            mode: "range",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "J M y", // Formats visually as: 2nd Nov 26 to 5 Nov 26
            allowInput: true,
            locale: {
                rangeSeparator: " to "
            },
            defaultDate: dateRangeInput.value ? dateRangeInput.value.split(' to ') : null
        });

        // Clear date button
        const clearDateBtn = document.getElementById('clear_date_btn');
        if (clearDateBtn) {
            clearDateBtn.addEventListener('click', function () {
                if (fpInstance) {
                    fpInstance.clear();
                }
                dateRangeInput.value = '';
                const altInput = dateRangeInput.parentNode.querySelector('.flatpickr-input:not([type="hidden"])');
                if (altInput) {
                    altInput.value = '';
                }
            });
        }

        // Helper to format Date as YYYY-MM-DD
        function formatYMD(d) {
            const yr = d.getFullYear();
            const mo = String(d.getMonth() + 1).padStart(2, '0');
            const dy = String(d.getDate()).padStart(2, '0');
            return `${yr}-${mo}-${dy}`;
        }

        // Preset quick buttons (Today, Yesterday, 7 Days, Month)
        document.querySelectorAll('.btn-date-preset').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const preset = this.getAttribute('data-preset');
                const today = new Date();
                let start = new Date();
                let end = new Date();

                if (preset === 'today') {
                    start = new Date();
                    end = new Date();
                } else if (preset === 'yesterday') {
                    start.setDate(today.getDate() - 1);
                    end.setDate(today.getDate() - 1);
                } else if (preset === 'last7') {
                    start.setDate(today.getDate() - 6);
                } else if (preset === 'month') {
                    start = new Date(today.getFullYear(), today.getMonth(), 1);
                }

                if (fpInstance) {
                    fpInstance.setDate([start, end], true);
                } else {
                    dateRangeInput.value = `${formatYMD(start)} to ${formatYMD(end)}`;
                }

                const filterForm = document.getElementById('logsFilterForm');
                if (filterForm) {
                    filterForm.submit();
                }
            });
        });
    }

    const payloadModal = document.getElementById('payloadModal');

    // Dictionary of user-friendly field labels, icons, and formatters
    const fieldDictionary = {
        api_id: { label: 'Partner ID', icon: 'bi-key-fill text-warning' },
        api_key: { label: 'Security Key', icon: 'bi-shield-lock-fill text-primary' },
        token_id: { label: 'Token ID', icon: 'bi-file-earmark-lock text-info' },
        dlNumber: { label: 'Driving License Number', icon: 'bi-card-heading text-danger', isMono: true, copyable: true },
        dl_number: { label: 'Driving License Number', icon: 'bi-card-heading text-danger', isMono: true, copyable: true },
        dob: { label: 'Date of Birth', icon: 'bi-calendar-event text-danger', isDate: true },
        holder_name: { label: 'Cardholder / Licensee Name', icon: 'bi-person-fill text-primary', highlight: true },
        name: { label: 'Full Legal Name', icon: 'bi-person-fill text-primary', highlight: true },
        name_on_pan: { label: 'Name on PAN Record', icon: 'bi-person-fill text-primary', highlight: true },
        owner_name: { label: 'Vehicle Owner Name', icon: 'bi-person-badge-fill text-primary', highlight: true },
        validity: { label: 'License Validity / Expiry', icon: 'bi-calendar-check text-success' },
        expiry_date: { label: 'License Expiry Date', icon: 'bi-calendar-check text-warning' },
        date_of_issue: { label: 'License Date of Issue', icon: 'bi-calendar-plus text-secondary' },
        father_or_husband_name: { label: 'Father / Husband Name', icon: 'bi-person text-secondary' },
        user_blood_group: { label: 'Blood Group', icon: 'bi-droplet-fill text-danger', isBadge: true },
        cov: { label: 'Classes of Vehicle (COV)', icon: 'bi-car-front-fill text-secondary', isBadge: true },
        pan: { label: 'PAN Card Number', icon: 'bi-credit-card-2-front-fill text-warning', isMono: true, copyable: true, isPan: true },
        pan_number: { label: 'PAN Card Number', icon: 'bi-credit-card-2-front-fill text-warning', isMono: true, copyable: true, isPan: true },
        linked_pan: { label: 'Linked PAN Number', icon: 'bi-credit-card-2-front-fill text-warning', isMono: true, copyable: true, isPan: true },
        fullname: { label: 'Full Legal Name', icon: 'bi-person-fill text-primary', highlight: true },
        father_name: { label: 'Father Name', icon: 'bi-person text-secondary' },
        pan_type: { label: 'PAN Cardholder Type', icon: 'bi-tag text-info', isBadge: true },
        pan_status: { label: 'PAN Card Status', icon: 'bi-check-circle-fill text-success', isStatusBadge: true },
        aadhaar_linked: { label: 'Aadhaar Linkage Status', icon: 'bi-link-45deg text-info', isBoolean: true },
        pan_display_name: { label: 'Display Name Option', icon: 'bi-eye-fill text-info' },
        name_match_method: { label: 'Match Method Algorithm', icon: 'bi-spellcheck text-success', isBadge: true },
        name_match_score: { label: 'Name Match Score', icon: 'bi-percent text-success', isScore: true },
        vehicle_num: { label: 'Vehicle Plate Number', icon: 'bi-car-front text-success', isMono: true, copyable: true, isPlate: true },
        maker_model: { label: 'Vehicle Maker & Model', icon: 'bi-truck text-primary' },
        fuel_type: { label: 'Fuel Type', icon: 'bi-fuel-pump-fill text-danger', isBadge: true },
        registration_date: { label: 'Registration Date', icon: 'bi-calendar3 text-muted' },
        mobile: { label: 'Registered Mobile Number', icon: 'bi-telephone-fill text-success', isMono: true },
        voterId: { label: 'Voter ID (EPIC) Number', icon: 'bi-person-vcard text-purple', isMono: true, copyable: true },
        constituency: { label: 'Assembly Constituency', icon: 'bi-geo-alt-fill text-danger' },
        polling_station: { label: 'Polling Station Booth', icon: 'bi-building text-secondary' },
        aadhar: { label: 'Aadhaar Card Number', icon: 'bi-fingerprint text-primary', isMono: true },
        aadhaar_number: { label: 'Aadhaar Card Number', icon: 'bi-fingerprint text-primary', isMono: true },
        transaction_id: { label: 'IDSPay Transaction ID', icon: 'bi-hash text-muted', isMono: true, copyable: true },
        status: { label: 'Status', icon: 'bi-activity text-info', isStatusBadge: true },
        response_code: { label: 'Response Code', icon: 'bi-qr-code text-secondary', isMono: true },
        message: { label: 'Gateway Message', icon: 'bi-chat-left-text text-muted' },
        verified: { label: 'Verification State', icon: 'bi-patch-check-fill text-success', isBoolean: true },
        timestamp: { label: 'Gateway Timestamp', icon: 'bi-clock-history text-muted' }
    };

    function toFriendlyLabel(key) {
        if (fieldDictionary[key] && fieldDictionary[key].label) {
            return fieldDictionary[key].label;
        }
        return key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }

    function getFieldIcon(key) {
        if (fieldDictionary[key] && fieldDictionary[key].icon) {
            return fieldDictionary[key].icon;
        }
        return 'bi-info-circle text-muted';
    }

    // Helper: Render friendly parameter cards into a target container
    function renderFriendlyGrid(container, dataObj) {
        container.innerHTML = '';
        if (!dataObj || typeof dataObj !== 'object' || Object.keys(dataObj).length === 0) {
            container.innerHTML = '<div class="alert alert-secondary small text-center mb-0 py-3">No parameter data available.</div>';
            return;
        }

        const entries = Object.entries(dataObj);
        const gridRow = document.createElement('div');
        gridRow.className = 'row g-2.5';

        entries.forEach(([key, val]) => {
            const def = fieldDictionary[key] || {};
            const col = document.createElement('div');
            col.className = 'col-12 col-md-6';

            let valContent = '';
            let rawCopyVal = '';

            if (val === null || val === undefined || val === '') {
                valContent = '<span class="text-muted fst-italic">Not Available</span>';
            } else if (typeof val === 'boolean' || def.isBoolean) {
                const isTrue = (val === true || val === 'true' || val === 1 || val === '1');
                valContent = isTrue
                    ? '<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i>Yes / Verified</span>'
                    : '<span class="badge bg-secondary-subtle text-secondary border px-2 py-1"><i class="bi bi-x-circle me-1"></i>No / Unlinked</span>';
                rawCopyVal = String(val);
            } else if (def.isStatusBadge) {
                const s = String(val).toUpperCase();
                const badgeColor = s.includes('SUCCESS') || s.includes('ACTIVE') ? 'bg-success' : (s.includes('FAIL') ? 'bg-danger' : 'bg-primary');
                valContent = `<span class="badge ${badgeColor} text-white px-2 py-1">${val}</span>`;
                rawCopyVal = String(val);
            } else if (def.isScore) {
                valContent = `<span class="badge bg-success-subtle text-success border border-success-subtle font-mono fw-bold fs-6 px-2.5 py-1">${val}</span>`;
                rawCopyVal = String(val);
            } else if (def.isBadge) {
                valContent = `<span class="badge bg-light text-dark border font-mono px-2 py-1">${val}</span>`;
                rawCopyVal = String(val);
            } else if (typeof val === 'object') {
                valContent = `<pre class="bg-white border rounded p-1.5 mb-0 font-mono small" style="font-size: 0.72rem;">${JSON.stringify(val, null, 2)}</pre>`;
            } else {
                rawCopyVal = String(val);
                const monoClass = def.isMono ? 'font-mono fw-bold' : (def.highlight ? 'fw-bold text-dark' : 'text-dark');
                valContent = `<span class="${monoClass}" style="word-break: break-all;">${val}</span>`;
            }

            const copyBtnHtml = (rawCopyVal && typeof val !== 'object')
                ? `<button type="button" class="btn btn-link btn-sm p-0 text-muted ms-auto copy-value-btn" data-value="${rawCopyVal}" title="Copy value">
                     <i class="bi bi-clipboard"></i>
                   </button>`
                : '';

            col.innerHTML = `
                <div class="p-2.5 px-3 bg-white rounded-3 border shadow-sm h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <div class="d-flex align-items-center gap-1.5 text-muted small" style="font-size: 0.74rem;">
                            <i class="bi ${getFieldIcon(key)}"></i>
                            <span class="fw-semibold">${toFriendlyLabel(key)}</span>
                        </div>
                        ${copyBtnHtml}
                    </div>
                    <div class="pt-0.5 text-dark" style="font-size: 0.86rem;">
                        ${valContent}
                    </div>
                </div>
            `;
            gridRow.appendChild(col);
        });

        container.appendChild(gridRow);
    }

    // Helper: Render full friendly response with top-level metadata banner
    function buildFriendlyResponseView(container, respObj) {
        container.innerHTML = '';
        if (!respObj || typeof respObj !== 'object' || Object.keys(respObj).length === 0) {
            container.innerHTML = '<div class="alert alert-secondary small text-center mb-0 py-4">No response details returned for this transaction.</div>';
            return;
        }

        // 1. Status Banner
        const status = respObj.status || (respObj.success ? 'SUCCESS' : 'COMPLETED');
        const code = respObj.response_code || respObj.http_code || '00';
        const msg = respObj.message || 'Operation processed successfully';
        const isSuccess = (status === 'SUCCESS' || code === '00' || code === 200 || code === '200');
        const bannerBg = isSuccess ? 'bg-success-subtle text-success-emphasis border-success-subtle' : 'bg-danger-subtle text-danger-emphasis border-danger-subtle';
        const bannerIcon = isSuccess ? 'bi-check-circle-fill text-success' : 'bi-exclamation-triangle-fill text-danger';

        const banner = document.createElement('div');
        banner.className = `p-3 rounded-3 border mb-3 d-flex align-items-start gap-3 shadow-sm ${bannerBg}`;
        banner.innerHTML = `
            <i class="bi ${bannerIcon} fs-4 flex-shrink-0 mt-0.5"></i>
            <div class="flex-grow-1">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <strong class="fs-6">${status}</strong>
                        <span class="badge bg-dark font-mono text-white px-2 py-0.5" style="font-size: 0.7rem;">Code: ${code}</span>
                    </div>
                    ${respObj.transaction_id || (respObj.data && respObj.data.transaction_id) ? `
                        <div class="small font-mono text-muted d-flex align-items-center gap-1">
                            <span>Txn:</span>
                            <span class="fw-semibold text-dark">${respObj.transaction_id || respObj.data.transaction_id}</span>
                        </div>
                    ` : ''}
                </div>
                <div class="small mt-1">${msg}</div>
            </div>
        `;
        container.appendChild(banner);

        // 2. Extract verified data payload
        const innerData = respObj.data || respObj.result || null;
        const mainData = (innerData && typeof innerData === 'object' && innerData.data) ? innerData.data : (innerData || respObj);

        // Filter out redundant top-level control keys for the main details section
        const detailsObj = {};
        Object.entries(mainData).forEach(([k, v]) => {
            if (!['status', 'response_code', 'message', 'error', 'http_code'].includes(k)) {
                detailsObj[k] = v;
            }
        });

        // If detailsObj is empty but root has fields, show root fields
        const displayObj = Object.keys(detailsObj).length > 0 ? detailsObj : mainData;

        const sectionHeading = document.createElement('div');
        sectionHeading.className = 'd-flex align-items-center justify-content-between mb-2 mt-3';
        sectionHeading.innerHTML = `
            <span class="small fw-bold text-uppercase text-secondary" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                <i class="bi bi-shield-check text-primary me-1"></i>Verified Identity & Record Details
            </span>
            <span class="badge bg-white border text-muted small" style="font-size: 0.7rem;">
                ${Object.keys(displayObj).length} Attributes
            </span>
        `;
        container.appendChild(sectionHeading);

        const gridContainer = document.createElement('div');
        renderFriendlyGrid(gridContainer, displayObj);
        container.appendChild(gridContainer);
    }

    if (payloadModal) {
        payloadModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const service = button.getAttribute('data-service') || 'Service';
            const serviceName = button.getAttribute('data-service-name') || service;
            const status = button.getAttribute('data-status') || 'SUCCESS';
            const latency = button.getAttribute('data-latency') || '-';
            const tokens = button.getAttribute('data-tokens') || '0.00';
            const timestamp = button.getAttribute('data-time') || '-';
            const reqRaw = button.getAttribute('data-request') || '{}';
            const respRaw = button.getAttribute('data-response') || '{}';
            const err = button.getAttribute('data-error');

            // Header & Strip Meta
            document.getElementById('modalServiceBadge').innerText = service;
            document.getElementById('modalServiceName').innerText = serviceName;
            document.getElementById('modalLatency').innerText = latency;
            document.getElementById('modalTokens').innerText = (tokens > 0 ? '-' : '') + tokens + ' Tokens';
            document.getElementById('modalTimestamp').innerText = timestamp;

            const statusBadge = document.getElementById('modalStatusBadge');
            statusBadge.innerText = status;
            statusBadge.className = 'badge ' + (status === 'SUCCESS' ? 'bg-success' : (status === 'INSUFFICIENT_FUNDS' ? 'bg-warning text-dark' : 'bg-danger'));

            // Parse Payloads
            let reqObj = {};
            let respObj = {};
            try { reqObj = JSON.parse(reqRaw); } catch(e) { reqObj = { raw: reqRaw }; }
            try { respObj = JSON.parse(respRaw); } catch(e) { respObj = { raw: respRaw }; }

            // Raw Payloads Tab
            document.getElementById('modalRequestPayload').innerText = JSON.stringify(reqObj, null, 2);
            document.getElementById('modalResponsePayload').innerText = JSON.stringify(respObj, null, 2);

            // Error notice
            const errNotice = document.getElementById('modalErrorNotice');
            if (err) {
                errNotice.classList.remove('d-none');
                document.getElementById('modalErrorText').innerText = err;
            } else {
                errNotice.classList.add('d-none');
            }

            // Build Friendly Views
            buildFriendlyResponseView(document.getElementById('friendlyResponseContainer'), respObj);
            renderFriendlyGrid(document.getElementById('friendlyRequestContainer'), reqObj);

            // Switch back to Friendly tab on open
            const friendlyTabBtn = document.getElementById('friendly-tab');
            if (friendlyTabBtn && window.bootstrap) {
                const tabInstance = bootstrap.Tab.getOrCreateInstance(friendlyTabBtn);
                tabInstance.show();
            }
        });
    }

    // Attach click listeners for copy buttons inside dynamic grids
    document.addEventListener('click', function (e) {
        const copyBtn = e.target.closest('.copy-value-btn');
        if (copyBtn) {
            const val = copyBtn.getAttribute('data-value');
            if (val) {
                navigator.clipboard.writeText(val).then(() => {
                    const originalHtml = copyBtn.innerHTML;
                    copyBtn.innerHTML = '<i class="bi bi-check text-success"></i>';
                    setTimeout(() => { copyBtn.innerHTML = originalHtml; }, 1500);
                });
            }
        }
    });

    // Copy Raw Buttons in Tab 3
    const btnCopyRawResponse = document.getElementById('btnCopyRawResponse');
    if (btnCopyRawResponse) {
        btnCopyRawResponse.addEventListener('click', function () {
            const txt = document.getElementById('modalResponsePayload').innerText;
            navigator.clipboard.writeText(txt).then(() => {
                const orig = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check-lg text-success me-1"></i>Copied!';
                setTimeout(() => { this.innerHTML = orig; }, 2000);
            });
        });
    }

    const btnCopyRawRequest = document.getElementById('btnCopyRawRequest');
    if (btnCopyRawRequest) {
        btnCopyRawRequest.addEventListener('click', function () {
            const txt = document.getElementById('modalRequestPayload').innerText;
            navigator.clipboard.writeText(txt).then(() => {
                const orig = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check-lg text-success me-1"></i>Copied!';
                setTimeout(() => { this.innerHTML = orig; }, 2000);
            });
        });
    }
});
</script>
@endpush
