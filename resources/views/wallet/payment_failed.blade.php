<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status | a4Digital</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0f172a;
            background-image: radial-gradient(circle at 50% 0%, #1e293b, #0f172a);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            color: #f8fafc;
        }

        .failed-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            max-width: 500px;
            width: 100%;
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .failed-icon-badge {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: #ffffff;
            font-size: 2.25rem;
            box-shadow: 0 10px 25px -5px rgba(239, 68, 68, 0.5);
        }

        .detail-box {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 1.25rem;
            margin: 1.5rem 0;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            padding: 0.35rem 0;
            color: #94a3b8;
        }

        .detail-row strong {
            color: #f1f5f9;
        }

        .status-badge {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
            padding: 0.25rem 0.65rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.78rem;
        }
    </style>
</head>
<body>

    <div class="failed-card">
        <div class="failed-icon-badge">
            <i class="bi bi-x-lg"></i>
        </div>

        <h3 class="fw-bold text-white mb-1">Transaction Incomplete</h3>
        <p class="text-secondary small mb-3">The payment gateway returned the following response.</p>

        <div class="detail-box">
            <div class="detail-row">
                <span>Transaction Status:</span>
                <span class="status-badge">{{ $status }}</span>
            </div>
            @if(!empty($amount) && $amount > 0)
            <div class="detail-row">
                <span>Requested Amount:</span>
                <strong>₹{{ number_format((float) $amount, 2) }}</strong>
            </div>
            @endif
            @if(!empty($orderId))
            <div class="detail-row">
                <span>Order Reference:</span>
                <strong class="font-monospace text-muted small">{{ $orderId }}</strong>
            </div>
            @endif
            <div class="detail-row border-top border-secondary border-opacity-25 mt-2 pt-2">
                <span>Gateway Message:</span>
                <strong class="text-warning text-end" style="max-width: 65%;">{{ $message }}</strong>
            </div>
        </div>

        @if(str_contains(strtolower($message), 'unauthorized') || str_contains(strtolower($message), 'token') || str_contains(strtolower($message), 'checksum'))
        <div class="alert text-start small mb-4" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.35); border-radius: 12px; color: #fde68a; padding: 1rem 1.25rem;">
            <div class="d-flex align-items-center gap-2 mb-2 fw-semibold text-warning">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <span>Gateway Connection Notice</span>
            </div>
            <p class="mb-2 text-light" style="font-size: 0.82rem; line-height: 1.5;">
                The payment gateway returned a verification notice (<code>{{ $message }}</code>). To resolve this in production:
            </p>
            <ul class="mb-3 ps-3 text-secondary" style="font-size: 0.8rem; line-height: 1.6;">
                <li><strong>Merchant Account & Token:</strong> Ensure your production <strong>API Token</strong> from <a href="https://apnacsc.online/Settings" target="_blank" class="text-info text-decoration-underline">apnacsc.online/Settings</a> matches <code>APNACSC_TOKEN</code> in your environment file.</li>
                <li><strong>UPI Unique ID (upiuid):</strong> Verify at <a href="https://apnacsc.online/UPIsAccounts" target="_blank" class="text-info text-decoration-underline">apnacsc.online/UPIsAccounts</a> that your merchant UPI ID corresponds to <code>APNACSC_UPIUID</code>.</li>
                <li><strong>Gateway Subscription:</strong> Ensure your merchant service plan on ApnaCSC is active and in good standing.</li>
            </ul>

            @if(app()->environment('local'))
            <div class="p-2 rounded-2 text-center" style="background: rgba(16, 185, 129, 0.15); border: 1px dashed rgba(16, 185, 129, 0.4);">
                <div class="small text-white fw-semibold mb-1">Local Development Testing</div>
                <a href="{{ route('wallet.simulate_success', ['orderId' => $orderId, 'amount' => $amount ?: 100]) }}" class="btn btn-sm btn-success fw-bold px-3 py-1">
                    <i class="bi bi-check2-circle me-1"></i> Credit ₹{{ number_format((float) ($amount ?: 100), 2) }} to Wallet (Test Mode)
                </a>
            </div>
            @endif
        </div>
        @endif

        <div class="d-flex flex-column gap-2 mt-4">
            <a href="{{ route('wallet.add_balance') }}" class="btn btn-primary btn-lg fw-semibold" style="border-radius: 10px;">
                <i class="bi bi-arrow-clockwise me-1"></i> Try Again
            </a>
            <a href="{{ route('admin.services') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: 10px;">
                <i class="bi bi-grid-fill me-1"></i> Back to Services
            </a>
        </div>
    </div>

</body>
</html>
