@extends('theme::layouts.app')

@section('title', 'যোগাযোগ - হেজবুত তওহীদ')
@section('meta_description', 'হেযবুত তওহীদের সাথে যোগাযোগ করুন। যেকোনো জিজ্ঞাসা, মতামত বা পরামর্শের জন্য আমাদের কেন্দ্রীয় কার্যালয় বা শাখা সমূহের সাথে সরাসরি যোগাযোগ করতে পারেন।')

@push('styles')
    <!-- Leaflet.js CSS for Interactive map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'যোগাযোগ করুন',
        'subtitle' => 'যেকোনো জিজ্ঞাসা, মতামত বা পরামর্শের জন্য আমাদের সাথে সরাসরি যোগাযোগ করুন',
        'badge_text' => 'যোগাযোগ ও সাপোর্ট',
        'badge_icon' => 'fas fa-headset'
    ])

    <!-- Contact Main Section -->
    <div class="contact-page-wrapper py-5">
        <div class="container">
            <div class="row g-4">
                
                <!-- Contact Information Column -->
                <div class="col-lg-5">
                    <div class="card premium-contact-card p-4 p-md-5 h-100">
                        <div class="mb-4">
                            <span class="section-badge-premium">যোগাযোগের বিবরণ</span>
                            <h3 class="section-title-premium">আমাদের কার্যালয়</h3>
                            <p class="text-muted lh-lg mb-0" style="font-size: 0.95rem;">
                                হেজবুত তওহীদের আদর্শ প্রচার, সমাজ সংস্কার এবং আমাদের বিভিন্ন মানবিক কার্যক্রম সম্পর্কে যেকোনো তথ্য জানতে নিচে দেওয়া ঠিকানায় বা ফর্ম ব্যবহার করে যোগাযোগ করতে পারেন।
                            </p>
                        </div>

                        <!-- Address -->
                        <div class="d-flex align-items-start mb-4">
                            <div class="icon-box-brand me-3">
                                <i class="fas fa-map-marker-alt fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.98rem;">কেন্দ্রীয় কার্যালয়</h6>
                                <p class="text-secondary mb-0" style="font-size: 0.9rem;">{{ $setting->getSetting('company_address', 'বাড়ী # ০৩, রোড # ২০/এ, সেক্টর # ১৪, উত্তরা আজমপুর, ঢাকা-১২৩০') }}</p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="d-flex align-items-start mb-4">
                            <div class="icon-box-brand me-3">
                                <i class="fas fa-phone-alt fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.98rem;">ফোন নম্বর</h6>
                                <p class="text-secondary mb-0" style="font-size: 0.9rem;">
                                    @php
                                        $phones = explode(',', $setting->getSetting('company_phone', '0171-1005 025, 0167-0174 643'));
                                    @endphp
                                    @foreach($phones as $index => $phone)
                                        <a href="tel:{{ trim($phone) }}" class="text-decoration-none text-secondary hover-brand-link">{{ trim($phone) }}</a>{{ $index < count($phones) - 1 ? ', ' : '' }}
                                    @endforeach
                                </p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="d-flex align-items-start mb-4">
                            <div class="icon-box-brand me-3">
                                <i class="fas fa-envelope fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.98rem;">ইমেইল ঠিকানা</h6>
                                <p class="text-secondary mb-0" style="font-size: 0.9rem;"><a href="mailto:{{ $setting->getSetting('company_email', 'askhezbuttawheed@gmail.com') }}" class="text-decoration-none text-secondary hover-brand-link">{{ $setting->getSetting('company_email', 'askhezbuttawheed@gmail.com') }}</a></p>
                            </div>
                        </div>

                        <!-- Website -->
                        <div class="d-flex align-items-start mb-4">
                            <div class="icon-box-brand me-3">
                                <i class="fas fa-globe fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.98rem;">ওয়েবসাইট</h6>
                                <p class="text-secondary mb-0" style="font-size: 0.9rem;"><a href="https://www.hezbuttawheed.org" target="_blank" class="text-decoration-none text-secondary hover-brand-link">www.hezbuttawheed.org</a></p>
                            </div>
                        </div>

                        <!-- Office Hours -->
                        <div class="d-flex align-items-start">
                            <div class="icon-box-brand me-3">
                                <i class="far fa-clock fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.98rem;">কার্য্যালয়ের সময়</h6>
                                <p class="text-secondary mb-0" style="font-size: 0.9rem;">{{ $setting->getSetting('working_hours', 'সকাল ৯:০০ - বিকাল ৫:০০') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form Column -->
                <div class="col-lg-7">
                    <div class="card premium-contact-card p-4 p-md-5 h-100">
                        <div class="mb-4">
                            <span class="section-badge-premium">বার্তা পাঠান</span>
                            <h3 class="section-title-premium mt-2">আপনার মতামত লিখুন</h3>
                        </div>
                        
                        <form action="{{ route('contact.send') }}" method="POST" id="contactForm" novalidate>
                            @csrf
                            <div class="row g-3">
                                <!-- Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label text-dark fw-semibold small">আপনার নাম *</label>
                                        <input type="text" name="name" id="name" class="form-control premium-input @error('name') is-invalid @enderror" placeholder="নাম লিখুন..." value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label text-dark fw-semibold small">আপনার ইমেল *</label>
                                        <input type="email" name="email" id="email" class="form-control premium-input @error('email') is-invalid @enderror" placeholder="ইমেল লিখুন..." value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label text-dark fw-semibold small">মোবাইল নম্বর *</label>
                                        <input type="text" name="phone" id="phone" class="form-control premium-input @error('phone') is-invalid @enderror" placeholder="মোবাইল নম্বর..." value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Subject -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subject" class="form-label text-dark fw-semibold small">বার্তার বিষয় *</label>
                                        <input type="text" name="subject" id="subject" class="form-control premium-input @error('subject') is-invalid @enderror" placeholder="বিষয়..." value="{{ old('subject') }}" required>
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Message -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="message" class="form-label text-dark fw-semibold small">বার্তা *</label>
                                        <textarea name="message" id="message" rows="5" class="form-control premium-input @error('message') is-invalid @enderror" placeholder="আপনার বার্তাটি বিস্তারিত লিখুন..." required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 text-end mt-4">
                                    <button type="submit" id="submitBtn" class="btn premium-btn-submit d-inline-flex align-items-center justify-content-center">
                                        <span class="btn-text">বার্তা পাঠান</span>
                                        <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                                        <i class="fas fa-paper-plane ms-2 text-warning btn-icon" style="font-size: 13px;"></i>
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
                    <span class="section-badge-premium">শাখা ও প্রতিনিধি</span>
                    <h3 class="section-title-premium mt-2"><i class="fas fa-map-marked-alt text-success-brand me-2"></i>শাখা ও জেলা ভিত্তিক যোগাযোগ</h3>
                    <p class="text-secondary small mb-4">সারাদেশে বিস্তৃত আমাদের শাখা কার্যালয়ের নাম, ঠিকানা ও প্রতিনিধিদের তালিকা (ম্যাপে দেখতে বাটনে ক্লিক করুন)</p>
                    
                    <!-- Branch Search Input -->
                    <div class="input-group premium-search-box">
                        <input type="text" id="branchSearch" class="form-control border-0 bg-transparent premium-search-input px-2" placeholder="শাখা কার্যালয় বা জেলার নাম লিখে খুঁজুন..." style="box-shadow: none;">
                        <span class="premium-search-btn"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>

            <!-- Branches Grid -->
            <div class="row g-4 mb-5" id="branchesContainer">
                @foreach($branches as $branch)
                    <div class="col-md-6 col-lg-4 branch-item" data-id="{{ $branch->id }}" data-name="{{ $branch->name }}" data-address="{{ $branch->address }}">
                        <div class="card premium-branch-card h-100 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box-brand d-flex justify-content-center align-items-center me-3" style="width: 44px; height: 44px; font-size: 0.95rem; flex-shrink: 0;">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1rem; line-height: 1.45;">{{ $branch->name }}</h5>
                                </div>
                                
                                <p class="text-secondary mb-2 lh-base" style="font-size: 0.85rem; text-align: justify;">
                                    <i class="fas fa-map-marker-alt text-success-brand me-2" style="width: 15px;"></i> <strong>ঠিকানা:</strong> {{ $branch->address }}
                                </p>
                                
                                @if($branch->phone)
                                    <p class="text-secondary mb-0" style="font-size: 0.85rem;">
                                        <i class="fas fa-phone-alt text-success-brand me-2" style="width: 15px;"></i> <strong>ফোন:</strong> <a href="tel:{{ $branch->phone }}" class="text-decoration-none text-secondary hover-brand-link">{{ $branch->phone }}</a>
                                    </p>
                                @endif
                            </div>
                            
                            <div class="pt-3 border-top border-light mt-3">
                                <button type="button" class="btn btn-branch-action w-100 view-map-btn" 
                                    data-id="{{ $branch->id }}" 
                                    data-lat="{{ $branch->latitude }}" 
                                    data-lng="{{ $branch->longitude }}" 
                                    data-url="{{ $branch->google_map_url }}" 
                                    data-name="{{ $branch->name }}">
                                    <i class="fas fa-map-marked-alt me-2"></i> ম্যাপে দেখুন
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <!-- No branch match message -->
                <div class="col-12 text-center py-5" id="noBranchMsg" style="display: none;">
                    <div class="text-muted mb-2"><i class="far fa-frown fa-2x"></i></div>
                    <p class="text-secondary mb-0">দুঃখিত, এই নামের কোনো শাখা খুঁজে পাওয়া যায়নি!</p>
                </div>
            </div>

            <!-- Full-width Google My Map Layout (Leaflet Integration) -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="mb-3 text-center">
                        <span class="section-badge-premium">ইন্টারেক্টিভ নেটওয়ার্ক ম্যাপ</span>
                        <h4 class="fw-bold mt-2 text-dark">দেশজুড়ে আমাদের জেলা কার্যালয়সমূহ</h4>
                    </div>
                    <div id="branchMap" class="premium-map-container" style="height: 500px; width: 100%;"></div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <!-- Leaflet.js JavaScript for Interactive Map -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        $(document).ready(function() {
            // Fetch branches collection from Blade
            const branches = @json($branches);
            
            // Initialize Leaflet Map centered on Bangladesh coordinates
            var map = L.map('branchMap', {
                scrollWheelZoom: false
            }).setView([23.6850, 90.3563], 7);

            // Add standard OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Custom Leaflet Pin Icon (Emerald Green to match our branding color palette)
            var brandMapIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            // Keep reference to all markers on the map by branch ID
            var markers = {};

            // Add markers dynamically for all branches with valid lat/long
            branches.forEach(function(branch) {
                if (branch.latitude && branch.longitude) {
                    var popupHtml = `
                        <div style="font-family: 'SolaimanLipi', 'Baloo Da 2', sans-serif; padding: 4px; max-width: 220px;">
                            <h6 style="font-weight: 700; color: #006A4E; margin: 0 0 6px 0; font-size: 13px; line-height: 1.4;">${branch.name}</h6>
                            <p style="margin: 0 0 6px 0; font-size: 11px; color: #475569; line-height: 1.5;"><i class="fas fa-map-marker-alt text-success-brand me-1"></i> ${branch.address}</p>
                            ${branch.phone ? `<p style="margin: 0; font-size: 11px; color: #475569;"><i class="fas fa-phone-alt text-success-brand me-1"></i> <strong>ফোন:</strong> <a href="tel:${branch.phone}" style="color: #006A4E; text-decoration: none; font-weight: 600;">${branch.phone}</a></p>` : ''}
                        </div>
                    `;

                    var marker = L.marker([parseFloat(branch.latitude), parseFloat(branch.longitude)], { icon: brandMapIcon })
                        .bindPopup(popupHtml)
                        .addTo(map);

                    markers[branch.id] = marker;
                }
            });

            // Clear invalid status when user starts typing/editing
            $('#contactForm').on('input change', '.premium-input', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').remove();
            });

            // Form Submit via AJAX
            $('#contactForm').on('submit', function(e) {
                e.preventDefault();
                
                var form = $(this);
                var btn = $('#submitBtn');
                var btnText = btn.find('.btn-text');
                var spinner = btn.find('.spinner-border');
                var icon = btn.find('.btn-icon');
                
                // Clear any existing validation style/errors
                form.find('.premium-input').removeClass('is-invalid');
                form.find('.invalid-feedback').remove();

                // Loading State
                btn.prop('disabled', true);
                btnText.text('বার্তা পাঠানো হচ্ছে...');
                spinner.removeClass('d-none');
                icon.addClass('d-none');

                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message || 'আপনার বার্তা সফলভাবে পাঠানো হয়েছে!');
                            form[0].reset(); // Reset form inputs
                        } else {
                            toastr.error(response.message || 'বার্তা পাঠাতে সমস্যা হয়েছে! দয়া করে পুনরায় চেষ্টা করুন।');
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        if (xhr.status === 422 && response && response.errors) {
                            // Highlight individual invalid inputs and show message below them
                            $.each(response.errors, function(field, messages) {
                                var input = form.find('[name="' + field + '"]');
                                input.addClass('is-invalid');
                                
                                // Create error message element directly under the input
                                var errorMsg = messages[0]; // Take the first error message
                                input.after('<div class="invalid-feedback">' + errorMsg + '</div>');
                            });
                        } else {
                            toastr.error(response && response.message ? response.message : 'বার্তা পাঠাতে সমস্যা হয়েছে! দয়া করে পুনরায় চেষ্টা করুন।');
                        }
                    },
                    complete: function() {
                        // Reset button state
                        btn.prop('disabled', false);
                        btnText.text('বার্তা পাঠান');
                        spinner.addClass('d-none');
                        icon.removeClass('d-none');
                    }
                });
            });

            // Client side Search Filter for both Grid and Map Pins
            $('#branchSearch').on('keyup', function() {
                var value = $(this).val().toLowerCase().trim();
                var visibleCount = 0;
                
                $('.branch-item').each(function() {
                    var name = $(this).data('name').toLowerCase();
                    var address = $(this).data('address').toLowerCase();
                    var branchId = $(this).data('id');
                    
                    if (name.indexOf(value) > -1 || address.indexOf(value) > -1) {
                        $(this).show();
                        visibleCount++;
                        // Show corresponding marker on map
                        if (markers[branchId]) {
                            map.addLayer(markers[branchId]);
                        }
                    } else {
                        $(this).hide();
                        // Hide corresponding marker from map
                        if (markers[branchId]) {
                            map.removeLayer(markers[branchId]);
                        }
                    }
                });
                
                if (visibleCount === 0) {
                    $('#noBranchMsg').show();
                } else {
                    $('#noBranchMsg').hide();
                }
            });

            // Dynamic Map interactions: Click "ম্যাপে দেখুন" to scroll, pan and zoom map to that branch
            $('.view-map-btn').on('click', function(e) {
                e.preventDefault();
                var branchId = $(this).data('id');
                var lat = $(this).data('lat');
                var lng = $(this).data('lng');
                var googleMapUrl = $(this).data('url');
                
                if (lat && lng) {
                    // Smoothly scroll down to the Map container
                    $('html, body').animate({
                        scrollTop: $("#branchMap").offset().top - 80
                    }, 600);

                    // Pan and zoom map to location with fluid motion
                    map.setView([parseFloat(lat), parseFloat(lng)], 13, {
                        animate: true,
                        duration: 1.2
                    });

                    // Open popup after transition completes
                    if (markers[branchId]) {
                        setTimeout(function() {
                            markers[branchId].openPopup();
                        }, 1200);
                    }
                } else if (googleMapUrl) {
                    // Fallback to open the Google Map link in new tab if coords missing
                    window.open(googleMapUrl, '_blank');
                }
            });
        });
    </script>
@endpush