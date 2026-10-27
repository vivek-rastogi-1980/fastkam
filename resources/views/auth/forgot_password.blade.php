<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | a4Digital – All-in-One Portal</title>

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

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            margin-bottom: 0.25rem;
        }

        .form-subtitle {
            color: #94a3b8;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            line-height: 1.5;
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
            margin-bottom: 1.25rem;
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
            margin-bottom: 1.25rem;
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
            text-decoration: none;
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
                Recover Your<br>
                <span>a4Digital Credentials</span>
            </h1>
            <p class="welcome-text">Ek hi Portal, Har Samasya Ka Samadhaan! ⚡

Apna registered email ya phone darj karein aur aasaani se apna password reset karein.</p>
            
            <div class="quote-section">
                <div class="quote-text">"Security and simplicity in every detail."</div>
                <div class="quote-author">— SECURITY PHILOSOPHY</div>
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

            <h2 class="form-title mb-1">Forgot Password?</h2>
            <p class="form-subtitle">Enter your registered email address or phone number to recover your credentials.</p>

            @if(session('status'))
                <div class="alert alert-success py-2 px-3 small bg-transparent text-success border-success mb-3">
                    <div class="d-flex align-items-center gap-1.5 mb-1">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                    @if(session('reset_url'))
                        <div class="mt-2 pt-2 border-top border-success border-opacity-25">
                            <a href="{{ session('reset_url') }}" class="btn btn-sm btn-info text-dark fw-bold w-100 py-1.5 shadow-sm d-flex align-items-center justify-content-center gap-1.5">
                                <i class="bi bi-key-fill"></i> Proceed to Create New Password
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2 small bg-transparent text-danger border-danger mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                
                <label class="form-label-custom">REGISTERED EMAIL OR PHONE</label>
                <div class="input-group-custom">
                    <span class="input-icon"><i class="bi bi-envelope-at-fill"></i></span>
                    <input type="text" class="form-control-custom" name="email" value="{{ old('email') }}" placeholder="Enter email or 10-digit mobile number" required autofocus>
                </div>

                <button type="submit" class="btn-enter">
                    <i class="bi bi-send-fill"></i> SEND RESET LINK
                </button>
            </form>

            <div class="or-divider">OR</div>

            <a href="{{ route('login') }}" class="btn-register">
                <i class="bi bi-arrow-left"></i> BACK TO SIGN IN
            </a>

            <div class="encryption-note">
                Protected by 256-bit secure encryption <i class="bi bi-shield-check text-info"></i>
            </div>
        </div>
    </div>

</body>
</html>
