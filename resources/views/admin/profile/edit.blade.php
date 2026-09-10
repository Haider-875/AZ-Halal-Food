@extends('admin.layouts.admin')

@section('title', 'My Profile & Security · Admin Portal')
@section('page_title', 'My Profile & Security Settings')

@section('content')
<div class="row g-4">
    <!-- Profile Overview Card (Left) -->
    <div class="col-lg-4">
        <div class="admin-card p-4 text-center">
            <div class="mx-auto mb-3 rounded-circle bg-gold text-dark d-flex align-items-center justify-content-center fw-bold shadow"
                 style="width: 80px; height: 80px; font-size: 2rem; border: 3px solid rgba(212, 175, 55, 0.4);">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <h5 class="font-heading text-gold mb-1 fw-bold">{{ $user->name }}</h5>
            <p class="text-parchment-muted small mb-2">{{ $user->email }}</p>

            <div class="d-flex justify-content-center gap-2 mb-4">
                @if($user->role === 'admin')
                    <span class="badge bg-gold text-dark text-uppercase px-3 py-1">Administrator</span>
                @elseif($user->role === 'manager')
                    <span class="badge bg-info text-dark text-uppercase px-3 py-1">Manager</span>
                @else
                    <span class="badge bg-secondary text-uppercase px-3 py-1">Staff Member</span>
                @endif

                @if($user->is_active)
                    <span class="badge bg-success bg-opacity-25 text-success border border-success px-3 py-1">Active</span>
                @else
                    <span class="badge bg-danger px-3 py-1">Disabled</span>
                @endif
            </div>

            <div class="border-top border-secondary border-opacity-25 pt-3 text-start">
                <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-10 small">
                    <span class="text-parchment-muted"><i class="bi bi-telephone me-2 text-gold"></i> Phone</span>
                    <span class="text-parchment fw-medium">{{ $user->phone ?: 'Not provided' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-10 small">
                    <span class="text-parchment-muted"><i class="bi bi-calendar-check me-2 text-gold"></i> Joined</span>
                    <span class="text-parchment fw-medium">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 small">
                    <span class="text-parchment-muted"><i class="bi bi-clock-history me-2 text-gold"></i> Last Updated</span>
                    <span class="text-parchment fw-medium">{{ $user->updated_at ? $user->updated_at->diffForHumans() : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="admin-card p-3 mt-4 border-gold border-opacity-25" style="background: rgba(212, 175, 55, 0.04);">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-shield-lock-fill text-gold fs-5 mt-1"></i>
                <div>
                    <h6 class="text-gold fw-bold mb-1 small text-uppercase" style="letter-spacing: 0.1em;">Account Security</h6>
                    <p class="text-parchment-muted small mb-0" style="font-size: 12px; line-height: 1.5;">
                        To keep your account secure, change your password periodically. Never share your administrative login with unauthorized staff.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Forms (Right) -->
    <div class="col-lg-8">
        <!-- Form 1: Profile Information -->
        <div class="admin-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-secondary border-opacity-25">
                <div>
                    <h6 class="font-heading text-gold mb-1 fw-bold fs-6">
                        <i class="bi bi-person-lines-fill me-2"></i> Profile Information
                    </h6>
                    <p class="text-parchment-muted small mb-0">Update your name, contact email, and phone number.</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label small text-uppercase text-gold">Full Name *</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label small text-uppercase text-gold">Email Address *</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label small text-uppercase text-gold">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $user->phone) }}" placeholder="e.g. 919-555-0100">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small text-uppercase text-gold">Role (Assigned)</label>
                        <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled readonly
                               style="opacity: 0.65; cursor: not-allowed;">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-gold">
                        <i class="bi bi-check2-circle me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Form 2: Change Password -->
        <div class="admin-card p-4">
            <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-secondary border-opacity-25">
                <div>
                    <h6 class="font-heading text-gold mb-1 fw-bold fs-6">
                        <i class="bi bi-key-fill me-2"></i> Change Password
                    </h6>
                    <p class="text-parchment-muted small mb-0">Ensure your account is using a long, secure password (minimum 6 characters).</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12">
                        <label for="current_password" class="form-label small text-uppercase text-gold">Current Password *</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   required autocomplete="current-password" placeholder="Enter your current password">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label small text-uppercase text-gold">New Password *</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required minlength="6" autocomplete="new-password" placeholder="Minimum 6 characters">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label small text-uppercase text-gold">Confirm New Password *</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                   required minlength="6" autocomplete="new-password" placeholder="Repeat new password">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-gold">
                        <i class="bi bi-shield-lock me-1"></i> Update My Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
</script>
@endpush

