@extends('admin.layouts.admin')

@section('title', 'Dynamic Website Content · Admin Portal')
@section('page_title', 'Dynamic Website Sections & Text')

@section('content')

<form action="{{ route('admin.sections.update') }}" method="POST">
    @csrf

    @foreach($sections as $group => $groupSections)
    @if($group === 'delivery')
    <div class="admin-card p-4 mb-4 border border-warning border-opacity-50" id="delivery">
        <div class="d-flex align-items-center justify-content-between border-bottom border-secondary border-opacity-25 pb-2 mb-3">
            <h5 class="font-heading text-gold mb-0 fw-bold text-uppercase">
                <i class="bi bi-truck me-2 text-warning"></i> Delivery & Shipping Fee Settings
            </h5>
            <span class="badge bg-warning text-dark text-uppercase px-2 py-1" style="font-size: 10px;">Live Checkout Calculation</span>
        </div>
        <p class="text-parchment-muted small mb-3">
            Configure the delivery fee charged during online checkout. By default, delivery is <strong>$0.00 (FREE)</strong>. To charge a fee, enter the dollar amount below.
        </p>

        <div class="row g-3">
            @foreach($groupSections as $sec)
            <div class="col-md-6">
                <label for="sec_{{ $sec->key }}" class="form-label small text-uppercase text-gold-light fw-semibold">
                    {{ $sec->label }}
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-dark text-gold border-secondary border-opacity-25">$</span>
                    <input type="number" step="0.01" min="0" name="{{ $sec->key }}" id="sec_{{ $sec->key }}" class="form-control" value="{{ old($sec->key, $sec->content ?? '0.00') }}" placeholder="0.00">
                </div>
                <div class="form-text text-parchment-muted" style="font-size: 11px;">
                    @if($sec->key === 'delivery_fee')
                        Standard fee for home delivery. Set to 0.00 for 100% Free Delivery.
                    @elseif($sec->key === 'delivery_free_threshold')
                        Orders with product subtotal at or above this amount get free delivery (set 0.00 to disable).
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="admin-card p-4 mb-4">
        <h5 class="font-heading text-gold mb-3 fw-bold text-uppercase border-bottom border-secondary border-opacity-25 pb-2">
            <i class="bi bi-pencil-square me-2"></i> {{ ucfirst($group) }} Section Texts
        </h5>

        <div class="row g-3">
            @foreach($groupSections as $sec)
            <div class="col-md-{{ $sec->type == 'textarea' ? '12' : '6' }}">
                <label for="sec_{{ $sec->key }}" class="form-label small text-uppercase text-gold-light fw-semibold">
                    {{ $sec->label }}
                </label>
                @if($sec->type == 'textarea')
                <textarea name="{{ $sec->key }}" id="sec_{{ $sec->key }}" rows="3" class="form-control">{{ old($sec->key, $sec->content) }}</textarea>
                @else
                <input type="text" name="{{ $sec->key }}" id="sec_{{ $sec->key }}" class="form-control" value="{{ old($sec->key, $sec->content) }}">
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endforeach

    <div class="d-flex justify-content-end mb-5">
        <button type="submit" class="btn btn-gold px-5 py-2 fs-6">Save All Changes</button>
    </div>
</form>

@endsection
