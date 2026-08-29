@extends('admin.layouts.admin')

@section('title', 'Dynamic Website Content · Admin Portal')
@section('page_title', 'Dynamic Website Sections & Text')

@section('content')

<form action="{{ route('admin.sections.update') }}" method="POST">
    @csrf

    @foreach($sections as $group => $groupSections)
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
    @endforeach

    <div class="d-flex justify-content-end mb-5">
        <button type="submit" class="btn btn-gold px-5 py-2 fs-6">Save All Changes</button>
    </div>
</form>

@endsection
