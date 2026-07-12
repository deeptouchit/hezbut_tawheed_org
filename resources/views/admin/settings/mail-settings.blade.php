@extends('admin.layouts.main')

@section('page-title', 'Mail Settings')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Mail Settings Management</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 pl-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.mail.update') }}" method="POST">
                            @csrf

                            <div class="card card-outline card-primary mb-3">
                                <div class="card-header">
                                    <h3 class="card-title">Primary Mailbox</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_MAILER">MAIL_MAILER</label>
                                            <select name="MAIL_MAILER" id="MAIL_MAILER" class="form-control" required>
                                                @php $mailer = old('MAIL_MAILER', env('MAIL_MAILER', 'failover')); @endphp
                                                <option value="smtp" {{ $mailer === 'smtp' ? 'selected' : '' }}>smtp</option>
                                                <option value="failover" {{ $mailer === 'failover' ? 'selected' : '' }}>failover</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_HOST">MAIL_HOST</label>
                                            <input type="text" name="MAIL_HOST" id="MAIL_HOST" class="form-control" value="{{ old('MAIL_HOST', env('MAIL_HOST')) }}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_PORT">MAIL_PORT</label>
                                            <input type="number" name="MAIL_PORT" id="MAIL_PORT" class="form-control" value="{{ old('MAIL_PORT', env('MAIL_PORT', 465)) }}" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="MAIL_USERNAME">MAIL_USERNAME</label>
                                            <input type="email" name="MAIL_USERNAME" id="MAIL_USERNAME" class="form-control" value="{{ old('MAIL_USERNAME', env('MAIL_USERNAME')) }}" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="MAIL_PASSWORD">MAIL_PASSWORD</label>
                                            <input type="text" name="MAIL_PASSWORD" id="MAIL_PASSWORD" class="form-control" value="{{ old('MAIL_PASSWORD', env('MAIL_PASSWORD')) }}" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_ENCRYPTION">MAIL_ENCRYPTION</label>
                                            <input type="text" name="MAIL_ENCRYPTION" id="MAIL_ENCRYPTION" class="form-control" value="{{ old('MAIL_ENCRYPTION', env('MAIL_ENCRYPTION', 'ssl')) }}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_FROM_ADDRESS">MAIL_FROM_ADDRESS</label>
                                            <input type="email" name="MAIL_FROM_ADDRESS" id="MAIL_FROM_ADDRESS" class="form-control" value="{{ old('MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')) }}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_FROM_NAME">MAIL_FROM_NAME</label>
                                            <input type="text" name="MAIL_FROM_NAME" id="MAIL_FROM_NAME" class="form-control" value="{{ old('MAIL_FROM_NAME', env('MAIL_FROM_NAME', env('APP_NAME'))) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-outline card-info mb-3">
                                <div class="card-header">
                                    <h3 class="card-title">Support Mailbox</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_SUPPORT_HOST">MAIL_SUPPORT_HOST</label>
                                            <input type="text" name="MAIL_SUPPORT_HOST" id="MAIL_SUPPORT_HOST" class="form-control" value="{{ old('MAIL_SUPPORT_HOST', env('MAIL_SUPPORT_HOST', env('MAIL_HOST'))) }}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_SUPPORT_PORT">MAIL_SUPPORT_PORT</label>
                                            <input type="number" name="MAIL_SUPPORT_PORT" id="MAIL_SUPPORT_PORT" class="form-control" value="{{ old('MAIL_SUPPORT_PORT', env('MAIL_SUPPORT_PORT', env('MAIL_PORT', 465))) }}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_SUPPORT_ENCRYPTION">MAIL_SUPPORT_ENCRYPTION</label>
                                            <input type="text" name="MAIL_SUPPORT_ENCRYPTION" id="MAIL_SUPPORT_ENCRYPTION" class="form-control" value="{{ old('MAIL_SUPPORT_ENCRYPTION', env('MAIL_SUPPORT_ENCRYPTION', env('MAIL_ENCRYPTION', 'ssl'))) }}" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_SUPPORT_USERNAME">MAIL_SUPPORT_USERNAME</label>
                                            <input type="email" name="MAIL_SUPPORT_USERNAME" id="MAIL_SUPPORT_USERNAME" class="form-control" value="{{ old('MAIL_SUPPORT_USERNAME', env('MAIL_SUPPORT_USERNAME')) }}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_SUPPORT_PASSWORD">MAIL_SUPPORT_PASSWORD</label>
                                            <input type="text" name="MAIL_SUPPORT_PASSWORD" id="MAIL_SUPPORT_PASSWORD" class="form-control" value="{{ old('MAIL_SUPPORT_PASSWORD', env('MAIL_SUPPORT_PASSWORD')) }}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_SUPPORT_FROM_ADDRESS">MAIL_SUPPORT_FROM_ADDRESS</label>
                                            <input type="email" name="MAIL_SUPPORT_FROM_ADDRESS" id="MAIL_SUPPORT_FROM_ADDRESS" class="form-control" value="{{ old('MAIL_SUPPORT_FROM_ADDRESS', env('MAIL_SUPPORT_FROM_ADDRESS')) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-outline card-warning mb-3">
                                <div class="card-header">
                                    <h3 class="card-title">Account Mailbox</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_ACCOUNT_HOST">MAIL_ACCOUNT_HOST</label>
                                            <input type="text" name="MAIL_ACCOUNT_HOST" id="MAIL_ACCOUNT_HOST" class="form-control" value="{{ old('MAIL_ACCOUNT_HOST', env('MAIL_ACCOUNT_HOST', env('MAIL_HOST'))) }}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_ACCOUNT_PORT">MAIL_ACCOUNT_PORT</label>
                                            <input type="number" name="MAIL_ACCOUNT_PORT" id="MAIL_ACCOUNT_PORT" class="form-control" value="{{ old('MAIL_ACCOUNT_PORT', env('MAIL_ACCOUNT_PORT', env('MAIL_PORT', 465))) }}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_ACCOUNT_ENCRYPTION">MAIL_ACCOUNT_ENCRYPTION</label>
                                            <input type="text" name="MAIL_ACCOUNT_ENCRYPTION" id="MAIL_ACCOUNT_ENCRYPTION" class="form-control" value="{{ old('MAIL_ACCOUNT_ENCRYPTION', env('MAIL_ACCOUNT_ENCRYPTION', env('MAIL_ENCRYPTION', 'ssl'))) }}" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_ACCOUNT_USERNAME">MAIL_ACCOUNT_USERNAME</label>
                                            <input type="email" name="MAIL_ACCOUNT_USERNAME" id="MAIL_ACCOUNT_USERNAME" class="form-control" value="{{ old('MAIL_ACCOUNT_USERNAME', env('MAIL_ACCOUNT_USERNAME')) }}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_ACCOUNT_PASSWORD">MAIL_ACCOUNT_PASSWORD</label>
                                            <input type="text" name="MAIL_ACCOUNT_PASSWORD" id="MAIL_ACCOUNT_PASSWORD" class="form-control" value="{{ old('MAIL_ACCOUNT_PASSWORD', env('MAIL_ACCOUNT_PASSWORD')) }}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="MAIL_ACCOUNT_FROM_ADDRESS">MAIL_ACCOUNT_FROM_ADDRESS</label>
                                            <input type="email" name="MAIL_ACCOUNT_FROM_ADDRESS" id="MAIL_ACCOUNT_FROM_ADDRESS" class="form-control" value="{{ old('MAIL_ACCOUNT_FROM_ADDRESS', env('MAIL_ACCOUNT_FROM_ADDRESS')) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                @can('mail_update')
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> Save Mail Settings
                                    </button>
                                @endcan
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')

@endsection
