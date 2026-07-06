@extends('admin.layouts.main')

@section('page-title', 'Cache Tools')

@section('page_style')
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
</style>
@endsection

@section('content')
<section class="content pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-file-alt mr-2"></i> Laravel Log Viewer
                        </h3>
                        <div class="card-tools">
                            <div class="btn-group">
                                <form action="{{ route('admin.cache-tools.run') }}" method="POST" class="mr-2">
                                    @csrf
                                    <input type="hidden" name="command" value="logs:view">
                                    <button type="submit" class="btn btn-info btn-sm">
                                        <i class="fas fa-sync-alt mr-1"></i> Refresh Logs
                                    </button>
                                </form>
                                <form action="{{ route('admin.cache-tools.run') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="command" value="logs:clear">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash mr-1"></i> Clear Logs
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-1">
                        <pre class="bg-dark text-light p-3 mb-0" style="max-height: 380px; overflow: auto;">{{ $logOutput }}</pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-broom mr-2"></i> Cache Commands
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            এই অংশ থেকে প্রয়োজনীয় Laravel cache clear command চালাতে পারবেন।
                        </p>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th style="width: 220px;">Command</th>
                                        <th>Description</th>
                                        <th style="width: 170px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($commands as $command => $description)
                                        @if(in_array($command, ['logs:view', 'logs:clear'], true))
                                            @continue
                                        @endif
                                        <tr>
                                            <td><code>{{ $command }}</code></td>
                                            <td>{{ $description }}</td>
                                            <td class="p-0">
                                                @can('cache-tools_custom-run')
                                                    <form action="{{ route('admin.cache-tools.run') }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="command" value="{{ $command }}">
                                                        <button type="submit" class="btn btn-warning btn-sm btn-block">
                                                            <i class="fas fa-play mr-1"></i> Run
                                                        </button>
                                                    </form>
                                                @else
                                                    <div class="alert alert-warning">আপনার কাস্টম কমান্ড চালানোর অনুমতি নেই।</div>
                                                @endcan
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

        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-terminal mr-2"></i> Custom Command Terminal
                        </h3>
                    </div>
                    <div class="card-body">
                    @can('cache-tools_custom-run')
                        <form action="{{ route('admin.cache-tools.custom-run') }}" method="POST" class="terminal-shell">
                            @csrf

                            <div class="terminal-head">Artisan Runner | shell style input</div>

                            <div class="terminal-line">
                                <span class="terminal-prompt">$ php artisan</span>
                                <input
                                    type="text"
                                    id="custom_command"
                                    name="custom_command"
                                    class="terminal-input"
                                    value="{{ old('custom_command') }}"
                                    placeholder="migrate:status"
                                    autocomplete="off"
                                    spellcheck="false"
                                    required
                                />
                            </div>

                            <div class="terminal-actions">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-play mr-1"></i> Run
                                </button>
                            </div>

                            <pre class="terminal-output"> @if($lastCommand)Last Command: {{ $lastCommand }}{{ $lastOutput }} @else No command executed yet.Try: migrate:status, queue:restart, schedule:list @endif</pre>
                        </form>
                    @else
                        <div class="alert alert-warning">আপনার কাস্টম কমান্ড চালানোর অনুমতি নেই।</div>
                    @endcan

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(function () {
        $('#custom_command').trigger('focus');
    });
</script>
@endsection
