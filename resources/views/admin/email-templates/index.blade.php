@extends('admin.layouts.master')

@section('page-title', 'ইমেইল টেমপ্লেট')

@section('filter_input')
<div class="row px-3">
    <div class="col-md-3 mb-3">
        <input type="text" id="search-input" class="form-control" placeholder="টেমপ্লেট খুঁজুন..." autocomplete="off">
    </div>
    <div class="col-md-2 mb-3">
        <select id="type-filter" class="form-control">
            <option value="">সব টাইপ</option>
            @foreach($types as $type)
                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 mb-3">
        <select id="status-filter" class="form-control">
            <option value="">সব স্ট্যাটাস</option>
            <option value="1">সক্রিয়</option>
            <option value="0">নিষ্ক্রিয়</option>
        </select>
    </div>
    <div class="col-md-2 mb-3">
        <select id="per-page-filter" class="form-control">
            <option value="10">১০টি দেখান</option>
            <option value="20" selected>২০টি দেখান</option>
            <option value="30">৩০টি দেখান</option>
            <option value="50">৫০টি দেখান</option>
            <option value="100">১০০টি দেখান</option>
            <option value="all">সব দেখান</option>
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <div class="btn-group w-100">
            <button id="reset-filter" class="btn btn-secondary">
                <i class="fas fa-sync-alt"></i> রিসেট
            </button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTemplateModal">
                <i class="fas fa-plus"></i> নতুন টেমপ্লেট
            </button>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-envelope mr-1"></i> ইমেইল টেমপ্লেট তালিকা
            </h3>
        </div>

        <div class="card-body p-0">
            <div id="loading-spinner" class="text-center py-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">ডাটা লোড হচ্ছে...</p>
            </div>

            <div id="templates-table-container">
                @include('admin.email-templates.partials.table', ['templates' => $templates])
            </div>
        </div>
    </div>
</div>

<!-- Add Template Modal -->
<div class="modal fade" id="addTemplateModal" tabindex="-1" aria-labelledby="addTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addTemplateModalLabel">
                    <i class="fas fa-plus-circle mr-1"></i> নতুন ইমেইল টেমপ্লেট
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addTemplateForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>টেমপ্লেট নাম <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="যেমন: order_confirmation">
                                <small class="text-muted">ইউনিক এবং স্পেস ছাড়া লিখুন ( snake_case ব্যবহার করুন)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>টাইপ</label>
                                <select name="type" class="form-control">
                                    <option value="general">সাধারণ</option>
                                    <option value="order">অর্ডার</option>
                                    <option value="customer">গ্রাহক</option>
                                    <option value="security">সিকিউরিটি</option>
                                    <option value="marketing">মার্কেটিং</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label>বিষয়</label>
                        <input type="text" name="subject" class="form-control" placeholder="ইমেইলের বিষয়">
                    </div>
                    <div class="form-group mb-3">
                        <label>বডি <span class="text-danger">*</span></label>
                        <textarea name="body" class="form-control" rows="10" placeholder="ইমেইলের কন্টেন্ট লিখুন..."></textarea>
                        <div class="mt-2">
                            <small class="text-muted">
                                <strong>প্লেসহোল্ডার সমূহ:</strong>
                                <code>{&#123;customer_name&#125;}</code>,
                                <code>{&#123;order_number&#125;}</code>,
                                <code>{&#123;email&#125;}</code>,
                                <code>{&#123;phone&#125;}</code>,
                                <code>{&#123;message&#125;}</code>,
                                <code>{&#123;year&#125;}</code>,
                                <code>{&#123;company_name&#125;}</code>
                            </small>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" checked>
                            <label class="form-check-label" for="is_active">সক্রিয়</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Template Modal -->
<div class="modal fade" id="editTemplateModal" tabindex="-1" aria-labelledby="editTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editTemplateModalLabel">
                    <i class="fas fa-edit mr-1"></i> ইমেইল টেমপ্লেট সম্পাদনা
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTemplateForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>টেমপ্লেট নাম <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>টাইপ</label>
                                <select name="type" id="edit_type" class="form-control">
                                    <option value="general">সাধারণ</option>
                                    <option value="order">অর্ডার</option>
                                    <option value="customer">গ্রাহক</option>
                                    <option value="security">সিকিউরিটি</option>
                                    <option value="marketing">মার্কেটিং</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label>বিষয়</label>
                        <input type="text" name="subject" id="edit_subject" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label>বডি <span class="text-danger">*</span></label>
                        <textarea name="body" id="edit_body" class="form-control" rows="10"></textarea>
                        <div class="mt-2">
                            <small class="text-muted">
                                <strong>প্লেসহোল্ডার সমূহ:</strong>
                                <code>{&#123;customer_name&#125;}</code>,
                                <code>{&#123;order_number&#125;}</code>,
                                <code>{&#123;email&#125;}</code>,
                                <code>{&#123;phone&#125;}</code>,
                                <code>{&#123;message&#125;}</code>,
                                <code>{&#123;year&#125;}</code>,
                                <code>{&#123;company_name&#125;}</code>
                            </small>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="edit_is_active">
                            <label class="form-check-label" for="edit_is_active">সক্রিয়</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary">আপডেট করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')



<script>
$(document).ready(function() {
    let searchTimeout;
    let isAjaxLoading = false;
    let addEditor = null;
    let editEditor = null;

    // Initialize Add Editor
    function initAddEditor() {
        if (addEditor) {
            addEditor.destroy();
        }

        ClassicEditor
            .create(document.querySelector('#addTemplateForm textarea[name="body"]'), {
                toolbar: ['heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', 'link', 'undo', 'redo'],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
                    ]
                }
            })
            .then(editor => {
                addEditor = editor;
            })
            .catch(error => {
                console.error('CKEditor error:', error);
                toastr.warning('টেক্সট এডিটর লোড হয়নি, সাধারণ টেক্সট এরিয়া ব্যবহার করুন');
            });
    }

    // Initialize Edit Editor
    function initEditEditor() {
        if (editEditor) {
            editEditor.destroy();
        }

        ClassicEditor
            .create(document.querySelector('#editTemplateForm textarea[name="body"]'), {
                toolbar: ['heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', 'link', 'undo', 'redo'],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
                    ]
                }
            })
            .then(editor => {
                editEditor = editor;
            })
            .catch(error => {
                console.error('CKEditor error:', error);
                toastr.warning('টেক্সট এডিটর লোড হয়নি, সাধারণ টেক্সট এরিয়া ব্যবহার করুন');
            });
    }

    // Load templates via AJAX
    function loadTemplates(page = 1) {
        if (isAjaxLoading) return;

        var search = $('#search-input').val();
        var type = $('#type-filter').val();
        var status = $('#status-filter').val();
        var perPage = $('#per-page-filter').val();

        isAjaxLoading = true;
        $('#loading-spinner').show();
        $('#templates-table-container').hide();

        $.ajax({
            url: "{{ route('admin.email-templates.index') }}",
            type: "GET",
            data: {
                search: search,
                type: type,
                status: status,
                per_page: perPage,
                page: page,
                _: Date.now()
            },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.html) {
                    $('#templates-table-container').html(response.html);
                    attachEventHandlers();
                } else {
                    $('#templates-table-container').html('<div class="alert alert-warning m-3">কোন টেমপ্লেট পাওয়া যায়নি</div>');
                }
                $('#loading-spinner').hide();
                $('#templates-table-container').show();
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr);
                toastr.error('ডাটা লোড করতে ব্যর্থ হয়েছে');
                $('#loading-spinner').hide();
                $('#templates-table-container').show();
            },
            complete: function() {
                isAjaxLoading = false;
            }
        });
    }

    // Attach event handlers for dynamic elements
    function attachEventHandlers() {
        // Toggle status
        $('.toggle-status').off('click').on('click', function() {
            var id = $(this).data('id');
            var btn = $(this);

            Swal.fire({
                title: 'স্ট্যাটাস পরিবর্তন?',
                text: 'আপনি কি টেমপ্লেটের স্ট্যাটাস পরিবর্তন করতে চান?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'হ্যাঁ, পরিবর্তন করুন',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("admin/email-templates") }}/' + id + '/toggle-status',
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                loadTemplates(); // Reload table
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('স্ট্যাটাস পরিবর্তন করতে ব্যর্থ হয়েছে');
                        }
                    });
                }
            });
        });

        // Delete template
        $('.delete-template').off('click').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            Swal.fire({
                title: 'টেমপ্লেট মুছুন?',
                html: `<strong>${name}</strong> টেমপ্লেটটি মুছে ফেলতে যাচ্ছেন!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'হ্যাঁ, মুছুন',
                cancelButtonText: 'বাতিল'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("admin/email-templates") }}/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                loadTemplates(); // Reload table
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('মুছে ফেলতে ব্যর্থ হয়েছে');
                        }
                    });
                }
            });
        });

        // Edit template
        $('.edit-template').off('click').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var subject = $(this).data('subject') || '';
            var body = $(this).data('body') || '';
            var type = $(this).data('type') || 'general';
            var isActive = $(this).data('is_active');

            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_subject').val(subject);
            $('#edit_type').val(type);
            $('#edit_is_active').prop('checked', isActive == 1);

            if (editEditor) {
                editEditor.setData(body);
            } else {
                $('#edit_body').val(body);
            }

            $('#editTemplateModal').modal('show');
        });

        // Preview template
        $('.preview-template').off('click').on('click', function() {
            var id = $(this).data('id');
            var url = '{{ url("admin/email-templates") }}/' + id + '/preview';
            window.open(url, '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes');
        });

        // Pagination
        $(document).off('click', '.pagination a').on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').match(/page=(\d+)/) ? $(this).attr('href').match(/page=(\d+)/)[1] : 1;
            if (page) loadTemplates(page);
        });
    }

    // Add template form submit
    $('#addTemplateForm').on('submit', function(e) {
        e.preventDefault();

        var name = $('input[name="name"]').val();
        var body = addEditor ? addEditor.getData() : $('textarea[name="body"]').val();

        if (!name) {
            toastr.error('টেমপ্লেট নাম দিন');
            return false;
        }
        if (!body || body.trim() === '') {
            toastr.error('বডি কন্টেন্ট দিন');
            return false;
        }

        var formData = $(this).serialize();
        if (addEditor) {
            // Replace body with editor content
            formData = formData.replace(/body=[^&]*/, 'body=' + encodeURIComponent(body));
        }

        $.ajax({
            url: '{{ route("admin.email-templates.store") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#addTemplateModal').modal('hide');
                    $('#addTemplateForm')[0].reset();
                    if (addEditor) {
                        addEditor.setData('');
                    }
                    loadTemplates();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    }
                } else {
                    toastr.error('টেমপ্লেট তৈরি করতে ব্যর্থ হয়েছে');
                }
            }
        });
    });

    // Edit template form submit
    $('#editTemplateForm').on('submit', function(e) {
        e.preventDefault();

        var id = $('#edit_id').val();
        var body = editEditor ? editEditor.getData() : $('#edit_body').val();

        var formData = $(this).serialize();
        if (editEditor) {
            formData = formData.replace(/body=[^&]*/, 'body=' + encodeURIComponent(body));
        }

        $.ajax({
            url: '{{ url("admin/email-templates") }}/' + id,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#editTemplateModal').modal('hide');
                    loadTemplates();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    }
                } else {
                    toastr.error('টেমপ্লেট আপডেট করতে ব্যর্থ হয়েছে');
                }
            }
        });
    });

    // Modal events
    $('#addTemplateModal').on('shown.bs.modal', function() {
        initAddEditor();
    });

    $('#editTemplateModal').on('shown.bs.modal', function() {
        initEditEditor();
    });

    // Filter events
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadTemplates(), 500);
    });

    $('#type-filter, #status-filter, #per-page-filter').on('change', function() {
        loadTemplates();
    });

    $('#reset-filter').on('click', function() {
        $('#search-input').val('');
        $('#type-filter').val('');
        $('#status-filter').val('');
        $('#per-page-filter').val('20');
        loadTemplates();
    });

    // Initial attachment
    attachEventHandlers();
});
</script>
@endpush
