@extends('layouts.admin')

@section('title', 'Update Password')
@section('page_title', 'Update Password')
@section('page_subtitle', 'Manage your account security and authentication credentials')

@section('content')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">

            <div class="card glass-card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3 bg-primary-subtle text-primary fs-4">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Change Password</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('admin.password.update') }}" method="POST" id="passwordForm">
                        @csrf

                        <!-- Current Password Input -->
                        <div class="mb-4">
                            <label for="current_password" class="form-label fw-semibold text-dark">
                                Current Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                                <input type="password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       id="current_password"
                                       name="current_password"
                                       value="{{ old('current_password', $currentPassword ?? '') }}"
                                       placeholder="Enter your current password"
                                       required
                                       autocomplete="current-password">
                                <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePasswordVisibility('current_password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4 text-muted opacity-25">

                        <!-- New Password Input -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold text-dark">
                                New Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       placeholder="Enter new strong password"
                                       required
                                       autocomplete="new-password"
                                       oninput="checkPasswordStrength(this.value)">
                                <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePasswordVisibility('password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Strength Meter -->
                        <div class="mb-3">
                            <div class="progress" style="height: 6px;">
                                <div id="strengthBar" class="progress-bar bg-danger" role="progressbar" style="width: 0%;"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <span class="text-muted" style="font-size: 0.75rem;">Password Strength:</span>
                                <span id="strengthText" class="fw-semibold text-muted" style="font-size: 0.75rem;">Too short</span>
                            </div>
                        </div>



                        <!-- Confirm Password Input -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold text-dark">
                                Confirm New Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-check2-circle text-muted"></i></span>
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       placeholder="Re-enter your new password"
                                       required
                                       autocomplete="new-password"
                                       oninput="checkPasswordMatch()">
                                <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePasswordVisibility('password_confirmation', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex align-items-center justify-content-end gap-2 pt-2">
                            <a href="{{ route('admin.services') }}" class="btn btn-light px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold d-flex align-items-center gap-2">
                                <i class="bi bi-shield-check"></i>
                                <span>Save & Update Password</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePasswordVisibility(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

function checkPasswordStrength(val) {
    const bar = document.getElementById('strengthBar');
    const text = document.getElementById('strengthText');
    const reqLength = document.getElementById('req-length');
    const reqNumber = document.getElementById('req-number');

    let score = 0;

    // Length check
    if (val.length >= 8) {
        score += 40;
        updateCheckItem(reqLength, true);
    } else {
        updateCheckItem(reqLength, false);
    }

    // Number / Special character check
    if (/[0-9!@#$%^&*(),.?":{}|<>]/.test(val)) {
        score += 30;
        updateCheckItem(reqNumber, true);
    } else {
        updateCheckItem(reqNumber, false);
    }

    // Uppercase + Lowercase
    if (/[a-z]/.test(val) && /[A-Z]/.test(val)) {
        score += 30;
    }

    bar.style.width = score + '%';

    if (score < 40) {
        bar.className = 'progress-bar bg-danger';
        text.innerText = 'Weak';
        text.className = 'fw-semibold text-danger';
    } else if (score < 80) {
        bar.className = 'progress-bar bg-warning';
        text.innerText = 'Moderate';
        text.className = 'fw-semibold text-warning';
    } else {
        bar.className = 'progress-bar bg-success';
        text.innerText = 'Strong';
        text.className = 'fw-semibold text-success';
    }

    checkPasswordMatch();
}

function checkPasswordMatch() {
    const p1 = document.getElementById('password').value;
    const p2 = document.getElementById('password_confirmation').value;
    const reqMatch = document.getElementById('req-match');

    if (p1.length > 0 && p1 === p2) {
        updateCheckItem(reqMatch, true);
    } else {
        updateCheckItem(reqMatch, false);
    }
}

function updateCheckItem(element, isMet) {
    if (!element) return;
    const icon = element.querySelector('i');
    if (!icon) return;
    if (isMet) {
        icon.className = 'bi bi-check-circle-fill text-success';
        element.classList.add('text-success');
    } else {
        icon.className = 'bi bi-circle text-muted';
        element.classList.remove('text-success');
    }
}
</script>
@endpush
