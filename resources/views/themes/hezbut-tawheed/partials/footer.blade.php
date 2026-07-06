<footer class="footer-area pt-5 pb-4 position-relative" style="background: linear-gradient(135deg, #002c1f 0%, #001f16 100%); color: #e2e8f0; border-top: 4px solid #10b981;">
    <div class="container">
        <div class="row g-4">

            <!-- Column 1: Organization Branding -->
            <div class="col-lg-3 col-md-6 text-center text-lg-start">
                <div class="footer-logo-wrapper mb-3 d-flex align-items-center justify-content-center">
                    @if($setting->getSetting('footer_logo'))
                        <img src="{{ asset($setting->getSetting('footer_logo')) }}" alt="Logo" style="max-height: 52px; max-width: 100%; object-fit: contain;">
                    @elseif($setting->getSetting('company_logo'))
                        <img src="{{ asset($setting->getSetting('company_logo')) }}" alt="Logo" style="max-height: 52px; max-width: 100%; object-fit: contain;">
                    @else
                        <!-- Vector SVG logo fallback with text next to it -->
                        <div class="me-3 bg-white p-1 rounded d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; flex-shrink: 0;">
                            <svg width="36" height="36" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="5" y="3" width="46" height="46" stroke="#1E293B" stroke-width="1.8" fill="#FFFFFF"/>
                                <path d="M12 43 C12 23, 18 11, 28 11 C38 11, 44 23, 44 43" stroke="#009639" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                                <path d="M22 28 L32 23 L38 35 L28 40 Z" fill="#F8FAFC" stroke="#94A3B8" stroke-width="0.5"/>
                                <path d="M20 29 L30 24 L36 36 L26 41 Z" fill="#D4AF37" stroke="#B45309" stroke-width="0.5"/>
                                <path d="M22 28.5 L28 25.5 L34 34.5 L28 37.5 Z" fill="#009639"/>
                                <circle cx="28" cy="31.5" r="2" fill="#D4AF37"/>
                                <line x1="2" y1="50" x2="52" y2="50" stroke="#D4AF37" stroke-width="1.5"/>
                                <line x1="2" y1="52" x2="48" y2="52" stroke="#D4AF37" stroke-width="1"/>
                                <path d="M51 48 C52.5 48, 54 49.5, 54 51 C54 52.5, 52.5 54, 51 54 C52 53, 52.5 52, 52 51 C51.5 50, 51 49, 51 48 Z" fill="#D4AF37"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="fw-bold text-white mb-0" style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.25rem;">{{ $setting->getSetting('company_name', 'হেযবুত তওহীদ') }}</h4>
                            <span class="text-success small fw-medium d-block" style="font-size: 0.72rem; letter-spacing: 0.5px;">{{ $setting->getSetting('company_tagline', 'একটি অরাজনৈতিক আন্দোলন') }}</span>
                        </div>
                    @endif
                </div>
                <p class="text-gray-light lh-lg" style="font-size: 0.92rem;  text-align: center; margin-bottom: 1.5rem;">
                    {{ $setting->getSetting('company_description', 'হেযবুত তওহীদ একটি অরাজনৈতিক ধর্মীয় সংস্কারমূলক আন্দোলন। ধর্মান্ধতা, ধর্মব্যবসা, জঙ্গিবাদ ও সাম্প্রদায়িকতার বিরুদ্ধে আদর্শিক প্রচারণাই আমাদের মূল আহ্বান।') }}
                </p>
                <div class="social-icons mb-4 mb-lg-0">
                    @if($setting->getSetting('facebook_url'))
                        <a href="{{ $setting->getSetting('facebook_url') }}" class="social-btn facebook" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    @if($setting->getSetting('youtube_url'))
                        <a href="{{ $setting->getSetting('youtube_url') }}" class="social-btn youtube" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    @endif
                    @if($setting->getSetting('twitter_url'))
                        <a href="{{ $setting->getSetting('twitter_url') }}" class="social-btn twitter" target="_blank" aria-label="Twitter"><i class="fab fa-x-twitter"></i></a>
                    @endif
                    @if($setting->getSetting('instagram_url'))
                        <a href="{{ $setting->getSetting('instagram_url') }}" class="social-btn instagram" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if($setting->getSetting('linkedin_url'))
                        <a href="{{ $setting->getSetting('linkedin_url') }}" class="social-btn linkedin" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    @endif
                </div>
            </div>

            <!-- Column 2: Important Links -->
            <div class="col-lg-3 col-md-6 ps-lg-4 text-center text-lg-start">
                <h5 class="fw-bold text-white mb-4 position-relative pb-2" style="font-family: 'Baloo Da 2', sans-serif;">
                    গুরুত্বপূর্ণ লিঙ্ক
                    <span class="position-absolute bottom-0 start-50 translate-middle-x start-lg-0 translate-middle-x-none bg-success" style="width: 35px; height: 3px; border-radius: 2px;"></span>
                </h5>
                <div class="row g-2">
                    <div class="col-6">
                        <ul class="list-unstyled footer-links mb-0">
                            <li><a href="/about-us"><i class="fas fa-chevron-right text-success small"></i> পরিচিতি</a></li>
                            <li><a href="/vision"><i class="fas fa-chevron-right text-success small"></i> দৃষ্টিভঙ্গি</a></li>
                            <li><a href="/principles"><i class="fas fa-chevron-right text-success small"></i> মূলনীতি</a></li>
                            <li><a href="/declaration"><i class="fas fa-chevron-right text-success small"></i> ঘোষণাপত্র</a></li>
                            <li><a href="/monogram"><i class="fas fa-chevron-right text-success small"></i> মনোগ্রাম</a></li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <ul class="list-unstyled footer-links mb-0">
                            <li><a href="/programs"><i class="fas fa-chevron-right text-success small"></i> কর্মসূচি</a></li>
                            <li><a href="/publications"><i class="fas fa-chevron-right text-success small"></i> প্রকাশনা</a></li>
                            <li><a href="/library"><i class="fas fa-chevron-right text-success small"></i> লাইব্রেরি</a></li>
                            <li><a href="/articles/category/approval"><i class="fas fa-chevron-right text-success small"></i> অনুমোদন</a></li>
                            <li><a href="/articles/category/attack-on-us"><i class="fas fa-chevron-right text-success small"></i> নির্যাতন</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Column 3: Contact & Support -->
            <div class="col-lg-3 col-md-6 ps-lg-3 text-center text-lg-start">
                <h5 class="fw-bold text-white mb-4 position-relative pb-2" style="font-family: 'Baloo Da 2', sans-serif;">
                    যোগাযোগ ও ঠিকানা
                    <span class="position-absolute bottom-0 start-50 translate-middle-x start-lg-0 translate-middle-x-none bg-success" style="width: 35px; height: 3px; border-radius: 2px;"></span>
                </h5>
                <ul class="list-unstyled text-gray-light footer-contact-info mb-0">
                    <li class="d-flex align-items-start mb-3 justify-content-center justify-content-lg-start">
                        <i class="fas fa-map-marker-alt text-success mt-1"></i>
                        <span style="color: #cbd5e1;">{{ $setting->getSetting('company_address', 'ঢাকা, বাংলাদেশ') }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3 justify-content-center justify-content-lg-start">
                        <i class="fas fa-phone-alt text-success"></i>
                        <span style="color: #cbd5e1; white-space: nowrap;">{{ $setting->getSetting('company_phone', '+৮৮০ ১৭১১-১১১১১১') }}</span>
                    </li>
                    @if($setting->getSetting('company_hotline'))
                    <li class="d-flex align-items-center mb-3 justify-content-center justify-content-lg-start">
                        <i class="fas fa-headset text-success"></i>
                        <span style="color: #cbd5e1; white-space: nowrap;">হটলাইন: {{ $setting->getSetting('company_hotline') }}</span>
                    </li>
                    @endif
                    <li class="d-flex align-items-center mb-3 justify-content-center justify-content-lg-start">
                        <i class="fas fa-envelope text-success"></i>
                        <span style="color: #cbd5e1; white-space: nowrap;">{{ $setting->getSetting('company_email', 'info@hezbuttawheed.org') }}</span>
                    </li>
                    @if($setting->getSetting('company_whatsapp'))
                    <li class="d-flex align-items-center justify-content-center justify-content-lg-start">
                        <i class="fab fa-whatsapp text-success fs-5"></i>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->getSetting('company_whatsapp')) }}" class="hover-success text-decoration-none" style="color: #cbd5e1; white-space: nowrap;" target="_blank">হোয়াটসঅ্যাপ সংযোগ</a>
                    </li>
                    @endif
                </ul>
            </div>

            <!-- Column 4: Newsletter -->
            <div class="col-lg-3 col-md-6 text-center text-lg-start">
                <h5 class="fw-bold text-white mb-4 position-relative pb-2" style="font-family: 'Baloo Da 2', sans-serif;">
                    বার্তা ও ঘোষণা পেতে
                    <span class="position-absolute bottom-0 start-50 translate-middle-x start-lg-0 translate-middle-x-none bg-success" style="width: 35px; height: 3px; border-radius: 2px;"></span>
                </h5>
                <p class="text-gray-light lh-lg" style="font-size: 0.9rem; color: #a0aec0; margin-bottom: 1.25rem;">
                    দলের সর্বশেষ খবর ও বিবৃতি নিয়মিত আপনার মেইলে পেতে সাবস্ক্রাইব করুন।
                </p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form">
                    @csrf
                    <div class="input-group shadow-sm max-w-sm mx-auto mx-lg-0">
                        <input type="email" name="email" class="form-control border-0 px-3" placeholder="আপনার ইমেইল..." required style="background: rgba(255, 255, 255, 0.08); color: #fff; font-size: 0.92rem; border-radius: 50px 0 0 50px !important;">
                        <button type="submit" class="btn btn-success px-3 d-flex align-items-center justify-content-center" style="background-color: #10b981; border: none; border-radius: 0 50px 50px 0 !important; width: 48px; height: 40px;"><i class="fas fa-paper-plane" style="font-size: 0.9rem;"></i></button>
                    </div>
                </form>
            </div>

        </div>

        <hr class="mt-5 mb-4" style="border-color: rgba(255,255,255,0.08);">

        <!-- Bottom Copyright Bar -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <p class="mb-0" style="color: #94a3b8; font-size: 0.88rem;">&copy; {{ date('Y') }} {{ $setting->getSetting('company_name', 'হেযবুত তওহীদ') }} &bull; সর্বস্বত্ব সংরক্ষিত।</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0" style="color: #94a3b8; font-size: 0.88rem;">ডিজাইন ও ডেভেলপমেন্ট: <a href="{{ route('home') }}" class="text-white hover-success text-decoration-none fw-medium">আইটি সেল, {{ $setting->getSetting('company_name', 'হেযবুত তওহীদ') }}</a></p>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer-links li {
        margin-bottom: 0.65rem;
    }
    .footer-links li a {
        color: #cbd5e1;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        font-size: 0.93rem;
    }
    .footer-links li a:hover {
        color: #34d399 !important;
        transform: translateX(4px);
    }
    .footer-links li a i {
        margin-right: 8px;
        font-size: 0.75rem;
    }
    .hover-success:hover {
        color: #34d399 !important;
    }
    /* Social Button Styles */
    .social-btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.05);
        color: #cbd5e1;
        margin-right: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .social-btn:hover {
        color: #ffffff;
        transform: translateY(-3px);
    }
    .social-btn.facebook:hover {
        background-color: #3b5998;
    }
    .social-btn.youtube:hover {
        background-color: #ff0000;
    }
    .social-btn.twitter:hover {
        background-color: #1da1f2;
    }
    .social-btn.instagram:hover {
        background-color: #e1306c;
    }
    .social-btn.linkedin:hover {
        background-color: #0077b5;
    }

    /* Contact Info Icons Margins */
    .footer-contact-info li i {
        margin-right: 12px;
        width: 20px;
        text-align: center;
        flex-shrink: 0;
    }

    /* Newsletter focus style */
    .newsletter-form input::placeholder {
        color: #94a3b8;
    }
    .newsletter-form input:focus {
        background: rgba(255,255,255,0.15) !important;
        box-shadow: none;
        color: #fff !important;
        outline: none;
    }
    .footer-contact-info li span, .footer-contact-info li a {
        font-size: 0.92rem;
    }
    .max-w-sm {
        max-width: 320px;
    }

    /* Responsive Text Alignment Helpers for Bootstrap 5 */
    @media (min-width: 992px) {
        .translate-middle-x-none {
            transform: none !important;
        }
        .start-lg-0 {
            left: 0 !important;
        }
        .text-justify {
            text-align: justify !important;
        }
        .text-align-last-left {
            text-align-last: left !important;
        }
    }
    @media (max-width: 991.98px) {
        .footer-logo-wrapper {
            flex-direction: column;
        }
        .footer-logo-wrapper img {
            margin-right: 0 !important;
            margin-bottom: 0.5rem;
        }
    }
</style>
