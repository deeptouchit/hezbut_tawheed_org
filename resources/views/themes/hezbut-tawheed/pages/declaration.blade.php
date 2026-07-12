@extends('theme::layouts.app')

@section('title', 'আন্দোলনের ঐতিহাসিক ঘোষণাপত্র - হেযবুত তওহীদ')

@push('styles')

@endpush

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'আন্দোলনের ঘোষণাপত্র',
        'subtitle' => 'ধর্মান্ধতার অবসান, মানবতার জয়গান ও ভ্রাতৃত্ব প্রতিষ্ঠার অঙ্গীকার।',
        'badge_text' => 'ঘোষণাপত্র',
        'badge_icon' => 'fas fa-file-contract'
    ])

    <!-- Main Declaration Section -->
    <section class="dec-section">
        <div class="container">
            
            <div class="dec-header">
                <h1 class="dec-title">ঐতিহাসিক <span class="highlight">ঘোষণাপত্র</span></h1>
                <p class="dec-subtitle">
                    হেযবুত তওহীদের পক্ষ থেকে মানবজাতির কল্যাণ, ঐক্য ও শান্তি প্রতিষ্ঠার সুনির্দিষ্ট দিকনির্দেশনা ও মূল অঙ্গীকারনামা।
                </p>
            </div>

            <!-- Scroll-like Charter Card -->
            <div class="charter-card">
                
                <div class="charter-icon-wrapper">
                    <div class="charter-icon">
                        <i class="fas fa-scroll"></i>
                    </div>
                </div>

                <div class="charter-preface">
                    আমরা ঘোষণা করছি যে, ধর্মের প্রকৃত শিক্ষাকে মানুষের সামনে সঠিক ও বিকৃতিহীনভাবে তুলে ধরা এবং শান্তি ও মানবতার ভিত্তিতে সৌহার্দ্যময় সমাজ বিনির্মাণ করাই আমাদের আন্দোলনের মূল ভিত্তি।
                </div>

                <!-- Core Declaration Points -->
                <div class="charter-points">
                    
                    <div class="point-item">
                        <span class="point-number">১</span>
                        <div class="point-content">
                            <h3 class="point-title">ধর্মের প্রকৃত রূপ প্রকাশ ও উগ্রবাদের প্রতিবাদ</h3>
                            <p class="point-desc">
                                ইসলাম বা কোনো সত্য ধর্মই উগ্রবাদ, মানুষের রক্তপাত ও অন্যায় বোমাবাজি সমর্থন করে না। প্রকৃত ধর্ম সর্বদা মানুষের সার্বিক কল্যাণ ও সামাজিক শান্তি নিশ্চিত করতে প্রেরিত হয়েছে, অশান্তি সৃষ্টির জন্য নয়।
                            </p>
                        </div>
                    </div>

                    <div class="point-item">
                        <span class="point-number">২</span>
                        <div class="point-content">
                            <h3 class="point-title">মানবজাতির ঐক্য ও ভ্রাতৃত্ব</h3>
                            <p class="point-desc">
                                জাতি, ধর্ম, বর্ণ নির্বিশেষে সমস্ত মানুষকে এক স্রষ্টার সৃষ্টির অংশ হিসেবে সমমর্যাদা দেওয়া এবং সমাজে একে অপরের সাথে ভ্রাতৃত্ব ও সহনশীলতার বন্ধন গড়ে তোলাই আমাদের প্রধান আহ্বান।
                            </p>
                        </div>
                    </div>

                    <div class="point-item">
                        <span class="point-number">৩</span>
                        <div class="point-content">
                            <h3 class="point-title">ধর্মব্যবসা ও অপরাজনীতি বর্জন</h3>
                            <p class="point-desc">
                                ধর্মকে ব্যক্তিগত ফায়দা বা রাজনৈতিক স্বার্থ হাসিলের হাতিয়ার হিসেবে ব্যবহারের ঘোর বিরোধিতা করছি। ধর্মের নামে মানুষকে বিভক্ত করা এবং সমাজে দাঙ্গা-হাঙ্গামা বাঁধানো ধর্মের মূল স্পিরিটের সম্পূর্ণ পরিপন্থী।
                            </p>
                        </div>
                    </div>

                    <div class="point-item">
                        <span class="point-number">৪</span>
                        <div class="point-content">
                            <h3 class="point-title">নারী অধিকার ও লিঙ্গ সমতা</h3>
                            <p class="point-desc">
                                সমাজ গঠনে পুরুষের পাশাপাশি নারীর সক্রিয় অংশগ্রহণকে নিশ্চিত করা এবং ধর্মের ভুল ব্যাখ্যার মাধ্যমে নারীদের ওপর আরোপিত সকল কৃত্রিম শৃঙ্খল ও পশ্চাৎপদতার অবসান ঘটানো।
                            </p>
                        </div>
                    </div>

                    <div class="point-item">
                        <span class="point-number">৫</span>
                        <div class="point-content">
                            <h3 class="point-title">আইন ও সার্বভৌমত্বের প্রতি শ্রদ্ধা</h3>
                            <p class="point-desc">
                                দেশের প্রচলিত সংবিধান, স্বাধীনতা ও সার্বভৌমত্বকে রক্ষা করা এবং রাষ্ট্রের আইনকে পূর্ণ সম্মান প্রদর্শনপূর্বক সম্পূর্ণ শান্তিময় পদ্ধতিতে সমাজ সংস্কারের কাজ চালিয়ে যাওয়া।
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Callout Quote -->
                <div class="charter-quote">
                    <i class="fas fa-quote-right quote-icon"></i>
                    <p>
                        "আমরা ঘোষণা করছি যে, কোনো ধর্মই উগ্রবাদ, মানুষের রক্তপাত ও অবিচার সমর্থন করে না। প্রকৃত ধর্ম মানুষের কল্যাণের জন্য, তাকে ধ্বংস করার জন্য নয়।"
                    </p>
                </div>

                <!-- Footer / Signatures -->
                <div class="charter-footer">
                    <p>
                        হেযবুত তওহীদ ধর্ম ও নৈতিকতাকে একটি বিজ্ঞানমনস্ক ও যুক্তিগ্রাহ্য দৃষ্টিকোণ থেকে লালন করে এবং একটি সর্বজনীন শান্তিময় বিশ্বসমাজ প্রতিষ্ঠায় সকলের সহযোগিতা প্রত্যাশা করে।
                    </p>
                    
                    <div class="signature-group">
                        <span class="signature-title">ঘোষক ও প্রবক্তা:</span>
                        <span class="signature-name">মোহাম্মদ বায়াজীদ খান পন্নী</span>
                        <span class="text-secondary" style="font-size:0.85rem; font-weight:700;">প্রতিষ্ঠাতা এমাম, হেযবুত তওহীদ</span>
                    </div>
                </div>

            </div>

        </div>
    </section>

@endsection
