@extends('layouts.frontend')

@section('title', 'Privacy Policy & Service Information | FastKam.in – Your Ideas, Our Printing!')
@section('meta_description', 'Privacy Policy and service terms for FastKam.in, an online printing and customization service portal. Learn about our services, file requirements, and customer privacy commitments.')

@section('content')

<!-- Header Banner -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(135deg, #091322 0%, #0f1e36 50%, #1e3a8a 100%);">
    <div class="container py-4 position-relative z-1">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2 text-sm">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none hover-white">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Privacy Policy</li>
            </ol>
        </nav>
        <span class="badge bg-primary text-uppercase px-3 py-2 rounded-pill fw-bold mb-2">Policy & Terms</span>
        <h1 class="display-5 fw-bold text-white mb-2">Privacy Policy & Service Terms</h1>
        <p class="text-white-50 fs-6 mb-0">FastKam.in – Your Ideas, Our Printing!</p>
    </div>
</section>

<!-- Content Container -->
<section class="py-5" style="background-color: var(--fk-bg-light);">
    <div class="container py-lg-3">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-white">
                    
                    <!-- Welcome Section -->
                    <div class="mb-4">
                        <h2 class="h3 fw-bold text-dark mb-3">Welcome to FastKam.in</h2>
                        <p class="lead fs-6 text-secondary mb-0">
                            FastKam.in is an online printing and customization service portal created to make everyday printing needs simple, convenient, and accessible. We help customers print documents and create personalized products such as mugs and T-shirts for personal, educational, and business use.
                        </p>
                    </div>

                    <hr class="my-4">

                    <!-- Who We Are -->
                    <div class="mb-4">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-people-fill text-primary"></i> Who We Are
                        </h3>
                        <p class="text-secondary mb-3">
                            We provide document printing, photocopying, scanning, digital document preparation, and customized printing services. Whether you need a clear document printout or a personalized gift, we aim to offer helpful service and quality results.
                        </p>
                        <p class="text-secondary mb-0">
                            At FastKam.in, we focus on customer convenience, transparent communication, and dependable service. We help customers understand available printing options, customization choices, file requirements, and applicable charges before proceeding.
                        </p>
                    </div>

                    <hr class="my-4">

                    <!-- Our Services -->
                    <div class="mb-4">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-printer-fill text-primary"></i> Our Services
                        </h3>
                        <p class="text-secondary mb-3">FastKam.in offers printing and document-related services, including:</p>
                        
                        <div class="list-group list-group-flush border rounded-3 mb-4">
                            <div class="list-group-item d-flex align-items-start gap-3 py-3">
                                <span class="badge bg-primary-subtle text-primary rounded-circle p-2 mt-1"><i class="bi bi-file-earmark-text"></i></span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Document Printing</h6>
                                    <p class="text-secondary small mb-0">Black-and-white and colour printing for personal, educational, and business documents.</p>
                                </div>
                            </div>

                            <div class="list-group-item d-flex align-items-start gap-3 py-3">
                                <span class="badge bg-danger-subtle text-danger rounded-circle p-2 mt-1"><i class="bi bi-cup-hot"></i></span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Customized Mug Printing</h6>
                                    <p class="text-secondary small mb-0">Personalized mugs featuring photos, names, messages, designs, or logos, subject to available options.</p>
                                </div>
                            </div>

                            <div class="list-group-item d-flex align-items-start gap-3 py-3">
                                <span class="badge bg-warning-subtle text-warning rounded-circle p-2 mt-1"><i class="bi bi-tag-fill"></i></span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Customized T-Shirt Printing</h6>
                                    <p class="text-secondary small mb-0">T-shirts printed with selected text, photos, artwork, or logos, subject to available options.</p>
                                </div>
                            </div>

                            <div class="list-group-item d-flex align-items-start gap-3 py-3">
                                <span class="badge bg-info-subtle text-info rounded-circle p-2 mt-1"><i class="bi bi-copy"></i></span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Photocopying</h6>
                                    <p class="text-secondary small mb-0">Convenient photocopying for everyday document needs.</p>
                                </div>
                            </div>

                            <div class="list-group-item d-flex align-items-start gap-3 py-3">
                                <span class="badge bg-secondary-subtle text-secondary rounded-circle p-2 mt-1"><i class="bi bi-upc-scan"></i></span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Document Scanning</h6>
                                    <p class="text-secondary small mb-0">Converting physical documents into digital files.</p>
                                </div>
                            </div>

                            <div class="list-group-item d-flex align-items-start gap-3 py-3">
                                <span class="badge bg-success-subtle text-success rounded-circle p-2 mt-1"><i class="bi bi-pencil-square"></i></span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Digital Document Preparation</h6>
                                    <p class="text-secondary small mb-0">Basic preparation and formatting of digital files for printing or sharing.</p>
                                </div>
                            </div>

                            <div class="list-group-item d-flex align-items-start gap-3 py-3">
                                <span class="badge text-white rounded-circle p-2 mt-1" style="background-color: #6366f1;"><i class="bi bi-cloud-arrow-up"></i></span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">File Preparation for Online Submission</h6>
                                    <p class="text-secondary small mb-0">Assistance with file formats and size requirements where applicable.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Service Availability Notice -->
                        <div class="alert alert-warning border-warning-subtle rounded-3 p-3 mb-0">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
                                <div class="text-dark">
                                    <strong>Service Availability Notice:</strong> Service availability, materials, customization options, prices, and turnaround times may vary. Please contact us to confirm details before placing an order.
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Mission & Commitment -->
                    <div class="p-4 rounded-4 text-white mb-4" style="background: linear-gradient(135deg, #091322 0%, #1e3a8a 100%);">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-light text-primary fw-bold text-uppercase px-3 py-1 rounded-pill">Our Mission</span>
                        </div>
                        <blockquote class="fs-5 fst-italic mb-3 text-light">
                            “Our mission is to make printing and customization convenient, accessible, and customer-friendly. We strive to provide clear information, helpful assistance, and a smooth service experience for every customer.”
                        </blockquote>
                        <div class="fs-4 fw-bold text-info">FastKam.in – Your Ideas, Our Printing!</div>
                    </div>

                    <!-- Contact Bar -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 rounded-3 bg-light border">
                        <div>
                            <span class="fw-semibold text-dark d-block">Questions about our privacy policy or services?</span>
                            <span class="text-muted small">We are always available via WhatsApp or email.</span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="https://wa.me/916398573278" target="_blank" class="btn btn-success rounded-pill px-3 py-2 btn-sm d-inline-flex align-items-center gap-1">
                                <i class="bi bi-whatsapp"></i> Chat on WhatsApp
                            </a>
                            <a href="mailto:support@fastkam.in" class="btn btn-outline-dark rounded-pill px-3 py-2 btn-sm">
                                <i class="bi bi-envelope"></i> Email Support
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection
