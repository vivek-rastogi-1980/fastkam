@extends('layouts.frontend')

@section('title', 'Terms & Conditions | FastKam.in – Operated by Ivaan Enterprises')
@section('meta_description', 'Read the Terms and Conditions for FastKam.in, operated by Ivaan Enterprises. Understand service guidelines, customer responsibilities, intellectual property, and legal terms.')

@section('content')

<!-- Header Banner -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(135deg, #091322 0%, #0f1e36 50%, #1e3a8a 100%);">
    <div class="container py-4 position-relative z-1">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2 text-sm">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none hover-white">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Terms & Conditions</li>
            </ol>
        </nav>
        <span class="badge bg-warning text-dark text-uppercase px-3 py-2 rounded-pill fw-bold mb-2">Legal Agreement</span>
        <h1 class="display-5 fw-bold text-white mb-2">Terms & Conditions</h1>
        <div class="d-flex flex-wrap gap-3 text-white-50 text-sm">
            <span><i class="bi bi-calendar-event me-1 text-info"></i> <strong>Effective Date:</strong> 15-10-2026</span>
            <span>&bull;</span>
            <span><i class="bi bi-clock-history me-1 text-info"></i> <strong>Last Updated:</strong> 15-10-2026</span>
        </div>
    </div>
</section>

<!-- Main Legal Content Section -->
<section class="py-5" style="background-color: var(--fk-bg-light);">
    <div class="container py-lg-3">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-white">

                    <!-- Preamble -->
                    <div class="mb-4 pb-3 border-bottom">
                        <p class="fs-6 text-dark leading-relaxed mb-3">
                            Welcome to <strong>FastKam.in</strong> (“Website,” “Portal,” “we,” “our,” or “us”), operated by <strong>Ivaan Enterprises</strong>. FastKam.in provides information about document printing, photocopying, scanning, digital document preparation, and customized printing services such as mug and T-shirt printing, subject to availability.
                        </p>
                        <p class="fs-6 text-dark leading-relaxed mb-3">
                            These Terms & Conditions govern your access to and use of FastKam.in, including any information, features, service requests, and assistance offered through our portal.
                        </p>
                        <div class="alert alert-primary border-0 rounded-3 mb-0 small">
                            <i class="bi bi-shield-lock-fill me-1"></i>
                            <strong>Agreement Notice:</strong> By accessing or using our Website, you agree to these Terms & Conditions. If you do not agree with any part of these terms, please discontinue using the Website.
                        </div>
                    </div>

                    <!-- 1. Governing Laws and Jurisdiction -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">1</span>
                            Governing Laws and Jurisdiction
                        </h3>
                        <p class="text-secondary mb-2">These Terms & Conditions shall be governed by and interpreted in accordance with the laws of India.</p>
                        <p class="text-secondary mb-2">Our operations and services are subject to applicable Indian laws and regulations, including consumer protection and data protection requirements where applicable.</p>
                        <p class="text-secondary mb-2">Any dispute arising out of or relating to the use of FastKam.in shall be subject to the jurisdiction of the competent courts in [Dehradun/Dehradun, State, India], subject to applicable law.</p>
                        <p class="text-secondary mb-0">Nothing in these terms shall exclude or restrict any legal rights or remedies available to consumers under applicable Indian law.</p>
                    </div>

                    <!-- 2. Legal Terms and Conditions -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">2</span>
                            Legal Terms and Conditions
                        </h3>

                        <!-- 2.1 Nature of Services -->
                        <div class="p-3 rounded-3 bg-light border mb-3">
                            <h5 class="fw-bold text-dark mb-2">2.1 Nature of Services</h5>
                            <p class="text-secondary small mb-2">
                                FastKam.in provides printing and related services, including document printing, customized mug and T-shirt printing, photocopying, scanning, digital document preparation, and file preparation for printing or online submission where offered.
                            </p>
                            <p class="text-secondary small mb-0">
                                The availability and scope of each service may vary depending on materials, equipment, file specifications, customization options, and technical availability.
                            </p>
                        </div>

                        <!-- 2.2 Nature of Business and Customer-Supplied Content -->
                        <div class="p-3 rounded-3 bg-light border mb-3">
                            <h5 class="fw-bold text-dark mb-2">2.2 Nature of Business and Customer-Supplied Content</h5>
                            <p class="text-secondary small mb-2">FastKam.in is an independently operated printing and customization service portal.</p>
                            <p class="text-secondary small mb-2">Any third-party names, logos, or artwork appearing in customer-supplied files remain the property of their respective owners.</p>
                            <p class="text-secondary small mb-0">Customers are responsible for ensuring that content submitted for printing is lawful and that they have permission to reproduce it.</p>
                        </div>

                        <!-- 2.3 Accuracy of Information -->
                        <div class="p-3 rounded-3 bg-light border mb-3">
                            <h5 class="fw-bold text-dark mb-2">2.3 Accuracy of Information</h5>
                            <p class="text-secondary small mb-2">
                                We make reasonable efforts to keep the information on our Website accurate and up to date. Service options, prices, materials, and turnaround times may change based on availability.
                            </p>
                            <p class="text-secondary small mb-0">
                                Customers should confirm the applicable service details, specifications, and charges before placing an order.
                            </p>
                        </div>

                        <!-- 2.4 User Eligibility and Responsibilities -->
                        <div class="p-3 rounded-3 bg-light border mb-3">
                            <h5 class="fw-bold text-dark mb-2">2.4 User Eligibility and Responsibilities</h5>
                            <p class="text-secondary small mb-2">By using FastKam.in, you confirm that:</p>
                            <ul class="text-secondary small mb-3 ps-3">
                                <li class="mb-1">You are legally capable of entering into a binding agreement under applicable law.</li>
                                <li class="mb-1">The information and documents you provide are accurate, complete, and genuine to the best of your knowledge.</li>
                                <li class="mb-1">You have the legal right or necessary authorization to submit any personal information or documents you provide.</li>
                                <li class="mb-1">You will use the Website only for lawful purposes.</li>
                                <li class="mb-1">You will use the Website and our services lawfully and comply with these Terms & Conditions.</li>
                            </ul>
                            <p class="text-secondary small mb-0 fw-semibold text-danger">
                                Users must not submit content for printing that is unlawful, fraudulent, infringing, abusive, or otherwise prohibited by applicable law.
                            </p>
                        </div>

                        <!-- 2.5 Fees and Payments -->
                        <div class="p-3 rounded-3 bg-light border mb-3">
                            <h5 class="fw-bold text-dark mb-2">2.5 Fees and Payments</h5>
                            <p class="text-secondary small mb-2">
                                Applicable service charges, customization costs, and any third-party charges will be communicated to customers where relevant before payment.
                            </p>
                            <p class="text-secondary small mb-2">
                                Any charges collected by FastKam.in for its own services will be distinguished from third-party charges where applicable.
                            </p>
                            <p class="text-secondary small mb-0">
                                Any refund, cancellation, or adjustment will be handled according to the applicable refund policy, the nature of the service, and applicable law. Payments for services already performed, non-refundable government charges, or third-party charges may not be refundable where legally permitted.
                            </p>
                        </div>

                        <!-- 2.6 Service Results and Specifications -->
                        <div class="p-3 rounded-3 bg-light border">
                            <h5 class="fw-bold text-dark mb-2">2.6 Service Results and Specifications</h5>
                            <p class="text-secondary small mb-2">
                                Placing an order does not guarantee a particular result where the outcome depends on the quality of the customer-supplied file, image, artwork, or design. We will communicate relevant specifications and limitations where reasonably practicable.
                            </p>
                            <p class="text-secondary small mb-0">
                                Printing results may vary due to file quality, colour settings, materials, equipment, and the nature of the product. We will address issues attributable to our own service in accordance with applicable law and our Refund & Cancellation Policy.
                            </p>
                        </div>
                    </div>

                    <!-- 3. Termination and Suspension -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">3</span>
                            Termination and Suspension
                        </h3>
                        <p class="text-secondary mb-2">FastKam.in reserves the right, subject to applicable law, to suspend or restrict access to the Website or discontinue a particular service where reasonably necessary, including when:</p>
                        <ul class="text-secondary ps-3 mb-3">
                            <li class="mb-1">A user violates these Terms & Conditions.</li>
                            <li class="mb-1">Fraudulent, unlawful, misleading, or unauthorized activity is suspected.</li>
                            <li class="mb-1">Access may be affected by maintenance, security concerns, technical failures, or changes to third-party systems.</li>
                        </ul>
                        <p class="text-secondary mb-2">Where appropriate and reasonably practicable, we may provide notice before restricting access. Immediate action may be taken when required for security, legal compliance, or fraud prevention.</p>
                        <p class="text-secondary mb-2">Users may stop using the Website at any time.</p>
                        <p class="text-secondary mb-0">Termination or suspension will not remove obligations or liabilities that arose before termination. Any accrued payment obligations and provisions that by their nature should continue after termination shall remain effective.</p>
                    </div>

                    <!-- 4. Intellectual Property Rights -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">4</span>
                            Intellectual Property Rights
                        </h3>
                        <p class="text-secondary mb-2">
                            Unless otherwise stated, the content available on FastKam.in, including its original text, website design, graphics, logos, branding, layouts, and other original materials, is owned by or licensed to the Website operator and is protected by applicable intellectual property laws.
                        </p>
                        <p class="text-secondary mb-2">You may access and use Website content for personal, lawful, and non-commercial purposes.</p>
                        <p class="text-secondary mb-2 fw-semibold">Without our prior written permission, you must not:</p>
                        <ul class="text-secondary ps-3 mb-3">
                            <li class="mb-1">Copy, reproduce, republish, distribute, or commercially exploit our original content.</li>
                            <li class="mb-1">Modify, adapt, or create derivative works from our protected materials.</li>
                            <li class="mb-1">Use our branding, logo, or design in a way that suggests an unauthorized affiliation with FastKam.in.</li>
                            <li class="mb-1">Use our content or Website materials in a manner that infringes intellectual property rights.</li>
                        </ul>
                        <p class="text-secondary mb-0">
                            Third-party names, logos, trademarks, and other materials remain the property of their respective owners. Their appearance in customer-supplied files does not imply ownership by or affiliation with FastKam.in.
                        </p>
                    </div>

                    <!-- 5. Limitation of Liability -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">5</span>
                            Limitation of Liability
                        </h3>
                        <p class="text-secondary mb-2">
                            FastKam.in will exercise reasonable care in providing the services and information that it offers. However, to the extent permitted by applicable law, we shall not be liable for losses arising from circumstances beyond our reasonable control, including:
                        </p>
                        <ul class="text-secondary ps-3 mb-3">
                            <li class="mb-1">Delays or interruptions caused by suppliers, delivery providers, payment providers, or other third parties beyond our reasonable control.</li>
                            <li class="mb-1">Changes in material availability, supplier availability, or technical requirements.</li>
                            <li class="mb-1">Temporary unavailability or technical failure of the Website, payment systems, internet services, or third-party platforms.</li>
                            <li class="mb-1">Inaccurate, incomplete, or misleading information supplied by a user.</li>
                            <li class="mb-1">Unauthorized access, malware, cyberattacks, or other security incidents despite reasonable protective measures.</li>
                            <li class="mb-1">The customer's failure to provide usable files, accurate specifications, or necessary permissions for supplied content.</li>
                        </ul>
                        <p class="text-secondary mb-2">
                            To the extent permitted by law, FastKam.in shall not be liable for indirect, incidental, special, or consequential losses arising from the use of the Website.
                        </p>
                        <p class="text-secondary mb-2">
                            Nothing in this section excludes or limits liability that cannot legally be excluded or limited, including liability arising from fraud, wilful misconduct, or any non-excludable consumer rights or statutory obligations.
                        </p>
                        <p class="text-secondary mb-0">
                            This section does not relieve FastKam.in of responsibility for its own acts, omissions, or service failures to the extent liability is imposed by applicable law.
                        </p>
                    </div>

                    <!-- 6. Privacy and Personal Information -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">6</span>
                            Privacy and Personal Information
                        </h3>
                        <p class="text-secondary mb-2">
                            To provide printing and related services, we may need to collect or process contact details, order information, and files or designs supplied for the requested service.
                        </p>
                        <p class="text-secondary mb-2">
                            Users should provide only the information necessary for the requested service and should not share passwords, OTPs, or other authentication credentials unless a specific, lawful, and authorized process requires them. FastKam.in will not ask users to disclose information unnecessarily.
                        </p>
                        <p class="text-secondary mb-2">
                            We will handle personal information in accordance with our <a href="{{ route('privacy.policy') }}" class="text-primary fw-semibold text-decoration-none">Privacy Policy</a> and applicable data protection laws. Information will be collected, used, stored, shared, and retained only as permitted by applicable law and for legitimate purposes associated with the services, legal obligations, security, and dispute resolution.
                        </p>
                        <p class="text-secondary mb-0">
                            Users should review our <a href="{{ route('privacy.policy') }}" class="text-primary fw-semibold text-decoration-none">Privacy Policy</a> to understand how their information is handled.
                        </p>
                    </div>

                    <!-- 7. Third-Party Websites and Services -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">7</span>
                            Third-Party Websites and Services
                        </h3>
                        <p class="text-secondary mb-2">Our Website may contain links to payment providers or other third-party websites for convenience.</p>
                        <p class="text-secondary mb-2">FastKam.in does not control all third-party websites, their availability, their content, or their privacy practices. Accessing an external website is subject to that website's own terms and policies.</p>
                        <p class="text-secondary mb-0">Users should verify that they are using a trusted payment page before submitting payment information.</p>
                    </div>

                    <!-- 8. Service Availability and Changes -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">8</span>
                            Service Availability and Changes
                        </h3>
                        <p class="text-secondary mb-2">We may modify, update, suspend, or discontinue Website features, service descriptions, or other content as necessary.</p>
                        <p class="text-secondary mb-2">We do not guarantee uninterrupted Website availability or that every printing option, material, or customization will be available at all times.</p>
                        <p class="text-secondary mb-0">We will make reasonable efforts to communicate material service changes where appropriate.</p>
                    </div>

                    <!-- 9. Prohibited Activities -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-danger rounded-circle text-white" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">9</span>
                            Prohibited Activities
                        </h3>
                        <p class="text-secondary mb-2 fw-semibold">Users must not:</p>
                        <ul class="text-secondary ps-3 mb-0">
                            <li>Use the Website to submit unlawful or infringing content, interfere with its operation, or misuse another person's personal information.</li>
                        </ul>
                    </div>

                    <!-- Bottom Operator Info Bar -->
                    <div class="p-4 rounded-4 bg-light border d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h6 class="fw-bold text-dark mb-1">FastKam.in &bull; Operated by Ivaan Enterprises</h6>
                            <span class="text-muted small">Your Ideas, Our Printing! &bull; Effective: 15-10-2026</span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ url('/') }}" class="btn btn-outline-primary rounded-pill px-3 py-2 btn-sm">
                                <i class="bi bi-house me-1"></i> Return Home
                            </a>
                            <a href="https://wa.me/916398573278" target="_blank" class="btn btn-success rounded-pill px-3 py-2 btn-sm d-inline-flex align-items-center gap-1">
                                <i class="bi bi-whatsapp"></i> Contact Us
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection
