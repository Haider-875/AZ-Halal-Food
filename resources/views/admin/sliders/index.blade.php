@extends('admin.layouts.admin')

@section('title', 'Hero Sliders & Banners · Admin Portal')
@section('page_title', 'Hero Sliders & Banners')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="font-heading text-gold mb-1 fw-bold">Frontend Hero Slider Management</h5>
        <p class="text-parchment-muted small mb-0">Control the slides, luxury background imagery, titles, and call-to-action buttons shown on the homepage.</p>
    </div>
    <a href="{{ route('admin.sliders.create') }}" class="btn btn-gold">
        <i class="bi bi-plus-lg me-1"></i> Add New Slide
    </a>
</div>

<div class="admin-card p-4">
    @if($sliders->isEmpty())
        <div class="text-center py-5">
            <div class="mb-3 text-gold opacity-50">
                <i class="bi bi-sliders2 fs-1"></i>
            </div>
            <h6 class="font-heading text-parchment fw-bold mb-2">No Custom Sliders Added Yet</h6>
            <p class="text-parchment-dim small mb-4" style="max-width: 460px; margin: 0 auto;">
                The storefront is currently using the default luxury presentation. Create your first dynamic hero slide with custom image uploads, messaging, and button links.
            </p>
            <a href="{{ route('admin.sliders.create') }}" class="btn btn-gold">
                <i class="bi bi-cloud-arrow-up me-1"></i> Create First Slide
            </a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table admin-table align-middle">
                <thead>
                    <tr>
                        <th style="width: 70px;">Order</th>
                        <th style="width: 140px;">Slide Preview</th>
                        <th>Headline & Messaging</th>
                        <th>Buttons / Links</th>
                        <th style="width: 100px;">Status</th>
                        <th class="text-end" style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sliders as $slider)
                    <tr>
                        <td>
                            <span class="badge bg-dark border border-secondary text-gold px-2 py-1">
                                #{{ $slider->display_order }}
                            </span>
                        </td>
                        <td>
                            <div class="rounded border border-secondary border-opacity-25 overflow-hidden position-relative" style="width: 130px; height: 75px; background: #070E07;">
                                <img src="{{ $slider->image_url }}" alt="{{ $slider->title }}" class="w-100 h-100 object-fit-cover">
                                @if($slider->badge)
                                    <span class="position-absolute top-0 start-0 badge bg-gold text-dark" style="font-size: 7px; border-radius: 0 0 4px 0;">
                                        {{ $slider->badge }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($slider->subtitle)
                                <div class="text-gold text-uppercase fw-semibold" style="font-size: 10px; letter-spacing: 0.15em;">
                                    {{ $slider->subtitle }}
                                </div>
                            @endif
                            <div class="font-heading text-parchment fw-bold fs-6 mb-1">
                                {{ $slider->title }}
                            </div>
                            @if($slider->description)
                                <div class="text-parchment-muted small text-truncate" style="max-width: 320px;">
                                    {{ $slider->description }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="small">
                                @if($slider->button_text)
                                    <div class="text-parchment-dim text-truncate" style="max-width: 180px;">
                                        <i class="bi bi-link-45deg text-gold"></i> <strong>{{ $slider->button_text }}</strong>
                                        <span class="text-parchment-muted" style="font-size: 11px;">({{ $slider->button_link ?: '#' }})</span>
                                    </div>
                                @endif
                                @if($slider->secondary_button_text)
                                    <div class="text-parchment-dim text-truncate mt-1" style="max-width: 180px;">
                                        <i class="bi bi-link-45deg text-gold"></i> {{ $slider->secondary_button_text }}
                                        <span class="text-parchment-muted" style="font-size: 11px;">({{ $slider->secondary_button_link ?: '#' }})</span>
                                    </div>
                                @endif
                                @if(!$slider->button_text && !$slider->secondary_button_text)
                                    <span class="text-parchment-muted fst-italic">Default buttons</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($slider->is_active)
                                <span class="badge bg-success bg-opacity-25 text-success border border-success px-2 py-1">
                                    <i class="bi bi-check-circle me-1"></i> Active
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary px-2 py-1">
                                    <i class="bi bi-pause-circle me-1"></i> Disabled
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-sm btn-outline-gold" title="Edit Slide">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this slider?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Slide">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection

