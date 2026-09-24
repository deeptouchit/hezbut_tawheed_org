@extends('admin.layouts.main')

@section('page-title', 'Backup Management')

@section('content')
<section class="content pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-plus-circle mr-2"></i> Create Custom Backup
                        </h3>
                        <div class="card-tools">
                            @can('backups_access')
                            <button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#backupManualModal">
                                <i class="fas fa-book mr-1"></i> User Manual
                            </button>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.backups.create') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox" class="custom-control-input" id="include_files" name="include_files" value="1" {{ ($settings['backup_include_files'] ?? '1') === '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="include_files">Include project files</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox" class="custom-control-input" id="include_database" name="include_database" value="1" {{ ($settings['backup_include_database'] ?? '1') === '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="include_database">Include database dump</label>
                                    </div>
                                </div>
                            </div>

                            <small class="text-muted d-block mb-3">
                                যদি দুইটাই unchecked থাকে, system একটি custom ZIP তৈরি করবে (শুধু note/metadata সহ) যেখানে File Prefix কাজ করবে।
                            </small>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="file_prefix">File Prefix</label>
                                    <input type="text" class="form-control" id="file_prefix" name="file_prefix" value="{{ $settings['backup_file_prefix'] ?? 'backup' }}">
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="include_paths">Include Paths (comma separated)</label>
                                    <input type="text" class="form-control" id="include_paths" name="include_paths" value="{{ old('include_paths') }}" placeholder="example: app/Http/Controllers, resources/views/admin/settings/backups.blade.php">
                                    <small class="text-muted">এই field দিলে শুধুমাত্র এই path গুলো backup হবে।</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="exclude_paths">Exclude Paths (comma separated)</label>
                                    <input type="text" class="form-control" id="exclude_paths" name="exclude_paths" value="{{ $settings['backup_exclude_paths'] ?? '' }}" placeholder="example: storage/app/temp, public/uploads/tmp">
                                </div>
                            </div>
                            @can('backups_create')
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-play mr-1"></i> Create Backup Now
                                </button>
                            @endcan
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cogs mr-2"></i> Schedule and Email Settings
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.backups.settings') }}" method="POST">
                            @csrf
                            <div class="custom-control custom-switch mb-3">
                                <input type="checkbox" class="custom-control-input" id="backup_schedule_enabled" name="backup_schedule_enabled" value="1" {{ ($settings['backup_schedule_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="backup_schedule_enabled">Enable automatic backup schedule</label>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="backup_schedule_frequency">Frequency</label>
                                    <select class="form-control" id="backup_schedule_frequency" name="backup_schedule_frequency">
                                        <option value="daily" {{ ($settings['backup_schedule_frequency'] ?? 'daily') === 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ ($settings['backup_schedule_frequency'] ?? 'daily') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ ($settings['backup_schedule_frequency'] ?? 'daily') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="backup_schedule_time">Time</label>
                                    <input type="time" class="form-control" id="backup_schedule_time" name="backup_schedule_time" value="{{ $settings['backup_schedule_time'] ?? '02:00' }}">
                                </div>
                            </div>

                            <div class="form-row" id="weeklyOption">
                                <div class="form-group col-md-12">
                                    <label for="backup_schedule_day_of_week">Day of Week</label>
                                    <select class="form-control" id="backup_schedule_day_of_week" name="backup_schedule_day_of_week">
                                        <option value="0" {{ ($settings['backup_schedule_day_of_week'] ?? '0') === '0' ? 'selected' : '' }}>Sunday</option>
                                        <option value="1" {{ ($settings['backup_schedule_day_of_week'] ?? '0') === '1' ? 'selected' : '' }}>Monday</option>
                                        <option value="2" {{ ($settings['backup_schedule_day_of_week'] ?? '0') === '2' ? 'selected' : '' }}>Tuesday</option>
                                        <option value="3" {{ ($settings['backup_schedule_day_of_week'] ?? '0') === '3' ? 'selected' : '' }}>Wednesday</option>
                                        <option value="4" {{ ($settings['backup_schedule_day_of_week'] ?? '0') === '4' ? 'selected' : '' }}>Thursday</option>
                                        <option value="5" {{ ($settings['backup_schedule_day_of_week'] ?? '0') === '5' ? 'selected' : '' }}>Friday</option>
                                        <option value="6" {{ ($settings['backup_schedule_day_of_week'] ?? '0') === '6' ? 'selected' : '' }}>Saturday</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row" id="monthlyOption">
                                <div class="form-group col-md-12">
                                    <label for="backup_schedule_day_of_month">Day of Month (1-28)</label>
                                    <input type="number" min="1" max="28" class="form-control" id="backup_schedule_day_of_month" name="backup_schedule_day_of_month" value="{{ $settings['backup_schedule_day_of_month'] ?? '1' }}">
                                </div>
                            </div>

                            <hr>

                            <div class="custom-control custom-switch mb-3">
                                <input type="checkbox" class="custom-control-input" id="backup_mail_enabled" name="backup_mail_enabled" value="1" {{ ($settings['backup_mail_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="backup_mail_enabled">Send backup to email</label>
                            </div>

                            <div class="form-group">
                                <label for="backup_mail_to">Recipient Email(s)</label>
                                <input type="text" class="form-control" id="backup_mail_to" name="backup_mail_to" value="{{ $settings['backup_mail_to'] ?? '' }}" placeholder="admin@example.com, owner@example.com">
                                <small class="text-muted">Use comma separated emails.</small>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox" class="custom-control-input" id="backup_include_files_setting" name="backup_include_files" value="1" {{ ($settings['backup_include_files'] ?? '1') === '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="backup_include_files_setting">Scheduled include files</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="custom-control custom-checkbox mt-2">
                                        <input type="checkbox" class="custom-control-input" id="backup_include_database_setting" name="backup_include_database" value="1" {{ ($settings['backup_include_database'] ?? '1') === '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="backup_include_database_setting">Scheduled include database</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="backup_file_prefix_setting">Scheduled File Prefix</label>
                                <input type="text" class="form-control" id="backup_file_prefix_setting" name="backup_file_prefix" value="{{ $settings['backup_file_prefix'] ?? 'backup' }}">
                            </div>

                            <div class="form-group">
                                <label for="backup_exclude_paths_setting">Scheduled Exclude Paths</label>
                                <input type="text" class="form-control" id="backup_exclude_paths_setting" name="backup_exclude_paths" value="{{ $settings['backup_exclude_paths'] ?? '' }}" placeholder="example: storage/app/temp">
                            </div>
                            @can('backups_settings')
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-save mr-1"></i> Save Backup Settings
                                </button>
                            @endcan
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-archive mr-2"></i> Backup Files
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th>File Name</th>
                                        <th style="width: 140px;">Size</th>
                                        <th style="width: 190px;">Created At</th>
                                        <th style="width: 220px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($files as $index => $file)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $file['name'] }}</td>
                                            <td>{{ $file['size'] }}</td>
                                            <td>{{ $file['created_at'] }}</td>
                                            <td>
                                                @can('backups_download')
                                                    <a href="{{ route('admin.backups.download', $file['name']) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-download mr-1"></i> Download
                                                    </a>
                                                @endcan
                                                @can('backups_delete')
                                                    <form action="{{ route('admin.backups.delete', $file['name']) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this backup file?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash mr-1"></i> Delete
                                                        </button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No backup file found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="backupManualModal" tabindex="-1" role="dialog" aria-labelledby="backupManualModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="backupManualModalLabel">
                    <i class="fas fa-book mr-2"></i>Backup System User Manual
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6 class="font-weight-bold mb-2">1) Manual Backup তৈরি</h6>
                <p class="mb-2">Create Custom Backup সেকশন থেকে include options সিলেক্ট করে Create Backup Now চাপুন।</p>
                <ul class="mb-3">
                    <li>Include project files: project files backup করবে</li>
                    <li>Include database dump: database SQL dump যুক্ত করবে</li>
                    <li>File Prefix: backup file name prefix সেট করবে</li>
                    <li>Exclude Paths: comma দিয়ে path দিয়ে বাদ দিতে পারবেন</li>
                </ul>

                <h6 class="font-weight-bold mb-2">2) Automatic Schedule সেটআপ</h6>
                <p class="mb-2">Schedule and Email Settings থেকে schedule enable করে frequency/time নির্বাচন করুন।</p>
                <ul class="mb-3">
                    <li>Daily: প্রতিদিন নির্দিষ্ট সময়ে backup</li>
                    <li>Weekly: নির্দিষ্ট weekday + time</li>
                    <li>Monthly: নির্দিষ্ট day of month + time</li>
                </ul>

                <h6 class="font-weight-bold mb-2">3) Backup Email Delivery</h6>
                <p class="mb-2">Send backup to email enable করে recipient emails দিন (comma separated)।</p>
                <ul class="mb-3">
                    <li>উদাহরণ: admin@example.com, owner@example.com</li>
                    <li>backup সফল হলে ZIP attachment সহ email যাবে</li>
                </ul>

                <h6 class="font-weight-bold mb-2">4) Backup Files Management</h6>
                <p class="mb-2">Backup Files টেবিল থেকে backup download অথবা delete করতে পারবেন।</p>

                <h6 class="font-weight-bold mb-2">5) Scheduler Server Requirement</h6>
                <p class="mb-0">Automatic backup চালাতে server cron বা task scheduler-এ Laravel scheduler চালু থাকতে হবে, নাহলে শুধু manual backup কাজ করবে।</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(function () {
        function toggleScheduleFields() {
            var frequency = $('#backup_schedule_frequency').val();
            $('#weeklyOption').toggle(frequency === 'weekly');
            $('#monthlyOption').toggle(frequency === 'monthly');
        }

        $('#backup_schedule_frequency').on('change', toggleScheduleFields);
        toggleScheduleFields();
    });
</script>
@endsection
