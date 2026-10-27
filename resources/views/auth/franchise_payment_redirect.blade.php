<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Paytm | a4Digital</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b1120;
            background-image: radial-gradient(circle at 50% -20%, #1a2a42, #0b1120);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            color: #fff;
        }

        .redirect-card {
            background: #0f172a;
            border: 1px solid #1e293b;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            max-width: 480px;
            width: 100%;
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .spinner-custom {
            width: 3.5rem;
            height: 3.5rem;
            border: 3px solid rgba(56, 189, 248, 0.15);
            border-top-color: #38bdf8;
            border-radius: 50%;
            animation: spin 0.9s linear infinite;
            margin: 0 auto 1.5rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .gateway-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            margin-bottom: 0.5rem;
        }

        .gateway-subtitle {
            color: #94a3b8;
            font-size: 0.88rem;
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
            padding: 0.25rem 0;
        }

        .info-row strong {
            color: #f1f5f9;
        }

        .info-row .amount {
            color: #38bdf8;
            font-weight: 700;
            font-size: 1.05rem;
        }

        .btn-manual {
            background: linear-gradient(to right, #38bdf8, #7dd3fc);
            color: #0b1120;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn-manual:hover {
            opacity: 0.9;
            color: #0b1120;
        }

        .security-badge {
            color: #64748b;
            font-size: 0.75rem;
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
        }
    </style>
</head>
<body>

    <div class="redirect-card">
        <div class="spinner-custom"></div>

        <h3 class="gateway-title">Connecting to Paytm</h3>
        <p class="gateway-subtitle">Redirecting to Paytm secure checkout. Please do not refresh or close this window.</p>

        <div class="info-pill">
            <div class="info-row">
                <span>Order ID:</span>
                <strong>{{ $paytmParams['ORDER_ID'] ?? '' }}</strong>
            </div>
            <div class="info-row">
                <span>Payment Purpose:</span>
                <strong>Franchise Onboarding</strong>
            </div>
            <div class="info-row border-top border-secondary border-opacity-25 mt-2 pt-2">
                <span>Total Payable:</span>
                <span class="amount">₹{{ number_format((float) ($paytmParams['TXN_AMOUNT'] ?? 100), 2) }}</span>
            </div>
        </div>

        <form id="paymentForm" method="POST" action="{{ $txnUrl }}">
            @foreach($paytmParams as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach

            <button type="submit" class="btn-manual mb-2">
                Click here if not redirected automatically <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <div class="security-badge">
            <i class="bi bi-shield-lock-fill text-warning"></i> 256-Bit SSL Encrypted Paytm Payment Gateway
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                document.getElementById('paymentForm').submit();
            }, 800);
        });
    </script>
</body>
</html>
