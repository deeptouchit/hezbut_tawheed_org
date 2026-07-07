@extends('theme::layouts.app')

@section('title', 'যোগাযোগ - হেজবুত তওহীদ')

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'যোগাযোগ করুন',
        'subtitle' => 'যেকোনো জিজ্ঞাসা, মতামত বা পরামর্শের জন্য আমাদের সাথে সরাসরি যোগাযোগ করুন',
        'badge_text' => 'যোগাযোগ ও সাপোর্ট',
        'badge_icon' => 'fas fa-headset'
    ])

    <!-- Contact Main Section -->
    <div class="py-5" style="background-color: #f8fafc; font-family: 'Baloo Da 2', sans-serif;">
        <div class="container">
            <div class="row g-4">
                
                <!-- Contact Information Column -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white h-100 border-light-grey">
                        <span class="fw-bold text-uppercase tracking-wider text-success-brand" style="font-size: 0.8rem; letter-spacing: 1px;">যোগাযোগের বিবরণ</span>
                        <h3 class="fw-bold mb-4 mt-1" style="color: #0f172a; font-size: 1.6rem;">আমাদের কার্যালয়</h3>
                        <p class="text-muted lh-lg mb-5" style="font-size: 0.95rem;">
                            হেজবুত তওহীদের আদর্শ প্রচার, সমাজ সংস্কার এবং আমাদের বিভিন্ন মানবিক কার্যক্রম সম্পর্কে যেকোনো তথ্য জানতে নিচে দেওয়া ঠিকানায় বা ফর্ম ব্যবহার করে যোগাযোগ করতে পারেন।
                        </p>

                        <!-- Address -->
                        <div class="d-flex align-items-start mb-4">
                            <div class="icon-box-brand rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                                <i class="fas fa-map-marker-alt fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.98rem;">কেন্দ্রীয় কার্যালয়</h6>
                                <p class="text-secondary mb-0" style="font-size: 0.9rem;">{{ $setting->getSetting('contact_address', 'ঢাকা, বাংলাদেশ') }}</p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="d-flex align-items-start mb-4">
                            <div class="icon-box-brand rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                                <i class="fas fa-phone-alt fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.98rem;">ফোন নম্বর</h6>
                                <p class="text-secondary mb-0" style="font-size: 0.9rem;">{{ $setting->getSetting('contact_phone', '+880 1234 567890') }}</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="d-flex align-items-start mb-4">
                            <div class="icon-box-brand rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                                <i class="fas fa-envelope fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.98rem;">ইমেইল ঠিকানা</h6>
                                <p class="text-secondary mb-0" style="font-size: 0.9rem;">{{ $setting->getSetting('contact_email', 'info@hezbuttawheed.org') }}</p>
                            </div>
                        </div>

                        <!-- Office Hours -->
                        <div class="d-flex align-items-start">
                            <div class="icon-box-brand rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                                <i class="far fa-clock fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.98rem;">কার্য্যালয়ের সময়</h6>
                                <p class="text-secondary mb-0" style="font-size: 0.9rem;">শনিবার - বৃহস্পতিবার: সকাল ৯টা থেকে বিকেল ৫টা</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form Column -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white h-100 border-light-grey">
                        <span class="fw-bold text-uppercase tracking-wider text-success-brand" style="font-size: 0.8rem; letter-spacing: 1px;">বার্তা পাঠান</span>
                        <h3 class="fw-bold mb-4 mt-1" style="color: #0f172a; font-size: 1.6rem;">আপনার মতামত লিখুন</h3>
                        
                        <form action="{{ route('contact.send') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <!-- Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label text-dark fw-semibold small">আপনার নাম *</label>
                                        <input type="text" name="name" id="name" class="form-control py-3 rounded-3 @error('name') is-invalid @enderror" placeholder="নাম লিখুন..." value="{{ old('name') }}" required style="font-size: 0.9rem; box-shadow: none;">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label text-dark fw-semibold small">আপনার ইমেল *</label>
                                        <input type="email" name="email" id="email" class="form-control py-3 rounded-3 @error('email') is-invalid @enderror" placeholder="ইমেল লিখুন..." value="{{ old('email') }}" required style="font-size: 0.9rem; box-shadow: none;">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label text-dark fw-semibold small">মোবাইল নম্বর *</label>
                                        <input type="text" name="phone" id="phone" class="form-control py-3 rounded-3 @error('phone') is-invalid @enderror" placeholder="মোবাইল নম্বর..." value="{{ old('phone') }}" required style="font-size: 0.9rem; box-shadow: none;">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Subject -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subject" class="form-label text-dark fw-semibold small">বার্তার বিষয় *</label>
                                        <input type="text" name="subject" id="subject" class="form-control py-3 rounded-3 @error('subject') is-invalid @enderror" placeholder="বিষয়..." value="{{ old('subject') }}" required style="font-size: 0.9rem; box-shadow: none;">
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Message -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="message" class="form-label text-dark fw-semibold small">বার্তা *</label>
                                        <textarea name="message" id="message" rows="6" class="form-control rounded-3 @error('message') is-invalid @enderror" placeholder="আপনার বার্তাটি বিস্তারিত লিখুন..." required style="font-size: 0.9rem; box-shadow: none;">{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 text-end mt-4">
                                    <button type="submit" class="btn btn-brand-success text-white fw-bold px-4 py-3 rounded shadow-sm transition">
                                        বার্তা পাঠান <i class="fas fa-paper-plane ms-2 text-warning" style="font-size: 13px;"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Branch-Based Contact Section -->
            <div class="row mt-5">
                <div class="col-12 text-center mb-4">
                    <h3 class="fw-bold text-dark" style="font-size: 1.6rem;"><i class="fas fa-map-marked-alt text-success-brand me-2"></i>শাখা ও জেলা ভিত্তিক যোগাযোগ</h3>
                    <p class="text-secondary small mb-4">সারাদেশে বিস্তৃত আমাদের শাখা কার্যালয়ের নাম, ঠিকানা ও প্রতিনিধিদের তালিকা</p>
                    
                    <!-- Branch Search Input -->
                    <div class="input-group border rounded-pill bg-white overflow-hidden p-1 shadow-sm mx-auto" style="max-width: 500px;">
                        <input type="text" id="branchSearch" class="form-control border-0 px-3" placeholder="শাখা কার্যালয় বা জেলার নাম লিখে খুঁজুন..." style="box-shadow: none; font-size: 0.9rem;">
                        <span class="btn btn-brand-success rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; padding: 0; pointer-events: none;"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>

            <!-- Branches Grid -->
            <div class="row g-4 mb-5" id="branchesContainer">
                @foreach($branches as $branch)
                    <div class="col-md-6 col-lg-4 branch-item" data-name="{{ $branch->name }}" data-address="{{ $branch->address }}">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white border-light-grey d-flex flex-column justify-content-between transition hover-grow-card">
                            <div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box-brand rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 42px; height: 42px; font-size: 0.95rem; flex-shrink: 0;">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1rem; line-height: 1.45;">{{ $branch->name }}</h5>
                                </div>
                                
                                <p class="text-secondary mb-2 lh-base" style="font-size: 0.85rem; text-align: justify;">
                                    <i class="fas fa-map-marker-alt text-success-brand me-2" style="width: 15px;"></i> <strong>ঠিকানা:</strong> {{ $branch->address }}
                                </p>
                                
                                @if($branch->phone)
                                    <p class="text-secondary mb-0" style="font-size: 0.85rem;">
                                        <i class="fas fa-phone-alt text-success-brand me-2" style="width: 15px;"></i> <strong>ফোন:</strong> {{ $branch->phone }}
                                    </p>
                                @endif
                            </div>
                            
                            @if($branch->google_map_url)
                                <div class="pt-3 border-top border-light mt-3">
                                    <button type="button" class="btn btn-sm btn-outline-success w-100 rounded-pill py-2 view-map-btn" data-url="{{ $branch->google_map_url }}" data-name="{{ $branch->name }}" style="font-size: 11.5px; font-weight: 600; transition: all 0.2s;">
                                        <i class="fas fa-map-marked-alt me-2"></i> ম্যাপে দেখুন
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                
                <!-- No branch match message -->
                <div class="col-12 text-center py-5" id="noBranchMsg" style="display: none;">
                    <div class="text-muted mb-2"><i class="far fa-frown fa-2x"></i></div>
                    <p class="text-secondary mb-0">দুঃখিত, এই নামের কোনো শাখা খুঁজে পাওয়া যায়নি!</p>
                </div>
            </div>

            <!-- Full-width Google Map Embed -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden border-light-grey">
                        <div class="card-body p-0" style="height: 380px;">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m13!1m7!1s0x3755b8b087026b81:0x8fa563bbd1ca690d!2sDhaka!3m2!1d23.810332!2d90.4125181!5e0!3m2!1sen!2sbd!4v1700000000000!5m2!1sen!2sbd" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Map Modal -->
    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true" style="font-family: 'Baloo Da 2', sans-serif;">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="mapModalLabel">ম্যাপ লোকেশন</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="box-shadow: none;"></button>
                </div>
                <div class="modal-body pt-3">
                    <div class="rounded-3 overflow-hidden shadow-sm border border-light-grey" style="height: 450px;">
                        <iframe id="mapIframe" src="" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS Styles -->
    <style>
        .border-light-grey {
            border: 1px solid #e2e8f0 !important;
        }
        .text-success-brand {
            color: #006A4E !important;
        }
        .icon-box-brand {
            background-color: rgba(0, 106, 78, 0.06) !important;
            color: #006A4E !important;
            transition: all 0.3s ease;
        }
        .icon-box-brand:hover {
            background-color: #006A4E !important;
            color: #ffffff !important;
            transform: scale(1.08);
        }
        .btn-brand-success {
            background: linear-gradient(135deg, #006A4E 0%, #00563F 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px !important;
            transition: all 0.25s ease;
        }
        .btn-brand-success:hover {
            background: linear-gradient(135deg, #00805E 0%, #006A4E 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 106, 78, 0.2) !important;
        }
        .form-control:focus {
            border-color: rgba(0, 106, 78, 0.5) !important;
            box-shadow: 0 0 0 0.25rem rgba(0, 106, 78, 0.15) !important;
        }
        .hover-grow-card {
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .hover-grow-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 106, 78, 0.05) !important;
            border-color: rgba(16, 185, 129, 0.2) !important;
        }
    </style>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Client side Search Filter
        $('#branchSearch').on('keyup', function() {
            var value = $(this).val().toLowerCase().trim();
            var visibleCount = 0;
            
            $('.branch-item').each(function() {
                var name = $(this).data('name').toLowerCase();
                var address = $(this).data('address').toLowerCase();
                
                if (name.indexOf(value) > -1 || address.indexOf(value) > -1) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide();
                }
            });
            
            if (visibleCount === 0) {
                $('#noBranchMsg').show();
            } else {
                $('#noBranchMsg').hide();
            }
        });

        // Open Map Modal
        $('.view-map-btn').on('click', function() {
            var mapUrl = $(this).data('url');
            var branchName = $(this).data('name');
            
            $('#mapModalLabel').text(branchName + ' - ম্যাপ লোকেশন');
            $('#mapIframe').attr('src', mapUrl);
            
            var myModal = new bootstrap.Modal(document.getElementById('mapModal'));
            myModal.show();
        });
        
        // Clear iframe src on modal close
        $('#mapModal').on('hidden.bs.modal', function () {
            $('#mapIframe').attr('src', '');
        });
    });
</script>
@endpush
