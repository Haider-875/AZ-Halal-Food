@extends('admin.layouts.admin')

@section('title', 'Staff Users & Roles · Admin Portal')
@section('page_title', 'Users & Access Control')

@section('content')

<!-- Header Action Bar & Metrics -->
<div class="row g-3 mb-4">
    <!-- Total Accounts Card -->
    <div class="col-sm-6 col-xl-3">
        <div class="admin-card p-3 h-100 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-parchment-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.1em;">Total Accounts</span>
                <h3 class="font-heading text-gold mb-0 mt-1 fw-bold">{{ $totalUsers }}</h3>
                <span class="text-success small" style="font-size: 11px;">
                    <i class="bi bi-check-circle me-1"></i> {{ $activeCount }} Active Accounts
                </span>
            </div>
            <div class="rounded-3 p-3 bg-gold bg-opacity-10 text-gold fs-4 border border-gold border-opacity-25">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
    </div>

    <!-- Administrators Card -->
    <div class="col-sm-6 col-xl-3">
        <div class="admin-card p-3 h-100 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-parchment-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.1em;">Administrators</span>
                <h3 class="font-heading text-gold mb-0 mt-1 fw-bold">{{ $adminCount }}</h3>
                <span class="text-parchment-muted small" style="font-size: 11px;">Full System Control</span>
            </div>
            <div class="rounded-3 p-3 bg-warning bg-opacity-10 text-warning fs-4 border border-warning border-opacity-25">
                <i class="bi bi-shield-shaded"></i>
            </div>
        </div>
    </div>

    <!-- Managers Card -->
    <div class="col-sm-6 col-xl-3">
        <div class="admin-card p-3 h-100 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-parchment-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.1em;">Store Managers</span>
                <h3 class="font-heading text-info mb-0 mt-1 fw-bold">{{ $managerCount }}</h3>
                <span class="text-parchment-muted small" style="font-size: 11px;">Catalog & Operations</span>
            </div>
            <div class="rounded-3 p-3 bg-info bg-opacity-10 text-info fs-4 border border-info border-opacity-25">
                <i class="bi bi-person-workspace"></i>
            </div>
        </div>
    </div>

    <!-- Staff Members Card -->
    <div class="col-sm-6 col-xl-3">
        <div class="admin-card p-3 h-100 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-parchment-muted text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.1em;">Staff Members</span>
                <h3 class="font-heading text-parchment mb-0 mt-1 fw-bold">{{ $staffCount }}</h3>
                <span class="text-parchment-muted small" style="font-size: 11px;">Orders & Inquiries</span>
            </div>
            <div class="rounded-3 p-3 bg-secondary bg-opacity-10 text-parchment-muted fs-4 border border-secondary border-opacity-25">
                <i class="bi bi-person-badge"></i>
            </div>
        </div>
    </div>
</div>

<!-- Tabs Navigation -->
<ul class="nav nav-tabs border-secondary border-opacity-25 mb-4" id="usersTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active px-4 py-2 text-uppercase fw-semibold small" id="users-tab" data-bs-toggle="tab" data-bs-target="#users-panel" type="button" role="tab" style="letter-spacing: 0.08em;">
            <i class="bi bi-person-lines-fill me-2 text-gold"></i> User Directory ({{ $totalUsers }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link px-4 py-2 text-uppercase fw-semibold small" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles-panel" type="button" role="tab" style="letter-spacing: 0.08em;">
            <i class="bi bi-shield-check me-2 text-gold"></i> Roles & Permissions Matrix
        </button>
    </li>
</ul>

<div class="tab-content" id="usersTabContent">

    <!-- Tab 1: User Directory & Filters -->
    <div class="tab-pane fade show active" id="users-panel" role="tabpanel" aria-labelledby="users-tab">
        <div class="admin-card mb-4">
            <!-- Search & Filters Toolbar -->
            <div class="p-3 border-bottom border-secondary border-opacity-25">
                <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2 align-items-center">
                    <!-- Search Input -->
                    <div class="col-lg-4 col-md-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-transparent border-secondary border-opacity-25 text-gold">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" id="userFilterSearch" class="form-control form-control-sm"
                                   placeholder="Search by name, email, or phone..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Role Filter -->
                    <div class="col-sm-6 col-md-3 col-lg-2">
                        <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="all">All Roles</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="manager" {{ request('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-sm-6 col-md-3 col-lg-2">
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="all">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                            <option value="disabled" {{ request('status') === 'disabled' ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>

                    <!-- Reset & Actions -->
                    <div class="col-lg-4 col-md-12 text-md-end d-flex gap-2 justify-content-md-end">
                        @if(request()->filled('search') || (request()->filled('role') && request('role') !== 'all') || (request()->filled('status') && request('status') !== 'all'))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filters">
                            <i class="bi bi-x-circle me-1"></i> Clear
                        </a>
                        @endif

                        <button type="button" class="btn btn-sm btn-gold d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="bi bi-person-plus-fill me-1"></i> Add New User
                        </button>
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="table-responsive">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="min-width: 240px;">User Profile</th>
                            <th style="min-width: 140px;">Role & Access</th>
                            <th style="min-width: 130px;">Phone</th>
                            <th style="min-width: 110px;">Status</th>
                            <th style="min-width: 120px;">Registered</th>
                            <th class="text-end" style="min-width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                        <tr>
                            <!-- Profile -->
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-dark flex-shrink-0 shadow-sm"
                                         style="width: 38px; height: 38px; font-size: 14px;
                                         @if($u->role === 'admin')
                                            background: var(--gold); border: 2px solid rgba(212, 175, 55, 0.6);
                                         @elseif($u->role === 'manager')
                                            background: #0dcaf0; border: 2px solid rgba(13, 202, 240, 0.6);
                                         @else
                                            background: #adb5bd; border: 2px solid rgba(173, 181, 189, 0.5);
                                         @endif">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="{{ route('admin.users.edit', $u) }}" class="text-parchment fw-bold text-decoration-none hover-gold">
                                                {{ $u->name }}
                                            </a>
                                            @if($u->id === auth()->id())
                                                <span class="badge bg-gold text-dark text-uppercase" style="font-size: 9px; padding: 2px 6px;">You</span>
                                            @endif
                                        </div>
                                        <span class="text-parchment-muted small">{{ $u->email }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Role -->
                            <td>
                                @if($u->role === 'admin')
                                    <span class="badge bg-gold text-dark text-uppercase px-2 py-1">
                                        <i class="bi bi-shield-lock-fill me-1"></i> Admin
                                    </span>
                                @elseif($u->role === 'manager')
                                    <span class="badge bg-info text-dark text-uppercase px-2 py-1">
                                        <i class="bi bi-person-workspace me-1"></i> Manager
                                    </span>
                                @else
                                    <span class="badge bg-secondary text-uppercase px-2 py-1">
                                        <i class="bi bi-person me-1"></i> Staff
                                    </span>
                                @endif
                            </td>

                            <!-- Phone -->
                            <td>
                                @if($u->phone)
                                    <span class="small text-parchment"><i class="bi bi-telephone text-gold me-1"></i> {{ $u->phone }}</span>
                                @else
                                    <span class="text-parchment-muted small">—</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td>
                                @if($u->is_active)
                                    <span class="badge bg-success bg-opacity-25 text-success border border-success d-inline-flex align-items-center gap-1">
                                        <span class="spinner-grow spinner-grow-sm" style="width: 6px; height: 6px;" role="status"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger">
                                        <i class="bi bi-x-circle me-1"></i> Disabled
                                    </span>
                                @endif
                            </td>

                            <!-- Registered Date -->
                            <td class="small text-parchment-muted">
                                {{ $u->created_at ? $u->created_at->format('M d, Y') : '—' }}
                            </td>

                            <!-- Actions -->
                            <td class="text-end">
                                <div class="d-inline-flex gap-1 align-items-center">
                                    <!-- View Profile & Edit Details -->
                                    <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-gold py-1 px-2" title="User Profile & Settings">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <!-- Quick Password Change Modal Trigger -->
                                    <button type="button" class="btn btn-sm btn-outline-warning py-1 px-2" data-bs-toggle="modal" data-bs-target="#userPassModal{{ $u->id }}" title="Change User Password">
                                        <i class="bi bi-key-fill"></i>
                                    </button>

                                    <!-- Delete (Non-Self only) -->
                                    @if($u->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to permanently delete user {{ $u->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Delete User">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-parchment-muted">
                                <i class="bi bi-people display-6 d-block mb-3 text-gold opacity-50"></i>
                                <h6>No Users Found</h6>
                                <p class="small mb-3">No user accounts matched your current search or filter criteria.</p>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-gold">
                                    Reset Filters
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            @if($users->hasPages())
            <div class="p-3 border-top border-secondary border-opacity-25 d-flex justify-content-center">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Tab 2: Roles & Permissions Matrix Guide -->
    <div class="tab-pane fade" id="roles-panel" role="tabpanel" aria-labelledby="roles-tab">
        <!-- Role Level Cards -->
        <div class="row g-4 mb-4">
            <!-- Administrator Card -->
            <div class="col-lg-4">
                <div class="admin-card p-4 h-100 border-gold" style="box-shadow: 0 4px 20px rgba(212, 175, 55, 0.08);">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 p-2 bg-gold text-dark fs-4 fw-bold">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div>
                            <h5 class="font-heading text-gold mb-0 fw-bold">Administrator</h5>
                            <span class="badge bg-gold text-dark text-uppercase small">Level 1 · Highest Access</span>
                        </div>
                    </div>
                    <p class="text-parchment-muted small mb-3">
                        Full administrative access over every aspect of the store, configuration, security credentials, and user permissions.
                    </p>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li class="text-parchment"><i class="bi bi-check2 text-gold me-2"></i> Manage Staff & Admin Accounts</li>
                        <li class="text-parchment"><i class="bi bi-check2 text-gold me-2"></i> Reset Any User's Password</li>
                        <li class="text-parchment"><i class="bi bi-check2 text-gold me-2"></i> Products & Inventory Control</li>
                        <li class="text-parchment"><i class="bi bi-check2 text-gold me-2"></i> Full Financials & Invoices</li>
                        <li class="text-parchment"><i class="bi bi-check2 text-gold me-2"></i> Website Content & Sliders</li>
                    </ul>
                </div>
            </div>

            <!-- Manager Card -->
            <div class="col-lg-4">
                <div class="admin-card p-4 h-100 border-info border-opacity-50">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 p-2 bg-info text-dark fs-4 fw-bold">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                        <div>
                            <h5 class="font-heading text-info mb-0 fw-bold">Store Manager</h5>
                            <span class="badge bg-info text-dark text-uppercase small">Level 2 · Operations</span>
                        </div>
                    </div>
                    <p class="text-parchment-muted small mb-3">
                        Supervises the live storefront catalog, manages stock levels, updates banners and gallery media, and handles order fulfillment.
                    </p>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li class="text-parchment"><i class="bi bi-check2 text-info me-2"></i> Product Catalog & Categories</li>
                        <li class="text-parchment"><i class="bi bi-check2 text-info me-2"></i> Orders & Delivery Statuses</li>
                        <li class="text-parchment"><i class="bi bi-check2 text-info me-2"></i> Gallery & Banner Sliders</li>
                        <li class="text-parchment"><i class="bi bi-check2 text-info me-2"></i> Customer Wholesale Inquiries</li>
                        <li class="text-parchment-muted"><i class="bi bi-x text-danger me-2"></i> No User/Role Management</li>
                    </ul>
                </div>
            </div>

            <!-- Staff Card -->
            <div class="col-lg-4">
                <div class="admin-card p-4 h-100 border-secondary border-opacity-50">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 p-2 bg-secondary text-white fs-4 fw-bold">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div>
                            <h5 class="font-heading text-parchment mb-0 fw-bold">Staff Member</h5>
                            <span class="badge bg-secondary text-uppercase small">Level 3 · Frontline</span>
                        </div>
                    </div>
                    <p class="text-parchment-muted small mb-3">
                        Day-to-day order processing, packaging status, customer communication, and answering contact requests.
                    </p>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li class="text-parchment"><i class="bi bi-check2 text-parchment me-2"></i> View & Process Incoming Orders</li>
                        <li class="text-parchment"><i class="bi bi-check2 text-parchment me-2"></i> Print Order Invoices</li>
                        <li class="text-parchment"><i class="bi bi-check2 text-parchment me-2"></i> Reply to Customer Inquiries</li>
                        <li class="text-parchment-muted"><i class="bi bi-x text-danger me-2"></i> No Product Editing / Pricing</li>
                        <li class="text-parchment-muted"><i class="bi bi-x text-danger me-2"></i> No Access to User Accounts</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Permissions Matrix Table -->
        <div class="admin-card">
            <div class="p-3 border-bottom border-secondary border-opacity-25">
                <h6 class="font-heading text-gold mb-0 fw-bold">
                    <i class="bi bi-table me-2"></i> Detailed Privileges & Access Matrix
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table admin-table mb-0 text-center">
                    <thead>
                        <tr>
                            <th class="text-start">Module / Capability</th>
                            <th>Staff</th>
                            <th>Manager</th>
                            <th>Administrator</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-start text-parchment fw-semibold">View Admin Dashboard & Key Metrics</td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start text-parchment fw-semibold">View & Update Order Status</td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start text-parchment fw-semibold">Print Order Invoices & Receipts</td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start text-parchment fw-semibold">Manage Wholesale Inquiries</td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start text-parchment fw-semibold">Create & Edit Products, Pricing, Stock</td>
                            <td><i class="bi bi-x-circle text-parchment-muted opacity-50 fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start text-parchment fw-semibold">Categories & Subcategories Management</td>
                            <td><i class="bi bi-x-circle text-parchment-muted opacity-50 fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start text-parchment fw-semibold">Gallery & Homepage Hero Sliders</td>
                            <td><i class="bi bi-x-circle text-parchment-muted opacity-50 fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start text-parchment fw-semibold">Dynamic Website Content & Settings</td>
                            <td><i class="bi bi-x-circle text-parchment-muted opacity-50 fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start text-parchment fw-semibold text-gold">Manage Users, Roles & Permissions</td>
                            <td><i class="bi bi-x-circle text-parchment-muted opacity-50 fs-5"></i></td>
                            <td><i class="bi bi-x-circle text-parchment-muted opacity-50 fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start text-parchment fw-semibold text-gold">Reset Any Staff/User Password</td>
                            <td><i class="bi bi-x-circle text-parchment-muted opacity-50 fs-5"></i></td>
                            <td><i class="bi bi-x-circle text-parchment-muted opacity-50 fs-5"></i></td>
                            <td><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Modal: Add New Staff User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background-color: var(--admin-card); border: 1px solid var(--border-gold); color: var(--parchment);">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-gold text-dark p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <h6 class="modal-title font-heading text-gold fw-bold mb-0" id="addUserModalLabel">
                        Create New Staff Account
                    </h6>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modal_name" class="form-label small text-uppercase text-gold">Full Name *</label>
                            <input type="text" name="name" id="modal_name" class="form-control" required placeholder="e.g. Tariq Aziz" value="{{ old('name') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="modal_email" class="form-label small text-uppercase text-gold">Email Address *</label>
                            <input type="email" name="email" id="modal_email" class="form-control" required placeholder="tariq@azhalal.com" value="{{ old('email') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="modal_password" class="form-label small text-uppercase text-gold">Initial Password *</label>
                            <div class="input-group">
                                <input type="password" name="password" id="modal_password" class="form-control" required minlength="6" placeholder="Min. 6 characters">
                                <button class="btn btn-outline-secondary toggle-modal-pass" type="button" data-target="modal_password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="modal_phone" class="form-label small text-uppercase text-gold">Phone Number</label>
                            <input type="text" name="phone" id="modal_phone" class="form-control" placeholder="919-555-0100" value="{{ old('phone') }}">
                        </div>

                        <div class="col-md-12">
                            <label for="modal_role" class="form-label small text-uppercase text-gold">Role & Privileges *</label>
                            <select name="role" id="modal_role" class="form-select" required>
                                <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>
                                    Staff Member — Order processing, invoices, wholesale inquiries
                                </option>
                                <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>
                                    Store Manager — Product catalog, categories, gallery, sliders, and orders
                                </option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                    Administrator — Full access including user & role management
                                </option>
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch pt-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="modal_is_active" name="is_active" value="1" checked>
                                <label class="form-check-label text-parchment small" for="modal_is_active">
                                    <strong>Active Account</strong> (Allow this user to sign in to the Admin Portal immediately)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-gold">
                        <i class="bi bi-check2-circle me-1"></i> Create User Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals: Quick Password Reset for Each User -->
@foreach($users as $u)
<div class="modal fade" id="userPassModal{{ $u->id }}" tabindex="-1" aria-labelledby="userPassModalLabel{{ $u->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: var(--admin-card); border: 1px solid var(--border-gold); color: var(--parchment);">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h6 class="modal-title font-heading text-gold fw-bold" id="userPassModalLabel{{ $u->id }}">
                    <i class="bi bi-key-fill me-2"></i> Change Password for {{ $u->name }}
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.password', $u) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p class="small text-parchment-muted mb-3">
                        Set a new password for <strong>{{ $u->name }}</strong> ({{ $u->email }}). The user will be able to log in with this new password immediately.
                    </p>

                    <div class="mb-3">
                        <label for="modal_pass_{{ $u->id }}" class="form-label small text-uppercase text-gold">New Password *</label>
                        <div class="input-group">
                            <input type="password" name="password" id="modal_pass_{{ $u->id }}" class="form-control" required minlength="6" placeholder="Min. 6 characters">
                            <button class="btn btn-outline-secondary toggle-modal-pass" type="button" data-target="modal_pass_{{ $u->id }}">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="modal_pass_conf_{{ $u->id }}" class="form-label small text-uppercase text-gold">Confirm New Password *</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="modal_pass_conf_{{ $u->id }}" class="form-control" required minlength="6" placeholder="Repeat new password">
                            <button class="btn btn-outline-secondary toggle-modal-pass" type="button" data-target="modal_pass_conf_{{ $u->id }}">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-gold">
                        <i class="bi bi-lock-fill me-1"></i> Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.toggle-modal-pass').forEach(btn => {
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
