@extends('admin.layouts.master')

@section('page-title', 'সেটিংস ম্যানেজার')

@php
    use App\Helpers\SettingsHelper;
@endphp

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-sliders-h"></i> সেটিংস ম্যানেজার
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addSettingModal">
                    <i class="fas fa-plus"></i> নতুন সেটিংস
                </button>
                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-upload"></i> ইমপোর্ট
                </button>
                <button type="button" class="btn btn-warning btn-sm" id="exportBtn">
                    <i class="fas fa-download"></i> এক্সপোর্ট
                </button>
                <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn" style="display: none;">
                    <i class="fas fa-trash"></i> ডিলিট (<span id="selectedCount">0</span>)
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="nav flex-column nav-pills" id="settingsTab" role="tablist">
                        @foreach($groups as $group)
                            <button class="nav-link text-start {{ $loop->first ? 'active' : '' }}"
                                    id="{{ $group }}-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#{{ Str::slug($group) }}"
                                    type="button" role="tab">
                                <i class="fas {{ SettingsHelper::getGroupIcon($group) }} me-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $group)) }}
                                <span class="badge bg-primary float-end">{{ count($settings[$group] ?? []) }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="tab-content">
                        @foreach($groups as $group)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                 id="{{ Str::slug($group) }}" role="tabpanel">
                                <form action="{{ route('admin.settings.store') }}" method="POST"
                                      enctype="multipart/form-data" class="settings-form">
                                    @csrf

                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">
                                                <i class="fas {{ SettingsHelper::getGroupIcon($group) }} me-1"></i>
                                                {{ ucfirst(str_replace('_', ' ', $group)) }} সেটিংস
                                            </h4>
                                            <div class="card-tools">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-save"></i> সংরক্ষণ
                                                </button>
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            @forelse($settings[$group] ?? [] as $item)
                                                <div class="form-group mb-4 setting-item" data-id="{{ $item->id }}" data-sort-order="{{ $item->sort_order }}">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <input type="checkbox" class="setting-checkbox me-2" value="{{ $item->id }}">
                                                            <label class="fw-bold mb-0">
                                                                {{ $item->label }}
                                                                @if($item->is_encrypted ?? false)
                                                                    <i class="fas fa-lock text-warning" title="এনক্রিপ্টেড"></i>
                                                                @endif
                                                            </label>
                                                        </div>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-sm btn-info edit-setting-btn"
                                                                    data-id="{{ $item->id }}"
                                                                    data-key="{{ $item->key }}"
                                                                    data-label="{{ $item->label }}"
                                                                    data-type="{{ $item->type }}"
                                                                    data-group="{{ $item->group }}"
                                                                    data-options="{{ $item->options }}"
                                                                    data-placeholder="{{ $item->placeholder }}"
                                                                    data-help_text="{{ $item->help_text }}"
                                                                    data-sort_order="{{ $item->sort_order }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-danger delete-setting-btn"
                                                                    data-id="{{ $item->id }}"
                                                                    data-key="{{ $item->key }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    @php
                                                        $oldValue = old($item->key, $item->value);
                                                    @endphp

                                                    {{-- Input field based on type --}}
                                                    @if($item->type == 'textarea')
                                                        <textarea name="{{ $item->key }}"
                                                                  class="form-control @error($item->key) is-invalid @enderror"
                                                                  rows="3"
                                                                  placeholder="{{ $item->placeholder ?? '' }}">{{ $oldValue }}</textarea>
                                                    @elseif($item->type == 'number')
                                                        <input type="number"
                                                               name="{{ $item->key }}"
                                                               class="form-control @error($item->key) is-invalid @enderror"
                                                               value="{{ $oldValue }}"
                                                               placeholder="{{ $item->placeholder ?? '' }}">
                                                    @elseif($item->type == 'email')
                                                        <input type="email"
                                                               name="{{ $item->key }}"
                                                               class="form-control @error($item->key) is-invalid @enderror"
                                                               value="{{ $oldValue }}"
                                                               placeholder="{{ $item->placeholder ?? '' }}">
                                                    @elseif($item->type == 'url')
                                                        <input type="url"
                                                               name="{{ $item->key }}"
                                                               class="form-control @error($item->key) is-invalid @enderror"
                                                               value="{{ $oldValue }}"
                                                               placeholder="{{ $item->placeholder ?? '' }}">
                                                    @elseif($item->type == 'image')
                                                         @php
                                                             $imagePath = $item->value;
                                                             if ($imagePath && !str_starts_with($imagePath, 'uploads/settings/')) {
                                                                 $imagePath = 'uploads/settings/' . $imagePath;
                                                             }
                                                         @endphp
                                                         @if(!empty($imagePath))
                                                             <div class="mb-2">
                                                                 <img src="{{ asset($imagePath) }}"
                                                                      alt="{{ $item->label }}"
                                                                      style="max-height: 100px;"
                                                                      class="img-thumbnail">
                                                                 <div class="mt-1">
                                                                     <label class="checkbox-inline">
                                                                         <input type="checkbox" name="remove_{{ $item->key }}" value="1">
                                                                         <small class="text-danger">বর্তমান ইমেজ মুছুন</small>
                                                                     </label>
                                                                 </div>
                                                             </div>
                                                         @endif
                                                         <input type="file"
                                                                name="{{ $item->key }}"
                                                                class="form-control @error($item->key) is-invalid @enderror"
                                                                accept="image/*">
                                                    @elseif($item->type == 'file')
                                                         @php
                                                             $filePath = $item->value;
                                                             if ($filePath && !str_starts_with($filePath, 'uploads/settings/')) {
                                                                 $filePath = 'uploads/settings/' . $filePath;
                                                             }
                                                         @endphp
                                                         @if(!empty($filePath))
                                                             <div class="mb-2">
                                                                 <a href="{{ asset($filePath) }}" target="_blank" class="btn btn-sm btn-info">
                                                                     <i class="fas fa-download"></i> বর্তমান ফাইল ডাউনলোড
                                                                 </a>
                                                                 <label class="checkbox-inline ms-2">
                                                                     <input type="checkbox" name="remove_{{ $item->key }}" value="1">
                                                                     <small class="text-danger">মুছুন</small>
                                                                 </label>
                                                             </div>
                                                         @endif
                                                         <input type="file"
                                                                name="{{ $item->key }}"
                                                                class="form-control @error($item->key) is-invalid @enderror">
                                                    @elseif($item->type == 'color')
                                                        <input type="color"
                                                               name="{{ $item->key }}"
                                                               class="form-control @error($item->key) is-invalid @enderror"
                                                               value="{{ $oldValue ?: '#000000' }}"
                                                               style="height: 50px; padding: 5px;">
                                                    @elseif($item->type == 'select')
                                                        @php
                                                            $options = is_string($item->options) ? json_decode($item->options, true) : ($item->options ?? []);
                                                            $currentValue = old($item->key, $item->value);
                                                        @endphp
                                                        <select name="{{ $item->key }}" class="form-control @error($item->key) is-invalid @enderror">
                                                            <option value="">-- সিলেক্ট করুন --</option>
                                                            @foreach($options as $optValue => $optLabel)
                                                                <option value="{{ $optValue }}" {{ $currentValue == $optValue ? 'selected' : '' }}>
                                                                    {{ $optLabel }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @elseif($item->type == 'boolean')
                                                        <div class="form-check form-switch">
                                                            <input type="hidden" name="{{ $item->key }}" value="0">
                                                            <input type="checkbox"
                                                                   name="{{ $item->key }}"
                                                                   class="form-check-input @error($item->key) is-invalid @enderror"
                                                                   value="1"
                                                                   id="switch_{{ $item->key }}"
                                                                   {{ $oldValue ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="switch_{{ $item->key }}">
                                                                {{ $oldValue ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                                            </label>
                                                        </div>
                                                    @elseif($item->type == 'json')
                                                        <textarea name="{{ $item->key }}"
                                                                  class="form-control @error($item->key) is-invalid @enderror"
                                                                  rows="5"
                                                                  placeholder='{"key": "value"}'>{{ is_array($oldValue) ? json_encode($oldValue, JSON_PRETTY_PRINT) : $oldValue }}</textarea>
                                                        <small class="text-muted">ভ্যালিড JSON ফরম্যাটে লিখুন</small>
                                                    @else
                                                        <input type="text"
                                                               name="{{ $item->key }}"
                                                               class="form-control @error($item->key) is-invalid @enderror"
                                                               value="{{ $oldValue }}"
                                                               placeholder="{{ $item->placeholder ?? '' }}">
                                                    @endif

                                                    @error($item->key)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror

                                                    @if($item->help_text)
                                                        <small class="text-muted d-block mt-1">
                                                            <i class="fas fa-info-circle"></i> {{ $item->help_text }}
                                                        </small>
                                                    @endif

                                                    <div class="mt-1">
                                                        <code class="text-muted" style="font-size: 11px;">
                                                            <i class="fas fa-key"></i> কী: {{ $item->key }}
                                                        </code>
                                                        @if($item->type)
                                                            <code class="text-muted ms-2" style="font-size: 11px;">
                                                                <i class="fas fa-database"></i> টাইপ: {{ $item->type }}
                                                            </code>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center py-5">
                                                    <i class="fas fa-cog fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted">কোন সেটিংস পাওয়া যায়নি</p>
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSettingModal">
                                                        নতুন সেটিংস যোগ করুন
                                                    </button>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Setting Modal --}}
<div class="modal fade" id="addSettingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus-circle"></i> নতুন সেটিংস যোগ করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addSettingForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>সেটিংস কী <span class="text-danger">*</span></label>
                                <input type="text" name="key" class="form-control" required
                                       placeholder="company_name">
                                <small class="text-muted">শুধু ইংরেজি অক্ষর, সংখ্যা এবং আন্ডারস্কোর</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>লেবেল <span class="text-danger">*</span></label>
                                <input type="text" name="label" class="form-control" required
                                       placeholder="কোম্পানির নাম">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>টাইপ <span class="text-danger">*</span></label>
                                <select name="type" class="form-control" required id="settingTypeSelect">
                                    <option value="text">Text</option>
                                    <option value="textarea">Textarea</option>
                                    <option value="number">Number</option>
                                    <option value="email">Email</option>
                                    <option value="url">URL</option>
                                    <option value="image">Image</option>
                                    <option value="file">File</option>
                                    <option value="color">Color</option>
                                    <option value="select">Select</option>
                                    <option value="boolean">Boolean</option>
                                    <option value="json">JSON</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>গ্রুপ <span class="text-danger">*</span></label>
                                <select name="group" class="form-control" required id="groupSelect">
                                    @foreach($groups as $group)
                                        <option value="{{ $group }}">{{ ucfirst(str_replace('_', ' ', $group)) }}</option>
                                    @endforeach
                                    <option value="new">+ নতুন গ্রুপ যোগ করুন</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- New Group Input --}}
                    <div id="newGroupDiv" style="display: none;" class="mb-3">
                        <div class="form-group">
                            <label>নতুন গ্রুপের নাম <span class="text-danger">*</span></label>
                            <input type="text" name="new_group" id="newGroupInput" class="form-control" placeholder="যেমন: payment, api, notification">
                            <small class="text-muted">শুধু ইংরেজি ছোট হাতের অক্ষর, সংখ্যা এবং আন্ডারস্কোর</small>
                        </div>
                    </div>

                    <div class="form-group mb-3" id="optionsDiv" style="display: none;">
                        <label>অপশন (JSON ফরম্যাটে) <small class="text-muted">select টাইপের জন্য</small></label>
                        <textarea name="options" class="form-control" rows="3"
                                  placeholder='{"1":"Option 1","2":"Option 2"}'></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label>প্লেসহোল্ডার</label>
                        <input type="text" name="placeholder" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label>হেল্প টেক্সট</label>
                        <textarea name="help_text" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label>ভ্যালু (ঐচ্ছিক)</label>
                        <input type="text" name="value" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-primary" id="saveSettingBtn">
                    <i class="fas fa-save"></i> সংরক্ষণ করুন
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Setting Modal --}}
<div class="modal fade" id="editSettingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit"></i> সেটিংস এডিট করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editSettingForm">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>সেটিংস কী <span class="text-danger">*</span></label>
                                <input type="text" name="key" id="edit_key" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>লেবেল <span class="text-danger">*</span></label>
                                <input type="text" name="label" id="edit_label" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>টাইপ <span class="text-danger">*</span></label>
                                <select name="type" id="edit_type" class="form-control" required>
                                    <option value="text">Text</option>
                                    <option value="textarea">Textarea</option>
                                    <option value="number">Number</option>
                                    <option value="email">Email</option>
                                    <option value="url">URL</option>
                                    <option value="image">Image</option>
                                    <option value="file">File</option>
                                    <option value="color">Color</option>
                                    <option value="select">Select</option>
                                    <option value="boolean">Boolean</option>
                                    <option value="json">JSON</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>গ্রুপ <span class="text-danger">*</span></label>
                                <select name="group" id="edit_group" class="form-control" required>
                                    @foreach($groups as $group)
                                        <option value="{{ $group }}">{{ ucfirst(str_replace('_', ' ', $group)) }}</option>
                                    @endforeach
                                    <option value="new">+ নতুন গ্রুপ যোগ করুন</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Edit New Group Input --}}
                    <div id="editNewGroupDiv" style="display: none;" class="mb-3">
                        <div class="form-group">
                            <label>নতুন গ্রুপের নাম <span class="text-danger">*</span></label>
                            <input type="text" name="edit_new_group" id="editNewGroupInput" class="form-control" placeholder="যেমন: payment, api">
                            <small class="text-muted">শুধু ইংরেজি ছোট হাতের অক্ষর</small>
                        </div>
                    </div>

                    <div class="form-group mb-3" id="editOptionsDiv" style="display: none;">
                        <label>অপশন (JSON ফরম্যাটে)</label>
                        <textarea name="options" id="edit_options" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label>প্লেসহোল্ডার</label>
                        <input type="text" name="placeholder" id="edit_placeholder" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label>হেল্প টেক্সট</label>
                        <textarea name="help_text" id="edit_help_text" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label>সর্ট অর্ডার</label>
                        <input type="number" name="sort_order" id="edit_sort_order" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                <button type="button" class="btn btn-primary" id="updateSettingBtn">
                    <i class="fas fa-save"></i> আপডেট করুন
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-upload"></i> সেটিংস ইমপোর্ট করুন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.settings.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>JSON ফাইল <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".json" required>
                        <small class="text-muted">
                            <a href="#" id="downloadSampleBtn">স্যাম্পল ফাইল ডাউনলোড করুন</a>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> ইমপোর্ট
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>
$(document).ready(function() {
    // Active tab persistence
    const activeTabId = localStorage.getItem('active_settings_tab');
    if (activeTabId) {
        const $tabEl = $('#' + activeTabId);
        if ($tabEl.length) {
            // Trigger click to activate the saved tab
            $tabEl.trigger('click');
        }
    }

    // Save active tab on click
    $('#settingsTab button[data-bs-toggle="pill"]').on('click', function() {
        localStorage.setItem('active_settings_tab', this.id);
    });

    // Show/hide options field based on type (Add Modal)
    $('#settingTypeSelect').change(function() {
        if ($(this).val() === 'select') {
            $('#optionsDiv').show();
        } else {
            $('#optionsDiv').hide();
        }
    });

    // Show/hide options field based on type (Edit Modal)
    $('#edit_type').change(function() {
        if ($(this).val() === 'select') {
            $('#editOptionsDiv').show();
        } else {
            $('#editOptionsDiv').hide();
        }
    });

    // Show/hide new group input (Add Modal)
    $('#groupSelect').change(function() {
        if ($(this).val() === 'new') {
            $('#newGroupDiv').show();
            $('#newGroupInput').prop('required', true);
        } else {
            $('#newGroupDiv').hide();
            $('#newGroupInput').prop('required', false);
        }
    });

    // Show/hide new group input (Edit Modal)
    $('#edit_group').change(function() {
        if ($(this).val() === 'new') {
            $('#editNewGroupDiv').show();
            $('#editNewGroupInput').prop('required', true);
        } else {
            $('#editNewGroupDiv').hide();
            $('#editNewGroupInput').prop('required', false);
        }
    });

    // Save new setting - সম্পূর্ণ আপডেট
    $('#saveSettingBtn').click(function() {
        let groupValue = $('#groupSelect').val();

        // If new group selected, use the input value
        if (groupValue === 'new') {
            const newGroup = $('#newGroupInput').val().trim().toLowerCase().replace(/\s+/g, '_');
            if (!newGroup) {
                Swal.fire('ত্রুটি!', 'নতুন গ্রুপের নাম দিন', 'error');
                return;
            }
            // Replace with new group name
            $('input[name="group"]').remove();
            $('#addSettingForm').append('<input type="hidden" name="group" value="' + newGroup + '">');
        }

        // Show loading
        const saveBtn = $('#saveSettingBtn');
        saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> সংরক্ষণ হচ্ছে...');

        const formData = $('#addSettingForm').serialize();

        $.ajax({
            url: "{{ route('admin.settings.create') }}",
            type: "POST",
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('Success Response:', response); // Debugging

                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'সফল!',
                        text: response.message || 'সেটিংস তৈরি করা হয়েছে!',
                        timer: 2000,
                        showConfirmButton: true
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('ত্রুটি!', response.message || 'সেটিংস সংরক্ষণ করতে ব্যর্থ হয়েছে', 'error');
                    saveBtn.prop('disabled', false).html('<i class="fas fa-save"></i> সংরক্ষণ করুন');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error Response:', xhr); // Debugging
                console.log('Status:', status);
                console.log('Error:', error);

                saveBtn.prop('disabled', false).html('<i class="fas fa-save"></i> সংরক্ষণ করুন');

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';
                    $.each(errors, function(key, value) {
                        errorMsg += value[0] + '\n';
                    });
                    Swal.fire('ভ্যালিডেশন ত্রুটি!', errorMsg, 'error');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    Swal.fire('ত্রুটি!', xhr.responseJSON.message, 'error');
                } else {
                    Swal.fire('ত্রুটি!', 'সেটিংস সংরক্ষণ করতে ব্যর্থ হয়েছে। দয়া করে আবার চেষ্টা করুন।', 'error');
                }
            }
        });
    });

    // Edit setting
    $('.edit-setting-btn').click(function() {
        const id = $(this).data('id');
        const key = $(this).data('key');
        const label = $(this).data('label');
        const type = $(this).data('type');
        const group = $(this).data('group');
        const options = $(this).data('options');
        const placeholder = $(this).data('placeholder');
        const help_text = $(this).data('help_text');
        const sort_order = $(this).data('sort_order');

        $('#edit_id').val(id);
        $('#edit_key').val(key);
        $('#edit_label').val(label);
        $('#edit_type').val(type);
        $('#edit_group').val(group);
        $('#edit_placeholder').val(placeholder || '');
        $('#edit_help_text').val(help_text || '');
        $('#edit_sort_order').val(sort_order || 0);

        // Hide new group input by default
        $('#editNewGroupDiv').hide();
        $('#editNewGroupInput').prop('required', false);

        // Handle options
        if (options) {
            if (typeof options === 'object') {
                $('#edit_options').val(JSON.stringify(options, null, 2));
            } else if (typeof options === 'string') {
                try {
                    const parsed = JSON.parse(options);
                    $('#edit_options').val(JSON.stringify(parsed, null, 2));
                } catch (e) {
                    $('#edit_options').val(options);
                }
            } else {
                $('#edit_options').val('');
            }
        } else {
            $('#edit_options').val('');
        }

        // Show/hide options based on type
        if (type === 'select') {
            $('#editOptionsDiv').show();
        } else {
            $('#editOptionsDiv').hide();
        }

        $('#editSettingModal').modal('show');
    });

    // Update setting - সম্পূর্ণ আপডেট
    $('#updateSettingBtn').click(function() {
        const id = $('#edit_id').val();
        let groupValue = $('#edit_group').val();

        // If new group selected, use the input value
        if (groupValue === 'new') {
            const newGroup = $('#editNewGroupInput').val().trim().toLowerCase().replace(/\s+/g, '_');
            if (!newGroup) {
                Swal.fire('ত্রুটি!', 'নতুন গ্রুপের নাম দিন', 'error');
                return;
            }
            // Replace with new group name
            $('input[name="group"]').remove();
            $('#editSettingForm').append('<input type="hidden" name="group" value="' + newGroup + '">');
        }

        // Show loading
        const updateBtn = $('#updateSettingBtn');
        updateBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> আপডেট হচ্ছে...');

        const formData = $('#editSettingForm').serialize();

        $.ajax({
            url: "{{ url('admin/settings') }}/" + id,
            type: "POST",
            data: formData + '&_method=PUT',
            dataType: 'json',
            success: function(response) {
                console.log('Update Success:', response); // Debugging

                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'সফল!',
                        text: response.message || 'সেটিংস আপডেট করা হয়েছে!',
                        timer: 2000,
                        showConfirmButton: true
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('ত্রুটি!', response.message || 'সেটিংস আপডেট করতে ব্যর্থ হয়েছে', 'error');
                    updateBtn.prop('disabled', false).html('<i class="fas fa-save"></i> আপডেট করুন');
                }
            },
            error: function(xhr, status, error) {
                console.log('Update Error:', xhr); // Debugging
                console.log('Status:', status);
                console.log('Error:', error);

                updateBtn.prop('disabled', false).html('<i class="fas fa-save"></i> আপডেট করুন');

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';
                    $.each(errors, function(key, value) {
                        errorMsg += value[0] + '\n';
                    });
                    Swal.fire('ভ্যালিডেশন ত্রুটি!', errorMsg, 'error');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    Swal.fire('ত্রুটি!', xhr.responseJSON.message, 'error');
                } else {
                    Swal.fire('ত্রুটি!', 'সেটিংস আপডেট করতে ব্যর্থ হয়েছে। দয়া করে আবার চেষ্টা করুন।', 'error');
                }
            }
        });
    });

    // Delete setting
    $('.delete-setting-btn').click(function() {
        const id = $(this).data('id');
        const key = $(this).data('key');

        Swal.fire({
            title: 'আপনি কি নিশ্চিত?',
            text: `"${key}" সেটিংসটি মুছে ফেলতে চান?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'হ্যাঁ, মুছুন',
            cancelButtonText: 'বাতিল'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/settings') }}/" + id,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Delete Success:', response); // Debugging

                        if (response.success) {
                            Swal.fire('মুছে ফেলা হয়েছে!', response.message, 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('ত্রুটি!', response.message || 'সেটিংস মুছতে ব্যর্থ হয়েছে', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.log('Delete Error:', xhr); // Debugging
                        Swal.fire('ত্রুটি!', 'সেটিংস মুছতে ব্যর্থ হয়েছে। দয়া করে আবার চেষ্টা করুন।', 'error');
                    }
                });
            }
        });
    });

    // Bulk delete functionality
    let selectedIds = [];

    // Initialize checkbox events
    $(document).on('change', '.setting-checkbox', function() {
        const id = $(this).val();
        if ($(this).is(':checked')) {
            if (!selectedIds.includes(id)) {
                selectedIds.push(id);
            }
        } else {
            selectedIds = selectedIds.filter(item => item != id);
        }

        $('#selectedCount').text(selectedIds.length);
        if (selectedIds.length > 0) {
            $('#bulkDeleteBtn').show();
        } else {
            $('#bulkDeleteBtn').hide();
        }
    });

    $('#bulkDeleteBtn').click(function() {
        if (selectedIds.length === 0) return;

        Swal.fire({
            title: 'আপনি কি নিশ্চিত?',
            text: `${selectedIds.length} টি সেটিংস মুছে ফেলতে চান?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'হ্যাঁ, সব মুছুন',
            cancelButtonText: 'বাতিল'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.settings.bulk-delete') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: selectedIds
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Bulk Delete Success:', response); // Debugging

                        if (response.success) {
                            Swal.fire('মুছে ফেলা হয়েছে!', response.message, 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('ত্রুটি!', response.message || 'সেটিংস মুছতে ব্যর্থ হয়েছে', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.log('Bulk Delete Error:', xhr); // Debugging
                        Swal.fire('ত্রুটি!', 'সেটিংস মুছতে ব্যর্থ হয়েছে। দয়া করে আবার চেষ্টা করুন।', 'error');
                    }
                });
            }
        });
    });

    // Export settings
    $('#exportBtn').click(function() {
        window.location.href = "{{ route('admin.settings.export') }}";
    });

    // Download sample JSON
    $('#downloadSampleBtn').click(function(e) {
        e.preventDefault();

        const sample = [
            {
                key: "sample_key",
                value: "sample_value",
                label: "Sample Setting",
                type: "text",
                group: "general",
                placeholder: "Enter value",
                help_text: "This is a sample setting",
                sort_order: 0
            },
            {
                key: "sample_select",
                value: "1",
                label: "Sample Select",
                type: "select",
                group: "general",
                options: {"1":"Option 1","2":"Option 2","3":"Option 3"},
                placeholder: "Select option",
                help_text: "This is a sample select",
                sort_order: 1
            }
        ];

        const dataStr = JSON.stringify(sample, null, 2);
        const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);

        const exportFileDefaultName = 'settings_sample.json';

        const linkElement = document.createElement('a');
        linkElement.setAttribute('href', dataUri);
        linkElement.setAttribute('download', exportFileDefaultName);
        linkElement.click();
    });
});
</script>
@endpush
