@extends('admin.layouts.main')

@section('page-title', 'Email Templates')

@section('filters')
    <form id="filter-form" method="GET" class="filter-form-wrapper">
        @php
            $filterCols = [
                'search'    => 'col-12 col-sm-6 col-md-3',
                'per_page'  => 'col-12 col-sm-6 col-md-2',
                'actions'   => 'col-12 col-sm-6 col-md-2',
            ];
        @endphp
        <x-filters
            :filters="['search' => true, 'per_page' => true , 'actions' => true]"
            :cols="$filterCols"
            :countCol="0"
        />
    </form>
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Email Template Settings</h3>
                        <div class="card-tools">
                            @can('email-templates_create')
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addTemplateModal">
                                    <i class="fa fa-plus"></i> Add New Template
                                </button>
                            @endcan

                        </div>
                    </div>
                    <div class="card-body">
                         <div class="table-responsive">
                             <table id="data_table" class="table table-bordered table-striped nowrap table-sm" style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Subject</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($templates as $template)
                                        <tr class="text-center">
                                            <td>{{ $template->id }}</td>
                                            <td>{{ $template->name }}</td>
                                            <td>{{ $template->subject }}</td>
                                            <td>
                                                <div class="btn-group">
                                                @can('email-templates_edit')
                                                    <button class="btn btn-warning btn-sm edit-template-btn" data-id="{{ $template->id }}" data-name="{{ $template->name }}" data-subject="{{ $template->subject }}" data-body="{{ e($template->body) }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @endcan
                                                @can('email-templates_destroy')
                                                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $template->id }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        @if ($templates instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $templates->links('pagination::bootstrap-5') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="addTemplateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Email Template</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="addTemplateForm">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Subject</label>
                            <input type="text" class="form-control" name="subject">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Body</label>
                        <textarea class="form-control" name="body" rows="10" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editTemplateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Email Template</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="editTemplateForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Subject</label>
                            <input type="text" class="form-control" name="subject">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Body</label>
                        <textarea class="form-control" name="body" rows="10" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent

@include('admin.component.script.data_table')
@include('admin.component.script.delete')

<script>
$(function () {
    $('#addTemplateForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: '{{ route("admin.email-templates.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                if (response.status === 'success') {
                    $('#addTemplateModal').modal('hide');
                    toastr.success(response.message, '', { timeOut: 2000 });
                    setTimeout(function () {
                        window.location.reload();
                    }, 300);
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                if (errors) {
                    $.each(errors, function (key, value) {
                        toastr.error(value[0], '', { timeOut: 2000 });
                    });
                } else {
                    toastr.error('Failed to create template', '', { timeOut: 2000 });
                }
            }
        });
    });
});
</script>

<script>
$(function () {
    $(document).on('click', '.edit-template-btn', function () {
        $('#editTemplateForm input[name="id"]').val($(this).data('id'));
        $('#editTemplateForm input[name="name"]').val($(this).data('name'));
        $('#editTemplateForm input[name="subject"]').val($(this).data('subject'));
        $('#editTemplateForm textarea[name="body"]').val($(this).data('body'));
        $('#editTemplateModal').modal('show');
    });
});
</script>

<script>
$(function () {
    $('#editTemplateForm').on('submit', function (e) {
        e.preventDefault();
        let id = $('input[name="id"]').val();

        $.ajax({
            url: '/admin/email-templates/' + id,
            method: 'POST',
            data: $(this).serialize() + '&_method=PUT',
            success: function (response) {
                if (response.status === 'success') {
                    $('#editTemplateModal').modal('hide');
                    toastr.success(response.message, '', { timeOut: 2000 });
                    setTimeout(function () {
                        window.location.reload();
                    }, 300);
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                if (errors) {
                    $.each(errors, function (key, value) {
                        toastr.error(value[0], '', { timeOut: 2000 });
                    });
                } else {
                    toastr.error('Failed to update template', '', { timeOut: 2000 });
                }
            }
        });
    });
});
</script>

<script>
$(function () {
    initializeDataTable('#data_table', { responsive: false });

    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();
        deleteData($(this).data('id'), '{{ url("admin/email-templates/") }}');
    });


});
</script>




@endsection
