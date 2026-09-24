@extends('admin.layouts.master')

@section('page-title', 'Activity Log Details')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-info-circle mr-1"></i>
                Activity Log Details
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Logs
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text">Log ID</span>
                            <span class="info-box-number">#{{ $log->id }}</span>
                        </div>
                    </div>

                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text">User</span>
                            <span class="info-box-number">
                                <strong>{{ $log->user?->name ?? 'System' }}</strong>
                                <br>
                                <small>{{ $log->user?->email ?? 'N/A' }}</small>
                            </span>
                        </div>
                    </div>

                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text">Action</span>
                            <span class="info-box-number">{!! $log->action_badge !!}</span>
                        </div>
                    </div>

                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text">Module</span>
                            <span class="info-box-number">{!! $log->module_badge !!}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text">IP Address</span>
                            <span class="info-box-number"><code>{{ $log->ip_address ?? '-' }}</code></span>
                        </div>
                    </div>

                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text">URL</span>
                            <span class="info-box-number"><small>{{ $log->url ?? '-' }}</small></span>
                        </div>
                    </div>

                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text">Method</span>
                            <span class="info-box-number">
                                <span class="badge bg-{{ $log->method == 'GET' ? 'info' : ($log->method == 'POST' ? 'success' : ($log->method == 'PUT' ? 'warning' : 'danger')) }}">
                                    {{ $log->method ?? '-' }}
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text">Created At</span>
                            <span class="info-box-number">{{ $log->created_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text">Description</span>
                            <span class="info-box-number">{{ $log->description ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($log->user_agent)
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text">User Agent</span>
                            <span class="info-box-number"><small>{{ $log->user_agent }}</small></span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($log->old_data || $log->new_data)
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h4 class="card-title">Data Changes</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($log->old_data)
                                <div class="col-md-6">
                                    <div class="alert alert-danger">
                                        <strong><i class="fas fa-database"></i> Old Data:</strong>
                                    </div>
                                    <pre class="bg-light p-3 rounded" style="max-height: 400px; overflow: auto;">{{ json_encode($log->old_data, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                                @endif
                                @if($log->new_data)
                                <div class="col-md-6">
                                    <div class="alert alert-success">
                                        <strong><i class="fas fa-database"></i> New Data:</strong>
                                    </div>
                                    <pre class="bg-light p-3 rounded" style="max-height: 400px; overflow: auto;">{{ json_encode($log->new_data, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="card-footer text-center">
            <button class="btn btn-danger delete-log" data-id="{{ $log->id }}">
                <i class="fas fa-trash"></i> Delete This Log
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.delete-log').on('click', function() {
        var id = $(this).data('id');

        Swal.fire({
            title: 'Delete Log?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/activity-logs") }}/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            window.location.href = '{{ route("admin.activity-logs.index") }}';
                        } else {
                            toastr.error(response.message);
                        }
                    }
                });
            }
        });
    });
});
</script>
@endpush
