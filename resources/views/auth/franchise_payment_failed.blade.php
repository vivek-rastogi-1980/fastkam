<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed | a4Digital</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,600&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b1120;
            background-image: radial-gradient(circle at 50% -20%, #1a2a42, #0b1120);
            min-height: 100vh;
            color: #fff;
            padding: 3rem 1rem;
        }

        .failed-wrapper {
            max-width: 860px;
            margin: 0 auto;
        }

        .main-card {
            background: #0f172a;
            border-radius: 20px;
            border: 1px solid #1e293b;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            padding: 2.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .main-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ef4444, #f59e0b, #ef4444);
        }

        .icon-circle {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.12);
            border: 2px solid #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ef4444;
            font-size: 2.5rem;
            margin: 0 auto 1.5rem;
            box-shadow: 0 0 30px rgba(239, 68, 68, 0.25);
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #94a3b8;
            text-align: center;
            font-size: 0.95rem;
            max-width: 580px;
            margin: 0 auto 2rem;
        }

        .error-alert-box {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .error-alert-box i {
            font-size: 1.5rem;
            color: #ef4444;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .error-alert-title {
            color: #f87171;
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .error-alert-desc {
            color: #cbd5e1;
            font-size: 0.88rem;
            margin: 0;
        }

        .summary-grid {
            background: #0b1120;
            border: 1px solid #1e293b;
            border-radius: 14px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .summary-item {
            padding: 0.5rem 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(30, 41, 59, 0.7);
            font-size: 0.9rem;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: #94a3b8;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-value {
            color: #f1f5f9;
            font-weight: 600;
        }

        .amount-highlight {
            color: #f87171;
            font-size: 1.35rem;
            font-weight: 700;
        }

        .badge-failed-custom {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 0.35rem 0.85rem;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* JSON Box Styling */
        .json-section {
            background: #0b1120;
            border: 1px solid #1e293b;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .json-header {
            background: #080d19;
            border-bottom: 1px solid #1e293b;
            padding: 0.85rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .json-header-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-copy {
            background: rgba(56, 189, 248, 0.1);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.25);
            border-radius: 6px;
            padding: 0.3rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-copy:hover {
            background: rgba(56, 189, 248, 0.2);
            color: #fff;
        }

        .json-pre {
            margin: 0;
            padding: 1.25rem;
            max-height: 380px;
            overflow-y: auto;
            background: #050811;
            font-family: 'JetBrains Mono', Consolas, monospace;
            font-size: 0.82rem;
            line-height: 1.5;
            color: #f87171;
        }

        .btn-retry {
            background: linear-gradient(to right, #38bdf8, #7dd3fc);
            color: #0b1120;
            border: none;
            border-radius: 8px;
            padding: 0.85rem 1.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: opacity 0.2s;
            box-shadow: 0 4px 14px rgba(56, 189, 248, 0.3);
        }

        .btn-retry:hover {
            opacity: 0.9;
            color: #0b1120;
        }

        .btn-secondary-custom {
            background: transparent;
            color: #cbd5e1;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 0.85rem 1.5rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-secondary-custom:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            border-color: #475569;
        }

        .footer-note {
            text-align: center;
            color: #64748b;
            font-size: 0.75rem;
            margin-top: 2rem;
        }
    </style>
</head>
<body>

<div class="failed-wrapper">
    <div class="main-card">
        <div class="icon-circle">
            <i class="bi bi-x-lg"></i>
        </div>

        <h1 class="page-title">Payment Failed / Cancelled</h1>
        <p class="page-subtitle">
            We were unable to verify your payment of <strong>₹100</strong> via Paytm. No charges have been made or any debited funds will be refunded by your bank.
        </p>

        <!-- Error Description -->
        <div class="error-alert-box">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <div class="error-alert-title">Gateway Message:</div>
                <p class="error-alert-desc">
                    {{ $respMsg ?? ($paytmParams['RESPMSG'] ?? 'Transaction was declined, cancelled or failed at Paytm Gateway.') }}
                </p>
            </div>
        </div>

        <!-- Summary Table -->
        <div class="summary-grid">
            <div class="summary-item">
                <span class="summary-label">Transaction Status</span>
                <span class="badge-failed-custom">
                    <i class="bi bi-x-circle-fill me-1"></i> {{ $status ?? ($paytmParams['STATUS'] ?? 'TXN_FAILURE') }}
                </span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Amount Attempted</span>
                <span class="amount-highlight">₹{{ number_format((float) ($paytmParams['TXNAMOUNT'] ?? 100), 2) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Order ID</span>
                <span class="summary-value">{{ $orderId ?? ($paytmParams['ORDERID'] ?? 'N/A') }}</span>
            </div>
            @if(!empty($respCode ?? ($paytmParams['RESPCODE'] ?? '')))
            <div class="summary-item">
                <span class="summary-label">Response Code</span>
                <span class="summary-value">{{ $respCode ?? ($paytmParams['RESPCODE'] ?? '') }}</span>
            </div>
            @endif
            @if(!empty($paytmParams['TXNID'] ?? ''))
            <div class="summary-item">
                <span class="summary-label">Paytm Transaction ID</span>
                <span class="summary-value">{{ $paytmParams['TXNID'] }}</span>
            </div>
            @endif
            @if(!empty($paytmParams['BANKTXNID'] ?? ''))
            <div class="summary-item">
                <span class="summary-label">Bank Reference No.</span>
                <span class="summary-value">{{ $paytmParams['BANKTXNID'] }}</span>
            </div>
            @endif
            @if(isset($checksumValid))
            <div class="summary-item">
                <span class="summary-label">Checksum Integrity</span>
                <span class="summary-value {{ $checksumValid ? 'text-success' : 'text-danger' }}">
                    {{ $checksumValid ? 'Verified' : 'Invalid / Mismatched' }}
                </span>
            </div>
            @endif
        </div>

        <!-- Raw JSON Data Section -->
        <div class="json-section">
            <div class="json-header">
                <span class="json-header-title">
                    <i class="bi bi-braces text-danger"></i> Complete Paytm Callback Response (JSON)
                </span>
                <button type="button" class="btn-copy" id="copyJsonBtn" onclick="copyJsonData()">
                    <i class="bi bi-clipboard me-1"></i> Copy JSON
                </button>
            </div>
            <pre class="json-pre" id="jsonBlock"><code>{{ json_encode($paytmParams, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="{{ route('login') }}" class="btn-retry">
                <i class="bi bi-arrow-repeat"></i> RETRY LOGIN / ONBOARDING
            </a>
            <a href="{{ url('/') }}" class="btn-secondary-custom">
                <i class="bi bi-house-door"></i> RETURN TO HOME
            </a>
        </div>
    </div>

    <div class="footer-note">
        If your amount was debited, it will be automatically refunded within 3-5 working days according to Paytm banking guidelines.
    </div>
</div>

<script>
    function copyJsonData() {
        const text = document.getElementById('jsonBlock').innerText;
        navigator.clipboard.writeText(text).then(function() {
            const btn = document.getElementById('copyJsonBtn');
            btn.innerHTML = '<i class="bi bi-check2 me-1"></i> Copied!';
            btn.classList.add('text-success');
            setTimeout(function() {
                btn.innerHTML = '<i class="bi bi-clipboard me-1"></i> Copy JSON';
                btn.classList.remove('text-success');
            }, 2500);
        });
    }
</script>

</body>
</html>
