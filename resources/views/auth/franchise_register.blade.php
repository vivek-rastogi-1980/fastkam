<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Franchise Onboarding | a4Digital – All-in-One Portal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400;1,600&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
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

        .login-wrapper {
            display: flex;
            background: #0f172a;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            max-width: 1060px;
            width: 100%;
            overflow: hidden;
            border: 1px solid #1e293b;
        }

        .left-panel {
            flex: 0 0 380px;
            padding: 3rem;
            background: linear-gradient(to bottom right, #0b1121, #0f172a);
            border-right: 1px solid #1e293b;
            position: relative;
            display: flex;
            flex-direction: column;
        }
        
        /* Grid pattern background */
        .left-panel::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: linear-gradient(#1e293b 1px, transparent 1px),
                              linear-gradient(90deg, #1e293b 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.15;
            pointer-events: none;
        }

        .logo-circle {
            width: 45px;
            height: 45px;
            border: 1px solid #38bdf8;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #38bdf8;
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .welcome-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.1rem;
            line-height: 1.3;
            margin-bottom: 1.25rem;
            position: relative;
        }
        
        .welcome-title span {
            color: #7dd3fc;
            font-style: italic;
        }

        .welcome-text {
            color: #94a3b8;
            line-height: 1.6;
            font-size: 0.92rem;
            position: relative;
            margin-bottom: 2rem;
            white-space: pre-line;
        }

        .perks-list {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .perk-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.85rem;
            color: #cbd5e1;
        }

        .perk-item i {
            font-size: 1rem;
        }

        .quote-section {
            margin-top: auto;
            position: relative;
        }

        .quote-text {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            color: #cbd5e1;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        .quote-author {
            color: #38bdf8;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .right-panel {
            flex: 1;
            padding: 3rem;
            background: #0f172a;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .members-only {
            color: #38bdf8;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 0.75rem;
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            margin-bottom: 0.25rem;
        }

        .form-subtitle {
            color: #94a3b8;
            font-size: 0.85rem;
            margin-bottom: 1.75rem;
        }
        
        .form-subtitle a {
            color: #38bdf8;
            text-decoration: none;
            font-weight: 500;
        }

        .form-label-custom {
            font-size: 0.75rem;
            font-weight: 600;
            color: #94a3b8;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 0.4rem;
            display: block;
        }

        .form-label-custom .text-danger {
            color: #f87171 !important;
        }

        .input-group-custom {
            background: #0b1120;
            border: 1px solid #1e293b;
            border-radius: 8px;
            display: flex;
            align-items: center;
            overflow: hidden;
            margin-bottom: 1rem;
            transition: all 0.2s;
        }
        
        .input-group-custom:focus-within {
            border-color: #38bdf8;
            box-shadow: 0 0 0 1px #38bdf8;
        }

        .input-icon {
            padding: 0.75rem 1rem;
            color: #64748b;
        }

        .input-icon-btn {
            background: transparent;
            border: none;
            color: #64748b;
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: color 0.2s;
        }

        .input-icon-btn:hover {
            color: #38bdf8;
        }

        .form-control-custom {
            background: transparent;
            border: none;
            color: #fff;
            width: 100%;
            padding: 0.75rem 0.75rem 0.75rem 0;
            font-size: 0.9rem;
        }
        
        .form-control-custom:focus {
            outline: none;
        }

        .form-control-custom::placeholder {
            color: #475569;
        }

        .password-hint {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: -0.5rem;
            margin-bottom: 1.25rem;
        }

        .btn-enter {
            background: linear-gradient(to right, #38bdf8, #7dd3fc);
            color: #0b1120;
            border: none;
            border-radius: 8px;
            padding: 0.85rem;
            font-weight: 700;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            margin-bottom: 1.5rem;
            transition: opacity 0.2s;
            box-shadow: 0 4px 14px rgba(56, 189, 248, 0.3);
        }
        
        .btn-enter:hover {
            opacity: 0.9;
            color: #0b1120;
        }

        .encryption-note {
            text-align: center;
            color: #64748b;
            font-size: 0.75rem;
        }

        @media (max-width: 991px) {
            .login-wrapper {
                flex-direction: column;
            }
            .left-panel {
                flex: none;
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #1e293b;
                padding: 2.5rem 2rem;
            }
            .right-panel {
                padding: 2.5rem 2rem;
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <!-- Left Panel -->
        <div class="left-panel">
            <div class="logo-circle">
                V
            </div>
            <h1 class="welcome-title">
                Welcome to the<br>
                <span>a4Digital – All-in-One Portal</span>
            </h1>
            <p class="welcome-text">Ek hi Login, Saare Kaam Aasaan! ⚡

Sabhi zaroori services ka access ek hi portal par — fast, secure aur exclusive, taaki aapka har kaam ho seconds mein.</p>

            <div class="perks-list">
                <div class="perk-item">
                    <i class="bi bi-shield-check text-info"></i>
                    <span>Instant Kendra Onboarding & Portal Access</span>
                </div>
                <div class="perk-item">
                    <i class="bi bi-lightning-charge-fill text-warning"></i>
                    <span>Real-time High Speed Processing</span>
                </div>
                <div class="perk-item">
                    <i class="bi bi-wallet2 text-success"></i>
                    <span>Dedicated Automated Wallet System</span>
                </div>
            </div>
            
            <div class="quote-section">
                <div class="quote-text">"Reliable speed for every Kendra transaction."</div>
                <div class="quote-author">— FRANCHISE NETWORK</div>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <div class="members-only">FRANCHISE ONBOARDING</div>
            <h2 class="form-title">Onboard New Kendra</h2>
            <p class="form-subtitle">Complete your registration to get immediate portal access. Already registered? <a href="{{ route('login') }}">Sign In</a></p>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small bg-transparent text-danger border-danger mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('franchise.register.submit', request()->query()) }}">
                @csrf

                <div class="row g-2">
                    <!-- Franchise Name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label-custom">Franchise Name / Kendra <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="bi bi-shop"></i></span>
                            <input type="text" class="form-control-custom" id="name" name="name" placeholder="e.g. Om Digital Seva Kendra" value="{{ old('name') }}" required autofocus>
                        </div>
                    </div>
                    
                    <!-- Full Name -->
                    <div class="col-md-6">
                        <label for="full_name" class="form-label-custom">Full Name (Owner / Operator) <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="bi bi-person-fill"></i></span>
                            <input type="text" class="form-control-custom" id="full_name" name="full_name" placeholder="e.g. Rajesh Kumar Sharma" value="{{ old('full_name') }}" required>
                        </div>
                    </div>

                    <!-- Login Email -->
                    <div class="col-md-6">
                        <label for="email" class="form-label-custom">Login Email <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="bi bi-envelope-fill"></i></span>
                            <input type="email" class="form-control-custom" id="email" name="email" placeholder="franchise@example.com" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    
                    <!-- Contact Phone -->
                    <div class="col-md-6">
                        <label for="phone" class="form-label-custom">Contact Phone <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="bi bi-telephone-fill"></i></span>
                            <input type="text" class="form-control-custom" id="phone" name="phone" placeholder="10-digit mobile number" value="{{ old('phone') }}" required>
                        </div>
                    </div>

                    <!-- Initial Password -->
                    <div class="col-md-6">
                        <label for="password" class="form-label-custom">Initial Password <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control-custom" id="password" name="password" placeholder="Min 8 characters" required>
                            <button type="button" class="input-icon-btn toggle-password" data-target="password" title="Toggle password visibility"><i class="bi bi-eye-fill"></i></button>
                        </div>
                    </div>

                    <!-- Password Reconfirm -->
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label-custom">Password Reconfirm <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="bi bi-shield-lock-fill"></i></span>
                            <input type="password" class="form-control-custom" id="password_confirmation" name="password_confirmation" placeholder="Re-enter password" required>
                            <button type="button" class="input-icon-btn toggle-password" data-target="password_confirmation" title="Toggle password visibility"><i class="bi bi-eye-fill"></i></button>
                        </div>
                    </div>
                </div>

                <div class="password-hint">
                    <i class="bi bi-info-circle me-1"></i> Password must contain at least 2 uppercase letters and 1 special character.
                </div>

                <!-- Hidden non-mandatory fields (kept for backward compatibility) -->
                <div class="d-none">
                    <input type="date" id="dob" name="dob" value="{{ old('dob') }}">
                    <input type="text" id="gov_id_type" name="gov_id_type" value="{{ old('gov_id_type') }}">
                    <input type="text" id="gov_id_number" name="gov_id_number" value="{{ old('gov_id_number') }}">
                    <textarea id="address" name="address">{{ old('address') }}</textarea>
                </div>

                <button type="submit" class="btn-enter">
                    <i class="bi bi-credit-card-fill"></i> PROCEED TO PAY ₹100
                </button>
            </form>

            <div class="encryption-note">
                Protected by 256-bit SSL encrypted onboarding <i class="bi bi-lock-fill text-warning"></i>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye-fill');
                    icon.classList.add('bi-eye-slash-fill');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash-fill');
                    icon.classList.add('bi-eye-fill');
                }
            });
        });
    </script>
</body>
</html>
