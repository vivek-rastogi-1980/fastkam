<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | a4Digital – All-in-One Portal</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400;1,600&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Noto+Sans+Devanagari:wght@400;500;600&display=swap" rel="stylesheet">
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
            padding: 2rem;
            color: #fff;
        }

        .login-wrapper {
            display: flex;
            background: #0f172a;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            max-width: 900px;
            width: 100%;
            overflow: hidden;
            border: 1px solid #1e293b;
        }

        .left-panel {
            flex: 1;
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
            margin-bottom: 3rem;
            position: relative;
        }

        .welcome-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            line-height: 1.3;
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .welcome-title span {
            color: #7dd3fc;
            font-style: italic;
        }

        .welcome-text {
            color: #94a3b8;
            line-height: 1.6;
            font-size: 0.95rem;
            position: relative;
            margin-bottom: 2rem;
            white-space: pre-line;
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

        .login-marquee-container {
            background: rgba(14, 165, 233, 0.08);
            border: 1px solid rgba(56, 189, 248, 0.22);
            border-radius: 10px;
            padding: 0.45rem 0.75rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            overflow: hidden;
            box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.25);
            font-family: 'Plus Jakarta Sans', 'Noto Sans Devanagari', sans-serif;
        }

        .marquee-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            white-space: nowrap;
            letter-spacing: 0.5px;
            border: 1px solid rgba(56, 189, 248, 0.3);
            flex-shrink: 0;
        }

        .pulse-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 8px #10b981;
            display: inline-block;
            animation: pulse-dot 1.5s infinite ease-in-out;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }

        .login-marquee-container marquee {
            color: #bae6fd;
            font-size: 0.85rem;
            font-weight: 500;
            line-height: 1.4;
            display: block;
        }

        .members-only {
            color: #38bdf8;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            margin-bottom: 0.25rem;
        }

        .form-subtitle {
            color: #94a3b8;
            font-size: 0.85rem;
            margin-bottom: 2rem;
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
            margin-bottom: 0.5rem;
            display: block;
        }

        .input-group-custom {
            background: #0b1120;
            border: 1px solid #1e293b;
            border-radius: 8px;
            display: flex;
            align-items: center;
            overflow: hidden;
            margin-bottom: 1.25rem;
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

        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
        }

        .form-check-label {
            color: #94a3b8;
            cursor: pointer;
        }

        .forgot-link {
            color: #38bdf8;
            text-decoration: none;
            font-weight: 500;
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
            margin-bottom: 1.5rem;
            transition: opacity 0.2s;
            box-shadow: 0 4px 14px rgba(56, 189, 248, 0.3);
        }
        
        .btn-enter:hover {
            opacity: 0.9;
            color: #0b1120;
        }

        .or-divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: #475569;
            font-size: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .or-divider::before, .or-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #1e293b;
        }
        
        .or-divider::before { margin-right: 1rem; }
        .or-divider::after { margin-left: 1rem; }

        .btn-register {
            background: transparent;
            color: #e2e8f0;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 0.85rem;
            font-weight: 600;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        
        .btn-register:hover {
            border-color: #475569;
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .encryption-note {
            text-align: center;
            color: #64748b;
            font-size: 0.75rem;
            margin-top: 1.5rem;
        }

        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
            }
            .left-panel {
                padding: 2rem;
            }
            .right-panel {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <!-- Left Panel -->
        <div class="left-panel">
            <h1 class="welcome-title">
                Welcome to the<br>
                <span>a4Digital – All-in-One Portal</span>
            </h1>
            <p class="welcome-text">Ek hi Login, Saare Kaam Aasaan! ⚡

Sabhi zaroori services ka access ek hi portal par — fast, secure aur exclusive, taaki aapka har kaam ho seconds mein.</p>
            
            <div class="quote-section">
                <div class="quote-text">"Luxury is in each detail."</div>
                <div class="quote-author">— HOUSE PHILOSOPHY</div>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <!-- 24x7 Support Announcement Marquee -->
            <div class="login-marquee-container">
                <div class="marquee-tag">
                    <span class="pulse-status-dot"></span>
                    <span>24x7</span>
                </div>
                <marquee behavior="scroll" direction="left" scrollamount="5" onmouseover="this.stop();" onmouseout="this.start();">
                    हमारी सेवाएँ आपके लिए 24 घंटे और सातों दिन (24x7) उपलब्ध हैं। किसी भी प्रकार की समस्या होने पर हमारी टीम आपको पूरा सहयोग और सहायता प्रदान करेगी।
                </marquee>
            </div>

            <h2 class="form-title mb-3">Sign in to your account</h2>

            @if(session('success'))
                <div class="alert alert-success py-2 small bg-transparent text-success border-success">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info py-2 small bg-transparent text-info border-info">{{ session('info') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2 small bg-transparent text-danger border-danger">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                
                <div class="input-group-custom">
                    <span class="input-icon"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control-custom" name="email" value="{{ old('email') }}" placeholder="Enter email, phone or user ID" required autofocus>
                </div>

                <label class="form-label-custom">PASSWORD</label>
                <div class="input-group-custom">
                    <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" class="form-control-custom" id="passwordInput" name="password" placeholder="••••••••••••" required>
                    <span class="input-icon" id="togglePasswordBtn" style="cursor: pointer;" title="Show/Hide Password">
                        <i class="bi bi-eye-fill" id="togglePasswordIcon"></i>
                    </span>
                </div>

                <div class="options-row">
                    <div class="form-check">
                        <input class="form-check-input bg-transparent border-secondary" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-enter">
                    <i class="bi bi-box-arrow-in-right"></i> ENTER ACCOUNT
                </button>
            </form>

            <div class="encryption-note">
                Protected by encrypted login <i class="bi bi-lock-fill text-warning"></i>
            </div>
        </div>
    </div>

    <!-- Password Visibility Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('togglePasswordBtn');
            const passwordInput = document.getElementById('passwordInput');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            if (toggleBtn && passwordInput && toggleIcon) {
                toggleBtn.addEventListener('click', function () {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    if (isPassword) {
                        toggleIcon.classList.remove('bi-eye-fill');
                        toggleIcon.classList.add('bi-eye-slash-fill');
                    } else {
                        toggleIcon.classList.remove('bi-eye-slash-fill');
                        toggleIcon.classList.add('bi-eye-fill');
                    }
                });
            }
        });
    </script>
</body>
</html>
