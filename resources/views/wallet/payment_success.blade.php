<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful | a4Digital</title>

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

        .success-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            max-width: 500px;
            width: 100%;
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .success-icon-badge {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: #ffffff;
            font-size: 2.25rem;
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.5);
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

        .amount-highlight {
            font-size: 1.2rem;
            font-weight: 700;
            color: #10b981;
        }
    </style>
</head>
<body>

    <div class="success-card">
        <div class="success-icon-badge">
            <i class="bi bi-check-lg"></i>
        </div>

        <h3 class="fw-bold text-white mb-1">Recharge Successful!</h3>
        <p class="text-secondary small mb-3">Your wallet has been credited with tokens successfully.</p>

        <div class="detail-box">
            <div class="detail-row">
                <span>Credited Amount:</span>
                <span class="amount-highlight">₹{{ number_format((float) $amount, 2) }}</span>
            </div>
            <div class="detail-row">
                <span>Tokens Added:</span>
                <strong class="text-info">{{ number_format((float) $amount, 2) }} Tokens (1 INR = 1 Token)</strong>
            </div>
            <div class="detail-row">
                <span>Current Wallet Balance:</span>
                <strong class="text-warning">₹{{ number_format((float) ($user->wallet_balance ?? $amount), 2) }}</strong>
            </div>
            <div class="detail-row border-top border-secondary border-opacity-25 mt-2 pt-2">
                <span>Order Reference:</span>
                <strong class="font-monospace text-muted small">{{ $orderId }}</strong>
            </div>
            <div class="detail-row">
                <span>Gateway Txn ID:</span>
                <strong class="font-monospace text-muted small">{{ $txnId }}</strong>
            </div>
            <div class="detail-row">
                <span>Account Name:</span>
                <strong>{{ $user->name ?? 'User' }}</strong>
            </div>
        </div>

        <div class="d-flex flex-column gap-2 mt-4">
            <a href="{{ route('admin.services') }}" class="btn btn-primary btn-lg fw-semibold" style="border-radius: 10px;">
                <i class="bi bi-grid-fill me-1"></i> Proceed to Our Services
            </a>
            <a href="{{ route('wallet.add_balance') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: 10px;">
                <i class="bi bi-plus-circle me-1"></i> Add More Tokens
            </a>
        </div>
    </div>

</body>
</html>
