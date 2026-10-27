<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connecting to ApnaCSC | FastKam</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b1120;
            background-image: radial-gradient(circle at 50% -20%, #172554, #0b1120);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            color: #ffffff;
        }

        .redirect-card {
            background: #0f172a;
            border: 1px solid #1e293b;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            max-width: 480px;
            width: 100%;
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .spinner-custom {
            width: 3.5rem;
            height: 3.5rem;
            border: 3.5px solid rgba(56, 189, 248, 0.15);
            border-top-color: #38bdf8;
            border-radius: 50%;
            animation: spin 0.85s linear infinite;
            margin: 0 auto 1.5rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .gateway-title {
            font-size: 1.45rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #f8fafc;
        }

        .gateway-subtitle {
            color: #94a3b8;
            font-size: 0.875rem;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        .info-pill {
            background: #0b1120;
            border: 1px solid #1e293b;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            color: #94a3b8;
            padding: 0.3rem 0;
        }

        .info-row strong {
            color: #f1f5f9;
        }

        .info-row .amount {
            color: #38bdf8;
            font-weight: 700;
            font-size: 1.15rem;
        }

        .btn-manual {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-manual:hover {
            background: #0284c7;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.4);
        }

        .security-badge {
            color: #64748b;
            font-size: 0.78rem;
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }
    </style>
</head>
<body>

    <div class="redirect-card">
        <div class="spinner-custom"></div>

        <h3 class="gateway-title">Connecting to ApnaCSC UPI</h3>
        <p class="gateway-subtitle">
            Redirecting to ApnaCSC secure payment gateway. Please do not refresh or close this window.
        </p>

        <div class="info-pill">
            <div class="info-row">
                <span>Order ID:</span>
                <strong class="font-monospace text-warning">{{ $orderId }}</strong>
            </div>
            <div class="info-row">
                <span>Payment Purpose:</span>
                <strong>Wallet Token Recharge</strong>
            </div>
            <div class="info-row">
                <span>Account:</span>
                <strong>{{ $user->name ?? 'User' }}</strong>
            </div>
            <div class="info-row border-top border-secondary border-opacity-25 mt-2 pt-2">
                <span>Recharge Amount:</span>
                <span class="amount">₹{{ number_format((float) $amount, 2) }}</span>
            </div>
        </div>

        <form id="apnacscPaymentForm" method="POST" action="{{ $processUrl }}">
            @foreach($apnacscParams as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach

            <button type="submit" class="btn-manual mb-2">
                Click here if not redirected automatically <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        @if(app()->environment('local'))
        <div class="mt-3 pt-3 border-top border-secondary border-opacity-25">
            <div class="badge bg-secondary-subtle text-secondary mb-2" style="font-size: 0.7rem;">LOCAL DEV SHORTCUT</div>
            <div>
                <a href="{{ route('wallet.simulate_success', ['orderId' => $orderId, 'amount' => $amount]) }}" class="btn btn-outline-success btn-sm" style="font-size: 0.8rem;">
                    <i class="bi bi-check2-circle me-1"></i>Simulate Success (Local Offline Test)
                </a>
            </div>
        </div>
        @endif

        <div class="security-badge">
            <i class="bi bi-shield-check text-info"></i> 256-Bit SSL Encrypted ApnaCSC Gateway
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                const form = document.getElementById('apnacscPaymentForm');
                if (form) {
                    form.submit();
                }
            }, 600);
        });
    </script>
</body>
</html>
