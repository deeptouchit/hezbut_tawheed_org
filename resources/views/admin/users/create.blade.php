@extends('admin.layouts.master')

@section('page-title', 'নতুন ইউজার তৈরি')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-plus me-2"></i> নতুন ইউজার তৈরি
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.users.index') }}" class="btn btn-default btn-sm">
                    <i class="fas fa-arrow-left"></i> ফিরে যান
                </a>
            </div>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">নাম <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">ইমেইল <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">ফোন</label>
                            <input type="text"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}">
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">পাসওয়ার্ড <span class="text-danger">*</span></label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation">পাসওয়ার্ড নিশ্চিত করুন <span class="text-danger">*</span></label>
                            <input type="password"
                                   class="form-control"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role">রোল <span class="text-danger">*</span></label>
                            <select class="form-control @error('role') is-invalid @enderror"
                                    id="role"
                                    name="role"
                                    required>
                                <option value="">সিলেক্ট করুন</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">স্ট্যাটাস <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror"
                                    id="status"
                                    name="status"
                                    required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                                <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>সাসপেন্ডেড</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-check">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="verify_email"
                                   name="verify_email"
                                   value="1">
                            <label class="form-check-label" for="verify_email">
                                ইমেইল ভেরিফাইড হিসাবে মার্ক করুন
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> ইউজার তৈরি করুন
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-default">
                    <i class="fas fa-times"></i> বাতিল
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
