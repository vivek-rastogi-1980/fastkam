@extends('layouts.admin')

@section('title', 'Add Wallet Balance')
@section('page_title', 'Add Wallet Balance')

@push('styles')
<style>
    /* Add Wallet Balance Page Styling */
    .balance-card {
        background-color: #0b1a30;
        background-image: 
            radial-gradient(circle at 90% 15%, rgba(245, 158, 11, 0.08) 0%, transparent 45%),
            radial-gradient(circle at 10% 85%, rgba(37, 99, 235, 0.06) 0%, transparent 40%);
        border-top: 4px solid #f59e0b;
        border-radius: 16px;
        padding: 1.4rem 1.75rem;
        color: #ffffff;
        box-shadow: 0 10px 25px -5px rgba(11, 26, 48, 0.3);
    }

    .balance-amount {
        font-size: 2.5rem;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: -0.5px;
        line-height: 1.15;
    }

    .account-line {
        font-size: 0.825rem;
        color: #94a3b8;
    }

    /* Recharge Wallet Form Card */
    .recharge-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    }

    .recharge-header {
        background: linear-gradient(135deg, #0d386c 0%, #124b8f 100%);
        padding: 1.25rem 1.75rem;
        position: relative;
        overflow: hidden;
    }

    .recharge-icon-box {
        width: 44px;
        height: 44px;
        background: rgba(10, 35, 68, 0.65);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: #ffffff;
        font-weight: 700;
        flex-shrink: 0;
    }

    .recharge-watermark {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 4.8rem;
        color: #ffffff;
        opacity: 0.12;
        pointer-events: none;
        line-height: 1;
    }

    .section-label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 0.75rem;
    }

    /* Preset Selection Grid */
    .preset-card {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 0.85rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .preset-card:hover {
        border-color: #93c5fd;
        background: #f0f7ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.08);
    }

    .preset-card.active {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
    }

    .preset-amount {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .preset-card.active .preset-amount {
        color: #1d4ed8;
    }

    .preset-label {
        font-size: 0.78rem;
        font-weight: 500;
        color: #64748b;
    }

    .preset-card.active .preset-label {
        color: #2563eb;
        font-weight: 600;
    }

    /* Custom Amount Input */
    .custom-amount-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .currency-symbol-addon {
        position: absolute;
        left: 18px;
        font-size: 1.15rem;
        font-weight: 600;
        color: #475569;
        pointer-events: none;
        z-index: 5;
    }

    .custom-amount-field {
        padding: 0.85rem 1rem 0.85rem 2.5rem;
        font-size: 1rem;
        font-weight: 500;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        color: #0f172a;
        transition: all 0.2s ease;
        background-color: #ffffff;
    }

    .custom-amount-field:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        outline: none;
    }

    .custom-amount-field::placeholder {
        color: #94a3b8;
        font-size: 0.95rem;
    }

    /* Proceed Button */
    .btn-proceed-pay {
        background: #0d386c;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 0.95rem 1.5rem;
        font-weight: 600;
        font-size: 1.05rem;
        letter-spacing: 0.2px;
        transition: all 0.2s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(13, 56, 108, 0.25);
    }

    .btn-proceed-pay:hover {
        background: #114a8b;
        color: #ffffff;
        box-shadow: 0 6px 20px rgba(13, 56, 108, 0.35);
        transform: translateY(-1px);
    }

    .btn-proceed-pay:active {
        transform: translateY(1px);
    }

    .btn-amount-badge {
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 20px;
        padding: 0.3rem 0.85rem;
        font-size: 0.95rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 0.3px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-0">

    <!-- Breadcrumb Row -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0 align-items-center" style="font-size: 0.85rem;">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}" class="text-decoration-none text-primary d-inline-flex align-items-center gap-1">
                    <i class="bi bi-house-door-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="breadcrumb-item text-secondary">Wallet</li>
            <li class="breadcrumb-item active text-muted fw-semibold" aria-current="page">Add Balance</li>
        </ol>
    </nav>

    <!-- Page Title Header -->
    <div class="d-flex align-items-center gap-2 mb-4">
        <div class="d-flex align-items-center justify-content-center rounded-3 shadow-sm" style="width: 36px; height: 36px; background: #fef3c7; color: #d97706; font-size: 1.15rem;">
            <i class="bi bi-wallet-fill"></i>
        </div>
        <h4 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.3px;">Add Wallet Balance</h4>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
            <div>{{ $errors->first() }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-9 col-lg-10">

            <!-- 1. Current Wallet Balance Card -->
            <div class="balance-card mb-4">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-credit-card-2-front text-secondary opacity-75"></i>
                    <span class="text-uppercase fw-bold letter-spacing-1" style="font-size: 0.72rem; color: #94a3b8;">CURRENT WALLET BALANCE</span>
                </div>
                <div class="balance-amount">
                    ₹ {{ number_format(Auth::user()->wallet_balance ?? 0, 2) }}
                </div>
                <div class="account-line mt-2">
                    Account: {{ strtoupper(Auth::user()->name) }} · {{ ucwords(str_replace('_', ' ', Auth::user()->role ?? 'Sub Registrar')) }}
                </div>
            </div>

            <!-- 2. Recharge Wallet Form Card -->
            <div class="recharge-card mb-4">
                <!-- Header with dark blue banner & watermark -->
                <div class="recharge-header d-flex align-items-center gap-3">
                    <div class="recharge-icon-box">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-white mb-0">Recharge Wallet</h5>
                        <small class="text-info opacity-75">Select a preset amount or enter a custom amount below</small>
                    </div>
                    <div class="recharge-watermark">
                        <i class="bi bi-credit-card-fill"></i>
                    </div>
                </div>

                <!-- Card Body Form -->
                <div class="p-4">
                    <form action="{{ route('wallet.process_payment') }}" method="POST" id="rechargeForm">
                        @csrf

                        <!-- Preset Amounts Section -->
                        <div class="mb-4">
                            <div class="section-label">QUICK SELECT AMOUNT</div>
                            <div class="row g-3">
                                @foreach($presetAmounts as $key => $preset)
                                    <div class="col-md-4 col-6">
                                        <div class="preset-card {{ $loop->first ? 'active' : '' }}" data-amount="{{ $preset['amount'] }}">
                                            <div class="preset-amount">₹{{ number_format($preset['amount']) }}</div>
                                            <div class="preset-label">{{ $preset['label'] }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Custom Amount Section -->
                        <div class="mb-2">
                            <div class="section-label">OR ENTER CUSTOM AMOUNT</div>
                            <div class="custom-amount-wrapper">
                                <span class="currency-symbol-addon">₹</span>
                                <input type="number" 
                                       id="customAmountInput" 
                                       name="amount" 
                                       value="1" 
                                       min="1" 
                                       step="1" 
                                       class="form-control custom-amount-field" 
                                       placeholder="Enter amount (min ₹100)" 
                                       required>
                            </div>
                        </div>

                        <!-- Info Note -->
                        <div class="d-flex align-items-center gap-2 mb-4" style="font-size: 0.82rem; color: #64748b;">
                            <i class="bi bi-exclamation-circle-fill text-warning"></i>
                            <span>Minimum recharge amount is ₹100</span>
                        </div>

                        <!-- Proceed to Pay Button -->
                        <button type="submit" id="proceedPayBtn" class="btn-proceed-pay w-100 position-relative">
                            <span class="d-flex align-items-center gap-2">
                                <i class="bi bi-shield-lock-fill"></i>
                                <span>Proceed to Pay</span>
                            </span>
                            <span class="btn-amount-badge position-absolute end-0 me-3" id="btnAmountBadge">₹100</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Return link -->
            <div class="text-center">
                <a href="{{ route('admin.services') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1">
                    <i class="bi bi-arrow-left"></i>
                    <span>Return to Our Services</span>
                </a>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const presetCards = document.querySelectorAll('.preset-card');
        const customInput = document.getElementById('customAmountInput');
        const btnBadge = document.getElementById('btnAmountBadge');
        const rechargeForm = document.getElementById('rechargeForm');

        function updateAmount(val) {
            const num = parseFloat(val);
            if (!isNaN(num) && num > 0) {
                btnBadge.textContent = '₹' + num.toLocaleString('en-IN');
            } else {
                btnBadge.textContent = '₹0';
            }
        }

        // Handle Preset card clicks
        presetCards.forEach(function(card) {
            card.addEventListener('click', function() {
                presetCards.forEach(c => c.classList.remove('active'));
                this.classList.add('active');

                const amount = this.getAttribute('data-amount');
                customInput.value = amount;
                updateAmount(amount);
            });
        });

        // Handle typing in custom input
        customInput.addEventListener('input', function() {
            const val = this.value;
            updateAmount(val);

            // Highlight preset if matches exactly
            let matched = false;
            presetCards.forEach(function(card) {
                if (card.getAttribute('data-amount') === val) {
                    card.classList.add('active');
                    matched = true;
                } else {
                    card.classList.remove('active');
                }
            });
        });

        // Form submit validation
        rechargeForm.addEventListener('submit', function(e) {
            const val = parseFloat(customInput.value);
            if (isNaN(val) || val < 1) {
                e.preventDefault();
                alert('Please enter a valid amount of at least ₹1.');
                customInput.focus();
                return false;
            }
        });

        // Initialize with default value (100)
        updateAmount(customInput.value || 1);
    });
</script>
@endpush
