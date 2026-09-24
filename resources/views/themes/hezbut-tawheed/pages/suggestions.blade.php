@extends('theme::layouts.app')

@section('title', 'পরামর্শ ও প্রস্তাবনা - হেজবুত তওহীদ')
@section('meta_description', 'হেযবুত তওহীদের নীতি, প্রচার কার্যক্রম ও সমাজ সংস্কার বিষয়ে আপনার যেকোনো গঠনমূলক পরামর্শ বা মতামত সরাসরি পাঠাতে পারেন।')

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'পরামর্শ ও প্রস্তাবনা প্রদান',
        'subtitle' => 'হেযবুত তওহীদের নীতি, প্রচার কার্যক্রম ও সমাজ সংস্কারে আপনার যেকোনো ইতিবাচক গঠনমূলক পরামর্শ আমাদের জানান',
        'badge_text' => 'পরামর্শ ও ইনবক্স',
        'badge_icon' => 'fas fa-lightbulb'
    ])

    <!-- Suggestions Main Section -->
    <div class="contact-page-wrapper py-5">
        <div class="container">
            
            <!-- Alert Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show p-4 rounded-3 shadow-sm mb-4 border-0" style="background-color: #ecfdf5; color: #065f46; border-left: 4px solid #059669 !important;" role="alert">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-check-circle fa-xl text-success"></i>
                        <div>
                            <h5 class="fw-bold mb-1" style="font-size: 1.1rem;">ধন্যবাদ!</h5>
                            <p class="mb-0 small" style="font-size: 0.95rem;">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show p-4 rounded-3 shadow-sm mb-4 border-0" style="background-color: #fef2f2; color: #991b1b; border-left: 4px solid #dc2626 !important;" role="alert">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-exclamation-triangle fa-xl text-danger"></i>
                        <div>
                            <h5 class="fw-bold mb-1" style="font-size: 1.1rem;">দুঃখিত!</h5>
                            <p class="mb-0 small" style="font-size: 0.95rem;">{{ session('error') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4 align-items-stretch">
                
                <!-- Published Suggestions Feed (Left Column: col-lg-7) -->
                <div class="col-lg-7">
                    <div class="card premium-contact-card p-4 p-md-5 h-100">
                        <div class="mb-4">
                            <span class="section-badge-premium">পরামর্শমালা</span>
                            <h3 class="section-title-premium mt-2"><i class="fas fa-comments text-success-brand me-2"></i>প্রকাশিত নাগরিক পরামর্শসমূহ</h3>
                            <p class="text-muted small mb-0">নাগরিকদের প্রেরিত যেসব গঠনমূলক পরামর্শ ওয়েবসাইটে প্রকাশের সম্মতি পাওয়া গেছে ও এডমিন কর্তৃক অনুমোদিত হয়েছে:</p>
                        </div>

                        <div class="row g-3">
                            @if (isset($publishedSuggestions) && count($publishedSuggestions) > 0)
                                @foreach($publishedSuggestions as $item)
                                    <div class="col-12">
                                        <div class="p-4 rounded-3 bg-light border-0 shadow-xs mb-2" style="border-left: 4px solid #006A4E !important;">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="icon-box-brand d-flex align-items-center justify-content-center me-1" style="width: 36px; height: 36px; font-size: 0.85rem; flex-shrink: 0;">
                                                        <i class="fas fa-user-check"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.98rem;">{{ $item->name }}</h6>
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 0.72rem;">🌐 অনুমোদিত পরামর্শ</span>
                                                    </div>
                                                </div>
                                                <span class="text-muted small" style="font-size: 0.8rem;"><i class="far fa-clock me-1"></i>{{ $item->created_at->format('d M, Y') }}</span>
                                            </div>

                                            @if($item->subject)
                                                <div class="fw-bold text-dark mb-2 mt-2" style="font-size: 0.95rem;">
                                                    <i class="fas fa-tag text-warning me-1"></i>{{ $item->subject }}
                                                </div>
                                            @endif

                                            <p class="text-secondary mb-0 lh-base" style="font-size: 0.92rem; text-align: justify; white-space: pre-line;">{{ $item->message }}</p>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="col-12 mt-3">
                                    {{ $publishedSuggestions->links() }}
                                </div>
                            @else
                                <div class="col-12 text-center py-5 bg-light rounded-3 my-3">
                                    <div class="text-muted mb-3"><i class="far fa-comments fa-3x text-success"></i></div>
                                    <h5 class="text-dark fw-bold mb-1">বর্তমানে কোনো পাবলিক পরামর্শ প্রকাশ করা হয়নি!</h5>
                                    <p class="text-secondary small mb-0">প্রথম প্রকাশযোগ্য পরামর্শটি ডানপাশের ফর্ম ব্যবহার করে অনুমতির টিক চিহ্নসহ জমা দিন।</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Suggestions Form Column (Right Column: col-lg-5) -->
                <div class="col-lg-5">
                    <div class="card premium-contact-card p-4 p-md-5 h-100 position-sticky" style="top: 90px; z-index: 10;">
                        <div class="mb-4">
                            <span class="section-badge-premium">পরামর্শ প্রদান</span>
                            <h3 class="section-title-premium mt-2">আপনার সুচিন্তিত পরামর্শ লিখুন</h3>
                        </div>

                        <form action="{{ route('suggestions.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <!-- Name -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="name" class="form-label text-dark fw-semibold small">আপনার নাম *</label>
                                        <input type="text" name="name" id="name" class="form-control premium-input @error('name') is-invalid @enderror" placeholder="পূর্ণ নাম লিখুন..." value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Contact -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="contact" class="form-label text-dark fw-semibold small">যোগাযোগের নম্বর বা ইমেল *</label>
                                        <input type="text" name="contact" id="contact" class="form-control premium-input @error('contact') is-invalid @enderror" placeholder="মোবাইল নম্বর অথবা ইমেল..." value="{{ old('contact') }}" required>
                                        @error('contact')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Subject -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="subject" class="form-label text-dark fw-semibold small">বিষয় (Subject)</label>
                                        <input type="text" name="subject" id="subject" class="form-control premium-input @error('subject') is-invalid @enderror" placeholder="বিষয় টাইপ করুন বা ওপরের ট্যাগসে ক্লিক করুন" value="{{ old('subject') }}">
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Message -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="message" class="form-label text-dark fw-semibold small">আপনার বার্তা/পরামর্শ বিস্তারিত লিখুন *</label>
                                        <textarea name="message" id="message" rows="4" class="form-control premium-input @error('message') is-invalid @enderror" placeholder="আপনার সুচিন্তিত পরামর্শ বা প্রস্তাবনা এখানে বিস্তারিত লিখুন..." required style="min-height: 110px;">{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Website Publishing Consent Option -->
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="form-label text-dark fw-semibold small mb-2 d-block">
                                            আপনার এই পরামর্শটি কি ওয়েবসাইটে প্রকাশের অনুমতি দিচ্ছেন? *
                                        </label>
                                        <div class="d-flex flex-column gap-2 ms-1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="allow_publish" id="allow_publish_0" value="0" {{ old('allow_publish', '0') == '0' ? 'checked' : '' }} required style="cursor: pointer;">
                                                <label class="form-check-label text-secondary small fw-medium" for="allow_publish_0" style="cursor: pointer; font-size: 0.9rem;">
                                                    🔒 না, শুধুমাত্র গোপন ইনবক্সের জন্য (প্রাইভেট)
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="allow_publish" id="allow_publish_1" value="1" {{ old('allow_publish') == '1' ? 'checked' : '' }} style="cursor: pointer;">
                                                <label class="form-check-label text-secondary small fw-medium" for="allow_publish_1" style="cursor: pointer; font-size: 0.9rem;">
                                                    🌐 হ্যাঁ, প্রকাশ করার অনুমতি দিচ্ছি (পাবলিকলি প্রকাশযোগ্য)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 mt-4">
                                    <button type="submit" id="submitBtn" class="btn premium-btn-submit d-inline-flex align-items-center justify-content-center w-100 py-3">
                                        <span class="btn-text">পরামর্শ জমা দিন</span>
                                        <i class="fas fa-paper-plane ms-2 text-warning btn-icon" style="font-size: 13px;"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
