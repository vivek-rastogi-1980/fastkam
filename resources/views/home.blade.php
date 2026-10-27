@extends('layouts.frontend')

@section('title', 'FastKam.in – Welcome to FastKam.in | Your Ideas, Our Printing!')
@section('meta_description', 'FastKam.in is an online printing and customization service portal created to make everyday printing needs simple, convenient, and accessible. Document printing, custom mugs, t-shirts, photocopying, scanning, and file preparation.')

@section('content')

<!-- ===================================================================
     1. HERO SECTION
=================================================================== -->
<section class="hero-section" id="hero">
    <div class="container position-relative z-1">
        <div class="row align-items-center g-5">
            <!-- Left Hero Content -->
            <div class="col-lg-7">
                <div class="hero-badge mb-3 shadow-xs">
                    <i class="bi bi-stars text-warning me-2 fs-6"></i>
                    <span>Premier Online Printing & Customization Portal</span>
                </div>

                <h1 class="display-4 fw-extrabold mb-3">
                    Welcome to <span class="gradient-text">FastKam.in</span>
                </h1>

                <p class="hero-lead mb-4">
                    <strong>FastKam.in</strong> is an online printing and customization service portal created to make everyday printing needs simple, convenient, and accessible. We help customers print documents and create personalized products such as mugs and T-shirts for personal, educational, and business use.
                </p>

                <!-- Action CTA Buttons -->
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="#services" class="btn btn-primary btn-lg rounded-pill px-4 py-3 fw-bold d-inline-flex align-items-center gap-2 shadow">
                        <i class="bi bi-grid-fill"></i> Explore Our Services
                    </a>
                    <a href="https://wa.me/916398573278?text=Hi%20FastKam.in,%20I%20would%20like%20to%20place%20a%20printing%20order!" target="_blank" class="btn btn-outline-success btn-lg rounded-pill px-4 py-3 fw-bold d-inline-flex align-items-center gap-2 shadow-xs">
                        <i class="bi bi-whatsapp fs-5"></i> Order via WhatsApp
                    </a>
                    <button type="button" class="btn btn-outline-dark btn-lg rounded-pill px-4 py-3 fw-bold d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#inquiryModal">
                        <i class="bi bi-calculator"></i> Quick Quote
                    </button>
                </div>

                <!-- Trust Points -->
                <div class="row g-3 pt-3 border-top border-light-subtle">
                    <div class="col-6 col-sm-3 d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        <span class="small fw-semibold text-secondary">Simple & Accessible</span>
                    </div>
                    <div class="col-6 col-sm-3 d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        <span class="small fw-semibold text-secondary">B&W and Colour</span>
                    </div>
                    <div class="col-6 col-sm-3 d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        <span class="small fw-semibold text-secondary">Customized Gifts</span>
                    </div>
                    <div class="col-6 col-sm-3 d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        <span class="small fw-semibold text-secondary">Doorstep Delivery</span>
                    </div>
                </div>
            </div>

            <!-- Right Hero Visual Showcase -->
            <div class="col-lg-5">
                <div class="position-relative">
                    <!-- Main Showcase Card -->
                    <div class="card border-0 rounded-4 shadow-xl overflow-hidden glass-card p-3">
                        <div class="position-relative rounded-3 overflow-hidden" style="height: 320px;">
                            <img src="{{ asset('images/documents.jpg') }}" alt="FastKam.in Document Printing" class="w-100 h-100 object-fit-cover">
                            <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-gradient text-white" style="background: linear-gradient(0deg, rgba(15,23,42,0.9) 0%, transparent 100%);">
                                <span class="badge bg-primary text-uppercase px-2 py-1 mb-1">Crisp Quality</span>
                                <h6 class="fw-bold mb-0 text-white">Professional Document & Report Printing</h6>
                            </div>
                        </div>

                        <!-- Mini Floating Cards -->
                        <div class="row g-2 mt-2">
                            <div class="col-6">
                                <div class="card border-0 rounded-3 shadow-sm overflow-hidden position-relative" style="height: 120px;">
                                    <img src="{{ asset('images/mug.jpg') }}" alt="Customized Mug" class="w-100 h-100 object-fit-cover">
                                    <div class="position-absolute bottom-0 start-0 end-0 p-2 text-white" style="background: rgba(15,23,42,0.75);">
                                        <span class="small fw-bold d-block text-truncate">Custom Mugs</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card border-0 rounded-3 shadow-sm overflow-hidden position-relative" style="height: 120px;">
                                    <img src="{{ asset('images/tshirt.jpg') }}" alt="Customized T-Shirt" class="w-100 h-100 object-fit-cover">
                                    <div class="position-absolute bottom-0 start-0 end-0 p-2 text-white" style="background: rgba(15,23,42,0.75);">
                                        <span class="small fw-bold d-block text-truncate">Custom T-Shirts</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Quality Badge -->
                    <div class="position-absolute top-0 end-0 translate-middle-y bg-white rounded-pill px-3 py-2 shadow-lg border d-none d-sm-flex align-items-center gap-2 animate-pulse-soft">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="text-start">
                            <div class="fw-bold text-dark text-xs">Quality Results</div>
                            <div class="text-muted text-2xs">Inspected Before Dispatch</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ===================================================================
     2. WHO WE ARE SECTION
=================================================================== -->
<section class="py-5 bg-white border-top border-bottom" id="about">
    <div class="container py-lg-4">
        <div class="row align-items-center g-5">
            <!-- Left Column: Story & Philosophy -->
            <div class="col-lg-6">
                <span class="section-badge"><i class="bi bi-people-fill me-1"></i> Who We Are</span>
                <h2 class="section-title">Your Everyday Printing & Personalization Partner</h2>
                
                <p class="fs-5 text-dark fw-medium mb-3">
                    We provide document printing, photocopying, scanning, digital document preparation, and customized printing services. Whether you need a clear document printout or a personalized gift, we aim to offer helpful service and quality results.
                </p>

                <p class="text-secondary mb-4">
                    At FastKam.in, we focus on <strong>customer convenience</strong>, <strong>transparent communication</strong>, and <strong>dependable service</strong>. We help customers understand available printing options, customization choices, file requirements, and applicable charges before proceeding.
                </p>

                <!-- 3 Key Pillars -->
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="feature-icon-wrapper bg-primary-subtle text-primary mt-1" style="width: 44px; height: 44px; min-width: 44px;">
                            <i class="bi bi-hand-thumbs-up-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Customer Convenience First</h6>
                            <p class="text-muted small mb-0">From easy digital submissions over WhatsApp/Email to doorstep courier deliveries, we eliminate printing hassles.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="feature-icon-wrapper bg-info-subtle text-info mt-1" style="width: 44px; height: 44px; min-width: 44px;">
                            <i class="bi bi-chat-heart-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Transparent Communication</h6>
                            <p class="text-muted small mb-0">No surprise charges. We outline materials, resolutions, customization options, and exact rates prior to confirmation.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="feature-icon-wrapper bg-warning-subtle text-warning mt-1" style="width: 44px; height: 44px; min-width: 44px;">
                            <i class="bi bi-patch-check-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Dependable Service & Results</h6>
                            <p class="text-muted small mb-0">Whether it’s a 1-page urgent resume, 500 college project booklets, or corporate mugs, we deliver with consistent quality.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Interactive 4-Box Feature Matrix -->
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="feature-card shadow-xs">
                            <div class="feature-icon-wrapper bg-primary text-white">
                                <i class="bi bi-printer"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Everyday Documents</h5>
                            <p class="text-muted small mb-0">Clear black-and-white & high-saturation colour prints on 75 to 300 GSM paper.</p>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="feature-card shadow-xs">
                            <div class="feature-icon-wrapper bg-danger text-white">
                                <i class="bi bi-gift"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Personalized Gifts</h5>
                            <p class="text-muted small mb-0">Customized photo mugs, event souvenirs, and printed apparel with precision sublimation.</p>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="feature-card shadow-xs">
                            <div class="feature-icon-wrapper bg-success text-white">
                                <i class="bi bi-file-earmark-check"></i>
                            </div>
                            <h5 class="fw-bold mb-2">File Assistance</h5>
                            <p class="text-muted small mb-0">Digital file optimization, margins, PDF conversions, and sizing for online application portals.</p>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="feature-card shadow-xs">
                            <div class="feature-icon-wrapper bg-indigo text-white" style="background-color: #6366f1;">
                                <i class="bi bi-truck"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Prompt Delivery</h5>
                            <p class="text-muted small mb-0">Safe packaging and swift courier dispatch straight to your home, office, or institution.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ===================================================================
     3. OUR SERVICES SECTION
=================================================================== -->
<section class="py-5" id="services" style="background-color: var(--fk-bg-light);">
    <div class="container py-lg-4">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-5">
            <span class="section-badge"><i class="bi bi-gear-wide-connected me-1"></i> Our Services</span>
            <h2 class="section-title">Comprehensive Printing & Document Services</h2>
            <p class="text-secondary fs-6">
                FastKam.in offers printing and document-related services, including:
            </p>
        </div>

        <!-- Services Grid -->
        <div class="row g-4">
            
            <!-- 1. Document Printing -->
            <div class="col-lg-4 col-md-6" id="service-doc-print">
                <div class="service-card shadow-sm">
                    <div class="service-card-media">
                        <img src="{{ asset('images/documents.jpg') }}" alt="Document Printing FastKam.in">
                        <div class="service-card-media-overlay"></div>
                        <span class="service-card-badge bg-primary text-white">Essential</span>
                    </div>
                    <div class="service-card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="text-primary fs-4"><i class="bi bi-file-earmark-text"></i></div>
                            <h4 class="fw-bold fs-5 mb-0">Document Printing</h4>
                        </div>
                        <p class="text-secondary small mb-3">
                            Black-and-white and colour printing for personal, educational, and business documents.
                        </p>
                        <ul class="list-unstyled text-xs text-muted mb-4 d-flex flex-column gap-1">
                            <li><i class="bi bi-check2 text-primary me-1"></i> Single & Double-sided laser printing</li>
                            <li><i class="bi bi-check2 text-primary me-1"></i> Spiral, Spico, & Thermal book binding</li>
                            <li><i class="bi bi-check2 text-primary me-1"></i> Project reports, contracts, manuals & resumes</li>
                        </ul>
                        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                            <span class="service-tag">A4 & A3 Formats</span>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#inquiryModal">Inquire Now</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Customized Mug Printing -->
            <div class="col-lg-4 col-md-6" id="service-mugs">
                <div class="service-card shadow-sm">
                    <div class="service-card-media">
                        <img src="{{ asset('images/mug.jpg') }}" alt="Customized Mug Printing FastKam.in">
                        <div class="service-card-media-overlay"></div>
                        <span class="service-card-badge bg-danger text-white">Popular Gift</span>
                    </div>
                    <div class="service-card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="text-danger fs-4"><i class="bi bi-cup-hot"></i></div>
                            <h4 class="fw-bold fs-5 mb-0">Customized Mug Printing</h4>
                        </div>
                        <p class="text-secondary small mb-3">
                            Personalized mugs featuring photos, names, messages, designs, or logos, subject to available options.
                        </p>
                        <ul class="list-unstyled text-xs text-muted mb-4 d-flex flex-column gap-1">
                            <li><i class="bi bi-check2 text-danger me-1"></i> Premium high-gloss white ceramic mugs</li>
                            <li><i class="bi bi-check2 text-danger me-1"></i> Magic color-changing mugs available</li>
                            <li><i class="bi bi-check2 text-danger me-1"></i> Corporate branding, quotes & memorable photos</li>
                        </ul>
                        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                            <span class="service-tag">Personal & Corporate</span>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#inquiryModal">Customize Mug</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Customized T-Shirt Printing -->
            <div class="col-lg-4 col-md-6" id="service-tshirts">
                <div class="service-card shadow-sm">
                    <div class="service-card-media">
                        <img src="{{ asset('images/tshirt.jpg') }}" alt="Customized T-Shirt Printing FastKam.in">
                        <div class="service-card-media-overlay"></div>
                        <span class="service-card-badge bg-warning text-dark">Trending</span>
                    </div>
                    <div class="service-card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="text-warning fs-4"><i class="bi bi-tag-fill"></i></div>
                            <h4 class="fw-bold fs-5 mb-0">Customized T-Shirt Printing</h4>
                        </div>
                        <p class="text-secondary small mb-3">
                            T-shirts printed with selected text, photos, artwork, or logos, subject to available options.
                        </p>
                        <ul class="list-unstyled text-xs text-muted mb-4 d-flex flex-column gap-1">
                            <li><i class="bi bi-check2 text-warning me-1"></i> High-definition DTF & Sublimation prints</li>
                            <li><i class="bi bi-check2 text-warning me-1"></i> Quality cotton & sports poly fabrics</li>
                            <li><i class="bi bi-check2 text-warning me-1"></i> Ideal for college fests, startups & sports teams</li>
                        </ul>
                        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                            <span class="service-tag">Sizes S to XXL</span>
                            <button type="button" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#inquiryModal">Order T-Shirt</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Photocopying -->
            <div class="col-lg-3 col-md-6" id="service-photocopy">
                <div class="service-card shadow-sm">
                    <div class="service-card-body">
                        <div class="feature-icon-wrapper bg-info-subtle text-info mb-3">
                            <i class="bi bi-copy"></i>
                        </div>
                        <h4 class="fw-bold fs-5 mb-2">Photocopying</h4>
                        <p class="text-secondary small mb-3">
                            Convenient photocopying for everyday document needs.
                        </p>
                        <ul class="list-unstyled text-xs text-muted mb-4 d-flex flex-column gap-1">
                            <li><i class="bi bi-check2 text-info me-1"></i> Single & multi-copy duplication</li>
                            <li><i class="bi bi-check2 text-info me-1"></i> Sharp black & grayscale contrast</li>
                            <li><i class="bi bi-check2 text-info me-1"></i> Bulk discounts for educational institutions</li>
                        </ul>
                        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                            <span class="service-tag">Fast Turnaround</span>
                            <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#inquiryModal">Inquire</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Document Scanning -->
            <div class="col-lg-3 col-md-6" id="service-scanning">
                <div class="service-card shadow-sm">
                    <div class="service-card-body">
                        <div class="feature-icon-wrapper bg-secondary-subtle text-secondary mb-3">
                            <i class="bi bi-upc-scan"></i>
                        </div>
                        <h4 class="fw-bold fs-5 mb-2">Document Scanning</h4>
                        <p class="text-secondary small mb-3">
                            Converting physical documents into digital files.
                        </p>
                        <ul class="list-unstyled text-xs text-muted mb-4 d-flex flex-column gap-1">
                            <li><i class="bi bi-check2 text-secondary me-1"></i> High-resolution 300 - 600 DPI scans</li>
                            <li><i class="bi bi-check2 text-secondary me-1"></i> Searchable PDF, TIFF, & JPG outputs</li>
                            <li><i class="bi bi-check2 text-secondary me-1"></i> Safe handling of original certificates & books</li>
                        </ul>
                        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                            <span class="service-tag">PDF / JPG Format</span>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#inquiryModal">Inquire</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 6. Digital Document Preparation -->
            <div class="col-lg-3 col-md-6" id="service-digital-prep">
                <div class="service-card shadow-sm">
                    <div class="service-card-body">
                        <div class="feature-icon-wrapper bg-success-subtle text-success mb-3">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <h4 class="fw-bold fs-5 mb-2">Digital Document Preparation</h4>
                        <p class="text-secondary small mb-3">
                            Basic preparation and formatting of digital files for printing or sharing.
                        </p>
                        <ul class="list-unstyled text-xs text-muted mb-4 d-flex flex-column gap-1">
                            <li><i class="bi bi-check2 text-success me-1"></i> Margin correction & page alignment</li>
                            <li><i class="bi bi-check2 text-success me-1"></i> Multi-file merging & splitting</li>
                            <li><i class="bi bi-check2 text-success me-1"></i> Pre-flight print readiness verification</li>
                        </ul>
                        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                            <span class="service-tag">Format Polish</span>
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#inquiryModal">Inquire</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 7. File Preparation for Online Submission -->
            <div class="col-lg-3 col-md-6" id="service-online-submission">
                <div class="service-card shadow-sm">
                    <div class="service-card-body">
                        <div class="feature-icon-wrapper text-white mb-3" style="background-color: #6366f1;">
                            <i class="bi bi-cloud-arrow-up"></i>
                        </div>
                        <h4 class="fw-bold fs-5 mb-2">File Prep for Online Submission</h4>
                        <p class="text-secondary small mb-3">
                            Assistance with file formats and size requirements where applicable.
                        </p>
                        <ul class="list-unstyled text-xs text-muted mb-4 d-flex flex-column gap-1">
                            <li><i class="bi bi-check2 text-primary me-1"></i> Precise KB / MB size compression</li>
                            <li><i class="bi bi-check2 text-primary me-1"></i> Photo & Signature aspect ratio tuning</li>
                            <li><i class="bi bi-check2 text-primary me-1"></i> Govt exam, university & visa portal compliance</li>
                        </ul>
                        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                            <span class="service-tag">Govt / Exam Forms</span>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#inquiryModal">Inquire</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Important User Notice Box -->
        <div class="notice-banner mt-5 shadow-xs">
            <div class="d-flex align-items-start gap-3">
                <div class="text-warning fs-3 mt-1"><i class="bi bi-info-circle-fill"></i></div>
                <div>
                    <h5 class="fw-bold text-dark mb-1">Service & Pricing Policy Notice</h5>
                    <p class="text-secondary mb-0">
                        <strong>Service availability, materials, customization options, prices, and turnaround times may vary. Please contact us to confirm details before placing an order.</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ===================================================================
     4. OUR MISSION SECTION
=================================================================== -->
<section class="py-5" id="mission">
    <div class="container py-lg-4">
        <div class="mission-card p-4 p-md-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge bg-light text-primary px-3 py-2 rounded-pill fw-bold text-uppercase mb-3">
                        <i class="bi bi-compass-fill me-1"></i> Our Mission
                    </span>
                    <blockquote class="mission-quote mb-4">
                        “Our mission is to make printing and customization convenient, accessible, and customer-friendly. We strive to provide clear information, helpful assistance, and a smooth service experience for every customer.”
                    </blockquote>
                    <div class="mission-tagline">
                        FastKam.in – Your Ideas, Our Printing!
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex flex-column gap-3 align-items-lg-end">
                        <a href="https://wa.me/916398573278?text=Hi%20FastKam.in,%20I%20want%20to%20discuss%20a%20printing%20requirement" target="_blank" class="btn btn-success btn-lg rounded-pill px-4 py-3 fw-bold d-inline-flex align-items-center gap-2 shadow">
                            <i class="bi bi-whatsapp"></i> Chat with Our Team
                        </a>
                        <button type="button" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#inquiryModal">
                            <i class="bi bi-send-fill me-1"></i> Send Online Inquiry
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ===================================================================
     5. HOW IT WORKS SECTION
=================================================================== -->
<section class="py-5 bg-white border-top border-bottom" id="how-it-works">
    <div class="container py-lg-4">
        <div class="text-center max-w-2xl mx-auto mb-5">
            <span class="section-badge"><i class="bi bi-arrow-repeat me-1"></i> Simple Workflow</span>
            <h2 class="section-title">How FastKam.in Works</h2>
            <p class="text-secondary">Getting your documents printed or personalized merchandise created is simple in four easy steps:</p>
        </div>

        <div class="row g-4">
            <!-- Step 1 -->
            <div class="col-lg-3 col-sm-6">
                <div class="step-card shadow-xs">
                    <div class="step-number">1</div>
                    <h5 class="fw-bold mb-2">Select Your Service</h5>
                    <p class="text-muted small mb-0">Choose from document printing, mug printing, t-shirt customization, scanning, or file preparation.</p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="col-lg-3 col-sm-6">
                <div class="step-card shadow-xs">
                    <div class="step-number">2</div>
                    <h5 class="fw-bold mb-2">Share Files & Details</h5>
                    <p class="text-muted small mb-0">Send your PDF, design, photo, or document via WhatsApp, Email, or through our quick inquiry form.</p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="col-lg-3 col-sm-6">
                <div class="step-card shadow-xs">
                    <div class="step-number">3</div>
                    <h5 class="fw-bold mb-2">Confirm Specs & Cost</h5>
                    <p class="text-muted small mb-0">We verify requirements, provide transparent pricing and estimated turnaround before proceeding.</p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="col-lg-3 col-sm-6">
                <div class="step-card shadow-xs">
                    <div class="step-number">4</div>
                    <h5 class="fw-bold mb-2">Prompt Delivery</h5>
                    <p class="text-muted small mb-0">Your order is printed with care, packed securely, and delivered straight to your doorstep.</p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ===================================================================
     6. EMBEDDED QUICK QUOTATION & INQUIRY CALCULATOR
=================================================================== -->
<section class="py-5" id="inquiry-section" style="background-color: var(--fk-bg-subtle);">
    <div class="container py-lg-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <span class="section-badge"><i class="bi bi-chat-quote-fill me-1"></i> Quick Assistance</span>
                <h2 class="section-title">Request a Custom Quote or Ask Any Question</h2>
                <p class="text-secondary mb-4">
                    Unsure which paper thickness or printing method suits your needs? Our support specialists will guide you through all options, file formats, and pricing.
                </p>

                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white mb-4">
                    <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-telephone-inbound-fill text-primary me-2"></i>Direct Help Lines</h6>
                    <div class="d-flex flex-column gap-2 text-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Customer Support:</span>
                            <a href="tel:+916398573278" class="fw-bold text-dark text-decoration-none">+91 6398573278</a>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">WhatsApp Inquiry:</span>
                            <a href="https://wa.me/916398573278" target="_blank" class="fw-bold text-success text-decoration-none">+91 6398573278</a>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Email Desk:</span>
                            <a href="mailto:support@fastkam.in" class="fw-bold text-primary text-decoration-none">support@fastkam.in</a>
                        </div>
                    </div>
                </div>

                <div class="alert alert-primary border-0 rounded-3 small mb-0">
                    <i class="bi bi-lightning-charge-fill me-1"></i>
                    <strong>FastKam.in – Your Ideas, Our Printing!</strong> We review all inquiries promptly during working hours.
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 rounded-4 shadow-lg p-4 p-md-5 bg-white">
                    <h4 class="fw-bold mb-2">Get an Instant Quote Estimate</h4>
                    <p class="text-muted small mb-4">Fill in the details below to generate a quote and connect with our team directly.</p>

                    <form onsubmit="handleInquirySubmit(event)">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Your Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3 py-2" placeholder="Enter your name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Phone / WhatsApp Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control rounded-3 py-2" placeholder="e.g. 6398573278" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Service Needed <span class="text-danger">*</span></label>
                                <select class="form-select rounded-3 py-2" required>
                                    <option value="" disabled selected>Select service...</option>
                                    <option value="Document Printing">Document Printing (B&W or Color)</option>
                                    <option value="Customized Mug Printing">Customized Mug Printing</option>
                                    <option value="Customized T-Shirt Printing">Customized T-Shirt Printing</option>
                                    <option value="Photocopying">Photocopying & Bulk Copies</option>
                                    <option value="Document Scanning">Document Scanning</option>
                                    <option value="Digital Document Preparation">Digital Document Preparation</option>
                                    <option value="File Preparation for Online Submission">File Prep for Online Submission</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Estimated Quantity / Pages</label>
                                <input type="text" class="form-control rounded-3 py-2" placeholder="e.g. 100 pages, 10 mugs">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Message / Specific Requirements</label>
                                <textarea class="form-control rounded-3" rows="3" placeholder="Paper preference (e.g. 75 GSM, 100 GSM), color requirements, deadline, or special instructions..."></textarea>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-4 pt-2">
                            <span class="text-muted small">
                                <i class="bi bi-shield-check text-success me-1"></i> No upfront commitment required
                            </span>
                            <button type="submit" class="btn btn-success rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm">
                                <i class="bi bi-whatsapp"></i> Send via WhatsApp
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ===================================================================
     7. FREQUENTLY ASKED QUESTIONS (FAQ)
=================================================================== -->
<section class="py-5 bg-white border-top border-bottom" id="faq">
    <div class="container py-lg-4">
        <div class="text-center max-w-2xl mx-auto mb-5">
            <span class="section-badge"><i class="bi bi-question-circle-fill me-1"></i> Got Questions?</span>
            <h2 class="section-title">Frequently Asked Questions</h2>
            <p class="text-secondary">Find answers to common questions about our printing and customization services.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="accordion accordion-flush" id="fastkamFaq">
                    
                    <div class="accordion-item border rounded-3 mb-3 shadow-xs">
                        <h2 class="accordion-header" id="faq1Heading">
                            <button class="accordion-button fw-bold text-dark rounded-3 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="false" aria-controls="faq1">
                                What file formats are accepted for document and custom printing?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" aria-labelledby="faq1Heading" data-bs-parent="#fastkamFaq">
                            <div class="accordion-body text-secondary small">
                                We accept PDF, DOCX, PPTX, JPG, PNG, and AI/PSD files. For document printing, PDF is recommended to preserve exact fonts and margins. If your files need formatting, our <strong>Digital Document Preparation</strong> service is available to assist you.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border rounded-3 mb-3 shadow-xs">
                        <h2 class="accordion-header" id="faq2Heading">
                            <button class="accordion-button fw-bold text-dark rounded-3 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                                How can FastKam.in help with online government or exam application forms?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" aria-labelledby="faq2Heading" data-bs-parent="#fastkamFaq">
                            <div class="accordion-body text-secondary small">
                                Through our <strong>File Preparation for Online Submission</strong> service, we help compress certificates, resize passport photos, format signatures to strict pixel dimensions (e.g. 20 KB - 50 KB), and convert files to valid formats required by state and national portals.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border rounded-3 mb-3 shadow-xs">
                        <h2 class="accordion-header" id="faq3Heading">
                            <button class="accordion-button fw-bold text-dark rounded-3 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                                Can I order single customized mugs or personalized t-shirts, or is there a minimum quantity?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" aria-labelledby="faq3Heading" data-bs-parent="#fastkamFaq">
                            <div class="accordion-body text-secondary small">
                                Yes! You can order single customized items for personal gifting, as well as bulk orders for corporate branding, family gatherings, or college fests. Please reach out to confirm current stock, colors, and turnaround times.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border rounded-3 mb-3 shadow-xs">
                        <h2 class="accordion-header" id="faq4Heading">
                            <button class="accordion-button fw-bold text-dark rounded-3 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
                                How is pricing calculated and communicated?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" aria-labelledby="faq4Heading" data-bs-parent="#fastkamFaq">
                            <div class="accordion-body text-secondary small">
                                We believe in 100% transparent communication. Pricing depends on page count, color vs. black-and-white, paper type, binding options, or the chosen custom merchandise. We confirm all applicable charges before placing your order.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border rounded-3 mb-3 shadow-xs">
                        <h2 class="accordion-header" id="faq5Heading">
                            <button class="accordion-button fw-bold text-dark rounded-3 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5">
                                What are the delivery options and turnaround times?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" aria-labelledby="faq5Heading" data-bs-parent="#fastkamFaq">
                            <div class="accordion-body text-secondary small">
                                Standard document printing is often processed on the same day. For customized mugs and t-shirts, processing typically takes 1 to 3 business days depending on design approvals. We deliver across India via reliable courier partners.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>


<!-- ===================================================================
     8. CONTACT & SERVICE DESK SECTION
=================================================================== -->
<section class="py-5" id="contact" style="background-color: var(--fk-bg-light);">
    <div class="container py-lg-4">
        <div class="text-center max-w-2xl mx-auto mb-5">
            <span class="section-badge"><i class="bi bi-geo-alt-fill me-1"></i> Reach Us</span>
            <h2 class="section-title">Get in Touch with FastKam.in</h2>
            <p class="text-secondary">Have an urgent document or a customized print project? We are here to help!</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white text-center h-100">
                    <div class="feature-icon-wrapper bg-success text-white mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-whatsapp fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">WhatsApp Chat</h5>
                    <p class="text-muted small mb-3">Send your documents or design photos directly for immediate review.</p>
                    <a href="https://wa.me/916398573278" target="_blank" class="btn btn-outline-success rounded-pill px-4 mt-auto fw-semibold">
                        +91 6398573278
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white text-center h-100">
                    <div class="feature-icon-wrapper bg-primary text-white mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-telephone-fill fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Phone Call</h5>
                    <p class="text-muted small mb-3">Speak directly with our printing specialists during business hours.</p>
                    <a href="tel:+916398573278" class="btn btn-outline-primary rounded-pill px-4 mt-auto fw-semibold">
                        Call +91 6398573278
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white text-center h-100">
                    <div class="feature-icon-wrapper bg-warning text-white mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-envelope-at-fill fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Email Support</h5>
                    <p class="text-muted small mb-3">Send high-resolution print files, large attachments, or corporate RFQs.</p>
                    <a href="mailto:support@fastkam.in" class="btn btn-outline-dark rounded-pill px-4 mt-auto fw-semibold">
                        support@fastkam.in
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
