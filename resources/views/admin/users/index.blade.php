@extends('admin.layouts.admin')

@section('title', 'Staff Users & Roles · Admin Portal')
@section('page_title', 'Roles & Permissions Management')

@section('content')

<div class="row g-4">
    <!-- User List -->
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="p-3 border-bottom border-secondary border-opacity-25">
                <h6 class="font-heading text-gold mb-0 fw-bold"><i class="bi bi-people me-2"></i> System Staff & Admins</h6>
            </div>

            <div class="table-responsive">
                <table class="table admin-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr>
                            <td>
                                <strong class="text-parchment">{{ $u->name }}</strong>
                                <div class="text-parchment-muted small">{{ $u->email }}</div>
                            </td>
                            <td>
                                @if($u->role === 'admin')
                                <span class="badge bg-gold text-dark text-uppercase">Admin</span>
                                @elseif($u->role === 'manager')
                                <span class="badge bg-info text-dark text-uppercase">Manager</span>
                                @else
                                <span class="badge bg-secondary text-uppercase">Staff</span>
                                @endif
                            </td>
                            <td>{{ $u->phone ?: '—' }}</td>
                            <td>
                                @if($u->is_active)
                                <span class="badge bg-success bg-opacity-25 text-success border border-success">Active</span>
                                @else
                                <span class="badge bg-danger">Disabled</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($u->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete user {{ $u->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2"><i class="bi bi-trash"></i></button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create User -->
    <div class="col-lg-4">
        <div class="admin-card p-3">
            <h6 class="font-heading text-gold mb-3 fw-bold"><i class="bi bi-person-plus me-2"></i> Add Staff Account</h6>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label small text-uppercase text-gold">Full Name *</label>
                    <input type="text" name="name" id="name" class="form-control" required placeholder="Tariq Aziz">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label small text-uppercase text-gold">Email Address *</label>
                    <input type="email" name="email" id="email" class="form-control" required placeholder="tariq@azhalal.com">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label small text-uppercase text-gold">Password *</label>
                    <input type="password" name="password" id="password" class="form-control" required minlength="6">
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label small text-uppercase text-gold">Role & Access Level *</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="staff">Staff (Orders & Inquiries)</option>
                        <option value="manager">Manager (Catalog, Orders & Content)</option>
                        <option value="admin">Administrator (Full Access & User Mgmt)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label small text-uppercase text-gold">Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="919-555-0100">
                </div>

                <button type="submit" class="btn btn-gold w-100">Create Staff User</button>
            </form>
        </div>
    </div>
</div>

@endsection
