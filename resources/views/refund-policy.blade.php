@extends('layouts.frontend')

@section('title', 'Refund & Cancellation Policy | FastKam.in – Operated by Ivaan Enterprises')
@section('meta_description', 'Read the Refund and Cancellation Policy for FastKam.in, operated by Ivaan Enterprises. Learn about cancellations, reprints, replacement conditions, and grievance redressal.')

@section('content')

<!-- Header Banner -->
<section class="py-5 text-white position-relative" style="background: linear-gradient(135deg, #091322 0%, #0f1e36 50%, #1e3a8a 100%);">
    <div class="container py-4 position-relative z-1">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2 text-sm">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none hover-white">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Refund & Cancellation Policy</li>
            </ol>
        </nav>
        <span class="badge bg-danger text-uppercase px-3 py-2 rounded-pill fw-bold mb-2">Customer Protection</span>
        <h1 class="display-5 fw-bold text-white mb-2">Refund & Cancellation Policy</h1>
        <div class="d-flex flex-wrap gap-3 text-white-50 text-sm">
            <span><i class="bi bi-calendar-event me-1 text-info"></i> <strong>Effective Date:</strong> 15-10-2026</span>
            <span>&bull;</span>
            <span><i class="bi bi-clock-history me-1 text-info"></i> <strong>Last Updated:</strong> 15-10-2026</span>
        </div>
    </div>
</section>

<!-- Main Policy Content -->
<section class="py-5" style="background-color: var(--fk-bg-light);">
    <div class="container py-lg-3">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-white">

                    <!-- Preamble -->
                    <div class="mb-4 pb-3 border-bottom">
                        <p class="fs-6 text-dark leading-relaxed mb-3">
                            At <strong>FastKam.in</strong>, we aim to provide reliable and transparent printing and customization services. This Refund & Cancellation Policy explains the conditions under which cancellations, refunds, reprints, or other service corrections may be requested for orders placed through our website or with <strong>Ivaan Enterprises</strong>.
                        </p>
                        <div class="alert alert-light border rounded-3 mb-0 small text-secondary">
                            <i class="bi bi-shield-check text-success fs-5 me-1"></i>
                            By using our services, you agree to the terms outlined in this policy, subject to applicable laws and consumer rights.
                        </div>
                    </div>

                    <!-- 1. Scope of Services -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">1</span>
                            Scope of Services
                        </h3>
                        <p class="text-secondary mb-3">FastKam.in offers the following services, subject to availability:</p>
                        <ul class="text-secondary ps-3 mb-3">
                            <li class="mb-1">Document printing, including black-and-white and colour printing.</li>
                            <li class="mb-1">Customized mug printing.</li>
                            <li class="mb-1">Customized T-shirt printing.</li>
                            <li class="mb-1">Photocopying and document scanning.</li>
                            <li class="mb-1">Digital document preparation and basic file formatting.</li>
                            <li class="mb-1">Assistance with preparing files for printing or online submission, where offered.</li>
                            <li class="mb-1">The availability of services, materials, sizes, customization options, and turnaround times may vary.</li>
                        </ul>
                        <p class="text-secondary small mb-0 fst-italic">
                            The availability of services depends on materials, equipment, file requirements, customization options, and technical availability.
                        </p>
                    </div>

                    <!-- 2. Refund, Return, Replacement, and Exchange Policy -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">2</span>
                            Refund, Return, Replacement, and Exchange Policy
                        </h3>
                        <p class="text-secondary mb-3">
                            Most services are made to order or involve digital files, so returns and exchanges may not be suitable in every case. Refunds, reprints, or other remedies will be assessed under the conditions below and applicable law.
                        </p>

                        <!-- 2.1 Services Already Completed -->
                        <div class="p-3 rounded-3 bg-light border mb-3">
                            <h5 class="fw-bold text-dark mb-2">2.1 Services Already Completed</h5>
                            <p class="text-secondary small mb-0">
                                Once a printing or customization service has been completed as ordered, the service charge will generally be non-refundable, except where a refund or other remedy is required by applicable law or the service or product was defective or incorrectly supplied.
                            </p>
                        </div>

                        <!-- 2.2 Services Not Yet Started -->
                        <div class="p-3 rounded-3 bg-light border mb-3">
                            <h5 class="fw-bold text-dark mb-2">2.2 Services Not Yet Started</h5>
                            <p class="text-secondary small mb-2">
                                If a customer requests cancellation before work on the order has started, the customer may be eligible for a refund of the amount paid, subject to any costs already incurred and applicable law.
                            </p>
                            <p class="text-secondary small mb-0">
                                If materials have already been purchased, printing has begun, or a customized product has been prepared, the refund amount may be adjusted to reflect reasonable costs incurred, where legally permitted.
                            </p>
                        </div>

                        <!-- 2.3 Services in Progress -->
                        <div class="p-3 rounded-3 bg-light border mb-3">
                            <h5 class="fw-bold text-dark mb-2">2.3 Services in Progress</h5>
                            <p class="text-secondary small mb-2">
                                If work has already started, any refund request will be evaluated based on the stage of processing, work completed, materials used, and the reason for cancellation.
                            </p>
                            <p class="text-secondary small mb-0">
                                Where appropriate, a partial refund may be provided for the unperformed portion of the service, subject to applicable law.
                            </p>
                        </div>

                        <!-- 2.4 Third-Party Charges -->
                        <div class="p-3 rounded-3 bg-light border mb-3">
                            <h5 class="fw-bold text-dark mb-2">2.4 Third-Party Charges</h5>
                            <p class="text-secondary small mb-2">
                                Payment processing fees or third-party charges, where applicable, will be assessed according to the relevant provider's rules and applicable law.
                            </p>
                            <p class="text-secondary small mb-0">
                                If a payment was made to a third-party provider, the refund may need to be requested from that provider. We will assist where reasonably possible.
                            </p>
                        </div>

                        <!-- 2.5 Incorrect or Failed Services -->
                        <div class="p-3 rounded-3 bg-light border mb-3">
                            <h5 class="fw-bold text-dark mb-2">2.5 Incorrect or Failed Services</h5>
                            <p class="text-secondary small mb-2">
                                If a printing or customization order is incorrect or defective due to an error attributable to FastKam.in, customers should contact us promptly with order details and, where appropriate, photographs of the issue. We will review the matter and may offer a correction, reprint, replacement, or refund as appropriate and required by law.
                            </p>
                            <p class="text-secondary small mb-0 text-muted">
                                Differences caused by low-resolution files, customer-approved proofs, colour variation between screens and printed output, or incorrect information supplied by the customer may not qualify for a refund, unless a remedy is required by applicable law.
                            </p>
                        </div>

                        <!-- 2.6 Physical Products and Consumables -->
                        <div class="p-3 rounded-3 bg-light border">
                            <h5 class="fw-bold text-dark mb-2">2.6 Physical Products and Consumables</h5>
                            <p class="text-secondary small mb-2">
                                If we offer physical printed products, such as customized mugs or T-shirts, eligibility for a return, replacement, reprint, or refund will depend on the product condition, whether it was customized, the nature of the issue, and applicable law.
                            </p>
                            <p class="text-secondary small mb-0">
                                Customized products and personalized print items generally cannot be returned simply because the customer changes their mind after production has begun or the item has been delivered. This does not limit any rights or remedies available for defective, damaged, or incorrectly supplied products under applicable law.
                            </p>
                        </div>
                    </div>

                    <!-- 3. Cancellation Policy and Time Limits -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">3</span>
                            Cancellation Policy and Time Limits
                        </h3>
                        <p class="text-secondary mb-3">Customers may request cancellation through the contact channels listed in this policy.</p>
                        <ul class="text-secondary ps-3 mb-3">
                            <li class="mb-2"><strong>Before work begins:</strong> Cancellation requests may be eligible for a refund of the amount paid.</li>
                            <li class="mb-2"><strong>After work begins but before completion:</strong> Refund eligibility will depend on the work completed, materials used, costs incurred, and applicable law.</li>
                            <li class="mb-2"><strong>After completion or delivery:</strong> Cancellation requests will generally not be accepted, but we will review reports of defects, damage, or incorrect orders and provide an appropriate remedy where required.</li>
                            <li class="mb-2"><strong>Customized orders:</strong> Once a design has been approved and production has started, cancellation may not be possible without costs being incurred.</li>
                        </ul>
                        <p class="text-secondary mb-2">
                            Customers should request cancellation as early as possible. An order is considered in progress once design preparation, file processing, material preparation, or production has begun.
                        </p>
                        <p class="text-secondary mb-0">
                            Cancellation does not guarantee a refund if work or costs have already been incurred. Any decision will be made in accordance with this policy and applicable law.
                        </p>
                    </div>

                    <!-- 4. Policy Validity and Applicable Time Period (TAT) -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">4</span>
                            Policy Validity and Applicable Time Period (TAT)
                        </h3>
                        <p class="text-secondary mb-2">
                            This policy applies to eligible orders placed through FastKam.in or with Ivaan Enterprises from the date of purchase or order confirmation.
                        </p>
                        <div class="alert alert-primary border-0 rounded-3 mb-3 small">
                            <i class="bi bi-clock-history me-1"></i>
                            <strong>7-Day Reporting Window:</strong> Unless a service-specific policy states otherwise, customers should report cancellation requests or order issues within <strong>7 calendar days</strong> of the transaction or delivery, as applicable.
                        </div>
                        <p class="text-secondary mb-2">
                            This 7-day reporting window does not override statutory rights, mandatory refund obligations, or other remedies available under Indian law.
                        </p>
                        <p class="text-secondary mb-0">
                            For orders that are delayed beyond the estimated turnaround time communicated to the customer, please contact us for an update. A delay does not automatically guarantee a refund, but we will review the circumstances and provide a remedy where required.
                        </p>
                    </div>

                    <!-- 5. Refund Processing Time -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">5</span>
                            Refund Processing Time
                        </h3>
                        <p class="text-secondary mb-2">
                            Once a refund has been approved, FastKam.in will generally initiate it within <strong>5–7 business days</strong>.
                        </p>
                        <p class="text-secondary mb-2">
                            The time taken for the refunded amount to appear in the customer's account may vary depending on the bank, payment gateway, card issuer, or payment provider.
                        </p>
                        <p class="text-secondary mb-2">
                            If a refund depends on a payment provider or another third party, the processing timeline may differ according to that provider's procedures.
                        </p>
                        <p class="text-secondary mb-0">We will communicate any material delay where reasonably practicable.</p>
                    </div>

                    <!-- 6. Mode of Refund -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">6</span>
                            Mode of Refund
                        </h3>
                        <p class="text-secondary mb-2">
                            Approved refunds will generally be processed through the original payment method used for the transaction, wherever technically possible.
                        </p>
                        <p class="text-secondary mb-2">Depending on the payment method and the circumstances, refunds may be issued through:</p>
                        <ul class="text-secondary ps-3 mb-3">
                            <li class="mb-1">The original UPI payment method.</li>
                            <li class="mb-1">The original debit card or credit card.</li>
                            <li class="mb-1">The original net banking or payment gateway method.</li>
                            <li class="mb-1">A bank transfer to an account verified as belonging to the customer, where appropriate and legally permissible.</li>
                        </ul>
                        <div class="alert alert-warning border-warning-subtle rounded-3 mb-0 small">
                            <i class="bi bi-shield-exclamation text-danger fs-5 me-1"></i>
                            <strong>Security Note:</strong> Customers may be asked to provide reasonable transaction details to verify the payment and process a refund securely. <strong>FastKam.in will never request your UPI PIN, ATM PIN, password, or OTP to issue a refund.</strong>
                        </div>
                    </div>

                    <!-- 7. How to Request a Cancellation or Refund -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">7</span>
                            How to Request a Cancellation or Refund
                        </h3>
                        <p class="text-secondary mb-3">To submit a cancellation, refund, or service-related complaint, customers can contact FastKam.in through any of the following channels:</p>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light border h-100">
                                    <div class="fw-bold text-dark mb-1"><i class="bi bi-envelope-fill text-primary me-2"></i>Email Support</div>
                                    <a href="mailto:ishusemwal@gmail.com" class="text-primary text-decoration-none">ishusemwal@gmail.com</a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light border h-100">
                                    <div class="fw-bold text-dark mb-1"><i class="bi bi-whatsapp text-success me-2"></i>Phone / WhatsApp</div>
                                    <a href="https://wa.me/916398573278" target="_blank" class="text-success text-decoration-none">+91 6398573278</a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light border h-100">
                                    <div class="fw-bold text-dark mb-1"><i class="bi bi-geo-alt-fill text-danger me-2"></i>In-Person Address</div>
                                    <span class="text-secondary small">Ivaan Enterprises, 18 Bhandari Bagh, Dehradun, Uttarakhand – 248001.</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light border h-100">
                                    <div class="fw-bold text-dark mb-1"><i class="bi bi-globe text-info me-2"></i>Website Portal</div>
                                    <a href="{{ url('/') }}" class="text-primary text-decoration-none">https://FastKam.in</a>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-dark mb-2">Please include the following information with your request:</h6>
                        <ul class="text-secondary ps-3 mb-3">
                            <li class="mb-1">Customer name.</li>
                            <li class="mb-1">Service or product ordered.</li>
                            <li class="mb-1">Transaction or receipt number, if available.</li>
                            <li class="mb-1">Date of payment.</li>
                            <li class="mb-1">Amount paid and payment method.</li>
                            <li class="mb-1">Reason for cancellation or refund.</li>
                            <li class="mb-1">Relevant supporting information or photographs, if required.</li>
                        </ul>
                        <p class="text-muted small mb-0">
                            Please do not send passwords, OTPs, UPI PINs, or unnecessary sensitive information with your request. We will review the request and communicate the outcome promptly.
                        </p>
                    </div>

                    <!-- 8. Refunds for Duplicate or Excess Payments -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">8</span>
                            Refunds for Duplicate or Excess Payments
                        </h3>
                        <p class="text-secondary mb-2">
                            If a customer is charged more than once for the same transaction or makes an identifiable excess payment to FastKam.in, the customer may contact us with the relevant transaction references.
                        </p>
                        <p class="text-secondary mb-2">
                            After verification, any confirmed duplicate or excess payment attributable to FastKam.in will be refunded or otherwise corrected as appropriate, subject to applicable law.
                        </p>
                        <p class="text-secondary mb-0">
                            Payments made directly to third-party providers may require a separate refund request to the relevant provider.
                        </p>
                    </div>

                    <!-- 9. Customer Responsibilities -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">9</span>
                            Customer Responsibilities
                        </h3>
                        <p class="text-secondary mb-2">Customers are responsible for:</p>
                        <ul class="text-secondary ps-3 mb-3">
                            <li class="mb-1">Providing accurate information when requesting a service.</li>
                            <li class="mb-1">Reviewing the design, spelling, quantities, sizes, and other order details before confirming production, where a proof or review is provided.</li>
                            <li class="mb-1">Keeping their payment receipts and transaction references.</li>
                            <li class="mb-1">Requesting cancellation or reporting service issues promptly.</li>
                            <li class="mb-1">Ensuring they have the necessary rights or permission to use any submitted photographs, logos, artwork, or designs.</li>
                        </ul>
                        <p class="text-secondary mb-0">
                            FastKam.in will not automatically be responsible for losses arising from incorrect information supplied by a customer, but this does not exclude any responsibility or remedy imposed on us by applicable law.
                        </p>
                    </div>

                    <!-- 10. Changes to This Policy -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">10</span>
                            Changes to This Policy
                        </h3>
                        <p class="text-secondary mb-2">
                            FastKam.in may update this Refund & Cancellation Policy to reflect changes in its services, payment methods, operational processes, or applicable legal requirements.
                        </p>
                        <p class="text-secondary mb-0">
                            The revised policy will be published on this page with an updated “Last Updated” date. Any changes will apply subject to applicable law and will not remove rights that have already accrued to customers.
                        </p>
                    </div>

                    <!-- 11. Contact and Grievance Redressal -->
                    <div class="mb-5">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">11</span>
                            Contact and Grievance Redressal
                        </h3>
                        <p class="text-secondary mb-3">For refund requests, cancellations, or questions about this policy, please contact:</p>

                        <div class="card border-primary-subtle rounded-3 p-4 bg-light mb-3">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Business Name:</span>
                                    <strong class="text-dark">Ivaan Enterprises</strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Website:</span>
                                    <a href="{{ url('/') }}" class="fw-semibold text-primary text-decoration-none">https://FastKam.in</a>
                                </div>
                                <div class="col-12">
                                    <span class="text-muted small d-block">Business Address:</span>
                                    <span class="text-dark">18 Bhandari Bagh, Dehradun, Uttarakhand – 248001</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Email Desk:</span>
                                    <a href="mailto:ishusemwal@gmail.com" class="fw-semibold text-primary text-decoration-none">ishusemwal@gmail.com</a>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block">Phone / WhatsApp:</span>
                                    <a href="https://wa.me/916398573278" target="_blank" class="fw-semibold text-success text-decoration-none">+91 6398573278</a>
                                </div>
                                <div class="col-12">
                                    <span class="text-muted small d-block">Grievance Redressal Contact:</span>
                                    <strong class="text-dark">6398573278</strong>
                                </div>
                            </div>
                        </div>

                        <p class="text-secondary small mb-0">
                            We aim to handle refund requests and complaints fairly, transparently, and within a reasonable period, in accordance with applicable law.
                        </p>
                    </div>

                    <!-- 12. Final Terms -->
                    <div class="mb-4">
                        <h3 class="h4 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.9rem;">12</span>
                            Final Terms
                        </h3>
                        <p class="text-secondary mb-2">
                            This Refund & Cancellation Policy should be read together with our <a href="{{ route('terms.conditions') }}" class="text-primary fw-semibold text-decoration-none">Terms & Conditions</a> and <a href="{{ route('privacy.policy') }}" class="text-primary fw-semibold text-decoration-none">Privacy Policy</a>.
                        </p>
                        <p class="text-secondary mb-3">
                            Nothing in this policy is intended to exclude or restrict any consumer right or legal remedy that cannot lawfully be excluded or restricted under Indian law.
                        </p>
                        <div class="p-3 rounded-3 bg-light text-center">
                            <strong class="text-primary">Thank you for choosing FastKam.in. We appreciate your trust in our services.</strong>
                        </div>
                    </div>

                    <!-- Bottom Action Buttons -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 border-top">
                        <a href="{{ url('/') }}" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="bi bi-house me-1"></i> Back to Home
                        </a>
                        <a href="https://wa.me/916398573278" target="_blank" class="btn btn-success rounded-pill px-4 d-inline-flex align-items-center gap-2">
                            <i class="bi bi-whatsapp"></i> Chat with Support
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection
