@extends('admin.layouts.admin')

@section('title', 'Edit User Profile: ' . $user->name . ' · Admin Portal')
@section('page_title', 'User Profile & Access Control')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-gold">
        <i class="bi bi-arrow-left me-1"></i> Back to All Users
    </a>
</div>

<div class="row g-4">
    <!-- User Summary Card (Left) -->
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
                    <span class="text-parchment-muted"><i class="bi bi-person-badge me-2 text-gold"></i> User ID</span>
                    <span class="text-parchment fw-medium">#{{ $user->id }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-10 small">
                    <span class="text-parchment-muted"><i class="bi bi-telephone me-2 text-gold"></i> Phone</span>
                    <span class="text-parchment fw-medium">{{ $user->phone ?: 'Not provided' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom border-secondary border-opacity-10 small">
                    <span class="text-parchment-muted"><i class="bi bi-calendar-check me-2 text-gold"></i> Registered</span>
                    <span class="text-parchment fw-medium">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 small">
                    <span class="text-parchment-muted"><i class="bi bi-clock-history me-2 text-gold"></i> Last Update</span>
                    <span class="text-parchment fw-medium">{{ $user->updated_at ? $user->updated_at->diffForHumans() : 'N/A' }}</span>
                </div>
            </div>

            @if($user->id !== auth()->id())
            <div class="border-top border-secondary border-opacity-25 pt-3 mt-3">
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete user {{ $user->name }}? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                        <i class="bi bi-trash me-1"></i> Delete User Account
                    </button>
                </form>
            </div>
            @else
            <div class="border-top border-secondary border-opacity-25 pt-3 mt-3">
                <span class="badge bg-secondary bg-opacity-25 text-parchment-dim border border-secondary w-100 py-2">
                    <i class="bi bi-shield-check me-1"></i> This is your own account
                </span>
            </div>
            @endif
        </div>
    </div>

    <!-- Edit Forms (Right) -->
    <div class="col-lg-8">
        <!-- Form 1: User Profile & Role Settings -->
        <div class="admin-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-secondary border-opacity-25">
                <div>
                    <h6 class="font-heading text-gold mb-1 fw-bold fs-6">
                        <i class="bi bi-person-gear me-2"></i> User Profile & Access Details
                    </h6>
                    <p class="text-parchment-muted small mb-0">Update contact information, system role, and access status.</p>
                </div>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

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
                        <label for="role" class="form-label small text-uppercase text-gold">Role & Privileges *</label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff (Orders & Inquiries)</option>
                            <option value="manager" {{ old('role', $user->role) === 'manager' ? 'selected' : '' }}>Manager (Catalog, Orders & Content)</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator (Full System Access)</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch pt-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label text-parchment small" for="is_active">
                                <strong>Active Account</strong> (When disabled, this user will not be able to log in to the system)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-gold">
                        <i class="bi bi-check2-circle me-1"></i> Update User Information
                    </button>
                </div>
            </form>
        </div>

        <!-- Form 2: Direct Password Reset for User -->
        <div class="admin-card p-4">
            <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-secondary border-opacity-25">
                <div>
                    <h6 class="font-heading text-gold mb-1 fw-bold fs-6">
                        <i class="bi bi-key-fill me-2"></i> Reset User's Password
                    </h6>
                    <p class="text-parchment-muted small mb-0">
                        As an administrator, you can set a new password directly for <strong>{{ $user->name }}</strong> without needing their current password.
                    </p>
                </div>
            </div>

            <form action="{{ route('admin.users.password', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="new_password" class="form-label small text-uppercase text-gold">New Password *</label>
                        <div class="input-group">
                            <input type="password" name="password" id="new_password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required minlength="6" autocomplete="new-password" placeholder="Minimum 6 characters">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="new_password_confirmation" class="form-label small text-uppercase text-gold">Confirm New Password *</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="new_password_confirmation"
                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                   required minlength="6" autocomplete="new-password" placeholder="Repeat new password">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password_confirmation">
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
                        <i class="bi bi-lock-fill me-1"></i> Set New Password for {{ $user->name }}
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

