@extends('theme::layouts.app')

@section('title', 'পেজ পাওয়া যায়নি')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-page">
                <h1 class="display-1 text-muted">404</h1>
                <div class="error-content">
                    <h3><i class="fas fa-exclamation-triangle text-warning"></i> ওহ! পেজটি পাওয়া যায়নি।</h3>
                    <p class="mt-3">
                        আপনি যে পেজটি খুঁজছেন তা বিদ্যমান নেই বা সরানো হয়েছে।
                        <br>
                        অনুগ্রহ করে হোম পেজে ফিরে যান।
                    </p>
                    <div class="mt-4">
                        <a href="{{ url('/') }}" class="btn btn-success">
                            <i class="fas fa-home"></i> হোম পেজে ফিরুন
                        </a>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary ms-2">
                            <i class="fas fa-arrow-left"></i> পূর্বের পেজে ফিরুন
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.error-page {
    padding: 40px 0;
}
.error-page h1 {
    font-size: 120px;
    font-weight: 700;
    text-shadow: 4px 4px 0 #e9ecef;
}
.error-content h3 {
    font-size: 24px;
    margin-bottom: 20px;
}
</style>
@endpush