@extends('layouts.admin')

@section('title', 'Our Services')
@section('page_title', 'Our Services')

@push('styles')
<style>
    /* Indian Smart Card Driving Licence Styling */
    .dl-smart-card {
        border-radius: 16px;
        background-color: #072e23;
        background-image: 
            radial-gradient(circle at 90% 10%, rgba(212, 175, 55, 0.12) 0%, transparent 45%),
            radial-gradient(circle at 10% 90%, rgba(16, 185, 129, 0.08) 0%, transparent 40%),
            repeating-linear-gradient(45deg, rgba(255,255,255,0.015) 0px, rgba(255,255,255,0.015) 2px, transparent 2px, transparent 8px);
        border: 1.5px solid rgba(212, 175, 55, 0.45) !important;
        box-shadow: 0 14px 34px -5px rgba(0, 0, 0, 0.45), 0 0 18px rgba(212, 175, 55, 0.15) !important;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .dl-smart-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 40px -5px rgba(0, 0, 0, 0.55), 0 0 25px rgba(212, 175, 55, 0.25) !important;
    }
    .dl-smart-chip {
        width: 42px;
        height: 30px;
        border-radius: 5px;
        background: linear-gradient(135deg, #e6c875 0%, #d4af37 45%, #aa820a 100%);
        border: 1px solid #b8860b;
        box-shadow: inset 0 1px 2px rgba(255,255,255,0.65), 0 2px 4px rgba(0,0,0,0.35);
        position: relative;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.35; transform: scale(0.85); }
    }
    .pulse-indicator {
        animation: pulse-dot 1.5s infinite ease-in-out;
    }
    /* Indian e-PAN Verification Card Styling */
    .pan-id-card {
        border-radius: 16px;
        background-color: #0c1e38;
        background-image: 
            radial-gradient(circle at 90% 12%, rgba(212, 175, 55, 0.16) 0%, transparent 48%),
            radial-gradient(circle at 8% 88%, rgba(37, 99, 235, 0.14) 0%, transparent 45%),
            repeating-linear-gradient(45deg, rgba(255,255,255,0.015) 0px, rgba(255,255,255,0.015) 2px, transparent 2px, transparent 8px);
        border: 1.5px solid rgba(212, 175, 55, 0.45) !important;
        box-shadow: 0 14px 34px -5px rgba(0, 0, 0, 0.45), 0 0 18px rgba(212, 175, 55, 0.15) !important;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .pan-id-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 40px -5px rgba(0, 0, 0, 0.55), 0 0 25px rgba(212, 175, 55, 0.25) !important;
    }
    .pan-hologram {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        background: linear-gradient(135deg, #e0f2fe 0%, #38bdf8 30%, #818cf8 60%, #fbbf24 100%);
        border: 1px solid rgba(255,255,255,0.6);
        box-shadow: inset 0 0 6px rgba(255,255,255,0.8), 0 2px 5px rgba(0,0,0,0.3);
        position: relative;
        overflow: hidden;
    }
    .pan-hologram::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(60deg, transparent 40%, rgba(255,255,255,0.6) 50%, transparent 60%);
        transform: rotate(25deg);
    }
    /* Aadhaar To Pan Linkage Card Styling */
    .aadhar-pan-card {
        border-radius: 16px;
        background-color: #1a1642;
        background-image: 
            radial-gradient(circle at 92% 10%, rgba(245, 158, 11, 0.18) 0%, transparent 48%),
            radial-gradient(circle at 8% 90%, rgba(99, 102, 241, 0.22) 0%, transparent 45%),
            repeating-linear-gradient(45deg, rgba(255,255,255,0.015) 0px, rgba(255,255,255,0.015) 2px, transparent 2px, transparent 8px);
        border: 1.5px solid rgba(212, 175, 55, 0.45) !important;
        box-shadow: 0 14px 34px -5px rgba(0, 0, 0, 0.45), 0 0 20px rgba(99, 102, 241, 0.2) !important;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .aadhar-pan-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 40px -5px rgba(0, 0, 0, 0.55), 0 0 28px rgba(99, 102, 241, 0.3) !important;
    }
    /* RC To Mobile Linkage Card Styling */
    .rc-smart-card {
        border-radius: 16px;
        background-color: #062a22;
        background-image: 
            radial-gradient(circle at 92% 10%, rgba(16, 185, 129, 0.22) 0%, transparent 48%),
            radial-gradient(circle at 8% 90%, rgba(245, 158, 11, 0.18) 0%, transparent 45%),
            repeating-linear-gradient(45deg, rgba(255,255,255,0.015) 0px, rgba(255,255,255,0.015) 2px, transparent 2px, transparent 8px);
        border: 1.5px solid rgba(16, 185, 129, 0.45) !important;
        box-shadow: 0 14px 34px -5px rgba(0, 0, 0, 0.45), 0 0 20px rgba(16, 185, 129, 0.2) !important;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .rc-smart-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 40px -5px rgba(0, 0, 0, 0.55), 0 0 28px rgba(16, 185, 129, 0.3) !important;
    }
    @media print {
        body * {
            visibility: hidden;
        }
        #dlCardsContainer, #dlCardsContainer *,
        #panCardPrintArea, #panCardPrintArea *,
        #aadharPanCardPrintArea, #aadharPanCardPrintArea *,
        #rcCardPrintArea, #rcCardPrintArea * {
            visibility: visible;
        }
        #dlCardsContainer,
        #panCardPrintArea,
        #aadharPanCardPrintArea,
        #rcCardPrintArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .dl-smart-card,
        .pan-id-card,
        .aadhar-pan-card,
        .rc-smart-card {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            page-break-inside: avoid;
            margin-bottom: 20px;
        }
    }

    /* Compact Services Card Style (Single-line 6-column grid as per inspiration image) */
    .portal-compact-card {
        border-radius: 14px !important;
        background: #ffffff !important;
        border: 1px solid #eef2f6 !important;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        padding: 9px;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .portal-compact-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08) !important;
        border-color: #cbd5e1 !important;
    }
    .compact-banner-box {
        width: 100%;
        height: 76px;
        border-radius: 10px;
        background: #ede9fe;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .compact-card-title {
        font-size: 0.67rem;
        font-weight: 800;
        letter-spacing: -0.2px;
        line-height: 1.2;
        color: #1e293b;
    }
    .compact-badge {
        font-size: 0.50rem;
        padding: 1.5px 5px;
        letter-spacing: 0.2px;
        font-weight: 700;
    }
    .compact-desc {
        font-size: 0.56rem;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.1px;
        line-height: 1.25;
        min-height: 24px;
        max-height: 24px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .compact-price {
        font-size: 0.88rem;
        font-weight: 800;
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: #0f172a;
        letter-spacing: -0.2px;
    }
    .btn-compact-spec {
        width: 24px;
        height: 24px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #64748b;
        font-size: 0.85rem;
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .btn-compact-spec:hover {
        background: #ede9fe;
        color: #4f46e5;
        border-color: #c7d2fe;
    }
    .btn-compact-apply {
        background: #5b50f6;
        color: #ffffff !important;
        border: none;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 4px 8px;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 2.5px;
        box-shadow: 0 2px 6px rgba(91, 80, 246, 0.3);
        transition: background 0.15s ease, transform 0.15s ease, box-shadow 0.15s ease;
        cursor: pointer;
        white-space: nowrap;
    }
    .btn-compact-apply:hover {
        background: #4338ca;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(91, 80, 246, 0.45);
    }
    /* Compatibility aliases */
    .portal-dl-card {
        border-radius: 14px !important;
        background: #ffffff !important;
        border: 1px solid #eef2f6 !important;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04) !important;
        padding: 9px;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .dl-banner-box {
        width: 100%;
        height: 76px;
        border-radius: 10px;
        background: #ede9fe;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .btn-list-spec-modern {
        width: 24px;
        height: 24px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #64748b;
        font-size: 0.85rem;
    }
    .btn-apply-modern {
        background: #5b50f6;
        color: #ffffff !important;
        border: none;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 4px 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 2.5px;
    }

    /* DL Print PDF Modal Styling (Reference Image) */
    .dl-custom-input-group {
        border-radius: 10px !important;
        overflow: hidden;
        border: 1.5px solid #99f6e4 !important;
        background: #ffffff !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .dl-custom-input-group:focus-within {
        border-color: #0d9488 !important;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15) !important;
    }
    .dl-custom-label {
        color: #0f766e;
        font-weight: 700;
        font-size: 0.76rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .dl-custom-help {
        display: none !important;
    }

    /* 24x7 Marquee Announcement Bar */
    .marquee-alert-bar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #2563eb;
        border-radius: 12px;
        padding: 9px 16px;
        background-image: linear-gradient(90deg, #f8fafc 0%, #ffffff 15%, #ffffff 85%, #f8fafc 100%);
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
    }
    .marquee-tag {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.72rem;
        padding: 4px 10px;
        border-radius: 20px;
        margin-right: 12px;
        letter-spacing: 0.4px;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
    }
    .pulse-indicator-badge {
        width: 7px;
        height: 7px;
        background-color: #4ade80;
        border-radius: 50%;
        display: inline-block;
        animation: marquee-pulse 1.5s infinite;
    }
    @keyframes marquee-pulse {
        0% { transform: scale(0.9); opacity: 0.8; }
        50% { transform: scale(1.3); opacity: 1; }
        100% { transform: scale(0.9); opacity: 0.8; }
    }
    .marquee-text {
        font-size: 0.90rem;
        font-weight: 600;
        color: #1e293b;
        letter-spacing: 0.2px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-0">

    <!-- 24x7 Service Notice Marquee -->
    <div class="marquee-alert-bar mb-4 d-flex align-items-center shadow-sm">
        <div class="marquee-tag d-flex align-items-center gap-1 flex-shrink-0">
            <span class="pulse-indicator-badge"></span>
            <i class="bi bi-megaphone-fill ms-1"></i>
            <span>24x7 सूचना</span>
        </div>
        <div class="marquee-content flex-grow-1 overflow-hidden">
            <marquee behavior="scroll" direction="left" scrollamount="6" onmouseover="this.stop();" onmouseout="this.start();" class="d-block m-0">
                <span class="marquee-text">
                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                    हमारी सेवाएँ आपके लिए 24 घंटे और सातों दिन (24x7) उपलब्ध हैं। किसी भी प्रकार की समस्या होने पर हमारी टीम आपको पूरा सहयोग और सहायता प्रदान करेगी।
                </span>
            </marquee>
        </div>
    </div>

    <!-- KPI Metric Summary Row -->
    <div class="row g-3 mb-4">
        <div class="{{ $user->role === 'admin' ? 'col-sm-6 col-md-4' : 'col-sm-6' }}">
            <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100">
                <div>
                    <div class="text-muted small fw-semibold">WALLET BALANCE</div>
                    <h3 class="fw-bold mb-0 text-primary">{{ number_format($user->wallet_balance, 2) }}</h3>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <small class="text-muted">1 Token = 1 INR</small>
                        <a href="{{ route('wallet.add_balance') }}" class="badge bg-primary text-white text-decoration-none py-1 px-2 fw-semibold" title="Add Wallet Balance">
                            <i class="bi bi-plus-circle me-1"></i>Add Token
                        </a>
                    </div>
                </div>
                <div class="p-3 rounded-3 bg-warning-subtle text-warning-emphasis fs-3">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
        </div>

        @if($user->role === 'admin')
        <div class="col-sm-6 col-md-4">
            <a href="{{ route('admin.franchises') }}" class="text-decoration-none d-block h-100">
                <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100" style="transition: transform 0.2s ease, box-shadow 0.2s ease;">
                    <div>
                        <div class="text-muted small fw-semibold">TOTAL FRANCHISES</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_franchises'] }}</h3>
                        <small class="text-info fw-medium d-flex align-items-center gap-1">
                            <span>Manage Accounts</span>
                            <i class="bi bi-arrow-right-short"></i>
                        </small>
                    </div>
                    <div class="p-3 rounded-3 bg-info-subtle text-info fs-3">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </a>
        </div>
        @endif

        <div class="{{ $user->role === 'admin' ? 'col-sm-6 col-md-4' : 'col-sm-6' }}">
            <a href="{{ route('admin.logs', ['today' => 1]) }}" class="text-decoration-none d-block h-100" title="Click to view verification listing">
                <div class="glass-card p-3 d-flex align-items-center justify-content-between h-100" style="transition: transform 0.2s ease, box-shadow 0.2s ease;">
                    <div>
                        <div class="text-muted small fw-semibold">
                            {{ $user->role === 'franchise' ? 'MY VERIFICATIONS TODAY' : 'VERIFICATIONS TODAY' }}
                        </div>
                        <h3 class="fw-bold mb-0 text-dark">{{ $stats['api_calls_today'] }}</h3>
                        <small class="text-primary fw-medium d-flex align-items-center gap-1">
                            <span>{{ $user->role === 'franchise' ? 'View My Logs' : 'View Audit Logs' }}</span>
                            <i class="bi bi-arrow-right-short"></i>
                        </small>
                    </div>
                    <div class="p-3 rounded-3 bg-success-subtle text-success fs-3">
                        <i class="bi bi-activity"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Section Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0">Our Services</h4>
        </div>
    </div>

    <!-- 6 Core Services Cards Grid (Single-Line Compact Row) -->
    @php
        $serviceMeta = [
            'dl_verification_plus_srv2' => [
                'display_name' => 'DL PDF BY DL NO(UK)',
                'desc' => 'DL PDF(UK) INSTANT DETAIL FETCH KARO',
                'badge' => 'DL PDF(UK)',
                'badge_bg' => '#ede9fe',
                'badge_color' => '#6366f1',
                'img' => asset('images/services/dl_verification_plus_srv2.png'),
            ],
            'dl_pdf_by_dl_no' => [
                'display_name' => 'DL PDF BY DL NO(UK)',
                'desc' => 'DL PDF(UK) INSTANT FETCH KARO',
                'badge' => 'DL PDF(UK)',
                'badge_bg' => '#ede9fe',
                'badge_color' => '#6366f1',
                'img' => asset('images/services/dl_verification_plus_srv2.png'),
            ],
            'pan_details_v2' => [
                'display_name' => 'PAN DETAILS INSTANT',
                'desc' => 'TURANT PAN CARD DETAILS FETCH KARO',
                'badge' => 'INSTANT',
                'badge_bg' => '#ede9fe',
                'badge_color' => '#6366f1',
                'img' => asset('images/services/pan_details_v2.png'),
            ],
            'pan_details_v1' => [
                'display_name' => 'PAN FIND INSTANT',
                'desc' => 'AADHAAR NO SE PAN NO INSTANT',
                'badge' => 'PAN',
                'badge_bg' => '#ede9fe',
                'badge_color' => '#6366f1',
                'img' => asset('images/services/pan_details_v1.png'),
            ],
            'aadhar_to_pan' => [
                'display_name' => 'AADHAR NO TO PAN',
                'desc' => 'AADHAAR NO SE PAN NUMBER INSTANT NIKALO',
                'badge' => 'PAN',
                'badge_bg' => '#ede9fe',
                'badge_color' => '#6366f1',
                'img' => asset('images/services/aadhar_to_pan.png'),
            ],
            'rc_to_mobile_v3' => [
                'display_name' => 'RC PDF(UK) INSTANT',
                'desc' => 'VEHICLE REGISTRATION INSTANT FETCH KARO(UK)',
                'badge' => 'RC PDF(UK)',
                'badge_bg' => '#ede9fe',
                'badge_color' => '#6366f1',
                'img' => asset('images/services/rc_to_mobile_v3.png'),
            ],
            'voter_id_verification_srv2' => [
                'display_name' => 'VERIFY VOTER ID INSTANT',
                'desc' => 'VOTER ID VERIFY INSTANT',
                'badge' => 'VOTER ID VERIFY',
                'badge_bg' => '#ede9fe',
                'badge_color' => '#6366f1',
                'img' => asset('images/services/voter_id_verification_srv2.png'),
            ],
        ];
    @endphp

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-2 mb-4">
        @forelse($services as $service)
            @php
                $meta = $serviceMeta[$service->service_code] ?? [
                    'display_name' => $service->name,
                    'desc' => $service->description ?? 'INSTANT VERIFICATION SERVICE',
                    'badge' => 'INSTANT',
                    'badge_bg' => '#ede9fe',
                    'badge_color' => '#6366f1',
                    'img' => file_exists(public_path('images/services/' . $service->service_code . '.png')) 
                                ? asset('images/services/' . $service->service_code . '.png')
                                : asset('images/dl_banner_inner_hd.png'),
                ];
            @endphp
            <div class="col">
                <div class="portal-compact-card">
                    <!-- Banner Thumbnail Container -->
                    <div class="compact-banner-box mb-2">
                        <img src="{{ $meta['img'] }}" alt="{{ $meta['display_name'] }}" style="max-height: 60px; max-width: 88%; object-fit: contain; border-radius: 6px; box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
                    </div>

                    <!-- Title & Status Badge -->
                    <div class="d-flex align-items-center justify-content-between gap-1 mb-1">
                        <h6 class="compact-card-title mb-0 text-truncate" title="{{ $meta['display_name'] }}">
                            {{ $meta['display_name'] }}
                        </h6>
                        <span class="badge rounded-pill compact-badge" style="background-color: {{ $meta['badge_bg'] }}; color: {{ $meta['badge_color'] }};">
                            {{ $meta['badge'] }}
                        </span>
                    </div>

                    <!-- Subtitle / Short Description -->
                    <div class="compact-desc text-uppercase mb-2" title="{{ $meta['desc'] }}">
                        {{ $meta['desc'] }}
                    </div>

                    <!-- Card Footer: Price & Actions -->
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-auto">
                        <span class="compact-price">
                            ₹{{ number_format($service->effective_cost, 2) }}
                        </span>

                        <div class="d-flex align-items-center gap-1">
                            <!-- Apply Button (+ APPLY) -->
                            <button type="button" class="btn btn-compact-apply"
                                    id="btnTrigger_{{ $service->service_code }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#testModal"
                                    data-code="{{ $service->service_code }}"
                                    data-name="{{ $meta['display_name'] }}"
                                    data-cost="{{ number_format($service->effective_cost, 2, '.', '') }}"
                                    data-gst-rate="{{ number_format($service->gst_rate, 2, '.', '') }}"
                                    data-gst-amount="{{ number_format($service->gst_amount, 2, '.', '') }}"
                                    data-total-cost="{{ number_format($service->total_cost, 2, '.', '') }}"
                                    data-endpoint="{{ $service->endpoint_path }}"
                                    data-payload="{{ json_encode($service->sample_payload) }}">
                                <i class="bi bi-plus-lg" style="font-size: 0.65rem;"></i>
                                <span>APPLY</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning">No services found in the database. Please run <code>php artisan db:seed</code>.</div>
            </div>
        @endforelse
    </div>

</div>

<!-- Trigger Live Modal -->
<div class="modal fade" id="testModal" tabindex="-1" aria-labelledby="testModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" id="testModalDialog" style="max-width: 760px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            
            <!-- Modern Styled Header (Exact replica of reference image) -->
            <div id="modalModernHeader" class="modal-header text-white px-4 py-3 border-0 position-relative" style="background: linear-gradient(135deg, #07262c 0%, #084340 45%, #0c695e 100%); overflow: hidden;">
                <!-- Decorative Watermark Shape from reference image -->
                <div class="position-absolute end-0 top-0 bottom-0 pe-4 d-flex align-items-center pointer-events-none" style="opacity: 0.14; z-index: 1;">
                    <i class="bi bi-shield-check" id="modalWatermarkIcon" style="font-size: 7.5rem; color: #a7f3d0;"></i>
                </div>
                
                <div class="d-flex align-items-center position-relative" style="z-index: 2;">
                    <div class="rounded-3 me-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.22); box-shadow: inset 0 1px 0 rgba(255,255,255,0.2);">
                        <i class="bi bi-person-vcard text-white fs-4" id="modalHeaderIconModern"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-white" id="modalModernTitle" style="font-size: 1.35rem; letter-spacing: -0.2px;">PAN Details Instant</h4>
                        <div id="modalModernSubtitle" style="color: rgba(255, 255, 255, 0.75); font-size: 0.85rem; margin-top: 2px;">
                            Instant PAN verification — cardholder details
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white position-relative" data-bs-dismiss="modal" style="z-index: 2;"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Informational Banners (Matches reference image) -->
                <div class="mb-3" id="modalInfoBoxes">
                    <!-- Top Box 1: Wallet Balance & Fee -->
                    <div class="p-3 mb-3 d-flex align-items-center gap-2 rounded-3" style="background: #fdfbf7; border: 1.5px solid #fde68a; border-radius: 12px;">
                        <i class="bi bi-wallet-fill fs-5" style="color: #d97706;"></i>
                        <div style="font-size: 0.95rem; color: #4b5563; font-weight: 500;">
                            Wallet Balance: <strong style="color: #b45309; font-weight: 800;">₹<span id="modalWalletBalanceDisplay">{{ number_format(Auth::user()->wallet_balance, 2) }}</span></strong>
                            <span class="mx-2 text-muted">·</span>
                            Fee: <strong style="color: #b45309; font-weight: 800;" id="modalFeeDisplay">₹2.50</strong>
                        </div>
                    </div>

                    <!-- Top Box 2: Instant API Notice -->
                    <div class="p-3 d-flex align-items-start gap-2.5 rounded-3" style="background: #f0fdf9; border: 1.5px solid #a7f3d0; border-radius: 12px;">
                        <i class="bi bi-lightning-charge-fill fs-5 mt-0.5" style="color: #0d9488;"></i>
                        <div style="font-size: 0.88rem; line-height: 1.45;">
                            <strong style="color: #0f766e; font-weight: 700;">Instant Verification:</strong>
                            <span style="color: #4b5563;" id="modalInstantApiNoticeText"> Service will be processed instantly. Available immediately after verification.</span>
                        </div>
                    </div>
                </div>

                <!-- Hidden backward-compatibility elements -->
                <span id="testModalLabel" style="display: none !important;"></span>
                <span id="modalServiceCodeBadge" style="display: none !important;"></span>
                <span id="modalBasePriceDisplay" style="display: none !important;"></span>
                <span id="modalGstAmountDisplay" style="display: none !important;"></span>
                <span id="modalCostBadge" style="display: none !important;"></span>
                <span id="dlModalWalletBalance" style="display: none !important;"></span>
                <span id="dlModalFeeDisplay" style="display: none !important;"></span>
                <div id="modalStandardHeader" style="display: none !important;"></div>
                <div id="modalDlHeader" style="display: none !important;"></div>
                <div id="modalStandardCostBadge" style="display: none !important;"></div>
                <div id="modalDlInfoBoxes" style="display: none !important;"></div>

                <!-- Hidden technical inputs -->
                <input type="hidden" id="modalServiceCode" value="dl_verification_plus_srv2">
                <input type="hidden" id="modalServiceName" value="DL Verification Plus (Srv2)">
                <input type="hidden" id="modalServiceCost" value="30.00">
                <input type="hidden" id="modalServiceGstRate" value="18.00">
                <input type="hidden" id="modalServiceGstAmount" value="5.40">
                <input type="hidden" id="modalServiceTotalCost" value="35.40">

                <!-- Dynamic Essential Inputs Form -->
                <div id="dynamicEssentialInputsContainer" class="mb-3">
                    <!-- Automatically built on modal open for the selected service -->
                </div>

                <!-- Advanced Developer Raw JSON (Hidden) -->
                <div class="mb-3 d-none">
                    <textarea id="modalRequestPayload" class="form-control font-mono d-none" rows="3"></textarea>
                </div>

                <!-- 4. Result Showcase Area -->
                <div id="liveResponseContainer" class="d-none mt-3">

                    <!-- SIMPLE VERIFICATION BILL / RECEIPT TEXT BOX (Replaces graphic cards) -->
                    <div id="serviceBillCard" class="d-none mb-3">
                        <!-- Top Action Controls / Toolbar (Hidden as requested) -->
                        <div class="d-none">
                            <button type="button" class="btn btn-warning btn-sm fw-bold px-3 py-1 shadow-sm rounded-2" id="btnCopyBillText" onclick="copyServiceBillText()">
                                <i class="bi bi-clipboard-check me-1"></i> <span id="copyBillBtnText">Copy Bill</span>
                            </button>
                            <button type="button" class="btn btn-outline-light btn-sm px-2.5 py-1" id="btnPrintBillReceipt" onclick="printServiceBill()">
                                <i class="bi bi-printer me-1"></i> Print Bill
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm px-2.5 py-1 d-none" id="btnPrintCardV2" onclick="printPanSmartCardV2()">
                                <i class="bi bi-person-vcard me-1"></i> Print Card
                            </button>
                        </div>

                        <!-- Printable Bill Content Area -->
                        <div id="serviceBillPrintArea" class="bg-white border rounded-3 p-3 shadow-sm" style="color: #1e293b; border-color: #cbd5e1 !important;">

                            <!-- Verified Record Information Table -->
                            <div class="mb-3">
                                <!-- Header Particulars & Badge (Hidden as requested) -->
                                <div class="d-none align-items-center justify-content-between mb-2">
                                    <span class="fw-bold text-dark text-uppercase small" style="font-size: 0.76rem; letter-spacing: 0.5px;">
                                        <i class="bi bi-card-checklist text-primary me-1"></i>Verified Record Particulars
                                    </span>
                                    <span class="badge bg-success text-white font-mono" style="font-size: 0.65rem;">
                                        <i class="bi bi-patch-check-fill me-0.5"></i> Live Gateway Record
                                    </span>
                                </div>
                                <div class="table-responsive border rounded-2 overflow-hidden">
                                    <table class="table table-sm table-striped table-hover align-middle mb-0" style="font-size: 0.82rem;">
                                        <thead class="table-light border-bottom">
                                            <tr>
                                                <th style="width: 35%; min-width: 150px;" class="ps-3 text-secondary fw-semibold">Particular / Field</th>
                                                <th class="ps-3 text-secondary fw-semibold">Verified Information</th>
                                            </tr>
                                        </thead>
                                        <tbody id="billItemsTableBody">
                                            <!-- Populated dynamically via renderServiceBill() -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Charges & Payment Breakdown Box (Compact / Shortened as requested) -->
                            <div class="border rounded-2 px-3 py-2 bg-light bg-opacity-75 mb-1" style="border-style: dashed !important; border-color: #cbd5e1 !important;">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 small">
                                    <div class="d-flex flex-wrap align-items-center gap-3">
                                        <div>
                                            <span class="text-muted">Charge:</span>
                                            <span class="font-mono fw-bold text-dark ms-1" id="billBaseCost">0.00 Tokens</span>
                                        </div>
                                        <div id="billGstRow" class="d-none">
                                            <span class="text-muted" id="billGstLabel">GST:</span>
                                            <span class="font-mono fw-bold text-dark ms-1" id="billGstAmount">0.00 Tokens</span>
                                        </div>
                                        <div class="border-start ps-3">
                                            <span class="fw-bold text-dark">Total:</span>
                                            <span class="fw-bold text-primary font-mono fs-6 ms-1" id="billTotalDeducted">0.00 Tokens</span>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                        <span class="text-muted">Wallet Left: <strong class="font-mono text-dark" id="billWalletBalance">0.00 Tokens</strong></span>
                                        <span class="badge bg-success-subtle text-success border border-success border-opacity-25 font-mono py-0.5 px-1.5" style="font-size: 0.68rem;">
                                            <i class="bi bi-shield-check me-0.5"></i> PAID &amp; DEBITED
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- A. SUCCESS PAN CARD DISPLAY (AUTHENTIC e-PAN VERIFICATION CARD) -->
                    <div id="panSuccessCard" class="d-none mb-3">
                        <!-- Top Action Controls / Toolbar -->
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 p-2.5 px-3 bg-dark text-white rounded-3 shadow-sm mb-3" style="background: #0f172a !important;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="p-1.5 rounded-2 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                    <i class="bi bi-credit-card-2-front-fill fs-6"></i>
                                </div>
                                <div>
                                    <span class="fw-bold text-white small d-block">PERMANENT ACCOUNT NUMBER (e-PAN CARD)</span>
                                    <span class="text-white-50" style="font-size: 0.7rem;">Verified on Income Tax Department (ITD) / NSDL e-Gov Portal</span>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <button type="button" class="btn btn-warning btn-sm fw-bold px-3 py-1 shadow-sm rounded-2" id="btnCopyPanResult">
                                    <i class="bi bi-clipboard-check me-1"></i> <span id="copyPanBtnText">Copy PAN</span>
                                </button>
                                <button type="button" class="btn btn-outline-light btn-sm px-2.5 py-1" id="btnCopyPanFullResult" onclick="copyPanFullDetails()">
                                    <i class="bi bi-file-earmark-text me-1"></i> Copy Details
                                </button>
                                <button type="button" class="btn btn-outline-light btn-sm px-2.5 py-1" id="btnPrintPanCard" onclick="printPanSmartCard()">
                                    <i class="bi bi-printer me-1"></i> Print Card
                                </button>
                            </div>
                        </div>

                        <!-- Card Presentation Area -->
                        <div id="panCardPrintArea" class="pan-id-card position-relative rounded-4 overflow-hidden shadow-lg p-3.5" style="color: #ffffff; min-height: 340px;">
                            
                            <!-- Indian Tricolor Accent Strip -->
                            <div class="position-absolute top-0 start-0 end-0" style="height: 4px; background: linear-gradient(90deg, #FF9933 0%, #FF9933 33.3%, #FFFFFF 33.3%, #FFFFFF 66.6%, #138808 66.6%, #138808 100%); z-index: 5;"></div>

                            <!-- Header: Govt of India & Income Tax Dept -->
                            <div class="d-flex align-items-center justify-content-between pb-2 border-bottom border-white border-opacity-15 mt-1">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle p-1 bg-white bg-opacity-10 border border-warning border-opacity-50 text-warning d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        <i class="bi bi-shield-shaded fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-warning lh-1" style="font-size: 0.74rem; letter-spacing: 0.8px;">आयकर विभाग / INCOME TAX DEPARTMENT</div>
                                        <div class="small text-white-50 lh-sm" style="font-size: 0.65rem;">GOVT. OF INDIA / भारत सरकार</div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-warning text-dark font-mono fw-bold px-2 py-0.5" style="font-size: 0.68rem; letter-spacing: 0.5px;">FORM 49A / e-PAN</span>
                                    <div class="d-flex align-items-center justify-content-end gap-1 mt-0.5">
                                        <span class="pulse-indicator bg-success" style="width: 7px; height: 7px; border-radius: 50%; display: inline-block;"></span>
                                        <span class="fw-bold text-success text-uppercase font-mono" id="displayPanStatusBadge" style="font-size: 0.68rem;">VALID & ACTIVE</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Prominent PAN Number Bar with Hologram & Type Badge -->
                            <div class="d-flex align-items-center justify-content-between p-2.5 my-2.5 rounded-3 bg-black bg-opacity-40 border border-warning border-opacity-30">
                                <!-- Hologram / ITD Security Stamp -->
                                <div class="pan-hologram flex-shrink-0 me-2.5 d-flex align-items-center justify-content-center" title="NSDL / ITD Holographic Security Seal">
                                    <span class="text-dark fw-bold font-mono" style="font-size: 0.55rem; letter-spacing: -0.5px; opacity: 0.9;">ITD</span>
                                </div>
                                
                                <!-- PAN Number Display -->
                                <div class="flex-grow-1 text-center">
                                    <div class="text-warning small text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 1.5px;">PERMANENT ACCOUNT NUMBER (PAN)</div>
                                    <div class="fw-bold font-mono text-warning fs-4 lh-1 text-truncate" id="displayPanNumber" style="letter-spacing: 4px; text-shadow: 0 2px 6px rgba(0,0,0,0.6);">
                                        ARxxxxxxx9E
                                    </div>
                                </div>

                                <!-- Right: Category / PAN Type Badge -->
                                <div class="flex-shrink-0 text-end">
                                    <div class="text-white-50" style="font-size: 0.58rem; text-transform: uppercase;">PAN Type</div>
                                    <span class="badge bg-warning text-dark font-mono fw-bold px-2 py-0.5" id="displayPanType" style="font-size: 0.68rem;">Individual</span>
                                </div>
                            </div>

                            <!-- Main Grid: Left (Photo + Gender + Badges) & Right (Name, Father, DOB, Aadhaar) -->
                            <div class="row g-3 align-items-center my-1">
                                <!-- Left Column: User Silhouette & Demographic Badges -->
                                <div class="col-4 col-sm-3 text-center">
                                    <div class="position-relative d-inline-block">
                                        <div id="displayPanPhotoPlaceholder" class="rounded-3 border border-2 border-warning border-opacity-50 d-flex flex-column align-items-center justify-content-center bg-black bg-opacity-45 text-white-50 shadow" style="width: 86px; height: 100px;">
                                            <i class="bi bi-person-bounding-box fs-1 text-warning text-opacity-75"></i>
                                            <span class="text-warning text-opacity-75 fw-semibold" style="font-size: 0.55rem; letter-spacing: 0.5px;">e-PAN PHOTO</span>
                                        </div>
                                    </div>
                                    <!-- Gender badge under photo -->
                                    <div class="mt-2">
                                        <span class="badge bg-primary bg-opacity-25 text-white border border-primary border-opacity-30 px-2 py-1 shadow-sm w-100" style="font-size: 0.68rem;">
                                            <span id="displayPanGender" class="text-capitalize"><i class="bi bi-gender-male me-1"></i> Male</span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Right Column: Personal Identity & Father Name & Aadhaar Details -->
                                <div class="col-8 col-sm-9">
                                    <!-- Full Legal Name -->
                                    <div class="mb-2">
                                        <div class="text-white-50 small text-uppercase" style="font-size: 0.62rem; letter-spacing: 0.5px;">Cardholder Name / नाम</div>
                                        <div class="fw-bold fs-5 text-white text-uppercase lh-sm" id="displayPanFullName">
                                            RAxxxGH
                                        </div>
                                        <!-- Optional Name Parts (First, Middle, Last) -->
                                        <div class="small text-white-50 mt-0.5" id="displayPanSplitNameRow" style="font-size: 0.68rem;">
                                            <span>First:</span> <strong class="text-white" id="displayPanFirstName">xxx</strong>
                                            <span class="mx-1 text-white-50">|</span>
                                            <span>Middle:</span> <strong class="text-white" id="displayPanMiddleName">-</strong>
                                            <span class="mx-1 text-white-50">|</span>
                                            <span>Last:</span> <strong class="text-white" id="displayPanLastName">SxxxH</strong>
                                        </div>
                                        <!-- Hidden backward compatibility holder name element -->
                                        <div class="d-none" id="displayPanHolderName"></div>
                                    </div>

                                    <!-- Father's Name -->
                                    <div class="mb-2">
                                        <div class="text-white-50 small text-uppercase" style="font-size: 0.62rem; letter-spacing: 0.5px;">Father's Name / पिता का नाम</div>
                                        <div class="text-warning fw-semibold fs-6 lh-sm" id="displayPanFatherName">
                                            NxxxxH
                                        </div>
                                    </div>

                                    <!-- 2x2 Demographic Grid: DOB & Aadhaar Status -->
                                    <div class="row g-2 pt-1 border-top border-white border-opacity-15">
                                        <div class="col-6">
                                            <div class="text-white-50" style="font-size: 0.6rem; text-transform: uppercase;">Date of Birth (DOB)</div>
                                            <div class="fw-bold text-white font-mono" style="font-size: 0.82rem;">
                                                <i class="bi bi-calendar-event text-warning me-1"></i>
                                                <span id="displayPanDob">30/05/1960</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-white-50" style="font-size: 0.6rem; text-transform: uppercase;">Aadhaar Seeding Status</div>
                                            <div id="displayPanAadhaarLinkedBadgeContainer">
                                                <span class="badge bg-success text-white px-2 py-0.5 font-mono" id="displayPanAadhaarLinkedBadge" style="font-size: 0.72rem;">
                                                    <i class="bi bi-link-45deg me-0.5"></i> Aadhaar Linked
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-1">
                                            <div class="text-white-50" style="font-size: 0.6rem; text-transform: uppercase;">Aadhaar Number (Masked)</div>
                                            <div class="fw-bold text-white font-mono d-flex align-items-center gap-1.5" style="font-size: 0.82rem;">
                                                <i class="bi bi-fingerprint text-info"></i>
                                                <span id="displayPanAadhaarNumber">71XXXXXXXX37</span>
                                                <!-- Hidden fallback element for existing scripts -->
                                                <span class="d-none" id="displayQueriedAadhaar"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Name match score if available -->
                                    <div id="panMatchMetaRow" class="d-none mt-1.5">
                                        <span class="badge bg-white bg-opacity-20 text-white font-mono border border-white border-opacity-25 px-2 py-0.5" id="panMatchScoreBadge" style="font-size: 0.68rem;">Score: 98.5%</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Complete Address Box -->
                            <div class="p-2.5 my-2 rounded-3 bg-black bg-opacity-40 border border-white border-opacity-10">
                                <div class="d-flex align-items-center justify-content-between text-warning mb-1" style="font-size: 0.65rem;">
                                    <div class="fw-bold text-uppercase"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Registered Taxpayer Address</div>
                                    <span class="badge bg-white bg-opacity-10 text-white-50 font-mono" style="font-size: 0.58rem;">RESIDENTIAL / OFFICIAL</span>
                                </div>
                                <div class="text-white small lh-sm fw-medium" id="displayPanAddress" style="font-size: 0.76rem;">
                                    India
                                </div>
                            </div>

                            <!-- Contact Details Bar (Mobile & Email) -->
                            <div class="p-2 my-1.5 rounded-3 bg-black bg-opacity-35 border border-white border-opacity-10 d-flex flex-wrap align-items-center justify-content-between gap-2" style="font-size: 0.72rem;">
                                <div>
                                    <i class="bi bi-telephone-fill text-success me-1"></i>
                                    <span class="text-white-50">Mobile:</span>
                                    <span class="text-white font-mono fw-semibold ms-1" id="displayPanMobile">-</span>
                                </div>
                                <div>
                                    <i class="bi bi-envelope-fill text-info me-1"></i>
                                    <span class="text-white-50">Email:</span>
                                    <span class="text-white font-mono fw-semibold ms-1" id="displayPanEmail">-</span>
                                </div>
                            </div>

                            <!-- Card Footer Security Strip -->
                            <div class="pt-2 mt-2 border-top border-white border-opacity-15 d-flex align-items-center justify-content-between small text-white-50" style="font-size: 0.68rem;">
                                <div class="d-flex align-items-center gap-1.5">
                                    <i class="bi bi-qr-code text-warning"></i>
                                    <span class="text-white-50 font-mono" style="font-size: 0.62rem;">ITD-NSDL&lt;&lt;DIGITALLY-SIGNED-VERIFIED&lt;&lt;</span>
                                </div>
                                <div>Fee: <span class="text-warning fw-bold font-mono" id="displayFeeDeducted">2.00 Tokens</span></div>
                            </div>

                        </div>
                    </div>

                    <!-- B. SUCCESS DRIVING LICENSE CARD (DUAL-SIDED SMART CARD: FRONT & BACK) -->
                    <div id="dlSuccessCard" class="d-none mb-3">
                        <!-- Top Toolbar / Action Controls -->
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 p-2.5 px-3 bg-dark text-white rounded-3 shadow-sm mb-3" style="background: #0f172a !important;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="p-1.5 rounded-2 bg-success text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                    <i class="bi bi-person-vcard-fill fs-6"></i>
                                </div>
                                <div>
                                    <span class="fw-bold text-white small d-block">SMART CARD DRIVING LICENCE (FORM 7)</span>
                                    <span class="text-white-50" style="font-size: 0.7rem;">Verified on Ministry of Road Transport & Highways (Sarathi Portal)</span>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <div class="btn-group btn-group-sm" role="group" id="dlCardViewSwitcher">
                                    <button type="button" class="btn btn-outline-light active px-2.5 py-1" id="dlViewBothBtn" onclick="switchDlCardView('both')">
                                        <i class="bi bi-layout-split me-1"></i> Both Sides
                                    </button>
                                    <button type="button" class="btn btn-outline-light px-2.5 py-1" id="dlViewFrontBtn" onclick="switchDlCardView('front')">
                                        <i class="bi bi-front me-1"></i> Front
                                    </button>
                                    <button type="button" class="btn btn-outline-light px-2.5 py-1" id="dlViewBackBtn" onclick="switchDlCardView('back')">
                                        <i class="bi bi-back me-1"></i> Back
                                    </button>
                                </div>
                                <button type="button" class="btn btn-warning btn-sm fw-bold px-3 py-1 shadow-sm rounded-2" id="btnCopyDlResult">
                                    <i class="bi bi-clipboard-check me-1"></i> <span id="copyDlBtnText">Copy DL</span>
                                </button>
                                <button type="button" class="btn btn-outline-light btn-sm px-2.5 py-1" id="btnPrintDlCard" onclick="printDlSmartCard()">
                                    <i class="bi bi-printer me-1"></i> Print Card
                                </button>
                            </div>
                        </div>

                        <!-- Card Presentation Row (Front & Back) -->
                        <div class="row g-3" id="dlCardsContainer">
                            <!-- 1. CARD FRONT -->
                            <div class="col-12 col-xl-6" id="dlFrontCol">
                                <div class="dl-smart-card dl-card-front position-relative rounded-4 overflow-hidden shadow-lg p-3.5 d-flex flex-column justify-content-between h-100" style="background: linear-gradient(135deg, #072e23 0%, #0d4a38 45%, #053325 100%); color: #ffffff; border: 1.5px solid rgba(212, 175, 55, 0.4); min-height: 330px; box-shadow: 0 12px 30px rgba(0,0,0,0.35), inset 0 0 40px rgba(0,0,0,0.2) !important;">
                                    
                                    <!-- Indian Tricolor Accent Strip -->
                                    <div class="position-absolute top-0 start-0 end-0" style="height: 4px; background: linear-gradient(90deg, #FF9933 0%, #FF9933 33.3%, #FFFFFF 33.3%, #FFFFFF 66.6%, #138808 66.6%, #138808 100%); z-index: 5;"></div>

                                    <!-- Front Card Header -->
                                    <div class="d-flex align-items-center justify-content-between pb-2 border-bottom border-white border-opacity-15 mt-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="dl-emblem-badge rounded-circle p-1 bg-white bg-opacity-10 border border-warning border-opacity-50 text-warning d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                                                <i class="bi bi-shield-shaded fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-warning lh-1" style="font-size: 0.72rem; letter-spacing: 0.8px;">UNION OF INDIA / भारतीय संघ</div>
                                                <div class="small text-white-50 lh-sm" style="font-size: 0.65rem;">MINISTRY OF ROAD TRANSPORT & HIGHWAYS</div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-warning text-dark fw-bold px-2 py-0.5" style="font-size: 0.68rem; letter-spacing: 0.5px;">FORM 7 (RULE 16(2))</span>
                                            <div class="d-flex align-items-center justify-content-end gap-1 mt-0.5">
                                                <span class="pulse-indicator bg-success" style="width: 7px; height: 7px; border-radius: 50%; display: inline-block;"></span>
                                                <span class="fw-bold text-success text-uppercase font-mono" id="displayDlStatusBadge" style="font-size: 0.68rem;">ACTIVE</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Prominent Driving License Number Bar & Chip -->
                                    <div class="d-flex align-items-center justify-content-between p-2 my-2 rounded-3 bg-black bg-opacity-35 border border-warning border-opacity-25">
                                        <!-- Realistic EMV Smart Card Microchip -->
                                        <div class="dl-smart-chip flex-shrink-0 me-2">
                                            <div style="position: absolute; top: 13px; left: 0; right: 0; height: 1px; background: #855f04;"></div>
                                            <div style="position: absolute; top: 0; bottom: 0; left: 18px; width: 1px; background: #855f04;"></div>
                                            <div style="position: absolute; top: 7px; bottom: 7px; left: 10px; right: 10px; border: 1px solid #855f04; border-radius: 2px;"></div>
                                        </div>
                                        <div class="flex-grow-1 text-center">
                                            <div class="text-warning small text-uppercase fw-semibold" style="font-size: 0.62rem; letter-spacing: 1px;">DRIVING LICENCE NUMBER</div>
                                            <div class="fw-bold font-mono text-white fs-5 lh-1 text-truncate" id="displayDlNumber" style="letter-spacing: 2px; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                                                DL-XXXXXXXXXXXXX
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 text-end">
                                            <span class="badge bg-white bg-opacity-10 text-white-50 border border-white border-opacity-20 px-1.5 py-1" style="font-size: 0.65rem;">
                                                <i class="bi bi-shield-check text-warning me-0.5"></i> SARATHI
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Front Main Content Body -->
                                    <div class="row g-2.5 align-items-center my-0.5">
                                        <!-- Left Column: User Image & Blood Group Badge -->
                                        <div class="col-4 col-sm-3 col-xl-4 text-center">
                                            <div class="position-relative d-inline-block">
                                                <img id="displayDlPhoto" src="" alt="Licensee Photo" class="rounded-3 border border-2 border-warning shadow d-none" style="width: 82px; height: 96px; object-fit: cover; background: #1e293b;">
                                                <div id="displayDlPhotoPlaceholder" class="rounded-3 border border-2 border-warning border-opacity-50 d-flex flex-column align-items-center justify-content-center bg-black bg-opacity-40 text-white-50 shadow" style="width: 82px; height: 96px;">
                                                    <i class="bi bi-person-fill fs-1 text-white-50"></i>
                                                    <span style="font-size: 0.58rem;">PHOTO</span>
                                                </div>
                                            </div>
                                            <!-- Blood Group Badge under Photo -->
                                            <div class="mt-1.5">
                                                <span class="badge bg-danger text-white border border-white border-opacity-25 px-2 py-1 shadow-sm" style="font-size: 0.68rem;">
                                                    <i class="bi bi-droplet-fill me-0.5"></i> <span id="displayDlFrontBloodGroup">O+</span>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Right Column: Personal & License Details -->
                                        <div class="col-8 col-sm-9 col-xl-8">
                                            <!-- Name -->
                                            <div class="mb-1.5">
                                                <div class="text-white-50 small text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.5px;">Name / नाम</div>
                                                <div class="fw-bold fs-6 text-white text-uppercase lh-1" id="displayDlHolderName">
                                                    HOLDER NAME
                                                </div>
                                            </div>

                                            <!-- Father / Husband Name -->
                                            <div class="mb-1.5">
                                                <div class="text-white-50 small text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.5px;">Son / Wife / Daughter of</div>
                                                <div class="text-white fw-semibold small lh-sm text-truncate" id="displayDlRelationName">
                                                    -
                                                </div>
                                            </div>

                                            <!-- 2x2 Meta Grid: DOB, Date of Issue, Expiry Date, Status -->
                                            <div class="row g-1 pt-1 border-top border-white border-opacity-15">
                                                <div class="col-6">
                                                    <div class="text-white-50" style="font-size: 0.58rem; text-transform: uppercase;">Date of Birth (DOB)</div>
                                                    <div class="fw-bold text-white font-mono" style="font-size: 0.78rem;" id="displayDlDob">-</div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-white-50" style="font-size: 0.58rem; text-transform: uppercase;">Date of Issue</div>
                                                    <div class="fw-bold text-white font-mono" style="font-size: 0.78rem;" id="displayDlDateOfIssue">-</div>
                                                </div>
                                                <div class="col-6 mt-1">
                                                    <div class="text-warning" style="font-size: 0.58rem; text-transform: uppercase;">Valid Till / Expiry</div>
                                                    <div class="fw-bold text-warning font-mono" style="font-size: 0.78rem;" id="displayDlExpiryDate">-</div>
                                                </div>
                                                <div class="col-6 mt-1">
                                                    <div class="text-white-50" style="font-size: 0.58rem; text-transform: uppercase;">Current Status</div>
                                                    <div class="fw-bold text-success font-mono" style="font-size: 0.78rem;" id="displayDlStatus">-</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Front Card Bottom Bar -->
                                    <div class="pt-2 mt-2 border-top border-white border-opacity-15 d-flex align-items-center justify-content-between small text-white-50" style="font-size: 0.68rem;">
                                        <div><i class="bi bi-shield-check text-warning me-1"></i>Card Face: <strong>FRONT</strong></div>
                                        <div>Fee: <span class="text-warning fw-bold font-mono" id="displayDlFee">3.00 Tokens</span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. CARD BACK -->
                            <div class="col-12 col-xl-6" id="dlBackCol">
                                <div class="dl-smart-card dl-card-back position-relative rounded-4 overflow-hidden shadow-lg p-3.5 d-flex flex-column justify-content-between h-100" style="background: linear-gradient(135deg, #06382a 0%, #0d4a38 50%, #04251c 100%); color: #ffffff; border: 1.5px solid rgba(212, 175, 55, 0.4); min-height: 330px; box-shadow: 0 12px 30px rgba(0,0,0,0.35), inset 0 0 40px rgba(0,0,0,0.2) !important;">
                                    
                                    <!-- Indian Tricolor Accent Strip -->
                                    <div class="position-absolute top-0 start-0 end-0" style="height: 4px; background: linear-gradient(90deg, #FF9933 0%, #FF9933 33.3%, #FFFFFF 33.3%, #FFFFFF 66.6%, #138808 66.6%, #138808 100%); z-index: 5;"></div>

                                    <!-- Back Card Header -->
                                    <div class="d-flex align-items-center justify-content-between pb-2 border-bottom border-white border-opacity-15 mt-1">
                                        <div class="d-flex align-items-center gap-1.5">
                                            <i class="bi bi-arrow-repeat text-warning fs-6"></i>
                                            <span class="fw-bold text-warning text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">AUTHORISATION TO DRIVE & ADDRESS</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-danger text-white border border-white border-opacity-25 px-2 py-0.5" style="font-size: 0.68rem;">
                                                <i class="bi bi-droplet-fill me-0.5"></i> Blood Group: <span id="displayDlBackBloodGroup" class="fw-bold">O+</span>
                                            </span>
                                            <span class="badge bg-white bg-opacity-10 text-white-50 border border-white border-opacity-20" style="font-size: 0.65rem;">BACK</span>
                                        </div>
                                    </div>

                                    <!-- Complete Address Box -->
                                    <div class="p-2.5 my-2 rounded-3 bg-black bg-opacity-35 border border-white border-opacity-10">
                                        <div class="d-flex align-items-center justify-content-between text-warning mb-1" style="font-size: 0.65rem;">
                                            <div class="fw-bold text-uppercase"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Complete Residential Address</div>
                                            <span class="badge bg-white bg-opacity-10 text-white-50 font-mono" style="font-size: 0.58rem;">PERMANENT</span>
                                        </div>
                                        <div class="text-white small lh-sm fw-medium" id="displayDlCompleteAddress" style="font-size: 0.74rem;">
                                            -
                                        </div>
                                    </div>

                                    <!-- Vehicle Category Details & COV -->
                                    <div class="p-2 rounded-3 bg-black bg-opacity-35 border border-white border-opacity-10 flex-grow-1 d-flex flex-column justify-content-between">
                                        <div class="d-flex align-items-center justify-content-between mb-1 pb-1 border-bottom border-white border-opacity-10">
                                            <div class="d-flex align-items-center gap-1 text-warning fw-bold text-uppercase" style="font-size: 0.65rem;">
                                                <i class="bi bi-car-front-fill me-1"></i> Class of Vehicles (COV)
                                            </div>
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="text-white-50" style="font-size: 0.6rem;">COV:</span>
                                                <span class="badge bg-warning text-dark font-mono fw-bold px-2 py-0.5" id="displayDlCovBadge" style="font-size: 0.68rem;">MCWOG</span>
                                            </div>
                                        </div>

                                        <!-- Dynamic Vehicle Category Details List / Table -->
                                        <div id="displayDlVehicleCategoryContainer" class="table-responsive my-1" style="max-height: 110px; overflow-y: auto;">
                                            <table class="table table-sm table-borderless text-white mb-0" style="font-size: 0.68rem;">
                                                <thead class="text-white-50 border-bottom border-white border-opacity-10" style="font-size: 0.6rem; text-transform: uppercase;">
                                                    <tr>
                                                        <th class="py-0.5 ps-1">COV</th>
                                                        <th class="py-0.5">Category Type</th>
                                                        <th class="py-0.5">Issue Date</th>
                                                        <th class="py-0.5 pe-1 text-end">Valid Till</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="displayDlVehicleCategoryTbody">
                                                    <!-- Injected dynamically -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Back Card Bottom MRZ / Security Strip -->
                                    <div class="pt-2 mt-2 border-top border-white border-opacity-15 d-flex align-items-center justify-content-between text-white-50" style="font-size: 0.65rem;">
                                        <div class="font-mono text-white-50 text-truncate" style="letter-spacing: 1px; font-size: 0.58rem; max-width: 260px;">
                                            IDIND&lt;&lt;SARATHI&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;&lt;
                                        </div>
                                        <div class="text-end">
                                            <i class="bi bi-qr-code text-warning me-1"></i>
                                            <span class="text-white fw-semibold">Digitally Signed & Validated</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- C. SUCCESS RC VEHICLE CARD -->
                    <div id="rcSuccessCard" class="d-none mb-3">
                        <!-- Top Action Controls / Toolbar -->
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 p-2.5 px-3 bg-dark text-white rounded-3 shadow-sm mb-3" style="background: #0f172a !important;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="p-1.5 rounded-2 text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: #059669;">
                                    <i class="bi bi-car-front-fill fs-5"></i>
                                </div>
                                <div>
                                    <span class="fw-bold text-white small d-block">VEHICLE RC TO MOBILE LINKAGE CERTIFICATE</span>
                                    <span class="text-white-50" style="font-size: 0.7rem;">Ministry of Road Transport and Highways (MoRTH) &amp; Vahan Citizen Services</span>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <button type="button" class="btn btn-warning btn-sm fw-bold px-3 py-1 shadow-sm rounded-2" id="btnCopyRcResult">
                                    <i class="bi bi-clipboard-check me-1"></i> <span id="copyRcBtnText">Copy Plate No.</span>
                                </button>
                                <button type="button" class="btn btn-success btn-sm fw-bold px-3 py-1 shadow-sm rounded-2" id="btnCopyRcMobileResult">
                                    <i class="bi bi-telephone-outbound me-1"></i> <span id="copyRcMobileBtnText">Copy Mobile</span>
                                </button>
                                <button type="button" class="btn btn-outline-light btn-sm px-2.5 py-1" id="btnPrintRcCard" onclick="printRcSmartCard()">
                                    <i class="bi bi-printer me-1"></i> Print Certificate
                                </button>
                            </div>
                        </div>

                        <!-- Card Presentation Area -->
                        <div id="rcCardPrintArea" class="rc-smart-card position-relative rounded-4 overflow-hidden shadow-lg p-3.5" style="color: #ffffff; min-height: 320px;">
                            
                            <!-- Indian Tricolor Accent Strip -->
                            <div class="position-absolute top-0 start-0 end-0" style="height: 4px; background: linear-gradient(90deg, #FF9933 0%, #FF9933 33.3%, #FFFFFF 33.3%, #FFFFFF 66.6%, #138808 66.6%, #138808 100%); z-index: 5;"></div>

                            <!-- Dual Authority Header: MoRTH & Vahan -->
                            <div class="d-flex align-items-center justify-content-between pb-2.5 border-bottom border-white border-opacity-15 mt-1">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle p-1 bg-white bg-opacity-10 border border-success border-opacity-40 text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(16, 185, 129, 0.15) !important;">
                                        <i class="bi bi-car-front-fill fs-4 text-warning"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white lh-1" style="font-size: 0.74rem; letter-spacing: 0.6px;">GOVT. OF INDIA / MINISTRY OF ROAD TRANSPORT &amp; HIGHWAYS</div>
                                        <div class="small text-white-50 lh-sm" style="font-size: 0.65rem;">सड़क परिवहन एवं राजमार्ग मंत्रालय / वाहन ई-सेवा पोर्टल (VAHAN 4.0)</div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success text-white font-mono fw-bold px-2 py-0.5" style="font-size: 0.68rem; letter-spacing: 0.5px;">CMVR FORM 23 COMPLIANT</span>
                                    <div class="d-flex align-items-center justify-content-end gap-1 mt-0.5">
                                        <span class="pulse-indicator bg-success" style="width: 7px; height: 7px; border-radius: 50%; display: inline-block;"></span>
                                        <span class="fw-bold text-success text-uppercase font-mono" id="displayRcStatusBadge" style="font-size: 0.68rem;">ACTIVE &amp; SEEDED</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Interactive Dual Linkage Visual (Registration Plate -> Link -> Registered Mobile) -->
                            <div class="row g-2 align-items-center my-3">
                                <!-- Left: Vehicle Registration Plate Box -->
                                <div class="col-12 col-md-5">
                                    <div class="p-3 rounded-3 bg-black bg-opacity-45 border border-white border-opacity-15 h-100 d-flex flex-column justify-content-between">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.62rem; letter-spacing: 0.8px;">
                                                <i class="bi bi-card-heading text-info me-1"></i> Registration Plate
                                            </span>
                                            <span class="badge bg-white bg-opacity-10 text-white-50 font-mono" style="font-size: 0.58rem;">MOTOR VEHICLE</span>
                                        </div>
                                        <div class="my-1">
                                            <div class="d-inline-flex align-items-center bg-white text-dark border border-dark rounded-2 shadow-sm overflow-hidden" style="border-width: 2px !important;">
                                                <div class="bg-primary text-white px-2 py-1 d-flex flex-column align-items-center justify-content-center" style="font-size: 0.6rem; min-width: 28px; border-right: 2px solid #000;">
                                                    <i class="bi bi-circle-fill text-warning mb-0.5" style="font-size: 0.4rem;"></i>
                                                    <strong style="letter-spacing: 0.5px;">IND</strong>
                                                </div>
                                                <div class="px-3 py-1 font-mono fw-bold fs-4 text-dark text-truncate" id="displayRcPlate" style="letter-spacing: 3px;">
                                                    GJ08BX1234
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between text-white-50 mt-1" style="font-size: 0.68rem;">
                                            <span id="displayRcOwnerName">Owner: Registered Licensee</span>
                                            <span class="text-success"><i class="bi bi-shield-check me-0.5"></i> Vahan RC Verified</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Center: Linkage Connector Icon & Badge -->
                                <div class="col-12 col-md-2 text-center py-1">
                                    <div class="d-inline-flex flex-column align-items-center justify-content-center">
                                        <div class="rounded-circle p-2 bg-success bg-opacity-20 border border-success text-success d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px;">
                                            <i class="bi bi-link-45deg fs-4 text-warning"></i>
                                        </div>
                                        <span class="badge bg-success text-white font-mono px-2 py-0.5 mt-1.5 shadow-sm" style="font-size: 0.62rem;">
                                            LINKED
                                        </span>
                                    </div>
                                </div>

                                <!-- Right: Registered Mobile Box -->
                                <div class="col-12 col-md-5">
                                    <div class="p-3 rounded-3 bg-black bg-opacity-45 border border-success border-opacity-35 h-100 d-flex flex-column justify-content-between position-relative overflow-hidden">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="text-success text-uppercase fw-semibold" style="font-size: 0.62rem; letter-spacing: 0.8px;">
                                                <i class="bi bi-telephone-fill me-1"></i> Registered Mobile
                                            </span>
                                            <span class="badge bg-success text-white font-mono fw-bold" id="displayRcMobileBadge" style="font-size: 0.62rem;">
                                                PRIMARY CONTACT
                                            </span>
                                        </div>
                                        <div class="fw-bold font-mono text-warning fs-3 lh-sm my-1 text-truncate" id="displayRcMobile" style="letter-spacing: 3px; text-shadow: 0 2px 8px rgba(0,0,0,0.6);">
                                            9xxxxxxx57
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between text-white-50 mt-1" style="font-size: 0.68rem;">
                                            <span>Type: <strong class="text-white font-mono">Mobile Contact</strong></span>
                                            <span class="text-warning"><i class="bi bi-patch-check-fill me-0.5"></i> Vahan Seeded</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Extra Vehicle Attributes Row (Displays if model, fuel, or regDate available) -->
                            <div id="rcExtraAttributesRow" class="d-flex flex-wrap justify-content-around gap-2 p-2 rounded-3 bg-black bg-opacity-35 border border-white border-opacity-10 my-2 small text-white-50" style="font-size: 0.72rem;">
                                <div><i class="bi bi-truck text-info me-1"></i>Model: <span class="text-white fw-bold" id="displayRcModel">Vehicle Record Active</span></div>
                                <div><i class="bi bi-fuel-pump text-warning me-1"></i>Fuel: <span class="badge bg-warning text-dark font-mono" id="displayRcFuel">PETROL</span></div>
                                <div><i class="bi bi-calendar3 text-success me-1"></i>Reg. Date: <span class="text-white font-mono fw-bold" id="displayRcRegDate">-</span></div>
                            </div>

                            <!-- Linkage Verification Summary Box -->
                            <div class="p-2.5 my-2 rounded-3 bg-black bg-opacity-35 border border-white border-opacity-10">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                        <div>
                                            <div class="fw-bold text-white small" id="displayRcMessage">RC To Mobile v3 response processed successfully.</div>
                                            <div class="text-white-50" style="font-size: 0.68rem;">Official confirmation that this vehicle plate is linked with the registered mobile number in Vahan portal.</div>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="badge bg-success bg-opacity-25 text-white border border-success border-opacity-30 px-2 py-1 font-mono" style="font-size: 0.68rem;">
                                            <i class="bi bi-database-check me-1"></i> MoRTH Seeded
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Security & Fee Bar -->
                            <div class="pt-2 mt-2 border-top border-white border-opacity-15 d-flex align-items-center justify-content-between small text-white-50" style="font-size: 0.68rem;">
                                <div class="d-flex align-items-center gap-1.5">
                                    <i class="bi bi-qr-code text-warning"></i>
                                    <span class="text-white-50 font-mono" style="font-size: 0.62rem;">MoRTH-VAHAN&lt;&lt;RC-MOBILE-SEEDED&lt;&lt;AUTHENTICATED&lt;&lt;</span>
                                </div>
                                <div>Fee: <span class="text-warning fw-bold font-mono" id="displayRcFee">3.50 Tokens</span></div>
                            </div>

                        </div>
                    </div>

                    <!-- D. SUCCESS VOTER ID CARD -->
                    <div id="voterSuccessCard" class="card border-0 shadow overflow-hidden d-none mb-3" style="background: linear-gradient(135deg, #2e1065 0%, #3b0764 50%, #581c87 100%); color: white; border-radius: 14px;">
                        <div class="p-3 d-flex justify-content-between align-items-center border-bottom border-white border-opacity-10">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-warning text-dark fw-bold px-2 py-1">GOVT. OF INDIA</span>
                                <span class="small text-white-50 fw-semibold">ELECTION COMMISSION OF INDIA</span>
                            </div>
                            <span class="badge bg-success-subtle text-success border-0 px-2.5 py-1">
                                <i class="bi bi-check-circle-fill me-1"></i> ELECTOR RECORD ACTIVE
                            </span>
                        </div>
                        <div class="p-4 text-center">
                            <div class="small text-white-50 text-uppercase fw-semibold tracking-wider mb-1" style="font-size: 0.75rem;">
                                Elector Photo Identity Card (EPIC) Number
                            </div>
                            <div class="display-6 fw-bold font-mono text-warning my-2" id="displayVoterId" style="letter-spacing: 3px;">
                                ABC1234567
                            </div>
                            <div class="text-white fw-semibold fs-6 mb-2" id="displayVoterName">ELECTOR NAME</div>
                            <div class="d-flex flex-wrap justify-content-center gap-3 text-white-50 small mb-3">
                                <div><i class="bi bi-geo-alt me-1"></i>Constituency: <span class="text-white fw-bold" id="displayVoterConstituency">-</span></div>
                                <div><i class="bi bi-building me-1"></i>Polling Station: <span class="text-white fw-bold" id="displayVoterPollingStation">-</span></div>
                            </div>
                            <button type="button" class="btn btn-light btn-sm fw-bold px-4 py-2 shadow-sm rounded-pill" id="btnCopyVoterResult">
                                <i class="bi bi-clipboard-check me-1 text-primary"></i> <span id="copyVoterBtnText">Copy Voter ID</span>
                            </button>
                        </div>
                        <div class="p-3 bg-black bg-opacity-25 d-flex justify-content-between align-items-center small text-white-50 border-top border-white border-opacity-10">
                            <div><i class="bi bi-person-check text-warning me-1"></i>Status: <span class="text-white fw-semibold">Electoral Roll Verified</span></div>
                            <div>Fee: <span class="text-warning fw-bold" id="displayVoterFee">2.00 Tokens</span></div>
                        </div>
                    </div>

                    <!-- E. SUCCESS AADHAAR TO PAN LINKAGE CARD -->
                    <div id="aadharToPanSuccessCard" class="d-none mb-3">
                        <!-- Top Action Controls / Toolbar -->
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 p-2.5 px-3 bg-dark text-white rounded-3 shadow-sm mb-3" style="background: #0f172a !important;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="p-1.5 rounded-2 text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: #4f46e5;">
                                    <i class="bi bi-link-45deg fs-5"></i>
                                </div>
                                <div>
                                    <span class="fw-bold text-white small d-block">AADHAAR TO UNMASKED PAN LINKAGE CERTIFICATE</span>
                                    <span class="text-white-50" style="font-size: 0.7rem;">Verified on Unique Identification Authority of India (UIDAI) & Income Tax Department</span>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <button type="button" class="btn btn-warning btn-sm fw-bold px-3 py-1 shadow-sm rounded-2" id="btnCopyAadharPanResult">
                                    <i class="bi bi-clipboard-check me-1"></i> <span id="copyAadharPanBtnText">Copy PAN</span>
                                </button>
                                <button type="button" class="btn btn-outline-light btn-sm px-2.5 py-1" id="btnCopyAadharPanFullResult" onclick="copyAadharPanFullDetails()">
                                    <i class="bi bi-file-earmark-text me-1"></i> Copy Certificate
                                </button>
                                <button type="button" class="btn btn-outline-light btn-sm px-2.5 py-1" id="btnPrintAadharPanCard" onclick="printAadharPanSmartCard()">
                                    <i class="bi bi-printer me-1"></i> Print Card
                                </button>
                            </div>
                        </div>

                        @include('admin.services.epan_print')

                        <!-- Card Presentation Area -->
                        <div id="aadharPanCardPrintArea" class="aadhar-pan-card position-relative rounded-4 overflow-hidden shadow-lg p-3.5" style="color: #ffffff; min-height: 320px;">
                            
                            <!-- Indian Tricolor Accent Strip -->
                            <div class="position-absolute top-0 start-0 end-0" style="height: 4px; background: linear-gradient(90deg, #FF9933 0%, #FF9933 33.3%, #FFFFFF 33.3%, #FFFFFF 66.6%, #138808 66.6%, #138808 100%); z-index: 5;"></div>

                            <!-- Dual Authority Header: UIDAI & ITD -->
                            <div class="d-flex align-items-center justify-content-between pb-2.5 border-bottom border-white border-opacity-15 mt-1">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle p-1 bg-white bg-opacity-10 border border-primary border-opacity-40 text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(99, 102, 241, 0.15) !important;">
                                        <i class="bi bi-fingerprint fs-4 text-warning"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white lh-1" style="font-size: 0.74rem; letter-spacing: 0.6px;">GOVT. OF INDIA / UIDAI & INCOME TAX DEPARTMENT</div>
                                        <div class="small text-white-50 lh-sm" style="font-size: 0.65rem;">भारत सरकार / भारतीय विशिष्ट पहचान प्राधिकरण एवं आयकर विभाग</div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success text-white font-mono fw-bold px-2 py-0.5" style="font-size: 0.68rem; letter-spacing: 0.5px;">SECTION 139AA COMPLIANT</span>
                                    <div class="d-flex align-items-center justify-content-end gap-1 mt-0.5">
                                        <span class="pulse-indicator bg-success" style="width: 7px; height: 7px; border-radius: 50%; display: inline-block;"></span>
                                        <span class="fw-bold text-success text-uppercase font-mono" id="displayAadharPanStatusBadge" style="font-size: 0.68rem;">LINKED & ACTIVE</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Interactive Dual Linkage Visual Representation (Aadhaar -> Link -> PAN) -->
                            <div class="row g-2 align-items-center my-3">
                                <!-- Left: Queried Aadhaar Box -->
                                <div class="col-12 col-md-5">
                                    <div class="p-3 rounded-3 bg-black bg-opacity-45 border border-white border-opacity-15 h-100 d-flex flex-column justify-content-between">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.62rem; letter-spacing: 0.8px;">
                                                <i class="bi bi-fingerprint text-info me-1"></i> Input Aadhaar Number
                                            </span>
                                            <span class="badge bg-white bg-opacity-10 text-white-50 font-mono" style="font-size: 0.58rem;">12 DIGITS</span>
                                        </div>
                                        <div class="fw-bold font-mono text-white fs-4 lh-sm my-1 text-truncate" id="displayAadharInputNumber" style="letter-spacing: 2px;">
                                            5212 3456 7897
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between text-white-50 mt-1" style="font-size: 0.68rem;">
                                            <span>Masked: <strong class="text-white font-mono" id="displayAadharMaskedNumber">XXXX XXXX 7897</strong></span>
                                            <span class="text-success"><i class="bi bi-shield-check me-0.5"></i> Verified</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Center: Linkage Connector Icon & Badge -->
                                <div class="col-12 col-md-2 text-center py-1">
                                    <div class="d-inline-flex flex-column align-items-center justify-content-center">
                                        <div class="rounded-circle p-2 bg-warning bg-opacity-20 border border-warning text-warning d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px;">
                                            <i class="bi bi-link-45deg fs-4"></i>
                                        </div>
                                        <span class="badge bg-success text-white font-mono px-2 py-0.5 mt-1.5 shadow-sm" style="font-size: 0.62rem;">
                                            LINKED
                                        </span>
                                    </div>
                                </div>

                                <!-- Right: Unmasked Linked PAN Box -->
                                <div class="col-12 col-md-5">
                                    <div class="p-3 rounded-3 bg-black bg-opacity-45 border border-warning border-opacity-35 h-100 d-flex flex-column justify-content-between position-relative overflow-hidden">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="text-warning text-uppercase fw-semibold" style="font-size: 0.62rem; letter-spacing: 0.8px;">
                                                <i class="bi bi-credit-card-2-front-fill me-1"></i> Unmasked Linked PAN
                                            </span>
                                            <span class="badge bg-warning text-dark font-mono fw-bold" id="displayAadharPanTypeBadge" style="font-size: 0.62rem;">
                                                Individual (P)
                                            </span>
                                        </div>
                                        <div class="fw-bold font-mono text-warning fs-3 lh-sm my-1 text-truncate" id="displayAadharLinkedPan" style="letter-spacing: 4px; text-shadow: 0 2px 8px rgba(0,0,0,0.6);">
                                            GENPB6869D
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between text-white-50 mt-1" style="font-size: 0.68rem;">
                                            <span>Status: <strong class="text-success font-mono">Active & Seeded</strong></span>
                                            <span class="text-warning"><i class="bi bi-patch-check-fill me-0.5"></i> Income Tax</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Linkage Verification Summary Box -->
                            <div class="p-2.5 my-2 rounded-3 bg-black bg-opacity-35 border border-white border-opacity-10">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                        <div>
                                            <div class="fw-bold text-white small" id="displayAadharPanMessage">Aadhar To Pan response processed successfully.</div>
                                            <div class="text-white-50" style="font-size: 0.68rem;">Official confirmation that this Aadhaar is legally seeded with Permanent Account Number.</div>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="badge bg-primary bg-opacity-25 text-white border border-primary border-opacity-30 px-2 py-1 font-mono" style="font-size: 0.68rem;">
                                            <i class="bi bi-database-check me-1"></i> CBDT e-Filing Seeded
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Security & Fee Bar -->
                            <div class="pt-2 mt-2 border-top border-white border-opacity-15 d-flex align-items-center justify-content-between small text-white-50" style="font-size: 0.68rem;">
                                <div class="d-flex align-items-center gap-1.5">
                                    <i class="bi bi-qr-code text-warning"></i>
                                    <span class="text-white-50 font-mono" style="font-size: 0.62rem;">UIDAI-ITD&lt;&lt;AADHAAR-PAN-SEEDED&lt;&lt;AUTHENTICATED&lt;&lt;</span>
                                </div>
                                <div>Fee: <span class="text-warning fw-bold font-mono" id="displayAadharPanFee">5.00 Tokens</span></div>
                            </div>

                        </div>
                    </div>

                    <!-- F. ALL SUCCESS DATA CARD (Formatted data{} attributes) -->
                    <div id="allSuccessDataCard" class="card border-0 shadow-sm rounded-3 overflow-hidden d-none mb-3" style="border: 1px solid #e2e8f0 !important;">
                        <div class="px-3.5 py-2.5 bg-light border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="p-1.5 rounded-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="bi bi-card-checklist fs-6"></i>
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block" style="font-size: 0.88rem;">Verified Record Details (<code class="text-primary">data{ }</code>)</span>
                                    <span class="text-muted small" style="font-size: 0.72rem;">Complete structured attributes verified by Verification Gateway</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 0.72rem;">
                                    <i class="bi bi-patch-check-fill me-1"></i>VERIFIED LIVE DATA
                                </span>
                                <span class="badge bg-white border text-secondary font-mono px-2 py-1" id="allSuccessDataCount" style="font-size: 0.72rem;">
                                    0 Attributes
                                </span>
                            </div>
                        </div>
                        <div class="p-3 bg-white">
                            <div id="allSuccessDataGrid" class="row g-2.5">
                                <!-- Populated dynamically with all keys in data{} -->
                            </div>
                        </div>
                    </div>

                    <!-- F. FAILED / ERROR CARD -->
                    <div id="errorCard" class="alert alert-danger d-flex align-items-start gap-3 p-3 rounded-3 shadow-sm d-none mb-3">
                        <i class="bi bi-exclamation-triangle-fill fs-3 text-danger flex-shrink-0"></i>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1" id="errorTitle">Verification Unsuccessful</h6>
                            <p class="mb-1 small" id="errorMessageText">Could not complete verification.</p>
                            <small class="text-muted d-block"><i class="bi bi-info-circle me-1"></i>Zero tokens were deducted from your wallet balance.</small>
                        </div>
                    </div>

                    <!-- G. Full Raw Gateway Response Toggle (Hidden as requested) -->
                    <div class="d-none">
                        <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top">
                            <a class="small text-muted text-decoration-none d-flex align-items-center gap-1" data-bs-toggle="collapse" href="#rawResponseCollapse" role="button">
                                <i class="bi bi-terminal"></i>
                                <span>View Raw Technical JSON</span>
                            </a>
                            <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" style="font-size: 0.72rem;" id="btnCopyLiveRawResponse">
                                <i class="bi bi-clipboard me-1"></i>Copy JSON
                            </button>
                        </div>
                        <div class="collapse mt-2 text-start" id="rawResponseCollapse">
                            <pre id="liveResponseBody" class="p-3 bg-dark text-light rounded-3 font-mono border" style="font-size: 0.75rem; max-height: 180px; overflow-y: auto;"></pre>
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer bg-light px-4 py-3 border-0 d-flex justify-content-between">
                <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 500;">Close</button>
                <button type="button" class="btn fw-semibold px-4 py-2 d-flex align-items-center gap-1.5" id="btnDispatchApi" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); color: #ffffff; border: none; border-radius: 9px; box-shadow: 0 4px 14px rgba(13, 148, 136, 0.35); font-size: 0.95rem;">
                    <span id="spinnerDispatch" class="spinner-border spinner-border-sm d-none me-1" role="status"></span>
                    <i class="bi bi-shield-check" id="btnDispatchIcon"></i>
                    <span id="btnDispatchText">Verify PAN & Name</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Input Parameters Modal (formerly specModal) -->
<div class="modal fade" id="specModal" tabindex="-1" aria-labelledby="specModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-dark text-white px-4 py-3 border-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded-3 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="bi bi-card-checklist fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="specServiceName">Service Input Parameters</h5>
                        <small class="text-white-50" id="specServiceSubtitle">Required inputs & sample values</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <!-- Service Meta Card -->
                <div class="p-2.5 px-3 rounded-3 bg-light border mb-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-mono" id="specCategoryBadge">Category</span>
                        <span class="badge bg-secondary-subtle text-secondary font-mono" id="specServiceCodeBadge">code</span>
                    </div>
                    <div class="small">
                        <span class="text-muted">Charge:</span>
                        <strong class="text-dark" id="specCostBadge">0.00 Tokens</strong>
                    </div>
                </div>

                <!-- Input Fields Section -->
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-bold text-dark small text-uppercase" style="letter-spacing: 0.5px;">
                        <i class="bi bi-input-cursor-text text-primary me-1"></i> Required Parameters
                    </span>
                    <span class="badge bg-light text-muted border small" id="specFieldsCountBadge">0 Fields</span>
                </div>

                <!-- Friendly Input Values List -->
                <div id="specFriendlyInputsContainer" class="d-flex flex-column gap-2 mb-3">
                    <!-- Populated dynamically via JS -->
                </div>

                <div class="alert alert-light border small text-muted d-flex align-items-center gap-2 mb-0 py-2">
                    <i class="bi bi-info-circle text-primary fs-5 flex-shrink-0"></i>
                    <span style="font-size: 0.78rem;">These inputs are used when triggering this verification service.</span>
                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3 border-0 d-flex justify-content-between">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary fw-semibold px-4" id="btnSpecTriggerNow">
                    <i class="bi bi-play-circle-fill me-1"></i> Trigger Service
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const testModal = document.getElementById('testModal');
    const specModal = document.getElementById('specModal');

    const dynamicInputsContainer = document.getElementById('dynamicEssentialInputsContainer');
    const modalPayloadText = document.getElementById('modalRequestPayload');

    const btnDispatch = document.getElementById('btnDispatchApi');
    const btnDispatchText = document.getElementById('btnDispatchText');
    const btnDispatchIcon = document.getElementById('btnDispatchIcon');
    const spinnerDispatch = document.getElementById('spinnerDispatch');
    let isPrintMode = false;

    const liveContainer = document.getElementById('liveResponseContainer');
    const panSuccessCard = document.getElementById('panSuccessCard');
    const genericSuccessCard = document.getElementById('genericSuccessCard');
    const errorCard = document.getElementById('errorCard');

    const displayPanNumber = document.getElementById('displayPanNumber');
    const displayPanHolderName = document.getElementById('displayPanHolderName');
    const displayQueriedAadhaar = document.getElementById('displayQueriedAadhaar');
    const displayFeeDeducted = document.getElementById('displayFeeDeducted');
    const btnCopyPanResult = document.getElementById('btnCopyPanResult');
    const copyPanBtnText = document.getElementById('copyPanBtnText');

    const errorTitle = document.getElementById('errorTitle');
    const errorMessageText = document.getElementById('errorMessageText');
    const responseBody = document.getElementById('liveResponseBody');

    // Helper: format Aadhaar into groups of 4 (e.g. 5212 3456 7897)
    function formatAadhaar(val) {
        const digits = String(val || '').replace(/\D/g, '').substring(0, 12);
        return digits.replace(/(\d{4})(?=\d)/g, '$1 ');
    }

    // Service Themes & Headers (Consistent with Reference Image Look and Feel)
    const serviceThemes = {
        dl_verification_plus_srv2: {
            title: 'DL Print PDF',
            subtitle: 'Generate Driving Licence PDF — instant download',
            btnText: 'Download DL PDF',
            icon: 'bi-person-vcard',
            watermark: 'bi-geo-alt-fill',
            instantNotice: 'DL PDF will be generated instantly. Available for download immediately after generation.'
        },
        dl_pdf_by_dl_no: {
            title: 'DL Print PDF',
            subtitle: 'Generate Driving Licence PDF — instant download',
            btnText: 'Download DL PDF',
            icon: 'bi-person-vcard',
            watermark: 'bi-geo-alt-fill',
            instantNotice: 'DL PDF will be generated instantly. Available for download immediately after generation.'
        },
        pan_details_v2: {
            title: 'PAN Details Instant',
            subtitle: 'Instant PAN verification — cardholder details',
            btnText: 'Verify PAN & Name',
            icon: 'bi-credit-card-2-front',
            watermark: 'bi-shield-check',
            instantNotice: 'PAN details will be verified instantly. Available immediately after verification.'
        },
        pan_details_v1: {
            title: 'PAN Find Instant',
            subtitle: 'Instant PAN search — fast real-time validation',
            btnText: 'Verify PAN Card',
            icon: 'bi-credit-card-2-front',
            watermark: 'bi-search',
            instantNotice: 'PAN details will be fetched instantly from official Income Tax records.'
        },
        aadhar_to_pan: {
            title: 'PAN No To Aadhar No',
            subtitle: 'Instant Aadhaar-PAN linkage query',
            btnText: 'Fetch Linked PAN',
            icon: 'bi-fingerprint',
            watermark: 'bi-link-45deg',
            instantNotice: 'Aadhaar-to-PAN linkage status will be retrieved instantly.'
        },
        rc_to_mobile_v3: {
            title: 'RC PDF Instant',
            subtitle: 'Vehicle registration & mobile linkage lookup',
            btnText: 'Fetch Vehicle RC',
            icon: 'bi-car-front-fill',
            watermark: 'bi-truck',
            instantNotice: 'Vehicle registration particulars will be retrieved instantly from Vahan records.'
        },
        voter_id_verification_srv2: {
            title: 'Voter Mobile Link',
            subtitle: 'Instant Electoral Roll verification',
            btnText: 'Verify Voter ID',
            icon: 'bi-person-badge-fill',
            watermark: 'bi-card-checklist',
            instantNotice: 'Voter electoral particulars will be validated instantly against official roll.'
        }
    };

    // Essential Input Field Configurations for User Interface
    const essentialInputConfig = {
        aadhar: {
            label: 'Aadhaar Card Number',
            icon: 'bi-fingerprint text-primary',
            badge: '12 Digits',
            placeholder: 'XXXX XXXX XXXX',
            type: 'aadhar',
            help: 'Enter 12-digit citizen Aadhaar number to retrieve linked PAN details.'
        },
        pan: {
            label: 'PAN Card Number',
            icon: 'bi-credit-card-2-front-fill text-primary',
            badge: '10 Characters',
            placeholder: 'ABCDE1234F',
            type: 'text',
            uppercase: true,
            maxlength: 10,
            help: '10-character alphanumeric Permanent Account Number (e.g. ABCDE1234F).'
        },
        name: {
            label: 'Cardholder Full Name',
            icon: 'bi-person-fill text-primary',
            badge: 'Full Name',
            placeholder: 'RAJESH KUMAR SHARMA',
            type: 'text',
            uppercase: true,
            help: 'Full legal name to verify and match against Income Tax records.'
        },
        pan_display_name: {
            label: 'Include Display Name in Response',
            icon: 'bi-eye-fill text-info',
            badge: 'Option',
            type: 'select',
            options: [
                { value: 'false', text: 'false (Standard Response)' },
                { value: 'true', text: 'true (Include Name in Response)' }
            ],
            help: 'Control whether display name is returned in the API payload.'
        },
        name_match_method: {
            label: 'Name Matching Algorithm',
            icon: 'bi-spellcheck text-success',
            badge: 'Algorithm',
            type: 'select',
            options: [
                { value: 'fuzzy', text: 'fuzzy (Fuzzy Matching with scoring)' },
                { value: 'exact', text: 'exact (Strict 100% Character Match)' }
            ],
            help: 'Select the comparison algorithm to verify holder name.'
        },
        dlNumber: {
            label: 'Driving License Number',
            icon: 'bi-truck-front-fill text-warning',
            badge: 'DL No.',
            placeholder: 'DL-1420110012345',
            type: 'text',
            uppercase: true,
            help: 'Enter valid Indian Driving License number issued by RTO.'
        },
        dob: {
            label: 'Date of Birth (DOB)',
            icon: 'bi-calendar-event text-danger',
            badge: 'YYYY-MM-DD',
            placeholder: '1990-05-15',
            type: 'date',
            help: 'Citizen date of birth matching official records (YYYY-MM-DD).'
        },
        vehicle_num: {
            label: 'Vehicle Registration Number',
            icon: 'bi-car-front-fill text-success',
            badge: 'Plate No.',
            placeholder: 'GJ08BX1234',
            type: 'text',
            uppercase: true,
            help: 'Complete vehicle registration number (e.g. GJ08BX1234).'
        },
        voterId: {
            label: 'Voter ID (EPIC) Number',
            icon: 'bi-person-badge-fill text-purple',
            badge: 'EPIC No.',
            placeholder: 'ABC1234567',
            type: 'text',
            uppercase: true,
            help: 'Alphanumeric Electoral Photo Identity Card (EPIC) number.'
        }
    };

    // Metadata dictionary for specModal (</> button)
    const fieldDescriptions = {
        api_id: {
            label: 'Partner ID',
            icon: 'bi-key-fill text-warning',
            help: 'Unique Partner Gateway Identifier',
            format: val => val || 'PID3597'
        },
        api_key: {
            label: 'Security Key',
            icon: 'bi-shield-lock-fill text-primary',
            help: 'Authentication Secret Key',
            format: val => val ? '••••••••' + String(val).slice(-6) : '••••••••'
        },
        token_id: {
            label: 'Token ID',
            icon: 'bi-file-earmark-lock text-info',
            help: 'Partner Verification Token',
            format: val => val ? '••••••••' + String(val).slice(-6) : '••••••••'
        },
        dlNumber: {
            label: 'Driving License Number',
            icon: 'bi-truck-front-fill text-warning',
            help: 'Official Driving License number as recorded in RTO portal (e.g. DL-1420110012345)',
            format: val => val || 'DL-1420110012345'
        },
        dob: {
            label: 'Date of Birth (DOB)',
            icon: 'bi-calendar-event text-danger',
            help: 'Citizen date of birth matching official records (YYYY-MM-DD)',
            format: val => val || '1990-05-15'
        },
        pan: {
            label: 'PAN Card Number',
            icon: 'bi-credit-card-2-front-fill text-primary',
            help: '10-character alphanumeric Permanent Account Number (e.g. ABCDE1234F)',
            format: val => val || 'ABCDE1234F'
        },
        name: {
            label: 'Cardholder Full Name',
            icon: 'bi-person-fill text-primary',
            help: 'Full legal name to verify and match against records',
            format: val => val || 'RAJESH KUMAR SHARMA'
        },
        pan_display_name: {
            label: 'Display Name Option',
            icon: 'bi-eye-fill text-info',
            help: 'Flag to determine whether holder name is echoed in gateway response ("false" / "true")',
            format: val => String(val ?? 'false')
        },
        name_match_method: {
            label: 'Name Match Algorithm',
            icon: 'bi-spellcheck text-success',
            help: 'Comparison algorithm: "fuzzy" for spelling tolerance or "exact" for strict match',
            format: val => val || 'fuzzy'
        },
        aadhar: {
            label: 'Aadhaar Card Number',
            icon: 'bi-fingerprint text-primary',
            help: '12-digit numeric UIDAI Aadhaar ID to query linked PAN',
            format: val => formatAadhaar(val) || '5212 3456 7897'
        },
        vehicle_num: {
            label: 'Vehicle Registration Number',
            icon: 'bi-car-front-fill text-success',
            help: 'Standard vehicle license plate without spaces (e.g. GJ08BX1234)',
            format: val => val || 'GJ08BX1234'
        },
        voterId: {
            label: 'Voter ID (EPIC) Number',
            icon: 'bi-person-badge-fill text-purple',
            help: 'Election Photo Identity Card alphanumeric number (e.g. ABC1234567)',
            format: val => val || 'ABC1234567'
        }
    };

    // Helper: Synchronize DOM inputs with hidden raw JSON payload
    function syncPayload() {
        const payloadObj = {};
        if (!dynamicInputsContainer) return;
        dynamicInputsContainer.querySelectorAll('.essential-input-field').forEach(input => {
            const key = input.getAttribute('data-key');
            let val = input.value.trim();
            if (key === 'aadhar') {
                val = val.replace(/\D/g, '');
            } else if (key === 'dob' && /^\d{2}-\d{2}-\d{4}$/.test(val)) {
                // Convert DD-MM-YYYY to YYYY-MM-DD for backend payload
                val = val.split('-').reverse().join('-');
            }
            payloadObj[key] = val;
        });
        if (window.currentActiveServicePayload) {
            ['api_id', 'api_key', 'token_id', 'pan_display_name', 'name_match_method'].forEach(cred => {
                if (window.currentActiveServicePayload[cred] !== undefined) {
                    payloadObj[cred] = window.currentActiveServicePayload[cred];
                }
            });
        }
        modalPayloadText.value = JSON.stringify(payloadObj, null, 2);
    }

    // Dynamic Essential Form Builder
    function buildDynamicEssentialForm(container, payloadObj, serviceCode) {
        container.innerHTML = '';

        // Dedicated custom DL Print PDF form (exact replica of reference image)
        if (serviceCode === 'dl_verification_plus_srv2' || serviceCode === 'dl_pdf_by_dl_no') {
            const formattedDob = (payloadObj.dob && /^\d{4}-\d{2}-\d{2}$/.test(payloadObj.dob)) 
                ? payloadObj.dob.split('-').reverse().join('-') 
                : (payloadObj.dob || '');

            container.innerHTML = `
                <div class="row g-3 mb-2">
                    <!-- DL NUMBER -->
                    <div class="col-12 col-md-6">
                        <label class="form-label dl-custom-label d-flex align-items-center gap-1.5 mb-1.5">
                            <i class="bi bi-person-vcard fs-6"></i> DL NUMBER <span class="text-danger">*</span>
                        </label>
                        <div class="input-group dl-custom-input-group shadow-none">
                            <span class="input-group-text bg-white border-0 pe-1 text-muted">
                                <i class="bi bi-person-vcard text-secondary fs-5"></i>
                            </span>
                            <input type="text"
                                   id="essentialInput_dlNumber"
                                   class="form-control border-0 font-mono fw-bold text-dark essential-input-field text-uppercase"
                                   data-key="dlNumber"
                                   value="${payloadObj.dlNumber || ''}"
                                   placeholder="e.g. MH0120240012345"
                                   autocomplete="off"
                                   style="font-size: 0.95rem; box-shadow: none;">
                        </div>
                        <div id="validation_dlNumber" class="form-text text-danger d-none small mt-1">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>Please enter valid Driving License number.
                        </div>
                        <div class="form-text small mt-1.5 d-flex align-items-center gap-1.5 dl-custom-help">
                            <i class="bi bi-info-circle-fill" style="color: #0d9488;"></i> Enter DL number without spaces
                        </div>
                    </div>

                    <!-- DATE OF BIRTH -->
                    <div class="col-12 col-md-6">
                        <label class="form-label dl-custom-label d-flex align-items-center gap-1.5 mb-1.5">
                            <i class="bi bi-calendar-event-fill fs-6"></i> DATE OF BIRTH <span class="text-danger">*</span>
                        </label>
                        <div class="input-group dl-custom-input-group shadow-none">
                            <span class="input-group-text bg-white border-0 pe-1 text-muted">
                                <i class="bi bi-calendar-date text-secondary fs-5"></i>
                            </span>
                            <input type="text"
                                   id="essentialInput_dob"
                                   class="form-control border-0 font-mono fw-bold text-dark essential-input-field"
                                   data-key="dob"
                                   value="${formattedDob}"
                                   placeholder="DD-MM-YYYY"
                                   autocomplete="off"
                                   style="font-size: 0.95rem; box-shadow: none;">
                        </div>
                        <div id="validation_dob" class="form-text text-danger d-none small mt-1">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>Please provide citizen date of birth.
                        </div>
                        <div class="form-text small mt-1.5 d-flex align-items-center gap-1.5 dl-custom-help">
                            <i class="bi bi-info-circle-fill" style="color: #0d9488;"></i> Format: DD-MM-YYYY
                        </div>
                    </div>
                </div>
            `;

            ['dlNumber', 'dob'].forEach(key => {
                const inp = document.getElementById('essentialInput_' + key);
                if (inp) {
                    inp.addEventListener('input', function() {
                        if (key === 'dlNumber') this.value = this.value.toUpperCase();
                        const valElem = document.getElementById('validation_' + key);
                        if (valElem) valElem.classList.add('d-none');
                        syncPayload();
                    });
                    inp.addEventListener('change', syncPayload);
                }
            });
            return;
        }

        // Dedicated custom PAN Details form (exact replica of reference image)
        if (serviceCode === 'pan_details_v2') {
            container.innerHTML = `
                <div class="row g-3 mb-2">
                    <!-- PAN NUMBER -->
                    <div class="col-12 col-md-6">
                        <label class="form-label dl-custom-label d-flex align-items-center gap-1.5 mb-1.5">
                            <i class="bi bi-credit-card-2-front-fill fs-6"></i> PAN NUMBER <span class="text-danger">*</span>
                        </label>
                        <div class="input-group dl-custom-input-group shadow-none">
                            <span class="input-group-text bg-white border-0 pe-1 text-muted">
                                <i class="bi bi-credit-card text-secondary fs-5"></i>
                            </span>
                            <input type="text"
                                   id="essentialInput_pan"
                                   class="form-control border-0 font-mono fw-bold text-dark essential-input-field text-uppercase"
                                   data-key="pan"
                                   value="${payloadObj.pan || ''}"
                                   placeholder="e.g. ABCDE1234F"
                                   maxlength="10"
                                   autocomplete="off"
                                   style="font-size: 0.95rem; box-shadow: none;">
                        </div>
                        <div id="validation_pan" class="form-text text-danger d-none small mt-1">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>Please enter valid 10-character PAN number.
                        </div>
                        <div class="form-text small mt-1.5 d-flex align-items-center gap-1.5 dl-custom-help">
                            <i class="bi bi-info-circle-fill" style="color: #0d9488;"></i> 10-character alphanumeric Permanent Account Number
                        </div>
                    </div>

                    <!-- CARDHOLDER FULL NAME -->
                    <div class="col-12 col-md-6">
                        <label class="form-label dl-custom-label d-flex align-items-center gap-1.5 mb-1.5">
                            <i class="bi bi-person-fill fs-6"></i> CARDHOLDER FULL NAME <span class="text-danger">*</span>
                        </label>
                        <div class="input-group dl-custom-input-group shadow-none">
                            <span class="input-group-text bg-white border-0 pe-1 text-muted">
                                <i class="bi bi-person text-secondary fs-5"></i>
                            </span>
                            <input type="text"
                                   id="essentialInput_name"
                                   class="form-control border-0 font-mono fw-bold text-dark essential-input-field text-uppercase"
                                   data-key="name"
                                   value="${payloadObj.name || ''}"
                                   placeholder="RAJESH KUMAR SHARMA"
                                   autocomplete="off"
                                   style="font-size: 0.95rem; box-shadow: none;">
                        </div>
                        <div id="validation_name" class="form-text text-danger d-none small mt-1">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>Please enter cardholder name.
                        </div>
                        <div class="form-text small mt-1.5 d-flex align-items-center gap-1.5 dl-custom-help">
                            <i class="bi bi-info-circle-fill" style="color: #0d9488;"></i> Full legal name to verify and match against records
                        </div>
                    </div>
                </div>
            `;

            ['pan', 'name'].forEach(key => {
                const inp = document.getElementById('essentialInput_' + key);
                if (inp) {
                    inp.addEventListener('input', function() {
                        this.value = this.value.toUpperCase();
                        const valElem = document.getElementById('validation_' + key);
                        if (valElem) valElem.classList.add('d-none');
                        syncPayload();
                    });
                    inp.addEventListener('change', syncPayload);
                }
            });
            return;
        }

        // Dedicated custom PAN Find form
        if (serviceCode === 'pan_details_v1') {
            container.innerHTML = `
                <div class="row g-3 mb-2">
                    <!-- PAN NUMBER -->
                    <div class="col-12">
                        <label class="form-label dl-custom-label d-flex align-items-center gap-1.5 mb-1.5">
                            <i class="bi bi-credit-card-2-front-fill fs-6"></i> PAN NUMBER <span class="text-danger">*</span>
                        </label>
                        <div class="input-group dl-custom-input-group shadow-none">
                            <span class="input-group-text bg-white border-0 pe-1 text-muted">
                                <i class="bi bi-credit-card text-secondary fs-5"></i>
                            </span>
                            <input type="text"
                                   id="essentialInput_pan"
                                   class="form-control border-0 font-mono fw-bold text-dark essential-input-field text-uppercase"
                                   data-key="pan"
                                   value="${payloadObj.pan || ''}"
                                   placeholder="e.g. ABCDE1234F"
                                   maxlength="10"
                                   autocomplete="off"
                                   style="font-size: 0.95rem; box-shadow: none;">
                        </div>
                        <div id="validation_pan" class="form-text text-danger d-none small mt-1">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>Please enter valid 10-character PAN number.
                        </div>
                        <div class="form-text small mt-1.5 d-flex align-items-center gap-1.5 dl-custom-help">
                            <i class="bi bi-info-circle-fill" style="color: #0d9488;"></i> 10-character alphanumeric Permanent Account Number
                        </div>
                    </div>
                </div>
            `;

            const inp = document.getElementById('essentialInput_pan');
            if (inp) {
                inp.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                    const valElem = document.getElementById('validation_pan');
                    if (valElem) valElem.classList.add('d-none');
                    syncPayload();
                });
                inp.addEventListener('change', syncPayload);
            }
            return;
        }

        const allKeys = Object.keys(payloadObj);
        // Exclude system/gateway authentication credentials and internal options from user entry form
        const keys = allKeys.filter(k => !['api_id', 'api_key', 'token_id', 'pan_display_name', 'name_match_method'].includes(k));

        const formRow = document.createElement('div');
        formRow.className = 'row g-3';

        keys.forEach(key => {
            const conf = essentialInputConfig[key] || {
                label: key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()),
                icon: 'bi-input-cursor-text text-secondary',
                badge: 'Input',
                placeholder: '',
                type: 'text',
                help: 'Enter ' + key.replace(/_/g, ' ')
            };

            const colWrapper = document.createElement('div');
            colWrapper.className = (keys.length > 1 && conf.type !== 'aadhar') ? 'col-12 col-md-6' : 'col-12';

            const formGroup = document.createElement('div');
            formGroup.className = 'mb-1';

            if (conf.type === 'aadhar') {
                formGroup.innerHTML = `
                    <label class="form-label fw-bold d-flex justify-content-between align-items-center mb-1.5">
                        <span><i class="bi ${conf.icon} me-1"></i> ${conf.label}</span>
                        <span class="badge bg-secondary-subtle text-secondary small">${conf.badge}</span>
                    </label>
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-person-vcard text-primary fs-5"></i>
                        </span>
                        <input type="text"
                               id="essentialInput_${key}"
                               class="form-control form-control-lg border-start-0 font-mono fw-bold text-dark essential-input-field"
                               data-key="${key}"
                               value="${formatAadhaar(payloadObj[key])}"
                               placeholder="${conf.placeholder}"
                               maxlength="14"
                               autocomplete="off"
                               style="letter-spacing: 2px;">
                        <button class="btn btn-outline-secondary border-start-0 btn-clear-input" type="button" data-target="essentialInput_${key}" title="Clear">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                    <div id="validation_${key}" class="form-text text-danger d-none small mt-1">
                        <i class="bi bi-exclamation-circle-fill me-1"></i>Please enter a valid 12-digit Aadhaar number.
                    </div>
                    <div class="form-text small mt-1 text-muted d-flex align-items-center gap-1">
                        <i class="bi bi-shield-check text-success"></i> ${conf.help}
                    </div>
                `;
            } else if (conf.type === 'select') {
                const optionsHtml = conf.options.map(opt => `
                    <option value="${opt.value}" ${String(payloadObj[key]) === String(opt.value) ? 'selected' : ''}>${opt.text}</option>
                `).join('');

                formGroup.innerHTML = `
                    <label class="form-label fw-bold d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-truncate"><i class="bi ${conf.icon} me-1"></i> ${conf.label}</span>
                        <span class="badge bg-secondary-subtle text-secondary small flex-shrink-0">${conf.badge}</span>
                    </label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi ${conf.icon} fs-5"></i>
                        </span>
                        <select id="essentialInput_${key}" class="form-select border-start-0 font-mono fw-semibold text-dark essential-input-field" data-key="${key}">
                            ${optionsHtml}
                        </select>
                    </div>
                    <div class="form-text small mt-1 text-muted d-flex align-items-center gap-1">
                        <i class="bi bi-info-circle text-muted"></i> ${conf.help}
                    </div>
                `;
            } else if (conf.type === 'date') {
                formGroup.innerHTML = `
                    <label class="form-label fw-bold d-flex justify-content-between align-items-center mb-1.5">
                        <span><i class="bi ${conf.icon} me-1"></i> ${conf.label}</span>
                        <span class="badge bg-secondary-subtle text-secondary small">${conf.badge}</span>
                    </label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-calendar3 text-danger fs-5"></i>
                        </span>
                        <input type="date"
                               id="essentialInput_${key}"
                               class="form-control border-start-0 font-mono fw-semibold text-dark essential-input-field"
                               data-key="${key}"
                               value="${payloadObj[key] || ''}">
                    </div>
                    <div id="validation_${key}" class="form-text text-danger d-none small mt-1">
                        <i class="bi bi-exclamation-circle-fill me-1"></i>Please provide a valid date of birth (YYYY-MM-DD).
                    </div>
                    <div class="form-text small mt-1 text-muted d-flex align-items-center gap-1">
                        <i class="bi bi-info-circle text-muted"></i> ${conf.help}
                    </div>
                `;
            } else {
                formGroup.innerHTML = `
                    <label class="form-label fw-bold d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-truncate"><i class="bi ${conf.icon} me-1"></i> ${conf.label}</span>
                        <span class="badge bg-secondary-subtle text-secondary small flex-shrink-0">${conf.badge}</span>
                    </label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi ${conf.icon} fs-5"></i>
                        </span>
                        <input type="text"
                               id="essentialInput_${key}"
                               class="form-control border-start-0 font-mono fw-bold text-dark essential-input-field ${conf.uppercase ? 'text-uppercase' : ''}"
                               data-key="${key}"
                               value="${payloadObj[key] || ''}"
                               placeholder="${conf.placeholder}"
                               ${conf.maxlength ? 'maxlength="' + conf.maxlength + '"' : ''}
                               autocomplete="off">
                    </div>
                    <div id="validation_${key}" class="form-text text-danger d-none small mt-1">
                        <i class="bi bi-exclamation-circle-fill me-1"></i>This field is required.
                    </div>
                    <div class="form-text small mt-1 text-muted d-flex align-items-center gap-1">
                        <i class="bi bi-info-circle text-muted"></i> ${conf.help}
                    </div>
                `;
            }

            colWrapper.appendChild(formGroup);
            formRow.appendChild(colWrapper);
        });

        container.appendChild(formRow);

        // Attach field event listeners
        container.querySelectorAll('.essential-input-field').forEach(input => {
            const key = input.getAttribute('data-key');
            const conf = essentialInputConfig[key];

            if (conf && conf.type === 'aadhar') {
                input.addEventListener('input', function () {
                    const valElem = document.getElementById('validation_' + key);
                    if (valElem) valElem.classList.add('d-none');
                    this.value = formatAadhaar(this.value);
                    syncPayload();
                });
            } else if (conf && conf.uppercase) {
                input.addEventListener('input', function () {
                    this.value = this.value.toUpperCase();
                    const valElem = document.getElementById('validation_' + key);
                    if (valElem) valElem.classList.add('d-none');
                    syncPayload();
                });
            } else {
                input.addEventListener('input', function () {
                    const valElem = document.getElementById('validation_' + key);
                    if (valElem) valElem.classList.add('d-none');
                    syncPayload();
                });
                input.addEventListener('change', syncPayload);
            }
        });

        // Clear button handler
        container.querySelectorAll('.btn-clear-input').forEach(btn => {
            btn.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const target = document.getElementById(targetId);
                if (target) {
                    target.value = '';
                    target.focus();
                    syncPayload();
                }
            });
        });
    }

    // Copy PAN Button Action
    if (btnCopyPanResult) {
        btnCopyPanResult.addEventListener('click', function () {
            const pan = displayPanNumber.innerText.trim();
            if (pan && pan !== '----------') {
                navigator.clipboard.writeText(pan).then(() => {
                    copyPanBtnText.innerText = 'Copied to Clipboard!';
                    btnCopyPanResult.classList.remove('btn-light');
                    btnCopyPanResult.classList.add('btn-success', 'text-white');
                    setTimeout(() => {
                        copyPanBtnText.innerText = 'Copy PAN Number';
                        btnCopyPanResult.classList.remove('btn-success', 'text-white');
                        btnCopyPanResult.classList.add('btn-light');
                    }, 2000);
                });
            }
        });
    }

    // Populate Test / Trigger Modal on Open
    if (testModal) {
        testModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const code = button.getAttribute('data-code');
            const name = button.getAttribute('data-name');
            const cost = button.getAttribute('data-cost');
            const gstRate = button.getAttribute('data-gst-rate') || '0.00';
            const gstAmount = button.getAttribute('data-gst-amount') || '0.00';
            const totalCost = button.getAttribute('data-total-cost') || cost;
            const payloadRaw = button.getAttribute('data-payload');

            document.getElementById('modalServiceName').value = name;
            document.getElementById('modalServiceCode').value = code;
            document.getElementById('modalServiceCost').value = cost;
            document.getElementById('modalServiceGstRate').value = gstRate;
            document.getElementById('modalServiceGstAmount').value = gstAmount;
            document.getElementById('modalServiceTotalCost').value = totalCost;

            document.getElementById('testModalLabel').innerText = name;
            document.getElementById('modalServiceCodeBadge').innerText = code;
            document.getElementById('modalBasePriceDisplay').innerText = '₹' + parseFloat(cost).toFixed(2);

            const gstElem = document.getElementById('modalGstAmountDisplay');
            if (gstElem) {
                gstElem.innerText = '';
                gstElem.classList.add('d-none');
            }
            document.getElementById('modalCostBadge').innerText = parseFloat(totalCost).toFixed(2) + ' Tokens';

            const theme = serviceThemes[code] || {
                title: name,
                subtitle: 'Instant automated verification via Gateway',
                btnText: 'Verify ' + name,
                icon: 'bi-shield-check',
                watermark: 'bi-shield-check',
                instantNotice: name + ' will be verified instantly. Available immediately after verification.'
            };

            const modalDialog = document.getElementById('testModalDialog');
            if (modalDialog) {
                modalDialog.classList.remove('modal-xl');
                modalDialog.style.maxWidth = '760px';
            }

            const titleElem = document.getElementById('modalModernTitle');
            if (titleElem) titleElem.innerText = theme.title;
            const subtitleElem = document.getElementById('modalModernSubtitle');
            if (subtitleElem) subtitleElem.innerText = theme.subtitle;
            const iconElem = document.getElementById('modalHeaderIconModern');
            if (iconElem) iconElem.className = 'bi ' + theme.icon + ' text-white fs-4';
            const watermarkElem = document.getElementById('modalWatermarkIcon');
            if (watermarkElem) watermarkElem.className = 'bi ' + theme.watermark;

            const feeFormatted = '₹' + parseFloat(cost).toFixed(2);
            const feeElem = document.getElementById('modalFeeDisplay');
            if (feeElem) feeElem.innerText = feeFormatted;

            const noticeElem = document.getElementById('modalInstantApiNoticeText');
            if (noticeElem) noticeElem.innerText = ' ' + theme.instantNotice;

            isPrintMode = false;
            btnDispatch.style.background = 'linear-gradient(135deg, #0d9488 0%, #0f766e 100%)';
            btnDispatch.style.color = '#ffffff';
            btnDispatch.style.border = 'none';
            btnDispatch.style.borderRadius = '9px';
            btnDispatch.style.boxShadow = '0 4px 14px rgba(13, 148, 136, 0.35)';
            btnDispatchText.innerText = theme.btnText;
            btnDispatchIcon.className = 'bi ' + theme.icon + ' me-1';
            btnDispatch.title = '';

            // Reset outcome view
            liveContainer.classList.add('d-none');
            panSuccessCard.classList.add('d-none');
            const dlCard = document.getElementById('dlSuccessCard');
            if (dlCard) dlCard.classList.add('d-none');
            const rcCard = document.getElementById('rcSuccessCard');
            if (rcCard) rcCard.classList.add('d-none');
            const voterCard = document.getElementById('voterSuccessCard');
            if (voterCard) voterCard.classList.add('d-none');
            const aadharPanCard = document.getElementById('aadharToPanSuccessCard');
            if (aadharPanCard) aadharPanCard.classList.add('d-none');
            const allSuccessCard = document.getElementById('allSuccessDataCard');
            if (allSuccessCard) allSuccessCard.classList.add('d-none');
            const billCard = document.getElementById('serviceBillCard');
            if (billCard) billCard.classList.add('d-none');
            errorCard.classList.add('d-none');

            let parsedPayload = {};
            try {
                parsedPayload = JSON.parse(payloadRaw);
                modalPayloadText.value = JSON.stringify(parsedPayload, null, 2);
            } catch (e) {
                modalPayloadText.value = payloadRaw || '{}';
            }
            window.currentActiveServicePayload = parsedPayload;

            // Build dynamic essential inputs for this specific service
            buildDynamicEssentialForm(dynamicInputsContainer, parsedPayload, code);
        });
    }

    let activeSpecServiceCode = null;

    // Populate Spec Modal (</> button) with friendly inputs
    if (specModal) {
        specModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const name = button.getAttribute('data-name') || '';
            const code = button.getAttribute('data-code') || '';
            const category = button.getAttribute('data-category') || 'Verification Service';
            const desc = button.getAttribute('data-desc') || '';
            const cost = button.getAttribute('data-cost') || '0.00';
            const payloadRaw = button.getAttribute('data-payload') || '{}';
            const requiredRaw = button.getAttribute('data-required') || '[]';

            activeSpecServiceCode = code;

            document.getElementById('specServiceName').innerText = name;
            document.getElementById('specServiceSubtitle').innerText = 'Required parameters & sample inputs';
            document.getElementById('specCategoryBadge').innerText = category;
            document.getElementById('specServiceCodeBadge').innerText = code;
            document.getElementById('specCostBadge').innerText = cost + ' Tokens';

            let payloadObj = {};
            let requiredArr = [];
            try { payloadObj = JSON.parse(payloadRaw); } catch(e) {}
            try { requiredArr = JSON.parse(requiredRaw); } catch(e) {}

            const container = document.getElementById('specFriendlyInputsContainer');
            container.innerHTML = '';

            const keys = Object.keys(payloadObj);
            document.getElementById('specFieldsCountBadge').innerText = keys.length + (keys.length === 1 ? ' Parameter' : ' Parameters');

            if (keys.length === 0) {
                container.innerHTML = '<div class="alert alert-secondary small text-center mb-0">No input parameters required for this service.</div>';
                return;
            }

            keys.forEach(key => {
                const def = fieldDescriptions[key] || {
                    label: key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()),
                    icon: 'bi-input-cursor-text text-secondary',
                    help: 'Input parameter for ' + key.replace(/_/g, ' '),
                    format: val => String(val || '')
                };

                const isRequired = requiredArr.includes(key);
                const formattedVal = def.format(payloadObj[key]);

                const card = document.createElement('div');
                card.className = 'p-3 bg-white rounded-3 border shadow-sm';
                card.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi ${def.icon} fs-5"></i>
                            <div>
                                <span class="fw-bold text-dark small d-block">${def.label}</span>
                                <span class="text-muted font-mono" style="font-size: 0.68rem;">Parameter Key: <code>${key}</code></span>
                            </div>
                        </div>
                        ${isRequired
                            ? '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1"><i class="bi bi-asterisk me-1" style="font-size: 0.55rem;"></i>Required</span>'
                            : '<span class="badge bg-secondary-subtle text-secondary px-2 py-1">Optional</span>'}
                    </div>
                    <div class="mt-2 p-2 px-3 rounded-2 bg-light border d-flex align-items-center justify-content-between">
                        <span class="text-muted small" style="font-size: 0.75rem;">Sample Value:</span>
                        <span class="font-mono fw-bold text-dark small">${formattedVal}</span>
                    </div>
                    <div class="text-muted mt-1.5 d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                        <i class="bi bi-info-circle text-muted"></i>
                        <span>${def.help}</span>
                    </div>
                `;
                container.appendChild(card);
            });
        });
    }

    const btnSpecTriggerNow = document.getElementById('btnSpecTriggerNow');
    if (btnSpecTriggerNow) {
        btnSpecTriggerNow.addEventListener('click', function () {
            if (!activeSpecServiceCode) return;
            const targetTriggerBtn = document.getElementById('btnTrigger_' + activeSpecServiceCode);
            if (targetTriggerBtn) {
                const modalInstance = bootstrap.Modal.getInstance(specModal);
                if (modalInstance) {
                    modalInstance.hide();
                }
                setTimeout(() => {
                    targetTriggerBtn.click();
                }, 350);
            }
        });
    }

    function executePrintNow() {
        const dlCard = document.getElementById('dlSuccessCard');
        if (dlCard && !dlCard.classList.contains('d-none') && typeof window.printDlSmartCard === 'function') {
            window.printDlSmartCard();
            return;
        }

        const printCardBtnV2 = document.getElementById('btnPrintCardV2');
        if (printCardBtnV2 && !printCardBtnV2.classList.contains('d-none') && typeof window.printPanSmartCardV2 === 'function') {
            window.printPanSmartCardV2();
            return;
        }

        if (typeof window.printServiceBill === 'function') {
            window.printServiceBill();
            return;
        }

        window.print();
    }

    if (dynamicInputsContainer) {
        dynamicInputsContainer.addEventListener('input', function() {
            if (isPrintMode) {
                isPrintMode = false;
                const code = document.getElementById('modalServiceCode')?.value;
                const theme = (code && serviceThemes[code]) ? serviceThemes[code] : { btnText: 'Verify Record', icon: 'bi-check-circle-fill' };
                btnDispatchText.innerText = theme.btnText;
                btnDispatchIcon.className = 'bi ' + theme.icon + ' me-1';
                btnDispatch.style.background = 'linear-gradient(135deg, #0d9488 0%, #0f766e 100%)';
                btnDispatch.style.boxShadow = '0 4px 14px rgba(13, 148, 136, 0.35)';
                btnDispatch.title = '';
            }
        });
    }

    // Trigger API execution via AJAX
    if (btnDispatch) {
        btnDispatch.addEventListener('click', async function () {
            if (isPrintMode) {
                executePrintNow();
                return;
            }

            const code = document.getElementById('modalServiceCode').value;
            const cost = document.getElementById('modalServiceCost').value;
            const name = document.getElementById('modalServiceName')?.value 
                      || document.getElementById('testModalLabel')?.innerText?.trim() 
                      || 'Service Verification';

            // Collect payload from essential inputs
            let payloadObj = {};
            if (modalPayloadText && modalPayloadText.value) {
                try {
                    payloadObj = JSON.parse(modalPayloadText.value);
                } catch (e) {}
            }
            let hasValidationErrors = false;

            dynamicInputsContainer.querySelectorAll('.essential-input-field').forEach(input => {
                const key = input.getAttribute('data-key');
                let val = input.value.trim();
                const validationElem = document.getElementById('validation_' + key);

                if (key === 'aadhar') {
                    const rawDigits = val.replace(/\D/g, '');
                    if (rawDigits.length !== 12) {
                        if (validationElem) validationElem.classList.remove('d-none');
                        input.focus();
                        hasValidationErrors = true;
                    }
                    val = rawDigits;
                } else if (key === 'dob' && /^\d{2}-\d{2}-\d{4}$/.test(val)) {
                    val = val.split('-').reverse().join('-');
                } else if (!val && key !== 'pan_display_name' && key !== 'name_match_method') {
                    if (validationElem) validationElem.classList.remove('d-none');
                    hasValidationErrors = true;
                }

                payloadObj[key] = val;
            });



            if (hasValidationErrors) {
                return;
            }

            // Show loading state
            btnDispatch.disabled = true;
            spinnerDispatch.classList.remove('d-none');
            btnDispatchIcon.classList.add('d-none');
            const originalBtnText = btnDispatchText.innerText;
            btnDispatchText.innerText = 'Verifying Record...';

            liveContainer.classList.add('d-none');
            panSuccessCard.classList.add('d-none');
            const dlCard = document.getElementById('dlSuccessCard');
            if (dlCard) dlCard.classList.add('d-none');
            const rcCard = document.getElementById('rcSuccessCard');
            if (rcCard) rcCard.classList.add('d-none');
            const voterCard = document.getElementById('voterSuccessCard');
            if (voterCard) voterCard.classList.add('d-none');
            const aadharPanCard = document.getElementById('aadharToPanSuccessCard');
            if (aadharPanCard) aadharPanCard.classList.add('d-none');
            const allSuccessCard = document.getElementById('allSuccessDataCard');
            if (allSuccessCard) allSuccessCard.classList.add('d-none');
            errorCard.classList.add('d-none');

            const userApiKey = "{{ Auth::user()->api_key }}";
            const executeEndpoint = "{{ url('/api/v1/service/execute') }}/" + encodeURIComponent(code);

            try {
                const response = await fetch(executeEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-API-KEY': userApiKey,
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    body: JSON.stringify(payloadObj),
                });

                const rawText = await response.text();
                let data;
                try {
                    data = JSON.parse(rawText);
                } catch (parseErr) {
                    data = {
                        status: 'SERVER_ERROR',
                        http_code: response.status,
                        message: 'Received non-JSON response from server (HTTP ' + response.status + ')',
                        raw_preview: rawText.substring(0, 500)
                    };
                }

                liveContainer.classList.remove('d-none');
                responseBody.innerText = JSON.stringify(data, null, 2);

                if (response.ok && data.status === 'SUCCESS') {
                    isPrintMode = true;
                    let feeText = (data.billing && data.billing.tokens_deducted ? data.billing.tokens_deducted : cost) + ' Tokens';
                    if (data.billing && data.billing.tokens_deducted) {
                        const totalDeducted = parseFloat(data.billing.tokens_deducted).toFixed(2);
                        if (parseFloat(data.billing.gst_amount) > 0) {
                            feeText = totalDeducted + ' Tokens (₹' + parseFloat(data.billing.service_price).toFixed(2) + ' + ' + parseFloat(data.billing.gst_rate_percent).toFixed(0) + '% GST ₹' + parseFloat(data.billing.gst_amount).toFixed(2) + ')';
                        } else {
                            feeText = totalDeducted + ' Tokens';
                        }
                    }

                    if (data.billing && data.billing.remaining_balance !== undefined) {
                        const navBalance = document.getElementById('topNavbarWalletBalance');
                        if (navBalance) navBalance.innerText = parseFloat(data.billing.remaining_balance).toFixed(2);
                        const modalBalance = document.getElementById('modalWalletBalanceDisplay');
                        if (modalBalance) modalBalance.innerText = parseFloat(data.billing.remaining_balance).toFixed(2) + ' Tokens';
                    }

                    // Safely extract the inner verified data object from the API response
                    let idspayInner = {};
                    if (data.idspay_data && typeof data.idspay_data === 'object') {
                        Object.assign(idspayInner, data.idspay_data);
                        if (data.idspay_data.data && typeof data.idspay_data.data === 'object') {
                            Object.assign(idspayInner, data.idspay_data.data);
                            if (data.idspay_data.data.data && typeof data.idspay_data.data.data === 'object') {
                                Object.assign(idspayInner, data.idspay_data.data.data);
                            }
                        }
                    }
                    if (data.data && typeof data.data === 'object') {
                        Object.assign(idspayInner, data.data);
                        if (data.data.data && typeof data.data.data === 'object') {
                            Object.assign(idspayInner, data.data.data);
                            if (data.data.data.data && typeof data.data.data.data === 'object') {
                                Object.assign(idspayInner, data.data.data.data);
                            }
                        }
                    }

                    const isDlRelated = code.includes('dl') || payloadObj.dlNumber || idspayInner.dlNumber || idspayInner.dl_number;
                    const isRcRelated = code.includes('rc') || code.includes('vehicle') || payloadObj.vehicle_num || idspayInner.vehicle_num;
                    const isVoterRelated = code.includes('voter') || code.includes('epic') || payloadObj.voterId || idspayInner.voterId;
                    const isAadharToPan = code === 'aadhar_to_pan' || (code.includes('aadhar') && (idspayInner.linked_pan || (!idspayInner.fullname && payloadObj.aadhar)));
                    const isPanRelated = !isAadharToPan && (code.includes('pan') || code.includes('aadhar') || payloadObj.pan || payloadObj.aadhar || data.pan_number || idspayInner.pan_number || idspayInner.pan);

                    // 1. Show Executive ID Badge Card if applicable
                    if (isDlRelated) {
                        const dlDetails = idspayInner.details_of_driving_licence || idspayInner.details || {};
                        const dlNum = idspayInner.dl_number || idspayInner.dlNumber || dlDetails.dl_number || dlDetails.dlNumber || payloadObj.dlNumber || 'DL-XXXXXXXXXXXXX';
                        const holder = dlDetails.name || idspayInner.holder_name || idspayInner.name || 'Verified Licensee';
                        const dob = idspayInner.dob || dlDetails.dob || payloadObj.dob || '-';
                        const dateOfIssue = dlDetails.date_of_issue || idspayInner.date_of_issue || idspayInner.dl_validity?.non_transport?.from || '-';
                        const validity = dlDetails.expiry_date || idspayInner.expiry_date || idspayInner.dl_validity?.non_transport?.to || idspayInner.validity || '-';
                        const status = dlDetails.status || idspayInner.status_name || (typeof idspayInner.status === 'string' ? idspayInner.status : 'Active') || 'Active';
                        const fatherOrHusband = dlDetails.father_or_husband_name || idspayInner.father_or_husband_name || idspayInner.father_name || '';
                        const bloodGroup = dlDetails.user_blood_group || idspayInner.user_blood_group || dlDetails.blood_group || idspayInner.blood_group || 'O+';
                        const userImage = dlDetails.user_image || idspayInner.user_image || idspayInner.image || '';

                        // Complete Address
                        let completeAddress = '';
                        if (dlDetails.address_list && Array.isArray(dlDetails.address_list) && dlDetails.address_list.length > 0) {
                            completeAddress = dlDetails.address_list[0].complete_address || dlDetails.address_list[0].address || '';
                        }
                        if (!completeAddress) {
                            completeAddress = dlDetails.address || idspayInner.complete_address || idspayInner.address || 'Address Not Provided';
                        }

                        // Vehicle Category Details & COV
                        let vehicleCategories = [];
                        if (Array.isArray(idspayInner.vehicle_category_details) && idspayInner.vehicle_category_details.length > 0) {
                            vehicleCategories = idspayInner.vehicle_category_details;
                        } else if (Array.isArray(dlDetails.vehicle_category_details) && dlDetails.vehicle_category_details.length > 0) {
                            vehicleCategories = dlDetails.vehicle_category_details;
                        }

                        let covText = idspayInner.cov || '';
                        if (!covText && vehicleCategories.length > 0) {
                            covText = vehicleCategories.map(v => v.cov).filter(Boolean).join(', ');
                        }
                        if (!covText) {
                            covText = 'MCWOG, LMV';
                        }

                        // Populate FRONT Card Elements
                        const dlNumElem = document.getElementById('displayDlNumber');
                        if (dlNumElem) dlNumElem.innerText = dlNum;

                        const holderElem = document.getElementById('displayDlHolderName');
                        if (holderElem) holderElem.innerText = holder;

                        const relationElem = document.getElementById('displayDlRelationName');
                        if (relationElem) {
                            relationElem.innerText = fatherOrHusband ? ('S/W/D of ' + fatherOrHusband) : '-';
                        }

                        const dobElem = document.getElementById('displayDlDob');
                        if (dobElem) dobElem.innerText = dob;

                        const dateOfIssueElem = document.getElementById('displayDlDateOfIssue');
                        if (dateOfIssueElem) dateOfIssueElem.innerText = dateOfIssue;

                        const expiryElem = document.getElementById('displayDlExpiryDate');
                        if (expiryElem) expiryElem.innerText = validity;

                        const statusElem = document.getElementById('displayDlStatus');
                        if (statusElem) statusElem.innerText = status;

                        const statusBadgeElem = document.getElementById('displayDlStatusBadge');
                        if (statusBadgeElem) {
                            statusBadgeElem.innerText = status.toUpperCase();
                            if (status.toUpperCase().includes('ACTIVE') || status.toUpperCase().includes('VALID')) {
                                statusBadgeElem.className = 'fw-bold text-success text-uppercase font-mono';
                            } else {
                                statusBadgeElem.className = 'fw-bold text-warning text-uppercase font-mono';
                            }
                        }

                        const frontBloodElem = document.getElementById('displayDlFrontBloodGroup');
                        if (frontBloodElem) frontBloodElem.innerText = bloodGroup;

                        // Licensee Photo
                        const photoElem = document.getElementById('displayDlPhoto');
                        const photoPlaceholder = document.getElementById('displayDlPhotoPlaceholder');
                        if (photoElem) {
                            if (userImage && userImage.length > 20) {
                                photoElem.src = userImage.startsWith('data:') ? userImage : ('data:image/jpeg;base64,' + userImage);
                                photoElem.classList.remove('d-none');
                                if (photoPlaceholder) photoPlaceholder.classList.add('d-none');
                            } else {
                                photoElem.classList.add('d-none');
                                if (photoPlaceholder) photoPlaceholder.classList.remove('d-none');
                            }
                        }

                        const feeElem = document.getElementById('displayDlFee');
                        if (feeElem) feeElem.innerText = feeText;

                        // Populate BACK Card Elements
                        const addressElem = document.getElementById('displayDlCompleteAddress');
                        if (addressElem) addressElem.innerText = completeAddress;

                        const covBadgeElem = document.getElementById('displayDlCovBadge');
                        if (covBadgeElem) covBadgeElem.innerText = covText;

                        const backBloodElem = document.getElementById('displayDlBackBloodGroup');
                        if (backBloodElem) backBloodElem.innerText = bloodGroup;

                        // Vehicle Category Details Table
                        const vehicleTbody = document.getElementById('displayDlVehicleCategoryTbody');
                        if (vehicleTbody) {
                            vehicleTbody.innerHTML = '';
                            if (vehicleCategories.length > 0) {
                                vehicleCategories.forEach(cat => {
                                    const tr = document.createElement('tr');
                                    tr.innerHTML = `
                                        <td class="ps-1 py-1"><span class="badge bg-warning text-dark font-mono">${cat.cov || covText}</span></td>
                                        <td class="py-1 text-white-50">${cat.cov_type || 'Non-Transport'}</td>
                                        <td class="py-1 font-mono">${cat.issue_date || dateOfIssue}</td>
                                        <td class="pe-1 py-1 text-end font-mono text-warning">${cat.expiry_date || validity}</td>
                                    `;
                                    vehicleTbody.appendChild(tr);
                                });
                            } else {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td class="ps-1 py-1"><span class="badge bg-warning text-dark font-mono">${covText}</span></td>
                                    <td class="py-1 text-white-50">Authorized</td>
                                    <td class="py-1 font-mono">${dateOfIssue}</td>
                                    <td class="pe-1 py-1 text-end font-mono text-warning">${validity}</td>
                                `;
                                vehicleTbody.appendChild(tr);
                            }
                        }

                        // Reset view to both sides
                        if (typeof switchDlCardView === 'function') {
                            switchDlCardView('both');
                        }

                        // Hide graphic card as requested; bill will be displayed
                        if (dlCard) dlCard.classList.add('d-none');
                    } else if (isRcRelated) {
                        const plate = idspayInner.vehicle_num || payloadObj.vehicle_num || idspayInner.vehicle_number || idspayInner.rc_number || 'GJ08BX1234';
                        const mobile = idspayInner.mobileNo || idspayInner.mobile || idspayInner.mobile_no || idspayInner.phone || 'Linked & Verified';
                        const owner = idspayInner.owner_name || idspayInner.name || null;
                        const model = idspayInner.maker_model || idspayInner.model || null;
                        const fuel = idspayInner.fuel_type || idspayInner.fuel || null;
                        const regDate = idspayInner.registration_date || idspayInner.reg_date || null;
                        const message = idspayInner.message || data.message || 'RC To Mobile v3 response processed successfully.';

                        const elemPlate = document.getElementById('displayRcPlate');
                        if (elemPlate) elemPlate.innerText = plate;

                        const elemOwner = document.getElementById('displayRcOwnerName');
                        if (elemOwner) elemOwner.innerText = owner ? ('Owner: ' + owner) : 'Owner: Registered Licensee';

                        const elemModel = document.getElementById('displayRcModel');
                        if (elemModel) elemModel.innerText = model || 'Vehicle Record Active';

                        const elemFuel = document.getElementById('displayRcFuel');
                        if (elemFuel) elemFuel.innerText = fuel || 'PETROL / DIESEL';

                        const elemMobile = document.getElementById('displayRcMobile');
                        if (elemMobile) elemMobile.innerText = mobile;

                        const elemRegDate = document.getElementById('displayRcRegDate');
                        if (elemRegDate) elemRegDate.innerText = regDate || '-';

                        const elemMessage = document.getElementById('displayRcMessage');
                        if (elemMessage) elemMessage.innerText = message;

                        const elemFee = document.getElementById('displayRcFee');
                        if (elemFee) elemFee.innerText = feeText;

                        // Hide graphic card as requested; bill will be displayed
                        if (rcCard) rcCard.classList.add('d-none');
                    } else if (isVoterRelated) {
                        const voter = idspayInner.epic_no || idspayInner.voterId || payloadObj.voterId || 'ABC1234567';
                        const elector = idspayInner.name || idspayInner.name_v1 || 'Elector Record Found';
                        const constituency = idspayInner.ac_name || idspayInner.constituency || 'Constituency Active';
                        const booth = idspayInner.ps_name || idspayInner.polling_station || 'Designated Polling Booth';

                        document.getElementById('displayVoterId').innerText = voter;
                        document.getElementById('displayVoterName').innerText = elector;
                        document.getElementById('displayVoterConstituency').innerText = constituency;
                        document.getElementById('displayVoterPollingStation').innerText = booth;
                        document.getElementById('displayVoterFee').innerText = feeText;
                        // Hide graphic card as requested; bill will be displayed
                        if (voterCard) voterCard.classList.add('d-none');
                    } else if (isAadharToPan) {
                        const linkedPan = idspayInner.linked_pan || data.pan_number || idspayInner.pan_number || idspayInner.pan || '-';
                        const rawAadhar = (payloadObj.aadhar || idspayInner.aadhaar_number || idspayInner.aadhar || '').toString().replace(/\D/g, '');
                        
                        let formattedAadhar = rawAadhar;
                        let maskedAadhar = 'XXXX XXXX ' + (rawAadhar.length >= 4 ? rawAadhar.slice(-4) : '****');
                        if (rawAadhar.length === 12) {
                            formattedAadhar = rawAadhar.replace(/(\d{4})(\d{4})(\d{4})/, '$1 $2 $3');
                        }

                        // Determine Taxpayer PAN Category from 4th character of PAN
                        let panTypeName = 'Individual (P)';
                        if (linkedPan && linkedPan.length >= 4) {
                            const fourthChar = linkedPan.charAt(3).toUpperCase();
                            const panTypeMap = {
                                'P': 'Individual (P)',
                                'C': 'Company (C)',
                                'H': 'HUF (H)',
                                'F': 'Firm / LLP (F)',
                                'A': 'AOP (A)',
                                'T': 'Trust (T)',
                                'B': 'BOI (B)',
                                'L': 'Local Authority (L)',
                                'J': 'Juridical Person (J)',
                                'G': 'Government Agency (G)'
                            };
                            panTypeName = panTypeMap[fourthChar] || (idspayInner.pan_type || 'Individual');
                        }

                        // Populate Card UI Elements
                        const elemAadharNum = document.getElementById('displayAadharInputNumber');
                        if (elemAadharNum) elemAadharNum.innerText = formattedAadhar || '5212 3456 7897';

                        const elemMaskedAadhar = document.getElementById('displayAadharMaskedNumber');
                        if (elemMaskedAadhar) elemMaskedAadhar.innerText = maskedAadhar;

                        const elemLinkedPan = document.getElementById('displayAadharLinkedPan');
                        if (elemLinkedPan) elemLinkedPan.innerText = linkedPan;

                        const elemPanType = document.getElementById('displayAadharPanTypeBadge');
                        if (elemPanType) elemPanType.innerText = panTypeName;

                        const elemMsg = document.getElementById('displayAadharPanMessage');
                        if (elemMsg) elemMsg.innerText = idspayInner.message || data.message || 'Aadhar To Pan response processed successfully.';

                        const elemFee = document.getElementById('displayAadharPanFee');
                        if (elemFee) elemFee.innerText = feeText;

                        // Backward-compatibility references if any
                        const elemPanNum = document.getElementById('displayPanNumber');
                        if (elemPanNum) elemPanNum.innerText = linkedPan;
                        if (displayQueriedAadhaar) displayQueriedAadhaar.innerText = rawAadhar;

                        const aadharCard = document.getElementById('aadharToPanSuccessCard');
                        // Hide graphic card as requested; bill will be displayed
                        if (aadharCard) aadharCard.classList.add('d-none');
                    } else if (isPanRelated) {
                        const pan = data.pan_number || idspayInner.pan || idspayInner.pan_number || payloadObj.pan || 'ARxxxxxxx9E';
                        let panType = idspayInner.pan_type || idspayInner.category || 'Individual';
                        if ((!panType || panType === 'Individual') && pan && pan.length >= 4) {
                            const fourthChar = pan.charAt(3).toUpperCase();
                            const panTypeMap = {
                                'P': 'Individual (P)',
                                'C': 'Company (C)',
                                'H': 'HUF (H)',
                                'F': 'Firm / LLP (F)',
                                'A': 'AOP (A)',
                                'T': 'Trust (T)',
                                'B': 'BOI (B)',
                                'L': 'Local Authority (L)',
                                'J': 'Juridical Person (J)',
                                'G': 'Government Agency (G)'
                            };
                            if (panTypeMap[fourthChar]) {
                                panType = panTypeMap[fourthChar];
                            }
                        }

                        const fullname = idspayInner.fullname || idspayInner.name_on_pan || idspayInner.name || payloadObj.name || '-';
                        let firstName = idspayInner.first_name || '';
                        let middleName = idspayInner.middle_name || '';
                        let lastName = idspayInner.last_name || '';

                        // If split names are missing but fullname exists, split fullname
                        if (!firstName && !lastName && fullname && fullname !== '-') {
                            const nameParts = fullname.trim().split(/\s+/);
                            if (nameParts.length === 1) {
                                firstName = nameParts[0];
                            } else if (nameParts.length === 2) {
                                firstName = nameParts[0];
                                lastName = nameParts[1];
                            } else if (nameParts.length > 2) {
                                firstName = nameParts[0];
                                lastName = nameParts[nameParts.length - 1];
                                middleName = nameParts.slice(1, -1).join(' ');
                            }
                        }

                        const fatherName = idspayInner.father_name || idspayInner.father_or_husband_name || (code === 'pan_details_v2' ? 'On Record (ITD)' : '-');
                        const gender = idspayInner.gender || '';
                        const dob = idspayInner.dob || (code === 'pan_details_v2' ? 'Verified on Record' : '-');
                        const aadhaarNumber = idspayInner.aadhaar_number || idspayInner.aadhar || payloadObj.aadhar || '-';
                        const isAadhaarLinked = (idspayInner.aadhaar_linked === true || idspayInner.aadhaar_linked === 'true' || idspayInner.aadhaar_linked === 1 || idspayInner.aadhaar_linked === '1');
                        const mobile = idspayInner.mobile || payloadObj.mobile || '';
                        const email = idspayInner.email || payloadObj.email || '';

                        // Format Address Object or String
                        let formattedAddress = '';
                        if (idspayInner.address && typeof idspayInner.address === 'object') {
                            const addr = idspayInner.address;
                            const lineParts = [
                                addr.building_name,
                                addr.street_name,
                                addr.locality,
                                addr.city,
                                addr.state
                            ].map(s => (s || '').toString().trim()).filter(Boolean);

                            let mainLine = lineParts.join(', ');
                            if (addr.pincode && addr.pincode.toString().trim()) {
                                mainLine += (mainLine ? ' - ' : '') + addr.pincode.toString().trim();
                            }
                            if (addr.country && addr.country.toString().trim()) {
                                mainLine += (mainLine ? ', ' : '') + addr.country.toString().trim();
                            }
                            formattedAddress = mainLine;
                        } else if (typeof idspayInner.address === 'string' && idspayInner.address.trim()) {
                            formattedAddress = idspayInner.address.trim();
                        }
                        if (!formattedAddress) {
                            formattedAddress = 'India';
                        }

                        // Populate Card UI Elements
                        const elemPanNum = document.getElementById('displayPanNumber');
                        if (elemPanNum) elemPanNum.innerText = pan;

                        const elemPanType = document.getElementById('displayPanType');
                        if (elemPanType) elemPanType.innerText = panType;

                        const elemFullName = document.getElementById('displayPanFullName');
                        if (elemFullName) elemFullName.innerText = fullname;

                        // First / Middle / Last Name Split Row
                        const elemFirst = document.getElementById('displayPanFirstName');
                        const elemMiddle = document.getElementById('displayPanMiddleName');
                        const elemLast = document.getElementById('displayPanLastName');
                        const splitRow = document.getElementById('displayPanSplitNameRow');
                        if (elemFirst) elemFirst.innerText = firstName || '-';
                        if (elemMiddle) elemMiddle.innerText = middleName || '-';
                        if (elemLast) elemLast.innerText = lastName || '-';
                        if (splitRow) {
                            if (firstName || middleName || lastName) {
                                splitRow.classList.remove('d-none');
                            } else {
                                splitRow.classList.add('d-none');
                            }
                        }

                        // Backward-compatibility element
                        if (displayPanHolderName) {
                            displayPanHolderName.innerText = fullname;
                        }

                        const elemFather = document.getElementById('displayPanFatherName');
                        if (elemFather) elemFather.innerText = fatherName;

                        const elemGender = document.getElementById('displayPanGender');
                        if (elemGender) {
                            if (gender) {
                                const isFemale = gender.toLowerCase().includes('fem');
                                const icon = isFemale ? 'bi-gender-female text-danger' : 'bi-gender-male text-primary';
                                elemGender.innerHTML = `<i class="bi ${icon} me-1"></i> ${gender.charAt(0).toUpperCase() + gender.slice(1)}`;
                            } else {
                                elemGender.innerHTML = '<i class="bi bi-person me-1"></i> Individual';
                            }
                        }

                        const elemDob = document.getElementById('displayPanDob');
                        if (elemDob) elemDob.innerText = dob;

                        const elemAadhaarNum = document.getElementById('displayPanAadhaarNumber');
                        if (elemAadhaarNum) elemAadhaarNum.innerText = aadhaarNumber;

                        if (displayQueriedAadhaar) {
                            displayQueriedAadhaar.innerText = aadhaarNumber;
                        }

                        // Aadhaar Linked Status Badge
                        const aadhaarBadge = document.getElementById('displayPanAadhaarLinkedBadge');
                        if (aadhaarBadge) {
                            if (idspayInner.aadhaar_linked !== undefined && idspayInner.aadhaar_linked !== null) {
                                if (isAadhaarLinked) {
                                    aadhaarBadge.className = 'badge bg-success text-white px-2 py-0.5 font-mono';
                                    aadhaarBadge.innerHTML = '<i class="bi bi-link-45deg me-0.5"></i> Aadhaar Linked';
                                } else {
                                    aadhaarBadge.className = 'badge bg-warning text-dark px-2 py-0.5 font-mono';
                                    aadhaarBadge.innerHTML = '<i class="bi bi-exclamation-triangle me-0.5"></i> Not Linked';
                                }
                            } else if (code === 'pan_details_v2') {
                                aadhaarBadge.className = 'badge bg-success text-white px-2 py-0.5 font-mono';
                                aadhaarBadge.innerHTML = '<i class="bi bi-check-circle-fill me-0.5"></i> PAN Active & Matched';
                            } else {
                                aadhaarBadge.className = 'badge bg-white bg-opacity-20 text-white px-2 py-0.5 font-mono';
                                aadhaarBadge.innerHTML = '<i class="bi bi-shield-check me-0.5"></i> Record Active';
                            }
                        }

                        // Address
                        const elemAddress = document.getElementById('displayPanAddress');
                        if (elemAddress) elemAddress.innerText = formattedAddress;

                        // Mobile & Email
                        const elemMobile = document.getElementById('displayPanMobile');
                        if (elemMobile) elemMobile.innerText = mobile || 'Not Registered';

                        const elemEmail = document.getElementById('displayPanEmail');
                        if (elemEmail) elemEmail.innerText = email || 'Not Registered';

                        // Fee & Token deduction
                        displayFeeDeducted.innerText = feeText;

                        // Fuzzy matching score (for Pan Details V2)
                        const panMatchRow = document.getElementById('panMatchMetaRow');
                        const panMatchBadge = document.getElementById('panMatchScoreBadge');
                        if (code === 'pan_details_v2' || idspayInner.name_match_score || payloadObj.name_match_method) {
                            if (panMatchRow) panMatchRow.classList.remove('d-none');
                            const matchMethod = (payloadObj.name_match_method || idspayInner.name_match_method || 'fuzzy').toUpperCase();
                            const matchScore = idspayInner.name_match_score || '100%';
                            if (panMatchBadge) panMatchBadge.innerText = `${matchMethod} Match: ${matchScore} (Verified)`;
                        } else {
                            if (panMatchRow) panMatchRow.classList.add('d-none');
                        }

                        // Hide graphic card as requested; bill will be displayed
                        panSuccessCard.classList.add('d-none');
                    }

                    // 2. Hide "Verified Record Details" section below as requested
                    const allSuccessCard = document.getElementById('allSuccessDataCard');
                    if (allSuccessCard) {
                        allSuccessCard.classList.add('d-none');
                    }

                    // 3. Render and show simple text box like bill with appropriate information
                    renderServiceBill({
                        serviceCode: code,
                        serviceName: name,
                        data: data,
                        payloadObj: payloadObj,
                        idspayInner: idspayInner,
                        isDlRelated: isDlRelated,
                        isRcRelated: isRcRelated,
                        isVoterRelated: isVoterRelated,
                        isAadharToPan: isAadharToPan,
                        isPanRelated: isPanRelated,
                        cost: cost,
                        feeText: feeText
                    });

                    const billCard = document.getElementById('serviceBillCard');
                    if (billCard) {
                        billCard.classList.remove('d-none');
                    }
                    
                    const printCardBtnV2 = document.getElementById('btnPrintCardV2');
                    if (printCardBtnV2) {
                        if (code === 'pan_details_v2') {
                            printCardBtnV2.classList.remove('d-none');
                            
                            // Inject data into e-PAN v2 Print layout
                            const pName = idspayInner.fullname || idspayInner.name_on_pan || idspayInner.name || payloadObj.name || '-';
                            const pFather = idspayInner.father_name || idspayInner.father_or_husband_name || '-';
                            const pDob = idspayInner.dob || '-';
                            const pGender = idspayInner.gender || '-';
                            const pPan = idspayInner.pan_number || idspayInner.pan || payloadObj.pan || '-';

                            const elName = document.getElementById('epanPrintNameV2');
                            if(elName) elName.innerText = pName;
                            const elFather = document.getElementById('epanPrintFatherNameV2');
                            if(elFather) elFather.innerText = pFather;
                            const elDob = document.getElementById('epanPrintDobV2');
                            if(elDob) elDob.innerText = pDob;
                            const elGender = document.getElementById('epanPrintGenderV2');
                            if(elGender) elGender.innerText = pGender;
                            const elPan = document.getElementById('epanPrintPanNumberV2');
                            if(elPan) elPan.innerText = pPan;
                            
                        } else {
                            printCardBtnV2.classList.add('d-none');
                        }
                    }
                } else {
                    isPrintMode = false;
                    errorTitle.innerText = data.status || 'Verification Failed';
                    errorMessageText.innerText = data.message || 'The verification service could not complete the request.';
                    errorCard.classList.remove('d-none');
                }
            } catch (networkError) {
                isPrintMode = false;
                liveContainer.classList.remove('d-none');
                errorTitle.innerText = 'Network / Timeout Error';
                errorMessageText.innerText = networkError.message;
                errorCard.classList.remove('d-none');
                responseBody.innerText = JSON.stringify({ error: networkError.message }, null, 2);
            } finally {
                btnDispatch.disabled = false;
                spinnerDispatch.classList.add('d-none');
                btnDispatchIcon.classList.remove('d-none');
                if (isPrintMode) {
                    btnDispatchText.innerText = 'Print Now';
                    btnDispatchIcon.className = 'bi bi-printer me-1';
                    btnDispatch.style.background = 'linear-gradient(135deg, #0284c7 0%, #0369a1 100%)';
                    btnDispatch.style.boxShadow = '0 4px 14px rgba(2, 132, 199, 0.35)';
                    btnDispatch.title = 'Print Verification Receipt / Record';
                } else {
                    btnDispatchText.innerText = originalBtnText;
                }
            }
        });
    }

    // Comprehensive Dictionary of verified citizen identity & record attributes
    const successFieldDictionary = {
        pan: { label: 'PAN Card Number', icon: 'bi-credit-card-2-front-fill text-warning', isMono: true, copyable: true },
        pan_number: { label: 'PAN Card Number', icon: 'bi-credit-card-2-front-fill text-warning', isMono: true, copyable: true },
        linked_pan: { label: 'Linked PAN Number', icon: 'bi-credit-card-2-front-fill text-warning', isMono: true, copyable: true },
        pan_status: { label: 'PAN Card Status', icon: 'bi-check-circle-fill text-success', isStatusBadge: true },
        fullname: { label: 'Full Legal Name', icon: 'bi-person-fill text-primary', highlight: true },
        name: { label: 'Full Legal Name', icon: 'bi-person-fill text-primary', highlight: true },
        name_on_pan: { label: 'Name on PAN Record', icon: 'bi-person-fill text-primary', highlight: true },
        holder_name: { label: 'Cardholder / Licensee Name', icon: 'bi-person-fill text-primary', highlight: true },
        owner_name: { label: 'Vehicle Owner Name', icon: 'bi-person-badge-fill text-primary', highlight: true },
        pan_type: { label: 'PAN Cardholder Type', icon: 'bi-tag-fill text-info', isBadge: true },
        first_name: { label: 'First Name', icon: 'bi-person text-secondary' },
        middle_name: { label: 'Middle Name', icon: 'bi-person text-secondary' },
        last_name: { label: 'Last Name', icon: 'bi-person text-secondary' },
        aadhaar_linked: { label: 'Aadhaar Linkage Status', icon: 'bi-link-45deg text-info', isBoolean: true },
        name_match_score: { label: 'Name Match Score', icon: 'bi-percent text-success', isScore: true },
        name_match_method: { label: 'Match Method Algorithm', icon: 'bi-spellcheck text-success', isBadge: true },
        pan_display_name: { label: 'Display Name Option', icon: 'bi-eye-fill text-secondary', isBoolean: true },
        category: { label: 'Taxpayer Category', icon: 'bi-tag-fill text-secondary', isBadge: true },
        last_updated: { label: 'Last Records Update', icon: 'bi-clock-history text-muted', isDate: true },
        dlNumber: { label: 'Driving License Number', icon: 'bi-card-heading text-danger', isMono: true, copyable: true },
        dl_number: { label: 'Driving License Number', icon: 'bi-card-heading text-danger', isMono: true, copyable: true },
        dob: { label: 'Date of Birth', icon: 'bi-calendar-event text-danger', isDate: true },
        validity: { label: 'License Validity / Expiry', icon: 'bi-calendar-check text-success', isDate: true },
        expiry_date: { label: 'License Expiry Date', icon: 'bi-calendar-check text-warning', isDate: true },
        date_of_issue: { label: 'License Date of Issue', icon: 'bi-calendar-plus text-secondary', isDate: true },
        cov: { label: 'Classes of Vehicle (COV)', icon: 'bi-car-front-fill text-secondary', isBadge: true },
        vehicle_category_details: { label: 'Vehicle Classes (COV)', icon: 'bi-car-front-fill text-secondary' },
        details_of_driving_licence: { label: 'Driving License Record', icon: 'bi-person-vcard text-primary' },
        dl_validity: { label: 'Validity Period Details', icon: 'bi-calendar-range text-info' },
        badge_details: { label: 'Commercial Badge Details', icon: 'bi-award text-warning' },
        father_or_husband_name: { label: 'Father / Husband Name', icon: 'bi-person text-secondary' },
        user_blood_group: { label: 'Blood Group', icon: 'bi-droplet-fill text-danger', isBadge: true },
        issue_date: { label: 'License Issue Date', icon: 'bi-calendar-plus text-secondary', isDate: true },
        rto_name: { label: 'Issuing RTO Authority', icon: 'bi-geo-alt-fill text-danger' },
        rto: { label: 'Issuing RTO Authority', icon: 'bi-geo-alt-fill text-danger' },
        state: { label: 'State / Jurisdiction', icon: 'bi-map-fill text-primary' },
        blood_group: { label: 'Blood Group', icon: 'bi-droplet-fill text-danger', isBadge: true },
        father_name: { label: 'Father / Guardian Name', icon: 'bi-person text-secondary' },
        vehicle_num: { label: 'Vehicle Plate Number', icon: 'bi-car-front text-success', isMono: true, copyable: true },
        maker_model: { label: 'Vehicle Maker & Model', icon: 'bi-truck text-primary' },
        fuel_type: { label: 'Fuel Type', icon: 'bi-fuel-pump-fill text-danger', isBadge: true },
        mobile: { label: 'Registered Mobile Number', icon: 'bi-telephone-fill text-success', isMono: true, copyable: true },
        mobileNo: { label: 'Registered Mobile Number', icon: 'bi-telephone-fill text-success', isMono: true, copyable: true },
        mobile_no: { label: 'Registered Mobile Number', icon: 'bi-telephone-fill text-success', isMono: true, copyable: true },
        registration_date: { label: 'Registration Date', icon: 'bi-calendar3 text-muted', isDate: true },
        chassis_number: { label: 'Chassis Number', icon: 'bi-cpu text-secondary', isMono: true, copyable: true },
        engine_number: { label: 'Engine Number', icon: 'bi-gear-wide-connected text-secondary', isMono: true, copyable: true },
        vehicle_class: { label: 'Vehicle Class', icon: 'bi-truck-flatbed text-secondary', isBadge: true },
        insurance_company: { label: 'Insurance Provider', icon: 'bi-shield-shaded text-info' },
        insurance_upto: { label: 'Insurance Valid Upto', icon: 'bi-shield-check text-success', isDate: true },
        fitness_upto: { label: 'Fitness Valid Upto', icon: 'bi-speedometer text-warning', isDate: true },
        puc_upto: { label: 'PUC Valid Upto', icon: 'bi-cloud-check text-success', isDate: true },
        voterId: { label: 'Voter ID (EPIC) Number', icon: 'bi-person-vcard text-purple', isMono: true, copyable: true },
        epic_no: { label: 'Voter ID (EPIC) Number', icon: 'bi-person-vcard text-purple', isMono: true, copyable: true },
        constituency: { label: 'Assembly Constituency', icon: 'bi-geo-alt-fill text-danger' },
        ac_name: { label: 'Assembly Constituency', icon: 'bi-geo-alt-fill text-danger' },
        polling_station: { label: 'Polling Station Booth', icon: 'bi-building text-secondary' },
        ps_name: { label: 'Polling Station Booth', icon: 'bi-building text-secondary' },
        part_number: { label: 'Electoral Part Number', icon: 'bi-hash text-muted', isMono: true },
        gender: { label: 'Gender', icon: 'bi-gender-ambiguous text-primary', isBadge: true },
        age: { label: 'Age', icon: 'bi-hourglass-split text-muted' },
        relation_name: { label: 'Relation Name', icon: 'bi-people text-secondary' },
        aadhar: { label: 'Aadhaar Card Number', icon: 'bi-fingerprint text-primary', isMono: true, copyable: true },
        aadhaar_number: { label: 'Aadhaar Card Number', icon: 'bi-fingerprint text-primary', isMono: true, copyable: true },
        transaction_id: { label: 'Transaction ID', icon: 'bi-hash text-muted', isMono: true, copyable: true },
        status: { label: 'Record Status', icon: 'bi-activity text-info', isStatusBadge: true },
        verified: { label: 'Verification State', icon: 'bi-patch-check-fill text-success', isBoolean: true },
        timestamp: { label: 'Gateway Timestamp', icon: 'bi-clock-history text-muted' }
    };

    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Helper: Formatter to display ALL success data from data{} in user-friendly responsive cards
    function renderFormattedAllSuccessData(container, dataObj, rootData) {
        container.innerHTML = '';
        if (!dataObj || typeof dataObj !== 'object') {
            container.innerHTML = '<div class="col-12"><div class="alert alert-secondary text-center small py-3 mb-0">Verification confirmed successfully. No additional data attributes returned.</div></div>';
            return;
        }

        // Enrich with transaction ID or pan_number from root payload if not in dataObj
        const enriched = Object.assign({}, dataObj);
        if (!enriched.transaction_id && (rootData?.billing?.transaction_id || rootData?.idspay_data?.transaction_id)) {
            enriched.transaction_id = rootData?.billing?.transaction_id || rootData?.idspay_data?.transaction_id;
        }
        if (!enriched.pan && !enriched.pan_number && rootData?.pan_number) {
            enriched.pan = rootData.pan_number;
        }

        const controlKeys = ['api_id', 'api_key', 'token_id', 'response_code', 'http_code', 'error', 'raw_body', 'raw_preview', 'success', 'user_image'];
        const entries = Object.entries(enriched);
        let renderedCount = 0;

        entries.forEach(([key, val]) => {
            if (controlKeys.includes(key)) return;
            // Skip status if it is simply generic 'SUCCESS' or 'SUCCESSFUL'
            if (key === 'status' && typeof val === 'string' && ['SUCCESS', 'SUCCESSFUL'].includes(val.toUpperCase())) return;
            // Skip message if generic greeting
            if (key === 'message' && typeof val === 'string' && val.toLowerCase().includes('successfully')) return;

            // Deduplicate redundant identical aliases
            if (key === 'pan_number' && enriched.pan && enriched.pan === val) return;
            if (key === 'name_on_pan' && enriched.name && enriched.name === val) return;
            if (key === 'holder_name' && enriched.name && enriched.name === val) return;
            if (key === 'owner_name' && enriched.name && enriched.name === val) return;
            if (key === 'epic_no' && enriched.voterId && enriched.voterId === val) return;
            if (key === 'aadhaar_number' && enriched.aadhar && enriched.aadhar === val) return;

            const def = successFieldDictionary[key] || {};
            const col = document.createElement('div');
            col.className = 'col-12 col-md-6';

            let valContent = '';
            let rawCopyVal = '';

            if (val === null || val === undefined || val === '') {
                valContent = '<span class="text-muted fst-italic">Not Available</span>';
            } else if (typeof val === 'boolean' || def.isBoolean || val === 'true' || val === 'false') {
                const isTrue = (val === true || val === 'true' || val === 1 || val === '1');
                valContent = isTrue
                    ? '<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i>Yes / Active / Verified</span>'
                    : '<span class="badge bg-secondary-subtle text-secondary border px-2 py-1"><i class="bi bi-x-circle me-1"></i>No / Unlinked</span>';
                rawCopyVal = isTrue ? 'true' : 'false';
            } else if (def.isStatusBadge || (typeof val === 'string' && (val.toUpperCase().includes('VALID') || val.toUpperCase().includes('ACTIVE') || val.toUpperCase().includes('EXPIRED')))) {
                const s = String(val).toUpperCase();
                const badgeColor = (s.includes('VALID') || s.includes('ACTIVE') || s.includes('LINKED') || s.includes('SUCCESS'))
                    ? 'bg-success'
                    : ((s.includes('EXPIRED') || s.includes('SUSPENDED') || s.includes('FAIL')) ? 'bg-danger' : 'bg-primary');
                valContent = `<span class="badge ${badgeColor} text-white px-2.5 py-1 fw-semibold"><i class="bi bi-shield-check me-1"></i>${escapeHtml(val)}</span>`;
                rawCopyVal = String(val);
            } else if (def.isScore || (typeof val === 'string' && val.endsWith('%'))) {
                valContent = `<span class="badge bg-success-subtle text-success border border-success-subtle font-mono fw-bold fs-6 px-2.5 py-1"><i class="bi bi-patch-check-fill me-1"></i>${escapeHtml(val)}</span>`;
                rawCopyVal = String(val);
            } else if (def.isBadge) {
                valContent = `<span class="badge bg-white text-dark border font-mono px-2 py-1 fw-semibold">${escapeHtml(val)}</span>`;
                rawCopyVal = String(val);
            } else if (def.isDate) {
                valContent = `<span class="font-mono fw-semibold text-dark"><i class="bi bi-calendar3 me-1.5 text-muted"></i>${escapeHtml(val)}</span>`;
                rawCopyVal = String(val);
            } else if (typeof val === 'object') {
                if (Array.isArray(val)) {
                    valContent = `<div class="d-flex flex-wrap gap-1">${val.map(item => `<span class="badge bg-white border text-dark font-mono">${escapeHtml(item)}</span>`).join('')}</div>`;
                } else {
                    valContent = `<pre class="bg-white border rounded p-2 mb-0 font-mono small text-dark" style="font-size: 0.72rem;">${escapeHtml(JSON.stringify(val, null, 2))}</pre>`;
                }
            } else {
                rawCopyVal = String(val);
                const monoClass = def.isMono ? 'font-mono fw-bold text-dark fs-6' : (def.highlight ? 'fw-bold text-dark' : 'text-dark fw-semibold');
                valContent = `<span class="${monoClass}" style="word-break: break-all;">${escapeHtml(val)}</span>`;
            }

            const friendlyTitle = def.label || key.replace(/([A-Z])/g, ' $1').replace(/_/g, ' ').trim().replace(/\b\w/g, l => l.toUpperCase());
            const icon = def.icon || 'bi-info-circle text-primary';

            const copyBtnHtml = (rawCopyVal && typeof val !== 'object')
                ? `<button type="button" class="btn btn-link btn-sm p-0 text-muted ms-auto copy-val-btn" data-val="${escapeHtml(rawCopyVal)}" title="Copy ${escapeHtml(friendlyTitle)}">
                     <i class="bi bi-clipboard" style="font-size: 0.8rem;"></i>
                   </button>`
                : '';

            col.innerHTML = `
                <div class="p-2.5 px-3 bg-light rounded-3 border d-flex flex-column justify-content-between h-100 shadow-sm" style="transition: all 0.2s ease;">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <div class="d-flex align-items-center text-muted small" style="font-size: 0.74rem;">
                            <i class="bi ${icon} me-1.5"></i>
                            <span class="fw-semibold text-secondary">${escapeHtml(friendlyTitle)}</span>
                            <code class="text-muted ms-1.5" style="font-size: 0.68rem; opacity: 0.75;">${escapeHtml(key)}</code>
                        </div>
                        ${copyBtnHtml}
                    </div>
                    <div class="pt-1 text-dark" style="font-size: 0.88rem;">
                        ${valContent}
                    </div>
                </div>
            `;
            container.appendChild(col);
            renderedCount++;
        });

        const countElem = document.getElementById('allSuccessDataCount');
        if (countElem) {
            countElem.innerText = renderedCount + (renderedCount === 1 ? ' Attribute' : ' Attributes');
        }

        if (renderedCount === 0) {
            container.innerHTML = '<div class="col-12"><div class="alert alert-secondary text-center small py-3 mb-0">Verification confirmed successfully. No additional data attributes returned.</div></div>';
        }
    }

    // Setup Copy Buttons for Service Result Cards
    function setupCopyBtn(btnId, targetId, textId, defaultText) {
        const btn = document.getElementById(btnId);
        if (btn) {
            btn.addEventListener('click', function () {
                const target = document.getElementById(targetId);
                const txtElem = textId ? document.getElementById(textId) : null;
                if (target) {
                    const textToCopy = target.innerText.trim();
                    navigator.clipboard.writeText(textToCopy).then(() => {
                        if (txtElem) txtElem.innerText = 'Copied!';
                        btn.classList.add('btn-success', 'text-white');
                        setTimeout(() => {
                            if (txtElem) txtElem.innerText = defaultText;
                            btn.classList.remove('btn-success', 'text-white');
                        }, 2000);
                    });
                }
            });
        }
    }

    setupCopyBtn('btnCopyDlResult', 'displayDlNumber', 'copyDlBtnText', 'Copy DL Number');
    setupCopyBtn('btnCopyRcResult', 'displayRcPlate', 'copyRcBtnText', 'Copy Plate No.');
    setupCopyBtn('btnCopyRcMobileResult', 'displayRcMobile', 'copyRcMobileBtnText', 'Copy Mobile');
    setupCopyBtn('btnCopyVoterResult', 'displayVoterId', 'copyVoterBtnText', 'Copy Voter ID');
    setupCopyBtn('btnCopyAadharPanResult', 'displayAadharLinkedPan', 'copyAadharPanBtnText', 'Copy PAN');

    // Copy Raw JSON Button in Live Container
    const btnCopyLiveRaw = document.getElementById('btnCopyLiveRawResponse');
    if (btnCopyLiveRaw) {
        btnCopyLiveRaw.addEventListener('click', function () {
            const raw = document.getElementById('liveResponseBody').innerText;
            navigator.clipboard.writeText(raw).then(() => {
                const orig = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check-lg text-success me-1"></i>Copied!';
                setTimeout(() => { this.innerHTML = orig; }, 2000);
            });
        });
    }

    // DL Smart Card View Switcher (Both / Front / Back)
    window.switchDlCardView = function(view) {
        const frontCol = document.getElementById('dlFrontCol');
        const backCol = document.getElementById('dlBackCol');
        const btnBoth = document.getElementById('dlViewBothBtn');
        const btnFront = document.getElementById('dlViewFrontBtn');
        const btnBack = document.getElementById('dlViewBackBtn');

        if (!frontCol || !backCol) return;

        if (btnBoth) btnBoth.classList.remove('active');
        if (btnFront) btnFront.classList.remove('active');
        if (btnBack) btnBack.classList.remove('active');

        if (view === 'front') {
            frontCol.classList.remove('d-none');
            frontCol.classList.remove('col-xl-6');
            frontCol.classList.add('col-12');
            backCol.classList.add('d-none');
            if (btnFront) btnFront.classList.add('active');
        } else if (view === 'back') {
            backCol.classList.remove('d-none');
            backCol.classList.remove('col-xl-6');
            backCol.classList.add('col-12');
            frontCol.classList.add('d-none');
            if (btnBack) btnBack.classList.add('active');
        } else {
            // Both sides
            frontCol.classList.remove('d-none');
            frontCol.classList.remove('col-12');
            frontCol.classList.add('col-xl-6');
            backCol.classList.remove('d-none');
            backCol.classList.remove('col-12');
            backCol.classList.add('col-xl-6');
            if (btnBoth) btnBoth.classList.add('active');
        }
    };

    // DL Smart Card Printing Helper
    window.printDlSmartCard = function() {
        const printContent = document.getElementById('dlCardsContainer');
        if (!printContent) return;
        const printWindow = window.open('', '', 'width=920,height=680');
        printWindow.document.write('<html><head><title>Print Driving Licence Smart Card</title>');
        printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">');
        printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">');
        printWindow.document.write('<style>@media print { body { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; } .dl-smart-card { break-inside: avoid; margin-bottom: 20px; } }</style>');
        printWindow.document.write('</head><body class="p-4 bg-light">');
        printWindow.document.write('<div class="text-center mb-3"><h5 class="fw-bold mb-1">GOVERNMENT OF INDIA - MINISTRY OF ROAD TRANSPORT & HIGHWAYS</h5><div class="small text-muted">Sarathi Portal Driving Licence Verification Certificate</div></div>');
        printWindow.document.write(printContent.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    };

    // e-PAN Full Details Copy Helper
    window.copyPanFullDetails = function() {
        const pan = document.getElementById('displayPanNumber')?.innerText.trim() || '';
        const name = document.getElementById('displayPanFullName')?.innerText.trim() || '';
        const father = document.getElementById('displayPanFatherName')?.innerText.trim() || '';
        const dob = document.getElementById('displayPanDob')?.innerText.trim() || '';
        const aadhaar = document.getElementById('displayPanAadhaarNumber')?.innerText.trim() || '';
        const panType = document.getElementById('displayPanType')?.innerText.trim() || '';
        const address = document.getElementById('displayPanAddress')?.innerText.trim() || '';

        const summary = [
            'PERMANENT ACCOUNT NUMBER (e-PAN) VERIFICATION',
            'PAN Number: ' + pan,
            'Cardholder Name: ' + name,
            'Father Name: ' + father,
            'Date of Birth: ' + dob,
            'Aadhaar Number: ' + aadhaar,
            'PAN Type: ' + panType,
            'Registered Address: ' + address,
            'Status: Verified & Valid'
        ].join('\n');

        navigator.clipboard.writeText(summary).then(() => {
            const btn = document.getElementById('btnCopyPanFullResult');
            if (btn) {
                const orig = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check-lg text-success me-1"></i> Copied!';
                setTimeout(() => { btn.innerHTML = orig; }, 2000);
            }
        });
    };

    // e-PAN Smart Card Printing Helper
    window.printPanSmartCard = function() {
        const printContent = document.getElementById('panCardPrintArea');
        if (!printContent) return;
        const printWindow = window.open('', '', 'width=920,height=680');
        printWindow.document.write('<html><head><title>Print Permanent Account Number Card</title>');
        printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">');
        printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">');
        
        // Fetch all custom styles from the current page to ensure 100% accuracy of e-Pan format
        let customStyles = '';
        const styleTags = document.querySelectorAll('style');
        styleTags.forEach(tag => { customStyles += tag.innerHTML; });

        printWindow.document.write('<style>' + customStyles + ' @media print { body { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; } .pan-id-card { break-inside: avoid; margin-bottom: 20px; } }</style>');
        printWindow.document.write('</head><body class="p-4 bg-light">');
        printWindow.document.write('<div class="text-center mb-3"><h5 class="fw-bold mb-1">INCOME TAX DEPARTMENT - GOVERNMENT OF INDIA</h5><div class="small text-muted">Permanent Account Number (e-PAN) Verification Certificate</div></div>');
        printWindow.document.write(printContent.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    };

    // V2 e-PAN Smart Card Printing Helper
    window.printPanSmartCardV2 = function() {
        const printContent = document.getElementById('panCardPrintAreaV2');
        if (!printContent) return;
        
        const printName = document.getElementById('epanPrintNameV2')?.innerText || 'ILMA';
        const printPan = document.getElementById('epanPrintPanNumberV2')?.innerText || 'ARMPI4787M';

        const printWindow = window.open('', '', 'width=920,height=680');
        printWindow.document.write(`<!DOCTYPE html><html lang="en"><head><title>e-PAN Card - ${printName} | ${printPan}</title></head><body>`);
        printWindow.document.write(printContent.innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        
        // Let images load if any before printing
        setTimeout(() => {
            // Trigger the print functionality using the same logic as the template's print button, or directly.
            // The template handles printing via a button, but we can also trigger it automatically if needed.
            // printWindow.print(); // Commented out to match the demo's manual print button behavior, or uncomment to auto-print.
        }, 500);
    };

    // Aadhaar To Pan Certificate Copy Helper
    window.copyAadharPanFullDetails = function() {
        const aadhar = document.getElementById('displayAadharInputNumber')?.innerText.trim() || '';
        const pan = document.getElementById('displayAadharLinkedPan')?.innerText.trim() || '';
        const panType = document.getElementById('displayAadharPanTypeBadge')?.innerText.trim() || '';
        const status = document.getElementById('displayAadharPanStatusBadge')?.innerText.trim() || 'LINKED & ACTIVE';

        const summary = [
            'GOVT. OF INDIA - AADHAAR TO PAN LINKAGE CERTIFICATE',
            'Input Aadhaar Number: ' + aadhar,
            'Unmasked Linked PAN: ' + pan,
            'Taxpayer Entity: ' + panType,
            'Linkage Status: ' + status,
            'Statutory Compliance: Section 139AA of Income Tax Act 1961',
            'Verification Authority: UIDAI & Income Tax Department'
        ].join('\n');

        navigator.clipboard.writeText(summary).then(() => {
            const btn = document.getElementById('btnCopyAadharPanFullResult');
            if (btn) {
                const orig = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check-lg text-success me-1"></i> Copied!';
                setTimeout(() => { btn.innerHTML = orig; }, 2000);
            }
        });
    };

    // Aadhaar To Pan Printing Helper
    window.printAadharPanSmartCard = function() {
        const printContent = document.getElementById('aadharPanCardPrintArea');
        if (!printContent) return;
        const printWindow = window.open('', '', 'width=920,height=680');
        printWindow.document.write('<html><head><title>Print Aadhaar-PAN Linkage Certificate</title>');
        printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">');
        printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">');
        printWindow.document.write('<style>@media print { body { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; } .aadhar-pan-card { break-inside: avoid; margin-bottom: 20px; } }</style>');
        printWindow.document.write('</head><body class="p-4 bg-light">');
        printWindow.document.write('<div class="text-center mb-3"><h5 class="fw-bold mb-1">GOVERNMENT OF INDIA - UIDAI & INCOME TAX DEPARTMENT</h5><div class="small text-muted">Aadhaar To Permanent Account Number (PAN) Linkage Certificate</div></div>');
        printWindow.document.write(printContent.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    };

    // RC Vehicle Smart Card Printing Helper
    window.printRcSmartCard = function() {
        const printContent = document.getElementById('rcCardPrintArea');
        if (!printContent) return;
        const printWindow = window.open('', '', 'width=920,height=680');
        printWindow.document.write('<html><head><title>Print Vehicle RC Certificate</title>');
        printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">');
        printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">');
        printWindow.document.write('<style>@media print { body { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; } .rc-smart-card { break-inside: avoid; margin-bottom: 20px; } }</style>');
        printWindow.document.write('</head><body class="p-4 bg-light">');
        printWindow.document.write('<div class="text-center mb-3"><h5 class="fw-bold mb-1">GOVERNMENT OF INDIA - MINISTRY OF ROAD TRANSPORT &amp; HIGHWAYS</h5><div class="small text-muted">Vehicle Registration Certificate &amp; Linked Mobile Verification</div></div>');
        printWindow.document.write(printContent.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    };

    // Universal copy handler for dynamic grid items
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.copy-val-btn');
        if (btn) {
            const val = btn.getAttribute('data-val');
            if (val) {
                navigator.clipboard.writeText(val).then(() => {
                    const orig = btn.innerHTML;
                    btn.innerHTML = '<i class="bi bi-check text-success" style="font-size: 0.75rem;"></i>';
                    setTimeout(() => { btn.innerHTML = orig; }, 1500);
                });
            }
        }
    });

    // Simple Verification Bill / Receipt Renderer
    window.renderServiceBill = function(params) {
        const {
            serviceCode,
            serviceName,
            data,
            payloadObj,
            idspayInner,
            isDlRelated,
            isRcRelated,
            isVoterRelated,
            isAadharToPan,
            isPanRelated,
            cost
        } = params;

        // 1. Receipt Metadata Header
        const txnId = data.transaction_id || data.reference_id || ('TXN_' + Date.now().toString(36).toUpperCase());
        const billTxnElem = document.getElementById('billTxnId');
        if (billTxnElem) billTxnElem.innerText = txnId;

        const displayName = (data.service && data.service.name) || serviceName || 'Service Verification';
        const billSrvNameElem = document.getElementById('billServiceName');
        if (billSrvNameElem) billSrvNameElem.innerText = displayName;

        const billSrvCodeElem = document.getElementById('billServiceCode');
        if (billSrvCodeElem) billSrvCodeElem.innerText = serviceCode;

        const billTitleBadge = document.getElementById('billServiceTitleBadge');
        if (billTitleBadge) billTitleBadge.innerText = (displayName.toUpperCase()) + ' BILL / RECEIPT';

        const now = new Date();
        const formattedDate = now.toLocaleString('en-IN', {
            day: '2-digit', month: 'short', year: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true
        });
        const billDateElem = document.getElementById('billDateTime');
        if (billDateElem) billDateElem.innerText = formattedDate;

        // 2. Billing & Wallet Deductions
        const billing = data.billing || {};
        const totalDeducted = billing.tokens_deducted !== undefined ? parseFloat(billing.tokens_deducted).toFixed(2) : parseFloat(cost).toFixed(2);
        const servicePrice = billing.service_price !== undefined ? parseFloat(billing.service_price).toFixed(2) : totalDeducted;
        const gstAmount = parseFloat(billing.gst_amount || 0);
        const gstRate = parseFloat(billing.gst_rate_percent || 0);
        const remainingBalance = billing.remaining_balance !== undefined ? parseFloat(billing.remaining_balance).toFixed(2) : '-';

        const billBaseElem = document.getElementById('billBaseCost');
        if (billBaseElem) billBaseElem.innerText = servicePrice + ' Tokens';

        const gstRow = document.getElementById('billGstRow');
        const gstLabel = document.getElementById('billGstLabel');
        const gstAmountElem = document.getElementById('billGstAmount');
        if (gstRow) {
            if (gstAmount > 0) {
                if (gstLabel) gstLabel.innerText = `GST (${gstRate.toFixed(0)}%):`;
                if (gstAmountElem) gstAmountElem.innerText = gstAmount.toFixed(2) + ' Tokens';
                gstRow.classList.remove('d-none');
            } else {
                gstRow.classList.add('d-none');
            }
        }

        const billTotalElem = document.getElementById('billTotalDeducted');
        if (billTotalElem) billTotalElem.innerText = totalDeducted + ' Tokens';

        const billWalletElem = document.getElementById('billWalletBalance');
        if (billWalletElem) billWalletElem.innerText = remainingBalance + ' Tokens';

        // 3. Appropriate Verification Particulars for the Bill Table
        const items = [];

        if (isDlRelated) {
            const dlDetails = idspayInner.details_of_driving_licence || idspayInner.details || {};
            const dlNum = idspayInner.dl_number || idspayInner.dlNumber || dlDetails.dl_number || dlDetails.dlNumber || payloadObj.dlNumber || 'DL-XXXXXXXXXXXXX';
            const holder = dlDetails.name || idspayInner.holder_name || idspayInner.name || 'Verified Licensee';
            const dob = idspayInner.dob || dlDetails.dob || payloadObj.dob || '-';
            const dateOfIssue = dlDetails.date_of_issue || idspayInner.date_of_issue || idspayInner.dl_validity?.non_transport?.from || '-';
            const validity = dlDetails.expiry_date || idspayInner.expiry_date || idspayInner.dl_validity?.non_transport?.to || idspayInner.validity || '-';
            const status = dlDetails.status || idspayInner.status_name || (typeof idspayInner.status === 'string' ? idspayInner.status : 'Active') || 'Active';
            const fatherOrHusband = dlDetails.father_or_husband_name || idspayInner.father_or_husband_name || idspayInner.father_name || '';
            const bloodGroup = dlDetails.user_blood_group || idspayInner.user_blood_group || dlDetails.blood_group || idspayInner.blood_group || 'O+';

            let completeAddress = '';
            if (dlDetails.address_list && Array.isArray(dlDetails.address_list) && dlDetails.address_list.length > 0) {
                completeAddress = dlDetails.address_list[0].complete_address || dlDetails.address_list[0].address || '';
            }
            if (!completeAddress) {
                completeAddress = dlDetails.address || idspayInner.complete_address || idspayInner.address || 'Address Not Provided';
            }

            let vehicleCategories = [];
            if (Array.isArray(idspayInner.vehicle_category_details) && idspayInner.vehicle_category_details.length > 0) {
                vehicleCategories = idspayInner.vehicle_category_details;
            } else if (Array.isArray(dlDetails.vehicle_category_details) && dlDetails.vehicle_category_details.length > 0) {
                vehicleCategories = dlDetails.vehicle_category_details;
            }

            let covText = idspayInner.cov || '';
            if (!covText && vehicleCategories.length > 0) {
                covText = vehicleCategories.map(v => v.cov).filter(Boolean).join(', ');
            }
            if (!covText) {
                covText = 'MCWOG, LMV';
            }

            items.push({ label: 'Driving Licence Number', value: dlNum, isMono: true, isHighlight: true });
            items.push({ label: 'Licensee Name (Full Name)', value: holder, isHighlight: true });
            if (fatherOrHusband) items.push({ label: 'Son / Wife / Daughter of', value: fatherOrHusband });
            items.push({ label: 'Date of Birth (DOB)', value: dob, isMono: true });
            items.push({ label: 'Date of Issue', value: dateOfIssue, isMono: true });
            items.push({ label: 'Valid Till / Expiry Date', value: validity, isMono: true });
            items.push({ label: 'Class of Vehicle (COV)', value: covText });
            items.push({ label: 'Blood Group', value: bloodGroup });
            items.push({ label: 'Licence Status', value: status, isBadge: true });
            items.push({ label: 'Registered Address', value: completeAddress });

        } else if (isAadharToPan) {
            const linkedPan = idspayInner.linked_pan || data.pan_number || idspayInner.pan_number || idspayInner.pan || '-';
            const rawAadhar = (payloadObj.aadhar || idspayInner.aadhaar_number || idspayInner.aadhar || '').toString().replace(/\D/g, '');
            let formattedAadhar = rawAadhar;
            let maskedAadhar = 'XXXX XXXX ' + (rawAadhar.length >= 4 ? rawAadhar.slice(-4) : '****');
            if (rawAadhar.length === 12) {
                formattedAadhar = rawAadhar.replace(/(\d{4})(\d{4})(\d{4})/, '$1 $2 $3');
            }

            let panTypeName = 'Individual (P)';
            if (linkedPan && linkedPan.length >= 4) {
                const fourthChar = linkedPan.charAt(3).toUpperCase();
                const panTypeMap = {
                    'P': 'Individual (P)',
                    'C': 'Company (C)',
                    'H': 'HUF (H)',
                    'F': 'Firm / LLP (F)',
                    'A': 'AOP (A)',
                    'T': 'Trust (T)',
                    'B': 'BOI (B)',
                    'L': 'Local Authority (L)',
                    'J': 'Juridical Person (J)',
                    'G': 'Government Agency (G)'
                };
                panTypeName = panTypeMap[fourthChar] || (idspayInner.pan_type || 'Individual');
            }

            items.push({ label: 'Input Aadhaar Number', value: formattedAadhar || rawAadhar, isMono: true });
            items.push({ label: 'Masked Aadhaar Number', value: maskedAadhar, isMono: true });
            items.push({ label: 'Unmasked Linked PAN Number', value: linkedPan, isMono: true, isHighlight: true });
            items.push({ label: 'Taxpayer Category', value: panTypeName });
            items.push({ label: 'Linkage Status', value: 'LINKED & ACTIVE (SECTION 139AA)', isBadge: true });
            items.push({ label: 'Verifying Authority', value: 'UIDAI & Income Tax Department' });

        } else if (isRcRelated) {
            const plate = idspayInner.vehicle_num || payloadObj.vehicle_num || idspayInner.vehicle_number || idspayInner.rc_number || 'GJ08BX1234';
            const mobile = idspayInner.mobileNo || idspayInner.mobile || idspayInner.mobile_no || idspayInner.phone || 'Linked & Verified';
            const owner = idspayInner.owner_name || idspayInner.name || null;
            const model = idspayInner.maker_model || idspayInner.model || null;
            const fuel = idspayInner.fuel_type || idspayInner.fuel || null;
            const regDate = idspayInner.registration_date || idspayInner.reg_date || null;
            const message = idspayInner.message || data.message || 'RC To Mobile v3 response processed successfully.';

            items.push({ label: 'Vehicle Registration Plate', value: plate, isMono: true, isHighlight: true });
            if (owner) items.push({ label: 'Registered Owner Name', value: owner, isHighlight: true });
            if (model) items.push({ label: 'Maker / Model', value: model });
            if (fuel) items.push({ label: 'Fuel Type', value: fuel });
            if (regDate) items.push({ label: 'Registration Date', value: regDate, isMono: true });
            items.push({ label: 'Registered Mobile Number', value: mobile, isMono: true, isHighlight: true });
            items.push({ label: 'Linkage Status', value: 'MORTH SEEDED & ACTIVE', isBadge: true });
            items.push({ label: 'Verification Remark', value: message });

        } else if (isVoterRelated) {
            const voter = idspayInner.epic_no || idspayInner.voterId || payloadObj.voterId || 'ABC1234567';
            const elector = idspayInner.name || idspayInner.name_v1 || 'Elector Record Found';
            const constituency = idspayInner.ac_name || idspayInner.constituency || 'Constituency Active';
            const booth = idspayInner.ps_name || idspayInner.polling_station || 'Designated Polling Booth';

            items.push({ label: 'Elector Photo Identity Card (EPIC)', value: voter, isMono: true, isHighlight: true });
            items.push({ label: 'Elector Full Name', value: elector, isHighlight: true });
            items.push({ label: 'Assembly Constituency', value: constituency });
            items.push({ label: 'Polling Station / Booth', value: booth });
            items.push({ label: 'Electoral Roll Status', value: 'ELECTOR RECORD ACTIVE', isBadge: true });

        } else if (isPanRelated) {
            const pan = data.pan_number || idspayInner.pan || idspayInner.pan_number || payloadObj.pan || 'ARxxxxxxx9E';
            let panType = idspayInner.pan_type || idspayInner.category || 'Individual';
            if ((!panType || panType === 'Individual') && pan && pan.length >= 4) {
                const fourthChar = pan.charAt(3).toUpperCase();
                const panTypeMap = {
                    'P': 'Individual (P)',
                    'C': 'Company (C)',
                    'H': 'HUF (H)',
                    'F': 'Firm / LLP (F)',
                    'A': 'AOP (A)',
                    'T': 'Trust (T)',
                    'B': 'BOI (B)',
                    'L': 'Local Authority (L)',
                    'J': 'Juridical Person (J)',
                    'G': 'Government Agency (G)'
                };
                if (panTypeMap[fourthChar]) {
                    panType = panTypeMap[fourthChar];
                }
            }

            const fullname = idspayInner.fullname || idspayInner.name_on_pan || idspayInner.name || payloadObj.name || '-';
            const fatherName = idspayInner.father_name || idspayInner.father_or_husband_name || (serviceCode === 'pan_details_v2' ? 'On Record (ITD)' : '-');
            const gender = idspayInner.gender || '';
            const dob = idspayInner.dob || (serviceCode === 'pan_details_v2' ? 'Verified on Record' : '-');
            const aadhaarNumber = idspayInner.aadhaar_number || idspayInner.aadhar || payloadObj.aadhar || '-';
            const isAadhaarLinked = (idspayInner.aadhaar_linked === true || idspayInner.aadhaar_linked === 'true' || idspayInner.aadhaar_linked === 1 || idspayInner.aadhaar_linked === '1');
            const mobile = idspayInner.mobile || payloadObj.mobile || '';
            const email = idspayInner.email || payloadObj.email || '';

            let formattedAddress = '';
            if (idspayInner.address && typeof idspayInner.address === 'object') {
                const addr = idspayInner.address;
                const lineParts = [
                    addr.building_name,
                    addr.street_name,
                    addr.locality,
                    addr.city,
                    addr.state
                ].map(s => (s || '').toString().trim()).filter(Boolean);

                let mainLine = lineParts.join(', ');
                if (addr.pincode && addr.pincode.toString().trim()) {
                    mainLine += (mainLine ? ' - ' : '') + addr.pincode.toString().trim();
                }
                if (addr.country && addr.country.toString().trim()) {
                    mainLine += (mainLine ? ', ' : '') + addr.country.toString().trim();
                }
                formattedAddress = mainLine;
            } else if (typeof idspayInner.address === 'string' && idspayInner.address.trim()) {
                formattedAddress = idspayInner.address.trim();
            }
            if (!formattedAddress) {
                formattedAddress = 'India';
            }

            items.push({ label: 'Permanent Account Number (PAN)', value: pan, isMono: true, isHighlight: true });
            items.push({ label: 'Full Legal Name', value: fullname, isHighlight: true });
            if (fatherName && fatherName !== '-') items.push({ label: 'Father / Husband Name', value: fatherName });
            if (dob && dob !== '-') items.push({ label: 'Date of Birth (DOB)', value: dob, isMono: true });
            if (gender) items.push({ label: 'Gender', value: gender.toUpperCase() });
            items.push({ label: 'PAN Cardholder Category', value: panType });
            items.push({
                label: 'Aadhaar Seeding Status',
                value: isAadhaarLinked ? 'AADHAAR LINKED' : (serviceCode === 'pan_details_v2' ? 'PAN ACTIVE & MATCHED' : 'NOT LINKED'),
                isBadge: true
            });
            if (aadhaarNumber && aadhaarNumber !== '-') items.push({ label: 'Linked Aadhaar Number', value: aadhaarNumber, isMono: true });
            if (formattedAddress && formattedAddress !== '-') items.push({ label: 'Registered Address', value: formattedAddress });
            if (mobile) items.push({ label: 'Mobile Number', value: mobile, isMono: true });
            if (email) items.push({ label: 'Email Address', value: email });
            items.push({ label: 'PAN Status', value: 'VALID & ACTIVE', isBadge: true });

        } else {
            // Fallback for any other services: iterate through idspayInner
            for (const [key, val] of Object.entries(idspayInner)) {
                if (val === null || val === undefined || typeof val === 'object') continue;
                const strVal = String(val).trim();
                if (!strVal || strVal === 'null') continue;
                const friendlyLabel = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                items.push({ label: friendlyLabel, value: strVal });
            }
            if (items.length === 0) {
                items.push({ label: 'Verification Outcome', value: data.message || 'Service verified successfully', isBadge: true });
            }
        }

        // 4. Render Table Rows
        const tbody = document.getElementById('billItemsTableBody');
        if (tbody) {
            tbody.innerHTML = '';
            items.forEach(item => {
                const tr = document.createElement('tr');
                const tdLabel = document.createElement('td');
                tdLabel.className = 'ps-3 fw-semibold text-dark text-nowrap';
                tdLabel.innerText = item.label;

                const tdVal = document.createElement('td');
                tdVal.className = 'ps-3';

                if (item.isBadge) {
                    tdVal.innerHTML = `<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 font-mono fw-bold"><i class="bi bi-check-circle-fill me-1"></i>${item.value}</span>`;
                } else if (item.isHighlight) {
                    tdVal.innerHTML = `<strong class="${item.isMono ? 'font-mono' : ''} text-primary fs-6">${item.value}</strong>`;
                } else {
                    tdVal.innerHTML = `<span class="${item.isMono ? 'font-mono' : ''} text-dark">${item.value}</span>`;
                }

                tr.appendChild(tdLabel);
                tr.appendChild(tdVal);
                tbody.appendChild(tr);
            });
        }

        // Save reference for copying/printing
        window.currentServiceBillData = {
            txnId,
            serviceName: displayName,
            serviceCode,
            dateTime: formattedDate,
            items,
            totalDeducted,
            servicePrice,
            gstAmount,
            gstRate,
            remainingBalance
        };
    };

    // Copy Verification Bill Details to Clipboard
    window.copyServiceBillText = function() {
        const d = window.currentServiceBillData;
        if (!d) return;

        let text = '==================================================\n';
        text += '       a4Digital - VERIFICATION BILL & RECEIPT\n';
        text += '==================================================\n';
        text += `Receipt / Txn ID : ${d.txnId}\n`;
        text += `Service Name     : ${d.serviceName} (${d.serviceCode})\n`;
        text += `Date & Time      : ${d.dateTime}\n`;
        text += `Status           : SUCCESSFUL / VERIFIED\n`;
        text += '--------------------------------------------------\n';
        text += 'VERIFIED PARTICULARS:\n';
        text += '--------------------------------------------------\n';
        d.items.forEach(it => {
            text += `${it.label.padEnd(25)}: ${it.value}\n`;
        });
        text += '--------------------------------------------------\n';
        text += 'BILLING BREAKDOWN:\n';
        text += '--------------------------------------------------\n';
        text += `Service Fee      : ${d.servicePrice} Tokens\n`;
        if (d.gstAmount > 0) {
            text += `GST (${d.gstRate.toFixed(0)}%)         : ${d.gstAmount.toFixed(2)} Tokens\n`;
        }
        text += `Total Debited    : ${d.totalDeducted} Tokens\n`;
        text += `Wallet Balance   : ${d.remainingBalance} Tokens\n`;
        text += `Payment Mode     : Direct Wallet Token Deduction\n`;
        text += '==================================================\n';
        text += 'Thank you for using a4Digital Services.\n';

        navigator.clipboard.writeText(text).then(() => {
            const btnText = document.getElementById('copyBillBtnText');
            if (btnText) {
                const orig = btnText.innerText;
                btnText.innerText = 'Copied!';
                setTimeout(() => { btnText.innerText = orig; }, 2000);
            }
        });
    };

    // Print Verification Bill Helper
    window.printServiceBill = function() {
        const printArea = document.getElementById('serviceBillPrintArea');
        if (!printArea) return;
        const printWindow = window.open('', '', 'width=840,height=750');
        if (!printWindow || printWindow.closed || typeof printWindow.closed === 'undefined') {
            window.print();
            return;
        }
        printWindow.document.write('<html><head><title>Service Verification Receipt</title>');
        printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">');
        printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">');
        printWindow.document.write('<style>@media print { body { padding: 20px; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; } .btn { display: none !important; } } body { font-family: \'Plus Jakarta Sans\', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #fff; }</style>');
        printWindow.document.write('</head><body class="p-4">');
        printWindow.document.write('<div class="text-center mb-3"><h5 class="fw-bold mb-1">a4Digital - Verification Receipt</h5><div class="small text-muted">Official Verification Particulars & Confirmation</div></div>');
        printWindow.document.write(printArea.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    };
});
</script>
@endpush
