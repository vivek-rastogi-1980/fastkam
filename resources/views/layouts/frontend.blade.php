<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'FastKam.in – Online Printing & Customization Service Portal | Your Ideas, Our Printing!')</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="@yield('meta_description', 'FastKam.in is your trusted online printing and customization service portal. We offer document printing, customized mugs, t-shirts, photocopying, scanning, and digital file preparation with transparent pricing and dependable service.')">
    <meta name="keywords" content="FastKam, FastKam.in, online printing, document printing, customized mug printing, customized t-shirt printing, photocopying, document scanning, digital document preparation, online submission file prep, India printing service">
    <meta name="author" content="FastKam.in">

    <!-- Open Graph / Social Meta -->
    <meta property="og:title" content="@yield('title', 'FastKam.in – Online Printing & Customization Portal')">
    <meta property="og:description" content="FastKam.in makes everyday printing needs simple, convenient, and accessible. Document printing, custom mugs, t-shirts, scanning, and more.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('images/documents.jpg') }}">

    <!-- Google Fonts: Plus Jakarta Sans & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Modern Design System Stylesheet -->
    <style>
        :root {
            --fk-primary: #1d4ed8;
            --fk-primary-dark: #1e3a8a;
            --fk-primary-light: #3b82f6;
            --fk-secondary: #0f172a;
            --fk-accent: #06b6d4;
            --fk-accent-warm: #f97316;
            --fk-accent-green: #10b981;
            --fk-bg-light: #f8fafc;
            --fk-bg-subtle: #f1f5f9;
            --fk-card-bg: #ffffff;
            --fk-text-heading: #0f172a;
            --fk-text-body: #475569;
            --fk-text-muted: #64748b;
            --fk-border: #e2e8f0;
            --fk-radius-sm: 8px;
            --fk-radius-md: 14px;
            --fk-radius-lg: 20px;
            --fk-radius-xl: 28px;
            --fk-font-display: 'Plus Jakarta Sans', sans-serif;
            --fk-font-body: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: var(--fk-font-body);
            color: var(--fk-text-body);
            background-color: var(--fk-bg-light);
            line-height: 1.65;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .display-1, .display-2, .display-3, .display-4, .display-5, .display-6, .brand-title {
            font-family: var(--fk-font-display);
            color: var(--fk-text-heading);
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* Top Bar Styling */
        .top-bar {
            background: linear-gradient(90deg, #091322 0%, #0f1e36 50%, #162a4a 100%);
            font-size: 0.825rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .hover-white:hover {
            color: #ffffff !important;
            transition: color 0.2s ease;
        }

        /* Brand Logo */
        .brand-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #2563eb 0%, #06b6d4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.35rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover .brand-icon-box {
            transform: rotate(-6deg) scale(1.05);
        }

        .brand-title {
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1.1;
            color: #0f172a;
        }

        .brand-accent {
            color: var(--fk-primary);
        }

        .brand-sub {
            font-size: 0.7rem;
            color: var(--fk-text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Navbar */
        .main-navbar {
            backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.95) !important;
            border-bottom: 1px solid var(--fk-border);
            transition: all 0.3s ease;
        }

        .main-navbar .nav-link {
            color: #334155;
            font-size: 0.95rem;
            transition: color 0.2s ease;
            position: relative;
        }

        .main-navbar .nav-link:hover,
        .main-navbar .nav-link.active {
            color: var(--fk-primary);
        }

        /* Custom Buttons */
        .btn-primary {
            background-color: var(--fk-primary);
            border-color: var(--fk-primary);
            transition: all 0.25s ease;
        }

        .btn-primary:hover {
            background-color: var(--fk-primary-dark);
            border-color: var(--fk-primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
        }

        .btn-outline-primary {
            color: var(--fk-primary);
            border-color: var(--fk-primary);
            transition: all 0.25s ease;
        }

        .btn-outline-primary:hover {
            background-color: var(--fk-primary);
            border-color: var(--fk-primary);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.2);
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            background: radial-gradient(circle at 85% 15%, rgba(37, 99, 235, 0.08) 0%, transparent 60%),
                        radial-gradient(circle at 10% 85%, rgba(6, 182, 212, 0.08) 0%, transparent 50%),
                        linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            padding-top: 4.5rem;
            padding-bottom: 5rem;
            overflow: hidden;
        }

        .hero-badge {
            background-color: #eff6ff;
            color: var(--fk-primary);
            border: 1px solid #bfdbfe;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            padding: 0.4rem 1.1rem;
            border-radius: 9999px;
        }

        .gradient-text {
            background: linear-gradient(135deg, #1d4ed8 0%, #0284c7 50%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-lead {
            font-size: 1.15rem;
            color: #475569;
            max-width: 620px;
        }

        /* Glass / Card Effects */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: var(--fk-radius-lg);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.06);
        }

        .feature-card {
            background: #ffffff;
            border: 1px solid var(--fk-border);
            border-radius: var(--fk-radius-lg);
            padding: 2rem 1.75rem;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            border-color: #93c5fd;
            box-shadow: 0 20px 35px -10px rgba(37, 99, 235, 0.12);
        }

        .feature-icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 1.25rem;
            transition: transform 0.3s ease;
        }

        .feature-card:hover .feature-icon-wrapper {
            transform: scale(1.1) rotate(-4deg);
        }

        /* Service Cards with Visual Preview */
        .service-card {
            background: #ffffff;
            border: 1px solid var(--fk-border);
            border-radius: var(--fk-radius-lg);
            overflow: hidden;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            height: 100%;
            position: relative;
        }

        .service-card:hover {
            transform: translateY(-8px);
            border-color: #93c5fd;
            box-shadow: 0 25px 45px -12px rgba(15, 23, 42, 0.12);
        }

        .service-card-media {
            height: 210px;
            position: relative;
            overflow: hidden;
            background-color: #0f172a;
        }

        .service-card-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .service-card:hover .service-card-media img {
            transform: scale(1.06);
        }

        .service-card-media-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 40%, rgba(15, 23, 42, 0.75) 100%);
        }

        .service-card-badge {
            position: absolute;
            top: 14px;
            right: 14px;
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            z-index: 2;
        }

        .service-card-body {
            padding: 1.75rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .service-tag {
            background-color: var(--fk-bg-subtle);
            color: var(--fk-text-muted);
            border-radius: 6px;
            padding: 0.25rem 0.6rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Section Headings */
        .section-badge {
            display: inline-block;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--fk-primary);
            background-color: #eff6ff;
            border: 1px solid #dbeafe;
            padding: 0.35rem 1rem;
            border-radius: 9999px;
            margin-bottom: 0.85rem;
        }

        .section-title {
            font-size: 2.35rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 1.85rem;
            }
        }

        /* Important Notice Banner */
        .notice-banner {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1.5px dashed #f59e0b;
            border-radius: var(--fk-radius-md);
            padding: 1.5rem 2rem;
            position: relative;
        }

        /* Mission Hero Banner */
        .mission-card {
            background: linear-gradient(135deg, #091322 0%, #172554 50%, #1e3a8a 100%);
            border-radius: var(--fk-radius-xl);
            color: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 25px 50px -15px rgba(30, 58, 138, 0.35);
        }

        .mission-quote {
            font-size: 1.35rem;
            line-height: 1.6;
            font-weight: 400;
            color: #e0e7ff;
            font-style: italic;
        }

        .mission-tagline {
            font-size: 1.65rem;
            font-weight: 800;
            color: #38bdf8;
            letter-spacing: -0.01em;
        }

        /* How It Works Steps */
        .step-card {
            background: #ffffff;
            border: 1px solid var(--fk-border);
            border-radius: var(--fk-radius-lg);
            padding: 2rem 1.5rem;
            text-align: center;
            position: relative;
            transition: transform 0.3s ease;
            height: 100%;
        }

        .step-card:hover {
            transform: translateY(-5px);
        }

        .step-number {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb 0%, #06b6d4 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem auto;
            box-shadow: 0 6px 15px rgba(37, 99, 235, 0.25);
        }

        /* Modal Custom Styles */
        .modal-header-custom {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%) !important;
            color: #ffffff !important;
        }

        .modal-header-custom .modal-title {
            color: #ffffff !important;
        }

        .modal-dialog {
            margin-top: 2.5rem !important;
        }

        /* Footer Styles */
        .footer-section {
            background: #09101d;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .footer-cta-card {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        }

        .cta-circle-bg {
            position: absolute;
            right: -60px;
            top: -60px;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            pointer-events: none;
        }

        .text-light-muted {
            color: #94a3b8;
        }

        .footer-heading {
            font-size: 1.1rem;
            padding-bottom: 0.5rem;
        }

        .footer-heading::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 32px;
            height: 2.5px;
            background: #38bdf8;
            border-radius: 2px;
        }

        .footer-links li {
            margin-bottom: 0.65rem;
        }

        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            display: inline-block;
        }

        .footer-links a:hover {
            color: #38bdf8;
            transform: translateX(4px);
        }

        .social-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.08);
            color: #cbd5e1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .social-icon-btn:hover {
            background: var(--fk-primary);
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* Floating WhatsApp Button */
        .floating-whatsapp {
            position: fixed;
            bottom: 28px;
            right: 28px;
            width: 60px;
            height: 60px;
            background-color: #25d366;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.9rem;
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.45);
            z-index: 1030;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .floating-whatsapp:hover {
            transform: scale(1.1) rotate(6deg);
            color: #ffffff;
            box-shadow: 0 12px 30px rgba(37, 211, 102, 0.6);
        }

        /* Back to top */
        .back-to-top {
            position: fixed;
            bottom: 28px;
            left: 28px;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #ffffff;
            color: #1e293b;
            border: 1px solid var(--fk-border);
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
            z-index: 1020;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .back-to-top:hover {
            background: var(--fk-primary);
            color: #ffffff;
            transform: translateY(-3px);
        }

        /* Micro animation keyframes */
        @keyframes pulseSoft {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .animate-pulse-soft {
            animation: pulseSoft 2.5s infinite ease-in-out;
        }
    </style>

    @stack('styles')
</head>
<body id="top">

    <!-- Header Navigation -->
    @include('partials.frontend-header')

    <!-- Main Content Yield -->
    <main>
        @yield('content')
    </main>

    <!-- Footer Component -->
    @include('partials.frontend-footer')

    <!-- Floating WhatsApp Action -->
    <a href="https://wa.me/916398573278?text=Hello%20FastKam.in,%20I%20would%20like%20to%20inquire%20about%20your%20printing%20services!" target="_blank" class="floating-whatsapp" aria-label="Chat with FastKam.in on WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>

    <!-- Back to top button -->
    <a href="#top" class="back-to-top" id="backToTopBtn" aria-label="Back to top">
        <i class="bi bi-arrow-up"></i>
    </a>

    <!-- Quick Quote / Inquiry Modal -->
    <div class="modal fade" id="inquiryModal" tabindex="-1" aria-labelledby="inquiryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg my-4">
            <div class="modal-content border-0 rounded-4 shadow-2xl overflow-hidden">
                <div class="modal-header modal-header-custom text-white p-3 p-md-4 border-0" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%) !important;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-white text-primary rounded-3 p-2 d-flex align-items-center justify-content-center shadow-xs" style="width: 40px; height: 40px;">
                            <i class="bi bi-printer-fill fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-white mb-0" id="inquiryModalLabel">Quick Printing Quote & Order Inquiry</h5>
                            <span class="text-white-50 small">FastKam.in – Your Ideas, Our Printing!</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 p-md-5">
                    <form id="quickQuoteForm" onsubmit="handleInquirySubmit(event)">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Your Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="quoteName" class="form-control rounded-3 py-2" placeholder="e.g. Rahul Sharma" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Phone / WhatsApp Number <span class="text-danger">*</span></label>
                                <input type="tel" id="quotePhone" class="form-control rounded-3 py-2" placeholder="e.g. 6398573278" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Email Address (Optional)</label>
                                <input type="email" id="quoteEmail" class="form-control rounded-3 py-2" placeholder="name@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Select Desired Service <span class="text-danger">*</span></label>
                                <select id="quoteService" class="form-select rounded-3 py-2" required>
                                    <option value="" disabled selected>Choose a printing service...</option>
                                    <option value="Document Printing (B&W / Colour)">Document Printing (B&W / Colour)</option>
                                    <option value="Customized Mug Printing">Customized Mug Printing</option>
                                    <option value="Customized T-Shirt Printing">Customized T-Shirt Printing</option>
                                    <option value="Photocopying & Bulk Copies">Photocopying & Bulk Copies</option>
                                    <option value="Document Scanning">Document Scanning</option>
                                    <option value="Digital Document Preparation">Digital Document Preparation</option>
                                    <option value="File Prep for Online Submission">File Preparation for Online Submission</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Estimated Quantity / Pages</label>
                                <input type="text" id="quoteQuantity" class="form-control rounded-3 py-2" placeholder="e.g. 50 pages / 5 mugs">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Delivery City / Location</label>
                                <input type="text" id="quoteLocation" class="form-control rounded-3 py-2" placeholder="e.g. Mumbai, Delhi, Lucknow">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-muted">Requirements / Special Instructions</label>
                                <textarea id="quoteNotes" class="form-control rounded-3" rows="3" placeholder="Tell us more about your paper choice, design, turnaround expectation, etc."></textarea>
                            </div>
                        </div>

                        <div class="alert alert-light border small text-muted mt-3 mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-success fs-5"></i>
                            <div>Transparent Communication: We confirm exact charges and file specifications with you before processing.</div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success rounded-pill px-4 d-inline-flex align-items-center gap-2 shadow-sm">
                                <i class="bi bi-whatsapp"></i> Send via WhatsApp
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-xl overflow-hidden">
                <div class="modal-header modal-header-custom text-white p-3 p-md-4 border-0">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-white text-primary rounded-3 p-2 d-flex align-items-center justify-content-center shadow-xs" style="width: 36px; height: 36px;">
                            <i class="bi bi-file-text-fill fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-white mb-0" id="termsModalLabel">Terms & Conditions</h5>
                            <span class="text-white-50 small">FastKam.in – Operated by Ivaan Enterprises</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 p-md-5 text-secondary">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold">Effective & Last Updated: 15-10-2026</span>
                        <a href="{{ route('terms.conditions') }}" class="small text-primary fw-semibold text-decoration-none">Open Full Page <i class="bi bi-box-arrow-up-right"></i></a>
                    </div>
                    <p class="text-dark">
                        Welcome to FastKam.in (“Website,” “Portal,” “we,” “our,” or “us”), operated by Ivaan Enterprises. FastKam.in provides information about document printing, photocopying, scanning, digital document preparation, and customized printing services such as mug and T-shirt printing, subject to availability.
                    </p>
                    <p class="text-dark">
                        These Terms & Conditions govern your access to and use of FastKam.in, including any information, features, service requests, and assistance offered through our portal.
                    </p>
                    <p class="text-dark fw-medium">
                        By accessing or using our Website, you agree to these Terms & Conditions. If you do not agree with any part of these terms, please discontinue using the Website.
                    </p>

                    <hr class="my-4">

                    <h6 class="fw-bold text-dark">1. Governing Laws and Jurisdiction</h6>
                    <p class="small mb-1">These Terms & Conditions shall be governed by and interpreted in accordance with the laws of India.</p>
                    <p class="small mb-1">Our operations and services are subject to applicable Indian laws and regulations, including consumer protection and data protection requirements where applicable.</p>
                    <p class="small mb-1">Any dispute arising out of or relating to the use of FastKam.in shall be subject to the jurisdiction of the competent courts in [Dehradun/Dehradun, State, India], subject to applicable law.</p>
                    <p class="small mb-3">Nothing in these terms shall exclude or restrict any legal rights or remedies available to consumers under applicable Indian law.</p>

                    <h6 class="fw-bold text-dark">2. Legal Terms and Conditions</h6>
                    <p class="small mb-1"><strong>2.1 Nature of Services:</strong> FastKam.in provides printing and related services, including document printing, customized mug and T-shirt printing, photocopying, scanning, digital document preparation, and file preparation for printing or online submission where offered. The availability and scope of each service may vary depending on materials, equipment, file specifications, customization options, and technical availability.</p>
                    <p class="small mb-1"><strong>2.2 Nature of Business and Customer-Supplied Content:</strong> FastKam.in is an independently operated printing and customization service portal. Any third-party names, logos, or artwork appearing in customer-supplied files remain the property of their respective owners. Customers are responsible for ensuring that content submitted for printing is lawful and that they have permission to reproduce it.</p>
                    <p class="small mb-1"><strong>2.3 Accuracy of Information:</strong> We make reasonable efforts to keep the information on our Website accurate and up to date. Service options, prices, materials, and turnaround times may change based on availability. Customers should confirm the applicable service details, specifications, and charges before placing an order.</p>
                    <p class="small mb-1"><strong>2.4 User Eligibility and Responsibilities:</strong> Users must be legally capable of entering binding agreements, submit genuine information, and use the website only for lawful purposes. Users must not submit unlawful, fraudulent, or infringing content.</p>
                    <p class="small mb-1"><strong>2.5 Fees and Payments:</strong> Applicable service charges and customization costs will be communicated prior to payment. Refunds/cancellations follow the applicable refund policy and law.</p>
                    <p class="small mb-3"><strong>2.6 Service Results and Specifications:</strong> Placing an order does not guarantee a specific result where the outcome depends on the customer-supplied file quality. Printing results may vary due to file quality, colour settings, and materials.</p>

                    <h6 class="fw-bold text-dark">3. Termination and Suspension</h6>
                    <p class="small mb-3">FastKam.in reserves the right to suspend or restrict access or discontinue service in case of violation of terms, suspected unlawful activity, or maintenance/security needs.</p>

                    <h6 class="fw-bold text-dark">4. Intellectual Property Rights</h6>
                    <p class="small mb-3">All original text, website design, graphics, branding, and layouts are owned by or licensed to the Website operator. Unauthorized reproduction or commercial exploitation is prohibited.</p>

                    <h6 class="fw-bold text-dark">5. Limitation of Liability</h6>
                    <p class="small mb-3">FastKam.in exercises reasonable care but is not liable for losses arising from circumstances beyond control, including supplier/delivery delays, technical failures, or customer-supplied file defects.</p>

                    <h6 class="fw-bold text-dark">6. Privacy and Personal Information</h6>
                    <p class="small mb-3">Personal information and files are handled in accordance with our <a href="{{ route('privacy.policy') }}">Privacy Policy</a> and applicable data protection laws.</p>

                    <h6 class="fw-bold text-dark">7. Third-Party Websites and Services</h6>
                    <p class="small mb-3">External links are provided for convenience; users should verify trusted payment pages.</p>

                    <h6 class="fw-bold text-dark">8. Service Availability and Changes</h6>
                    <p class="small mb-3">Website features and service descriptions may be modified or discontinued as necessary.</p>

                    <h6 class="fw-bold text-dark">9. Prohibited Activities</h6>
                    <p class="small mb-0">Users must not use the Website to submit unlawful or infringing content, interfere with its operation, or misuse another person's personal information.</p>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('terms.conditions') }}" class="btn btn-primary rounded-pill px-4">Full Terms Page</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-xl overflow-hidden">
                <div class="modal-header modal-header-custom text-white p-3 p-md-4 border-0">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-white text-primary rounded-3 p-2 d-flex align-items-center justify-content-center shadow-xs" style="width: 36px; height: 36px;">
                            <i class="bi bi-shield-check fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-white mb-0" id="privacyModalLabel">Privacy Policy & Service Information</h5>
                            <span class="text-white-50 small">FastKam.in – Your Ideas, Our Printing!</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 p-md-5 text-secondary">
                    <div class="mb-4">
                        <h4 class="fw-bold text-dark mb-2">Welcome to FastKam.in</h4>
                        <p class="lead fs-6 text-dark mb-0">
                            FastKam.in is an online printing and customization service portal created to make everyday printing needs simple, convenient, and accessible. We help customers print documents and create personalized products such as mugs and T-shirts for personal, educational, and business use.
                        </p>
                    </div>

                    <hr class="my-4">

                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-people-fill text-primary me-2"></i>Who We Are</h5>
                        <p class="mb-2">
                            We provide document printing, photocopying, scanning, digital document preparation, and customized printing services. Whether you need a clear document printout or a personalized gift, we aim to offer helpful service and quality results.
                        </p>
                        <p class="mb-0">
                            At FastKam.in, we focus on customer convenience, transparent communication, and dependable service. We help customers understand available printing options, customization choices, file requirements, and applicable charges before proceeding.
                        </p>
                    </div>

                    <hr class="my-4">

                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-printer-fill text-primary me-2"></i>Our Services</h5>
                        <p class="mb-3">FastKam.in offers printing and document-related services, including:</p>
                        <ul class="list-group list-group-flush border rounded-3 mb-3">
                            <li class="list-group-item d-flex align-items-start gap-2 py-2">
                                <span class="badge bg-primary-subtle text-primary mt-1">&bull;</span>
                                <div><strong>Document Printing:</strong> Black-and-white and colour printing for personal, educational, and business documents.</div>
                            </li>
                            <li class="list-group-item d-flex align-items-start gap-2 py-2">
                                <span class="badge bg-danger-subtle text-danger mt-1">&bull;</span>
                                <div><strong>Customized Mug Printing:</strong> Personalized mugs featuring photos, names, messages, designs, or logos, subject to available options.</div>
                            </li>
                            <li class="list-group-item d-flex align-items-start gap-2 py-2">
                                <span class="badge bg-warning-subtle text-warning mt-1">&bull;</span>
                                <div><strong>Customized T-Shirt Printing:</strong> T-shirts printed with selected text, photos, artwork, or logos, subject to available options.</div>
                            </li>
                            <li class="list-group-item d-flex align-items-start gap-2 py-2">
                                <span class="badge bg-info-subtle text-info mt-1">&bull;</span>
                                <div><strong>Photocopying:</strong> Convenient photocopying for everyday document needs.</div>
                            </li>
                            <li class="list-group-item d-flex align-items-start gap-2 py-2">
                                <span class="badge bg-secondary-subtle text-secondary mt-1">&bull;</span>
                                <div><strong>Document Scanning:</strong> Converting physical documents into digital files.</div>
                            </li>
                            <li class="list-group-item d-flex align-items-start gap-2 py-2">
                                <span class="badge bg-success-subtle text-success mt-1">&bull;</span>
                                <div><strong>Digital Document Preparation:</strong> Basic preparation and formatting of digital files for printing or sharing.</div>
                            </li>
                            <li class="list-group-item d-flex align-items-start gap-2 py-2">
                                <span class="badge text-white mt-1" style="background-color: #6366f1;">&bull;</span>
                                <div><strong>File Preparation for Online Submission:</strong> Assistance with file formats and size requirements where applicable.</div>
                            </li>
                        </ul>

                        <div class="alert alert-warning border-warning-subtle rounded-3 small mb-0">
                            <i class="bi bi-info-circle-fill me-1"></i>
                            <strong>Service Availability Notice:</strong> Service availability, materials, customization options, prices, and turnaround times may vary. Please contact us to confirm details before placing an order.
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="p-3 rounded-3 bg-light border">
                        <h6 class="fw-bold text-dark mb-1"><i class="bi bi-compass-fill text-primary me-2"></i>Our Mission</h6>
                        <p class="mb-2 fst-italic">
                            “Our mission is to make printing and customization convenient, accessible, and customer-friendly. We strive to provide clear information, helpful assistance, and a smooth service experience for every customer.”
                        </p>
                        <div class="fw-bold text-primary">FastKam.in – Your Ideas, Our Printing!</div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                    <a href="https://wa.me/916398573278" target="_blank" class="btn btn-success rounded-pill px-4 d-inline-flex align-items-center gap-2">
                        <i class="bi bi-whatsapp"></i> Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Script -->
    <script>
        // Back to top behavior
        const backToTopBtn = document.getElementById('backToTopBtn');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 350) {
                backToTopBtn.style.display = 'flex';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });

        // WhatsApp Quick Inquiry Generator
        function handleInquirySubmit(e) {
            e.preventDefault();
            const name = document.getElementById('quoteName').value;
            const phone = document.getElementById('quotePhone').value;
            const email = document.getElementById('quoteEmail').value || 'N/A';
            const service = document.getElementById('quoteService').value;
            const quantity = document.getElementById('quoteQuantity').value || '1';
            const location = document.getElementById('quoteLocation').value || 'N/A';
            const notes = document.getElementById('quoteNotes').value || 'Standard request';

            const message = `*New Printing Inquiry - FastKam.in*%0A%0A` +
                            `*Customer Name:* ${encodeURIComponent(name)}%0A` +
                            `*Phone:* ${encodeURIComponent(phone)}%0A` +
                            `*Email:* ${encodeURIComponent(email)}%0A` +
                            `*Service:* ${encodeURIComponent(service)}%0A` +
                            `*Estimated Qty/Pages:* ${encodeURIComponent(quantity)}%0A` +
                            `*Location:* ${encodeURIComponent(location)}%0A` +
                            `*Notes:* ${encodeURIComponent(notes)}%0A%0A` +
                            `_Sent via FastKam.in Instant Portal_`;

            const whatsappUrl = `https://wa.me/916398573278?text=${message}`;
            window.open(whatsappUrl, '_blank');
        }
    </script>

    @stack('scripts')
</body>
</html>
