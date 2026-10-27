<!-- Top Micro Bar -->
<div class="top-bar py-2 d-none d-md-block text-white">
    <div class="container">
        <div class="row align-items-center g-2 text-sm">
            <div class="col-md-6 d-flex align-items-center gap-3">
                <span class="d-inline-flex align-items-center">
                    <i class="bi bi-lightning-charge-fill text-warning me-1"></i>
                    <strong class="me-1">FastKam.in:</strong> Your Ideas, Our Printing!
                </span>
                <span class="text-white-50">|</span>
                <span class="d-inline-flex align-items-center text-white-50">
                    <i class="bi bi-clock me-1 text-info"></i> Mon - Sat: 9:00 AM - 8:00 PM
                </span>
            </div>
            <div class="col-md-6 d-flex justify-content-end align-items-center gap-3">
                <a href="mailto:ishusemwal@gmail.com" class="top-link d-inline-flex align-items-center text-white-50 text-decoration-none hover-white">
                    <i class="bi bi-envelope-fill me-1 text-info"></i> ishusemwal@gmail.com
                </a>
                <span class="text-white-50">|</span>
                <a href="https://wa.me/916398573278?text=Hi%20FastKam.in,%20I%20have%20an%20inquiry%20regarding%20printing%20services" target="_blank" class="top-link d-inline-flex align-items-center text-success-light text-decoration-none">
                    <i class="bi bi-whatsapp me-1 text-success"></i> +91 6398573278
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Sticky Navigation -->
<nav class="navbar navbar-expand-lg navbar-light sticky-top main-navbar bg-white shadow-sm">
    <div class="container">
        <!-- Brand Logo -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
            <div class="brand-icon-box shadow-sm">
                <i class="bi bi-printer-fill"></i>
            </div>
            <div class="d-flex flex-column">
                <span class="brand-title">FastKam<span class="brand-accent">.in</span></span>
                <span class="brand-sub">Print & Customization Portal</span>
            </div>
        </a>

        <!-- Mobile Toggler -->
        <button class="navbar-toggler border-0 shadow-none p-2" type="button" data-bs-toggle="collapse" data-bs-target="#fastkamNav" aria-controls="fastkamNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav Links & Actions -->
        <div class="collapse navbar-collapse" id="fastkamNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 py-2 py-lg-0">
                <li class="nav-item">
                    <a class="nav-link px-3 active fw-semibold" href="{{ url('/') }}#hero">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 fw-semibold" href="{{ url('/') }}#about">Who We Are</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link px-3 fw-semibold dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Our Services
                    </a>
                    <ul class="dropdown-menu border-0 shadow-lg rounded-3 py-2 animate-fade-in" aria-labelledby="servicesDropdown">
                        <li><h6 class="dropdown-header text-uppercase text-muted small fw-bold">Document Services</h6></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ url('/') }}#service-doc-print">
                                <i class="bi bi-file-earmark-text text-primary"></i> Document Printing
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ url('/') }}#service-photocopy">
                                <i class="bi bi-copy text-info"></i> Photocopying & Bulk Copies
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ url('/') }}#service-scanning">
                                <i class="bi bi-upc-scan text-secondary"></i> Document Scanning
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><h6 class="dropdown-header text-uppercase text-muted small fw-bold">Personalized Products</h6></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ url('/') }}#service-mugs">
                                <i class="bi bi-cup-hot text-danger"></i> Customized Mug Printing
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ url('/') }}#service-tshirts">
                                <i class="bi bi-bag-heart text-warning"></i> Customized T-Shirt Printing
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><h6 class="dropdown-header text-uppercase text-muted small fw-bold">Digital Preparation</h6></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ url('/') }}#service-digital-prep">
                                <i class="bi bi-pencil-square text-success"></i> Digital Document Prep
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ url('/') }}#service-online-submission">
                                <i class="bi bi-cloud-arrow-up text-indigo"></i> File Prep for Online Submission
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 fw-semibold" href="{{ url('/') }}#how-it-works">How It Works</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 fw-semibold" href="{{ url('/') }}#mission">Our Mission</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 fw-semibold" href="{{ url('/') }}#contact">Contact Us</a>
                </li>
            </ul>

            <!-- Right Actions -->
            <div class="d-flex align-items-center gap-2 pt-2 pt-lg-0">
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#inquiryModal">
                    <i class="bi bi-chat-dots-fill"></i> Get a Quote
                </button>
            </div>
        </div>
    </div>
</nav>
