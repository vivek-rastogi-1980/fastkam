<!-- Footer Section -->
<footer class="footer-section bg-dark text-white pt-5 pb-4">
    <div class="container">
        <!-- Top Footer CTA Bar -->
        <div class="footer-cta-card p-4 p-md-5 rounded-4 mb-5 shadow-lg position-relative overflow-hidden">
            <div class="row align-items-center g-4 position-relative z-1">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold text-uppercase mb-2">FastKam.in – Your Ideas, Our Printing!</span>
                    <h3 class="fw-bold text-white mb-2">Have a Custom Printing or Document Requirement?</h3>
                    <p class="text-white-50 mb-0">From single page urgent prints to customized corporate mugs and t-shirts, our team is ready to assist you right away.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <a href="https://wa.me/916398573278?text=Hello%20FastKam.in,%20I%20want%20to%20place%20an%20order" target="_blank" class="btn btn-success rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow">
                            <i class="bi bi-whatsapp"></i> Chat on WhatsApp
                        </a>
                        <button type="button" class="btn btn-light rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow" data-bs-toggle="modal" data-bs-target="#inquiryModal">
                            <i class="bi bi-envelope-paper"></i> Request Quote
                        </button>
                    </div>
                </div>
            </div>
            <!-- Decorative circle overlay -->
            <div class="cta-circle-bg"></div>
        </div>

        <div class="row g-4 g-lg-5 mb-5">
            <!-- Brand & Info Column -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand mb-3">
                    <a class="d-flex align-items-center gap-2 text-decoration-none text-white" href="{{ url('/') }}">
                        <div class="brand-icon-box bg-primary text-white shadow-sm">
                            <i class="bi bi-printer-fill"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="brand-title fs-4 fw-bold">FastKam<span class="text-info">.in</span></span>
                            <span class="text-white-50 small">Online Printing & Customization Portal</span>
                        </div>
                    </a>
                </div>
                <p class="text-light-muted mb-4 pe-lg-3">
                    FastKam.in is an online printing and customization service portal created to make everyday printing needs simple, convenient, and accessible. We help customers print documents and create personalized products such as mugs and T-shirts for personal, educational, and business use.
                </p>
                <div class="d-flex align-items-center gap-2">
                    <a href="#" class="social-icon-btn" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    <a href="#" class="social-icon-btn" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon-btn" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon-btn" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>

            <!-- Our Services Links -->
            <div class="col-lg-3 col-md-6">
                <h5 class="fw-bold text-white mb-3 position-relative footer-heading">Our Services</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ url('/') }}#service-doc-print"><i class="bi bi-chevron-right me-1 small"></i> Document Printing (B&W / Colour)</a></li>
                    <li><a href="{{ url('/') }}#service-mugs"><i class="bi bi-chevron-right me-1 small"></i> Customized Mug Printing</a></li>
                    <li><a href="{{ url('/') }}#service-tshirts"><i class="bi bi-chevron-right me-1 small"></i> Customized T-Shirt Printing</a></li>
                    <li><a href="{{ url('/') }}#service-photocopy"><i class="bi bi-chevron-right me-1 small"></i> Photocopying & Bulk Copies</a></li>
                    <li><a href="{{ url('/') }}#service-scanning"><i class="bi bi-chevron-right me-1 small"></i> Document Scanning & Digitization</a></li>
                    <li><a href="{{ url('/') }}#service-digital-prep"><i class="bi bi-chevron-right me-1 small"></i> Digital Document Preparation</a></li>
                    <li><a href="{{ url('/') }}#service-online-submission"><i class="bi bi-chevron-right me-1 small"></i> File Prep for Online Submission</a></li>
                </ul>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold text-white mb-3 position-relative footer-heading">Quick Links</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ url('/') }}#hero"><i class="bi bi-chevron-right me-1 small"></i> Home</a></li>
                    <li><a href="{{ url('/') }}#about"><i class="bi bi-chevron-right me-1 small"></i> Who We Are</a></li>
                    <li><a href="{{ url('/') }}#services"><i class="bi bi-chevron-right me-1 small"></i> All Services</a></li>
                    <li><a href="{{ url('/') }}#how-it-works"><i class="bi bi-chevron-right me-1 small"></i> How It Works</a></li>
                    <li><a href="{{ url('/') }}#mission"><i class="bi bi-chevron-right me-1 small"></i> Our Mission</a></li>
                    <li><a href="{{ url('/') }}#faq"><i class="bi bi-chevron-right me-1 small"></i> FAQs</a></li>
                    <li><a href="{{ route('terms.conditions') }}"><i class="bi bi-chevron-right me-1 small"></i> Terms & Conditions</a></li>
                    <li><a href="{{ route('privacy.policy') }}"><i class="bi bi-chevron-right me-1 small"></i> Privacy Policy</a></li>
                    <li><a href="{{ route('refund.policy') }}"><i class="bi bi-chevron-right me-1 small"></i> Refund & Cancellation</a></li>
                </ul>
            </div>

            <!-- Contact & Assistance -->
            <div class="col-lg-3 col-md-6">
                <h5 class="fw-bold text-white mb-3 position-relative footer-heading">Contact & Support</h5>
                <ul class="list-unstyled footer-contact">
                    <li class="d-flex align-items-start gap-3 mb-3">
                        <div class="contact-icon text-info mt-1"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <span class="d-block fw-semibold text-white">Business Address</span>
                            <span class="text-light-muted small">Ivaan Enterprises, 18 Bhandari Bagh, Dehradun, Uttarakhand – 248001</span>
                        </div>
                    </li>
                    <li class="d-flex align-items-start gap-3 mb-3">
                        <div class="contact-icon text-success mt-1"><i class="bi bi-whatsapp"></i></div>
                        <div>
                            <span class="d-block fw-semibold text-white">WhatsApp & Call</span>
                            <a href="https://wa.me/916398573278" target="_blank" class="text-light-muted text-decoration-none small hover-white">+91 6398573278</a>
                        </div>
                    </li>
                    <li class="d-flex align-items-start gap-3 mb-3">
                        <div class="contact-icon text-warning mt-1"><i class="bi bi-envelope-at-fill"></i></div>
                        <div>
                            <span class="d-block fw-semibold text-white">Email Inquiries</span>
                            <a href="mailto:ishusemwal@gmail.com" class="text-light-muted text-decoration-none small hover-white">ishusemwal@gmail.com</a>
                        </div>
                    </li>
                    <li class="d-flex align-items-start gap-3">
                        <div class="contact-icon text-primary mt-1"><i class="bi bi-clock-history"></i></div>
                        <div>
                            <span class="d-block fw-semibold text-white">Working Hours</span>
                            <span class="text-light-muted small">Monday – Saturday: 9:00 AM – 8:00 PM</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="border-secondary opacity-25 my-4">

        <!-- Bottom Bar -->
        <div class="row align-items-center g-3 text-sm text-light-muted">
            <div class="col-md-5 text-center text-md-start">
                <p class="mb-0">&copy; {{ date('Y') }} <strong class="text-white">FastKam.in</strong> (Ivaan Enterprises). All rights reserved.</p>
            </div>
            <div class="col-md-7 text-center text-md-end">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item"><a href="{{ route('terms.conditions') }}" class="text-light-muted text-decoration-none small hover-white">Terms & Conditions</a></li>
                    <li class="list-inline-item text-secondary">&bull;</li>
                    <li class="list-inline-item"><a href="{{ route('privacy.policy') }}" class="text-light-muted text-decoration-none small hover-white">Privacy Policy</a></li>
                    <li class="list-inline-item text-secondary">&bull;</li>
                    <li class="list-inline-item"><a href="{{ route('refund.policy') }}" class="text-light-muted text-decoration-none small hover-white">Refund & Cancellation</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
