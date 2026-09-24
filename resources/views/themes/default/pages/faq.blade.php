@extends('theme::layouts.app')

@section('title', 'প্রশ্নোত্তর ও জিজ্ঞাসা (FAQ) - হেযবুত তওহীদ')

@push('styles')
<style>
    /* ============================================================
       FAQ PAGE - Premium, Smart & High-Contrast
       ============================================================ */
    .faq-section {
        background: #fafbfc;
        padding: 4rem 0 6rem;
    }

    .faq-header {
        max-width: 800px;
        margin: 0 auto 3rem;
        text-align: center;
    }

    .faq-title {
        font-size: 2.8rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        line-height: 1.25;
        margin-bottom: 0.75rem;
    }
    .faq-title .highlight {
        color: #1e40af; /* Premium Indigo Blue */
        position: relative;
    }
    .faq-title .highlight::after {
        content: '';
        position: absolute;
        bottom: 4px;
        left: 0;
        width: 100%;
        height: 4px;
        background: #3b82f6; /* Vibrant Blue */
        border-radius: 2px;
    }

    .faq-subtitle {
        color: #475569;
        font-weight: 600;
        font-size: 1.2rem;
        line-height: 1.75;
    }

    /* ---------- Search & Filters ---------- */
    .faq-controls {
        max-width: 900px;
        margin: 0 auto 2.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        align-items: center;
        padding: 0 1rem;
    }

    .search-wrapper {
        position: relative;
        width: 100%;
        max-width: 600px;
    }

    .search-input {
        width: 100%;
        height: 52px;
        padding: 0 1.5rem 0 3rem;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 30px;
        font-size: 1.05rem;
        color: #0f172a;
        outline: none;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }

    .search-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 1.1rem;
        pointer-events: none;
    }

    .filter-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
    }

    .filter-pill {
        border: none;
        background: #f1f5f9;
        color: #475569;
        padding: 0.5rem 1.25rem;
        border-radius: 30px;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        outline: none;
    }

    .filter-pill:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .filter-pill.active {
        background: #0f172a;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
    }

    /* ---------- Accordion Wrapper ---------- */
    .faq-container {
        max-width: 850px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .faq-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.01), 0 8px 24px rgba(0, 0, 0, 0.02);
        transition: all 0.2s ease;
    }

    .faq-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    }

    .faq-trigger {
        width: 100%;
        padding: 1.5rem;
        background: transparent;
        border: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        text-align: left;
        cursor: pointer;
        outline: none;
    }

    .faq-question {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.4;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .faq-question .q-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #eff6ff;
        color: #1e40af;
        font-size: 0.85rem;
        font-weight: 800;
        flex-shrink: 0;
    }

    .faq-icon-indicator {
        font-size: 1rem;
        color: #64748b;
        transition: transform 0.3s ease;
        flex-shrink: 0;
    }

    .faq-item.expanded {
        border-left: 4px solid #3b82f6; /* Vibrant Blue Accent on Active FAQ */
    }

    .faq-item.expanded .faq-trigger {
        background: #eff6ff; /* Soft Blue Active Background */
    }

    .faq-item.expanded .faq-question {
        color: #1e40af;
    }

    .faq-item.expanded .faq-icon-indicator {
        transform: rotate(180deg);
        color: #1e40af;
    }

    .faq-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
        background: #ffffff;
    }

    .faq-body {
        padding: 1.5rem 1.5rem 1.75rem 3.25rem;
        font-size: 1.05rem;
        font-weight: 600;
        color: #475569;
        line-height: 1.8;
        border-top: 1px solid #f1f5f9;
    }

    /* ---------- Responsive ---------- */
    @media (max-width: 767.98px) {
        .faq-title {
            font-size: 2.2rem;
        }
        .faq-question {
            font-size: 1.05rem;
        }
        .faq-body {
            padding: 1.25rem;
            font-size: 0.98rem;
        }
    }
</style>
@endpush

@section('content')

    @include('theme::partials.hero_banner', [
        'title' => 'প্রশ্নোত্তর ও জিজ্ঞাসা',
        'subtitle' => 'হেযবুত তওহীদ সম্পর্কে সাধারণ কিছু প্রশ্ন ও তার দাপ্তরিক উত্তরসমূহ।',
        'badge_text' => 'এফএকিউ',
        'badge_icon' => 'fas fa-question-circle'
    ])

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            
            <div class="faq-header">
                <h1 class="faq-title">সাধারণ <span class="highlight">জিজ্ঞাসা</span> ও প্রশ্নোত্তর</h1>
                <p class="faq-subtitle">
                    আন্দোলনের পরিচিতি, উদ্দেশ্য ও বিভিন্ন বিষয়ে সাধারণ মানুষের জিজ্ঞাসাগুলোর দাপ্তরিক ও যুক্তিপূর্ণ উত্তর।
                </p>
            </div>

            <!-- Smart Controls (Search & Filter Pills) -->
            <div class="faq-controls">
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="faq-search" class="search-input" placeholder="যেকোনো প্রশ্ন বা জিজ্ঞাসা খুঁজুন..." oninput="filterFaqs()">
                </div>
                <div class="filter-group">
                    <button class="filter-pill active" onclick="setFaqFilter('all')">সব প্রশ্ন</button>
                    <button class="filter-pill" onclick="setFaqFilter('org')">সাংগঠনিক কাঠামো</button>
                    <button class="filter-pill" onclick="setFaqFilter('ideology')">আদর্শ ও লক্ষ্য</button>
                    <button class="filter-pill" onclick="setFaqFilter('jihad')">উগ্রবাদ ও জেহাদ</button>
                    <button class="filter-pill" onclick="setFaqFilter('women')">নারী ও সমাজ</button>
                </div>
            </div>

            <!-- FAQ Accordion List -->
            <div class="faq-container">
                
                <!-- FAQ 1 -->
                <div class="faq-item" data-category="org">
                    <button class="faq-trigger" onclick="toggleFaq(this)">
                        <h3 class="faq-question">
                            <span class="q-num">১</span>
                            হেযবুত তওহীদ কী একটি ইসলামিক রাজনৈতিক দল?
                        </h3>
                        <i class="fas fa-chevron-down faq-icon-indicator"></i>
                    </button>
                    <div class="faq-content">
                        <div class="faq-body">
                            না, হেযবুত তওহীদ সম্পূর্ণ অরাজনীতিক সংস্কারমূলক সামাজিক আন্দোলন। এটি কোনো নির্বাচনে অংশ নেয় না এবং ক্ষমতার রাজনীতিতে বিশ্বাসী নয়। এর একমাত্র উদ্দেশ্য নৈতিক ও সামাজিক সংস্কার এবং সমাজে শান্তি ও সত্যের প্রতিষ্ঠা করা। ডানপন্থী-বামপন্থী বা ধর্মনিরপেক্ষ রাজনৈতিক দলের সাথে এর কোনো সম্পর্ক নেই।
                        </div>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="faq-item" data-category="org">
                    <button class="faq-trigger" onclick="toggleFaq(this)">
                        <h3 class="faq-question">
                            <span class="q-num">২</span>
                            হেযবুত তওহীদের আন্দোলন কার বিরুদ্ধে?
                        </h3>
                        <i class="fas fa-chevron-down faq-icon-indicator"></i>
                    </button>
                    <div class="faq-content">
                        <div class="faq-body">
                            আমাদের আন্দোলন অসত্যের বিরুদ্ধে, অন্যায়ের বিরুদ্ধে, ধর্মব্যবসার বিরুদ্ধে, জঙ্গিবাদের বিরুদ্ধে, সাম্প্রদায়িকতা ও ধর্মান্ধতার বিরুদ্ধে। আমরা কোনো মানুষের বা নির্দিষ্ট গোষ্ঠীর শত্রু নই; তবে আমরা সকল অনৈক্য ও কুসংস্কারের অবসান ঘটিয়ে সত্য ও ঐক্যের প্রচার করতে কাজ করে যাচ্ছি।
                        </div>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="faq-item" data-category="org">
                    <button class="faq-trigger" onclick="toggleFaq(this)">
                        <h3 class="faq-question">
                            <span class="q-num">৩</span>
                            আন্দোলনের আয়ের উৎস কী?
                        </h3>
                        <i class="fas fa-chevron-down faq-icon-indicator"></i>
                    </button>
                    <div class="faq-content">
                        <div class="faq-body">
                            আন্দোলনের সদস্যরা সম্পূর্ণ নিজস্ব অর্থায়নে এবং স্বেচ্ছাপ্রদত্ত দানের (এয়ানত) মাধ্যমে এর ব্যয়ভার বহন করেন। কোনো জাতীয় বা আন্তর্জাতিক কালো তহবিল বা রাজনৈতিক অনুদান গ্রহণ করা হয় না। এছাড়া আন্দোলনের নিজস্ব প্রকাশনা, বই ও পত্রিকা বিক্রয়ের আয়ের মাধ্যমে এর কর্মকাণ্ড পরিচালিত হয়।
                        </div>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="faq-item" data-category="ideology">
                    <button class="faq-trigger" onclick="toggleFaq(this)">
                        <h3 class="faq-question">
                            <span class="q-num">৪</span>
                            হেযবুত তওহীদের মূল লক্ষ্য ও উদ্দেশ্য কী?
                        </h3>
                        <i class="fas fa-chevron-down faq-icon-indicator"></i>
                    </button>
                    <div class="faq-content">
                        <div class="faq-body">
                            সমগ্র মানবজাতিকে এক স্রষ্টার হুকুমের অধীনে ঐক্যবদ্ধ করে অশান্তিময় সমাজকে শান্তিময় সমাজে রূপান্তরিত করা। ধর্মকে ব্যক্তিগত স্বার্থে ব্যবহার না করে, ধর্মব্যবসা, জঙ্গিবাদ ও সাম্প্রদায়িকতার অবসান ঘটিয়ে সত্যের ওপর ভিত্তি করে একটি বৈষম্যহীন সমাজ গঠন করা আমাদের প্রধান লক্ষ্য।
                        </div>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="faq-item" data-category="ideology">
                    <button class="faq-trigger" onclick="toggleFaq(this)">
                        <h3 class="faq-question">
                            <span class="q-num">৫</span>
                            হেযবুত তওহীদ কি প্রচলিত কোনো পীর-মুরিদী বা সুফিবাদ সমর্থন করে?
                        </h3>
                        <i class="fas fa-chevron-down faq-icon-indicator"></i>
                    </button>
                    <div class="faq-content">
                        <div class="faq-body">
                            না, হেযবুত তওহীদ কোনো নির্দিষ্ট তরিকাপন্থা বা পীর-মুরিদী ব্যবস্থার অংশ নয়। আমরা মুসলিম উম্মাহর শিয়া-সুন্নি বা বিভিন্ন তরিকতভিত্তিক অনৈক্যের বিপরীতে রসুলের দেখানো সামগ্রিক সামাজিক সংগ্রাম ও প্রকৃত ইসলামের সোনালী যুগের ঐক্যের পুনরুদ্ধারকেই অগ্রাধিকার দেই।
                        </div>
                    </div>
                </div>

                <!-- FAQ 6 -->
                <div class="faq-item" data-category="jihad">
                    <button class="faq-trigger" onclick="toggleFaq(this)">
                        <h3 class="faq-question">
                            <span class="q-num">৬</span>
                            জেহাদ ও উগ্রবাদের বিষয়ে হেযবুত তওহীদের অবস্থান কী?
                        </h3>
                        <i class="fas fa-chevron-down faq-icon-indicator"></i>
                    </button>
                    <div class="faq-content">
                        <div class="faq-body">
                            হেযবুত তওহীদ কোনো ধরণের সহিংসতা, বোমাবাজি বা সশস্ত্র চরমপন্থাকে সমর্থন করে না। জেহাদ অর্থ সত্য ও ন্যায় প্রতিষ্ঠার শান্তিময় আদর্শিক লড়াই। সশস্ত্র যুদ্ধ বা কেতাল কেবল একটি সার্বভৌম রাষ্ট্র করতে পারে, কোনো ব্যক্তি বা দল নিজের হাতে আইন তুলে নিয়ে তা করতে পারে না।
                        </div>
                    </div>
                </div>

                <!-- FAQ 7 -->
                <div class="faq-item" data-category="women">
                    <button class="faq-trigger" onclick="toggleFaq(this)">
                        <h3 class="faq-question">
                            <span class="q-num">৭</span>
                            ইসলামে পর্দা ও নারী অধিকারের ব্যাপারে আপনাদের নীতি কী?
                        </h3>
                        <i class="fas fa-chevron-down faq-icon-indicator"></i>
                    </button>
                    <div class="faq-content">
                        <div class="faq-body">
                            ইসলামে নারীদের মুখমণ্ডল ঢেকে পর্দা করার কোনো বাধ্যবাধকতা নেই। শালীনতা বজায় রেখে নারীরা সমাজ ও দেশ গঠনের সকল সামাজিক, দাপ্তরিক ও নেতৃত্বের কাজে সক্রিয়ভাবে অংশগ্রহণ করতে পারেন। রসুলাল্লাহর যুগেও নারী আসহাবগণ চিকিৎসা, কৃষি ও জ্ঞানচর্চাসহ সকল প্রকার সামাজিক কর্মকাণ্ডে অংশ নিতেন।
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

@endsection

@push('scripts')
<script>
    let currentFaqFilter = 'all';

    function setFaqFilter(cat) {
        currentFaqFilter = cat;
        
        // Update active class on pills
        document.querySelectorAll('.filter-pill').forEach(btn => {
            const isActive = btn.getAttribute('onclick').includes(`'${cat}'`);
            btn.classList.toggle('active', isActive);
        });

        filterFaqs();
    }

    function filterFaqs() {
        const query = document.getElementById('faq-search').value.toLowerCase().trim();
        const items = document.querySelectorAll('.faq-item');

        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            const category = item.getAttribute('data-category');

            const matchesFilter = (currentFaqFilter === 'all' || category === currentFaqFilter);
            const matchesSearch = (query === '' || text.includes(query));

            if (matchesFilter && matchesSearch) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function toggleFaq(btn) {
        const item = btn.closest('.faq-item');
        const content = item.querySelector('.faq-content');
        const isExpanded = item.classList.contains('expanded');
        
        // Close all other expanded items
        document.querySelectorAll('.faq-item.expanded').forEach(expItem => {
            if (expItem !== item) {
                expItem.classList.remove('expanded');
                expItem.querySelector('.faq-content').style.maxHeight = null;
            }
        });

        if (isExpanded) {
            item.classList.remove('expanded');
            content.style.maxHeight = null;
        } else {
            item.classList.add('expanded');
            content.style.maxHeight = content.scrollHeight + "px";
        }
    }
</script>
@endpush
