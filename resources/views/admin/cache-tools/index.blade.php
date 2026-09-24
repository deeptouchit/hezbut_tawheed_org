@extends('admin.layouts.master')

@section('page-title', 'Cache Tools')

@push('styles')
    <style>
        .terminal-shell {
            background: #0f172a;
            color: #e2e8f0;
            border: 1px solid #1e293b;
            border-radius: 8px;
            overflow: hidden;
            font-family: Consolas, Monaco, 'Courier New', monospace;
        }

        .terminal-head {
            background: #111827;
            border-bottom: 1px solid #1f2937;
            padding: 8px 12px;
            font-size: 12px;
            color: #94a3b8;
        }

        .terminal-line {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            border-bottom: 1px solid #1f2937;
        }

        .terminal-prompt {
            color: #22c55e;
            white-space: nowrap;
            font-size: 13px;
        }

        .terminal-input {
            flex: 1;
            background: transparent;
            border: 0;
            color: #f8fafc;
            outline: none;
            font-size: 13px;
            padding: 0;
        }

        .terminal-actions {
            padding: 10px 12px;
            border-bottom: 1px solid #1f2937;
        }

        .terminal-output {
            margin: 0;
            padding: 12px;
            min-height: 180px;
            max-height: 360px;
            overflow: auto;
            background: #020617;
            color: #93c5fd;
            font-size: 12px;
            line-height: 1.45;
        }

        .log-viewer {
            max-height: 400px;
            overflow: auto;
            background: #1a1a2e;
            color: #eee;
            padding: 15px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-cached {
            background: #28a745;
            color: white;
        }

        .status-not-cached {
            background: #dc3545;
            color: white;
        }
    </style>
@endpush

@section('filter_input')
    <div class="row px-3">
        <div class="col-md-12 mb-3">
            <div class="btn-group w-100">
                <button type="button" class="btn btn-info" id="refreshStatusBtn">
                    <i class="fas fa-chart-line"></i> Cache Status
                </button>
                <button type="button" class="btn btn-secondary" id="refreshLogsBtn">
                    <i class="fas fa-sync-alt"></i> Refresh Logs
                </button>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Alert Messages -->
        @if (session('message'))
            <div class="alert alert-{{ session('alert-type', 'info') }} alert-dismissible fade show shadow-sm mb-3"
                role="alert">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        @if (session('alert-type') == 'success')
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        @elseif(session('alert-type') == 'error')
                            <i class="fas fa-exclamation-circle fa-2x text-danger"></i>
                        @else
                            <i class="fas fa-info-circle fa-2x text-info"></i>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <strong>{{ session('message') }}</strong>
                        @if (session('command_name'))
                            <div class="small mt-1">
                                <strong>Command:</strong> {{ session('command_name') }}
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <div class="row">
            <!-- Cache Status Card -->
            <div class="col-md-12 mb-3">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line mr-2"></i> Cache Status
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="cacheStatus">
                            <div class="text-center">
                                <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                                <p class="mt-2">Loading cache status...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Log Viewer Card -->
            <div class="col-md-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-file-alt mr-2"></i> Laravel Log Viewer
                        </h3>
                        <div class="card-tools">
                            <div class="btn-group">
                                <form action="{{ route('admin.cache-tools.run') }}" method="POST" class="d-inline me-2">
                                    @csrf
                                    <input type="hidden" name="command" value="logs:view">
                                    <button type="submit" class="btn btn-sm btn-info">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </button>
                                </form>
                                <form action="{{ route('admin.cache-tools.run') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="command" value="logs:clear">
                                    <button type="button" class="btn btn-sm btn-danger" id="clearLogsBtn">
                                        <i class="fas fa-trash"></i> Clear
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-1">
                        <pre class="log-viewer">{{ $logOutput }}</pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <!-- Cache Commands Card -->
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-broom mr-2"></i> Cache Commands
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Run Laravel cache clear commands from here.
                        </p>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 220px;">Command</th>
                                        <th>Description</th>
                                        <th style="width: 150px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($commands as $command => $description)
                                        @if (in_array($command, ['logs:view', 'logs:clear']))
                                            @continue
                                        @endif
                                        <tr>
                                            <td><code>{{ $command }}</code></td>
                                            <td>{{ $description }}</td>
                                            <td>
                                                <form action="{{ route('admin.cache-tools.run') }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="command" value="{{ $command }}">
                                                    <button type="submit" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-play"></i> Run
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <!-- Custom Command Terminal Card -->
            <div class="col-md-12">
                <div class="card card-outline card-dark">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-terminal mr-2"></i> Custom Command Terminal
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.cache-tools.custom-run') }}" method="POST"
                            class="terminal-shell">
                            @csrf

                            <div class="terminal-head">Artisan Runner | Run any artisan command</div>

                            <div class="terminal-line">
                                <span class="terminal-prompt">$ php artisan</span>
                                <input type="text" id="custom_command" name="custom_command" class="terminal-input"
                                    value="{{ old('custom_command') }}" placeholder="migrate:status" autocomplete="off"
                                    spellcheck="false" required>
                            </div>

                            <div class="terminal-actions">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-play"></i> Run Command
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm" id="clearTerminalBtn">
                                    <i class="fas fa-eraser"></i> Clear
                                </button>
                            </div>

<pre class="terminal-output">
@if ($lastCommand)
<strong>Last Command:</strong> {{ $lastCommand }}
<strong>Output:</strong>
{{ $lastOutput }}
@else
No command executed yet. Try: migrate:status, queue:restart, schedule:list, make:controller TestController
@endif
</pre>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            'use strict';

            // Auto-hide alerts after 5 seconds
            $('.alert-dismissible').each(function() {
                setTimeout(() => {
                    $(this).fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);
            });

            // Focus on custom command input
            $('#custom_command').trigger('focus');

            // Clear terminal output button
            $('#clearTerminalBtn').on('click', function() {
                $('.terminal-output').html('No output to display. Run a command to see output.');
            });

            // Refresh cache status
            function loadCacheStatus() {
                const statusContainer = $('#cacheStatus');

                statusContainer.html(
                    '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x text-muted"></i><p class="mt-2">Loading cache status...</p></div>'
                    );

                $.ajax({
                    url: '{{ route('admin.cache-tools.status') }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.status) {
                            let html = '<div class="row">';

                            $.each(response.status, function(key, value) {
                                const statusClass = value.cached ? 'status-cached' :
                                    'status-not-cached';
                                const statusText = value.cached ? 'Cached' : 'Not Cached';

                                html += `
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5><i class="fas fa-${key === 'config' ? 'cog' : (key === 'routes' ? 'route' : (key === 'views' ? 'eye' : 'calendar'))}"></i> ${ucfirst(key)}</h5>
                                        <span class="status-badge ${statusClass}">${statusText}</span>
                                        <div class="small text-muted mt-2">${value.path}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                            });

                            html += '</div>';
                            statusContainer.html(html);
                        } else {
                            statusContainer.html(
                                '<div class="alert alert-warning">Unable to load cache status.</div>'
                                );
                        }
                    },
                    error: function() {
                        statusContainer.html(
                            '<div class="alert alert-danger">Failed to load cache status.</div>');
                    }
                });
            }

            function ucfirst(str) {
                return str.charAt(0).toUpperCase() + str.slice(1);
            }

            // Refresh logs
            $('#refreshLogsBtn').on('click', function() {
                window.location.reload();
            });

            // Refresh status button
            $('#refreshStatusBtn').on('click', function() {
                loadCacheStatus();
            });

            // Initial load
            loadCacheStatus();
        });
    </script>

    <script>
document.getElementById('clearLogsBtn').addEventListener('click', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Are you sure?',
        text: "All log files will be cleared permanently!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, clear it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.cache-tools.run") }}';
            form.style.display = 'none';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';

            const commandInput = document.createElement('input');
            commandInput.type = 'hidden';
            commandInput.name = 'command';
            commandInput.value = 'logs:clear';

            form.appendChild(csrfInput);
            form.appendChild(commandInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
});
</script>
@endpush
