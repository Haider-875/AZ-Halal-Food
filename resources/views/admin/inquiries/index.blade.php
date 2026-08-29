@extends('admin.layouts.admin')

@section('title', 'Wholesale Inquiries · Admin Portal')
@section('page_title', 'Wholesale & Partnership Inquiries')

@section('content')

<div class="admin-card">
    <div class="p-3 border-bottom border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
        <h6 class="font-heading text-gold mb-0 fw-bold"><i class="bi bi-envelope-paper me-2"></i> Inquiries ({{ $inquiries->total() }})</h6>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.inquiries.index', ['status' => 'all']) }}" class="btn btn-sm btn-outline-secondary">All</a>
            <a href="{{ route('admin.inquiries.index', ['status' => 'new']) }}" class="btn btn-sm btn-outline-warning">New</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>From</th>
                    <th>Type</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inquiries as $inq)
                <tr>
                    <td>
                        <strong class="text-parchment">{{ $inq->name }}</strong>
                        @if($inq->company)
                        <div class="text-gold small">{{ $inq->company }}</div>
                        @endif
                        <div class="text-parchment-muted small">{{ $inq->email }} · {{ $inq->phone }}</div>
                    </td>
                    <td>
                        <span class="badge bg-dark border border-secondary text-uppercase">{{ $inq->inquiry_type }}</span>
                    </td>
                    <td style="max-width: 320px;">
                        <p class="mb-0 small text-parchment-dim" style="line-height: 1.5;">{{ $inq->message }}</p>
                    </td>
                    <td>
                        @if($inq->status === 'new')
                        <span class="badge bg-warning text-dark">New</span>
                        @elseif($inq->status === 'contacted')
                        <span class="badge bg-info text-dark">Contacted</span>
                        @else
                        <span class="badge bg-secondary">Closed</span>
                        @endif
                    </td>
                    <td>
                        <span class="text-parchment-muted small">{{ $inq->created_at->format('M d, Y') }}</span>
                    </td>
                    <td class="text-end">
                        <form action="{{ route('admin.inquiries.status', $inq) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                <option value="new" {{ $inq->status == 'new' ? 'selected' : '' }}>New</option>
                                <option value="contacted" {{ $inq->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                <option value="closed" {{ $inq->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-parchment-muted">No inquiries received.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top border-secondary border-opacity-25">
        {{ $inquiries->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
