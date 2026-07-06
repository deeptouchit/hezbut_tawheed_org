@extends('admin.layouts.master')

@section('page-title', 'হোমপেজ বিল্ডার (এলিমেন্টর)')

@push('styles')
<style>
    /* Sticky Builder Sidebar */
    .builder-sidebar-card {
        position: -webkit-sticky;
        position: sticky;
        top: 20px;
        z-index: 10;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }
    
    .sidebar-header {
        background: #f8fafc;
        padding: 15px 20px;
        border-bottom: 1px solid #e2e8f0;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }
    
    .sidebar-content-scroll {
        max-height: 60vh;
        overflow-y: auto;
        padding: 20px;
    }
    
    /* Layout Preview Section */
    .builder-preview-card {
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }
    
    /* Sortable List Cards */
    .preview-card {
        background: #ffffff;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        padding: 18px 20px;
        margin-bottom: 15px;
        cursor: grab;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
        transition: all 0.25s ease;
    }
    
    .preview-card:hover {
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
    }
    
    .preview-card.active-edit {
        border-color: #006A4E !important;
        box-shadow: 0 0 0 4px rgba(0, 106, 78, 0.15);
    }
    
    .preview-card.inactive-section {
        opacity: 0.55;
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    
    .card-handle {
        color: #94a3b8;
        cursor: move;
        font-size: 1.1rem;
    }
    
    /* Elementor-Style Visual Previews */
    .card-visual-preview {
        border-radius: 6px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-top: 10px;
    }
    
    /* Elementor Tabs */
    .elementor-tabs {
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    
    .elementor-tab-link {
        font-family: 'Baloo Da 2', sans-serif;
        font-weight: 700;
        color: #64748b;
        padding: 12px 20px;
        border: none;
        background: transparent;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
        font-size: 0.95rem;
    }
    
    .elementor-tab-link:hover {
        color: #006A4E;
    }
    
    .elementor-tab-link.active {
        color: #006A4E;
        border-bottom-color: #006A4E;
    }
    
    /* Form controls styling */
    .form-group-builder {
        margin-bottom: 18px;
    }
    
    .form-group-builder label {
        font-weight: 600;
        font-size: 0.9rem;
        color: #334155;
        margin-bottom: 6px;
        display: block;
    }
    
    /* Spacing Slider Styles */
    .spacing-control {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .spacing-slider {
        flex-grow: 1;
        accent-color: #006A4E;
    }
    
    .spacing-value {
        width: 70px;
        text-align: center;
        font-weight: bold;
    }
    
    .preview-placeholder-text {
        text-align: center;
        color: #94a3b8;
        padding: 50px 20px;
        font-family: 'Baloo Da 2', sans-serif;
    }

    .bg-light-green {
        background-color: #e6f4ea !important;
    }

    .text-dark-green {
        color: #006A4E !important;
    }

    .x-small {
        font-size: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header Controls -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-7">
            <h3 class="fw-bold text-dark-green m-0"><i class="fas fa-cubes me-2"></i>হোমপেজ লেআউট ও সেকশন বিল্ডার (এলিমেন্টর)</h3>
            <p class="text-muted mb-0">ওয়ার্ডপ্রেস এলিমেন্টরের স্টাইলে সেকশন সাজান, কন্টেন্ট আপডেট করুন ও ডাইনামিক সিএসএস লিখুন।</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-secondary rounded-pill px-4 me-2">
                <i class="fas fa-external-link-alt me-1"></i>ওয়েবসাইট দেখুন
            </a>
            <button type="button" onclick="document.getElementById('builderForm').submit();" class="btn btn-success rounded-pill px-4 shadow-sm">
                <i class="fas fa-save me-1"></i>পরিবর্তন সংরক্ষণ করুন
            </button>
        </div>
    </div>

    <!-- Main Content Form -->
    <form id="builderForm" action="{{ route('admin.homepage-builder.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <!-- Left Side: Settings Panel (Sticky) -->
            <div class="col-lg-5 col-xl-4 mb-4">
                <div class="builder-sidebar-card">
                    <div class="sidebar-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0 fw-bold text-dark-green" id="activeSectionTitle">সেকশন এডিটর</h5>
                        <span class="badge bg-secondary rounded-pill" id="activeSectionIdBadge">কোনোটি সিলেক্ট করা নেই</span>
                    </div>
                    
                    <!-- Elementor Style Tabs -->
                    <div class="elementor-tabs d-flex justify-content-around">
                        <button type="button" class="elementor-tab-link active" onclick="switchTab('content')">
                            <i class="fas fa-edit me-1"></i>কন্টেন্ট
                        </button>
                        <button type="button" class="elementor-tab-link" onclick="switchTab('style')">
                            <i class="fas fa-palette me-1"></i>শৈলী ও রং
                        </button>
                        <button type="button" class="elementor-tab-link" onclick="switchTab('advanced')">
                            <i class="fas fa-cog me-1"></i>উন্নত
                        </button>
                    </div>
                    
                    <!-- Scrollable Settings Wrapper -->
                    <div class="sidebar-content-scroll" id="settingsContainer">
                        <!-- Placeholder when no section is selected -->
                        <div id="noSectionPlaceholder" class="preview-placeholder-text">
                            <i class="fas fa-mouse-pointer fa-3x mb-3 text-muted"></i>
                            <p class="fs-5 fw-bold mb-1">সেকশন সেটিংস</p>
                            <p class="small text-muted mb-0">ডান পাশের তালিকা থেকে যেকোনো সেকশনে ক্লিক করে তার কন্টেন্ট, শৈলী এবং স্পেসিংস এডিট করা শুরু করুন।</p>
                        </div>

                        <!-- Settings Wrappers per Section -->
                        @foreach($sections as $sec)
                            <div id="settings-wrapper-{{ $sec['id'] }}" class="section-settings-group d-none">
                                
                                <!-- TAB 1: CONTENT -->
                                <div class="tab-pane-elementor" data-tab-type="content">
                                    @if($sec['id'] === 'leaders_section')
                                        <!-- Founder Details -->
                                        <div class="card card-outline card-success mb-3 shadow-none border">
                                            <div class="card-header py-2"><h6 class="fw-bold mb-0 text-dark-green"><i class="fas fa-user-tie me-1"></i>প্রতিষ্ঠাতা এমামুযযামান</h6></div>
                                            <div class="card-body p-3">
                                                <div class="form-group-builder">
                                                    <label>প্রতিষ্ঠাতার নাম</label>
                                                    <input type="text" name="content[leaders_section][founder_name]" class="form-control" value="{{ $content['leaders_section']['founder_name'] ?? '' }}">
                                                </div>
                                                <div class="form-group-builder">
                                                    <label>পরিচিতি/জীবনী</label>
                                                    <textarea name="content[leaders_section][founder_bio]" class="form-control" rows="4">{{ $content['leaders_section']['founder_bio'] ?? '' }}</textarea>
                                                </div>
                                                <div class="form-group-builder">
                                                    <label>জীবনকাল ও উপাধি</label>
                                                    <input type="text" name="content[leaders_section][founder_designation]" class="form-control" value="{{ $content['leaders_section']['founder_designation'] ?? '' }}">
                                                </div>
                                                <div class="form-group-builder">
                                                    <label>প্রতিষ্ঠাতার ছবি</label>
                                                    @if(!empty($content['leaders_section']['founder_image']))
                                                        <div class="mb-2 position-relative d-inline-block">
                                                            <img src="{{ str_starts_with($content['leaders_section']['founder_image'], 'http') ? $content['leaders_section']['founder_image'] : asset($content['leaders_section']['founder_image']) }}" style="max-height: 80px;" class="rounded border">
                                                        </div>
                                                    @endif
                                                    <input type="file" name="content[leaders_section][founder_image]" class="form-control">
                                                </div>
                                                <div class="form-group-builder">
                                                    <label>বাটন টেক্সট</label>
                                                    <input type="text" name="content[leaders_section][founder_btn_text]" class="form-control" value="{{ $content['leaders_section']['founder_btn_text'] ?? '' }}">
                                                </div>
                                                <div class="form-group-builder">
                                                    <label>বাটন লিঙ্ক</label>
                                                    <input type="text" name="content[leaders_section][founder_btn_link]" class="form-control" value="{{ $content['leaders_section']['founder_btn_link'] ?? '' }}">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Current Emam Details -->
                                        <div class="card card-outline card-success mb-3 shadow-none border">
                                            <div class="card-header py-2"><h6 class="fw-bold mb-0 text-dark-green"><i class="fas fa-user me-1"></i>মাননীয় এমাম</h6></div>
                                            <div class="card-body p-3">
                                                <div class="form-group-builder">
                                                    <label>এমামের নাম</label>
                                                    <input type="text" name="content[leaders_section][emam_name]" class="form-control" value="{{ $content['leaders_section']['emam_name'] ?? '' }}">
                                                </div>
                                                <div class="form-group-builder">
                                                    <label>এমামের উক্তি/বক্তব্য</label>
                                                    <textarea name="content[leaders_section][emam_quote]" class="form-control" rows="4">{{ $content['leaders_section']['emam_quote'] ?? '' }}</textarea>
                                                </div>
                                                <div class="form-group-builder">
                                                    <label>পদবী</label>
                                                    <input type="text" name="content[leaders_section][emam_designation]" class="form-control" value="{{ $content['leaders_section']['emam_designation'] ?? '' }}">
                                                </div>
                                                <div class="form-group-builder">
                                                    <label>এমামের ছবি</label>
                                                    @if(!empty($content['leaders_section']['emam_image']))
                                                        <div class="mb-2 position-relative d-inline-block">
                                                            <img src="{{ str_starts_with($content['leaders_section']['emam_image'], 'http') ? $content['leaders_section']['emam_image'] : asset($content['leaders_section']['emam_image']) }}" style="max-height: 80px;" class="rounded border">
                                                        </div>
                                                    @endif
                                                    <input type="file" name="content[leaders_section][emam_image]" class="form-control">
                                                </div>
                                                <div class="form-group-builder">
                                                    <label>বাটন টেক্সট</label>
                                                    <input type="text" name="content[leaders_section][emam_btn_text]" class="form-control" value="{{ $content['leaders_section']['emam_btn_text'] ?? '' }}">
                                                </div>
                                                <div class="form-group-builder">
                                                    <label>বাটন লিঙ্ক</label>
                                                    <input type="text" name="content[leaders_section][emam_btn_link]" class="form-control" value="{{ $content['leaders_section']['emam_btn_link'] ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    @elseif(str_starts_with($sec['id'], 'custom_'))
                                        <!-- Custom HTML Content Editor -->
                                        <div class="form-group-builder">
                                            <label>সেকশনের নাম</label>
                                            <input type="text" name="layout[{{ $sec['id'] }}][name]" class="form-control" value="{{ $sec['name'] }}" oninput="updateCustomTitle('{{ $sec['id'] }}', this.value)">
                                        </div>
                                        <div class="form-group-builder">
                                            <label>কাস্টম এইচটিএমএল / টেক্সট কন্টেন্ট</label>
                                            <textarea name="content[{{ $sec['id'] }}][html_content]" class="form-control text-monospace" rows="10" placeholder="এখানে কাস্টম টেক্সট বা এইচটিএমএল কোড লিখুন...">{{ $content[$sec['id']]['html_content'] ?? '' }}</textarea>
                                        </div>
                                    @else
                                        <div class="text-center py-4 text-muted">
                                            <i class="fas fa-info-circle fa-2x mb-2 text-secondary"></i>
                                            <p class="small mb-0">এই সেকশনের কন্টেন্ট ডাটাবেজ মডিউল (স্লাইডার, বই, কার্যক্রম, কলাম) থেকে লোড হয়। এর অবস্থান, ব্যাকগ্রাউন্ড ও স্পেসিংস আপনি ডানে ও উপরে ট্যাবগুলোতে পরিবর্তন করতে পারবেন।</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- TAB 2: STYLE & BACKGROUND -->
                                <div class="tab-pane-elementor d-none" data-tab-type="style">
                                    <div class="form-group-builder">
                                        <label>ব্যাকগ্রাউন্ড রং (Background Color)</label>
                                        <input type="color" name="layout[{{ $sec['id'] }}][bg_color]" class="form-control form-control-color w-100" value="{{ $sec['bg_color'] ?? '#ffffff' }}" oninput="updateColorPreview('{{ $sec['id'] }}', this.value)">
                                    </div>
                                    <div class="form-group-builder">
                                        <label>ব্যাকগ্রাউন্ড ছবি (Background Image)</label>
                                        @if(!empty($sec['bg_image']))
                                            <div class="mb-2 position-relative d-inline-block w-100" id="bg-img-container-{{ $sec['id'] }}">
                                                <img src="{{ asset($sec['bg_image']) }}" style="max-height: 80px;" class="rounded border w-100">
                                                <input type="hidden" name="layout[{{ $sec['id'] }}][remove_bg_image]" id="remove-bg-image-{{ $sec['id'] }}" value="0">
                                                <button type="button" onclick="removeBgImage('{{ $sec['id'] }}')" class="btn btn-danger btn-xs position-absolute top-0 end-0 m-1">রিমুভ</button>
                                            </div>
                                        @endif
                                        <input type="file" name="layout[{{ $sec['id'] }}][bg_image]" class="form-control">
                                    </div>
                                </div>

                                <!-- TAB 3: ADVANCED (SPACING) -->
                                <div class="tab-pane-elementor d-none" data-tab-type="advanced">
                                    <!-- Padding Top -->
                                    <div class="form-group-builder">
                                        <label>উপরে স্পেসিং (Padding Top - px)</label>
                                        <div class="spacing-control">
                                            <input type="range" name="layout[{{ $sec['id'] }}][padding_top]" min="0" max="150" step="5" class="spacing-slider" value="{{ $sec['padding_top'] ?? 50 }}" oninput="updateSpacingPreview('{{ $sec['id'] }}', 'padding-top', this.value)">
                                            <input type="number" class="form-control spacing-value" value="{{ $sec['padding_top'] ?? 50 }}" readonly>
                                        </div>
                                    </div>
                                    <!-- Padding Bottom -->
                                    <div class="form-group-builder">
                                        <label>নিচে স্পেসিং (Padding Bottom - px)</label>
                                        <div class="spacing-control">
                                            <input type="range" name="layout[{{ $sec['id'] }}][padding_bottom]" min="0" max="150" step="5" class="spacing-slider" value="{{ $sec['padding_bottom'] ?? 50 }}" oninput="updateSpacingPreview('{{ $sec['id'] }}', 'padding-bottom', this.value)">
                                            <input type="number" class="form-control spacing-value" value="{{ $sec['padding_bottom'] ?? 50 }}" readonly>
                                        </div>
                                    </div>
                                    <!-- Margin Top -->
                                    <div class="form-group-builder">
                                        <label>বাইরের উপরের মার্জিন (Margin Top - px)</label>
                                        <div class="spacing-control">
                                            <input type="range" name="layout[{{ $sec['id'] }}][margin_top]" min="0" max="100" step="5" class="spacing-slider" value="{{ $sec['margin_top'] ?? 0 }}" oninput="updateSpacingPreview('{{ $sec['id'] }}', 'margin-top', this.value)">
                                            <input type="number" class="form-control spacing-value" value="{{ $sec['margin_top'] ?? 0 }}" readonly>
                                        </div>
                                    </div>
                                    <!-- Margin Bottom -->
                                    <div class="form-group-builder">
                                        <label>বাইরের নিচের মার্জিন (Margin Bottom - px)</label>
                                        <div class="spacing-control">
                                            <input type="range" name="layout[{{ $sec['id'] }}][margin_bottom]" min="0" max="100" step="5" class="spacing-slider" value="{{ $sec['margin_bottom'] ?? 0 }}" oninput="updateSpacingPreview('{{ $sec['id'] }}', 'margin-bottom', this.value)">
                                            <input type="number" class="form-control spacing-value" value="{{ $sec['margin_bottom'] ?? 0 }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Bottom Code Box for Custom CSS -->
                    <div class="card-footer bg-light p-3 border-top">
                        <h6 class="fw-bold mb-2 text-dark-green"><i class="fas fa-code me-1"></i>হোমপেজ কাস্টম সিএসএস (Custom CSS)</h6>
                        <textarea name="custom_css" class="form-control text-monospace" rows="5" placeholder="e.g. .section-title { font-size: 32px !important; }">{{ $customCss }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Side: Live Visual Layout & Reordering -->
            <div class="col-lg-7 col-xl-8 mb-4">
                <div class="builder-preview-card">
                    
                    <!-- Dynamic Header controls for Add/Remove Section -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                        <h5 class="fw-bold m-0 text-secondary"><i class="fas fa-sort me-1"></i>সেকশন লেআউট ও অবস্থান (ড্র্যাগ করুন)</h5>
                        
                        <div class="d-flex gap-2">
                            <select id="addDefaultSectionSelect" class="form-select form-select-sm rounded-pill" style="max-width: 220px; height: 32px; font-size: 0.85rem;">
                                <option value="">-- ডিফল্ট সেকশন যোগ করুন --</option>
                                <option value="hero_slider">হিরো স্লাইডার</option>
                                <option value="news_ticker">ব্রেকিং নিউজ টিকার</option>
                                <option value="about_section">আমাদের পরিচিতি</option>
                                <option value="leaders_section">আমাদের পথপ্রদর্শক ও নেতৃত্ব</option>
                                <option value="ideology_section">আমাদের আদর্শ ও স্তম্ভ</option>
                                <option value="activities_section">আমাদের কার্যক্রম</option>
                                <option value="publications_section">বই ও প্রকাশনা</option>
                                <option value="leadership_section">কেন্দ্রীয় নেতৃবৃন্দ</option>
                                <option value="testimonials_section">নাগরিক মতামত ও সুধী বাণী</option>
                                <option value="videos_section">ভিডিও গ্যালারি</option>
                            </select>
                            <button type="button" onclick="addSelectedSection()" class="btn btn-success btn-sm rounded-pill px-3 fw-bold"><i class="fas fa-plus me-1"></i>যোগ করুন</button>
                            <button type="button" onclick="addCustomSection()" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold"><i class="fas fa-plus me-1"></i>নতুন কাস্টম সেকশন</button>
                        </div>
                    </div>
                    
                    <!-- Sortable Sections List with Visual Mockups -->
                    <div id="sortableSections">
                        @foreach($sections as $sec)
                            <div class="preview-card {{ $sec['is_active'] ? '' : 'inactive-section' }}" 
                                 id="preview-card-{{ $sec['id'] }}" 
                                 onclick="selectSection('{{ $sec['id'] }}', '{{ $sec['name'] }}')"
                                 style="background-color: {{ $sec['bg_color'] }}; padding-top: {{ $sec['padding_top']/3 }}px; padding-bottom: {{ $sec['padding_bottom']/3 }}px; margin-top: {{ $sec['margin_top']/3 }}px; margin-bottom: {{ $sec['margin_bottom']/3 }}px;">
                                
                                <div class="d-flex align-items-center justify-content-between w-100">
                                    <div class="d-flex align-items-center">
                                        <span class="card-handle me-3"><i class="fas fa-grip-vertical"></i></span>
                                        <div class="card-info">
                                            <h6 class="fw-bold mb-0 text-dark-green" id="title-text-{{ $sec['id'] }}">{{ $sec['name'] }}</h6>
                                            <span class="x-small text-muted">সেকশন আইডি: {{ $sec['id'] }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="card-actions">
                                        <!-- Hidden Fields for deletion, order, and name -->
                                        <input type="hidden" name="layout[{{ $sec['id'] }}][order]" class="section-order-val" value="{{ $sec['order'] }}">
                                        <input type="hidden" name="layout[{{ $sec['id'] }}][name]" value="{{ $sec['name'] }}">
                                        <input type="hidden" name="layout[{{ $sec['id'] }}][is_deleted]" id="deleted-{{ $sec['id'] }}" value="0" class="section-deleted-val">
                                        
                                        <!-- Toggle Status -->
                                        <div class="form-check form-switch m-0 d-inline-block me-3">
                                            <input type="checkbox" name="layout[{{ $sec['id'] }}][is_active]" value="1" class="form-check-input" {{ $sec['is_active'] ? 'checked' : '' }} onchange="toggleSectionActive('{{ $sec['id'] }}', this.checked)">
                                            <label class="form-check-label x-small text-muted text-uppercase fw-bold">{{ $sec['is_active'] ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}</label>
                                        </div>

                                        <button type="button" onclick="deleteSection('{{ $sec['id'] }}', event)" class="btn btn-outline-danger btn-xs px-3 rounded-pill fw-bold">
                                            <i class="fas fa-trash-alt me-1"></i>মুছে ফেলুন
                                        </button>
                                    </div>
                                </div>

                                <!-- Dynamic Visual Mockup of the Section -->
                                @if($sec['id'] === 'hero_slider')
                                    <div class="card-visual-preview bg-dark text-white p-3 rounded text-center" style="background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.65)), url('https://images.unsplash.com/photo-1541872703-74c5e44368f9?q=80&width=600'); background-size: cover; height: 90px; display: flex; flex-direction: column; justify-content: center;">
                                        <h5 class="fw-bold mb-1 text-white" style="font-size: 1.1rem;">একটি অনন্য বিপ্লবী নেতৃত্ব</h5>
                                        <p class="x-small text-light mb-0">সর্বশেষ আপডেট ও হিরো স্লাইডার সেকশন</p>
                                    </div>
                                @elseif($sec['id'] === 'news_ticker')
                                    <div class="card-visual-preview text-white p-2 rounded d-flex align-items-center gap-2" style="background-color: #006A4E; font-size: 0.8rem;">
                                        <span class="badge bg-white text-dark-green fw-bold">ব্রেকিং নিউজ</span>
                                        <marquee class="m-0 text-white">আন্দোলনের সত্য প্রচার ও মানবতার কল্যাণে এগিয়ে আসুন। হেজবুত তওহীদের সাথেই থাকুন।</marquee>
                                    </div>
                                @elseif($sec['id'] === 'leaders_section')
                                    <div class="card-visual-preview bg-light p-3 rounded border">
                                        <div class="row g-2">
                                            <div class="col-6 border-end text-center">
                                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=150" class="rounded-circle mb-1 border" style="width: 40px; height: 40px; object-fit: cover;">
                                                <div class="x-small fw-bold text-dark-green">জনাব মোহাম্মদ বায়েজীদ খান পন্নী</div>
                                                <div class="x-small text-muted">প্রতিষ্ঠাতা এমামুযযামান</div>
                                            </div>
                                            <div class="col-6 text-center">
                                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=150" class="rounded-circle mb-1 border" style="width: 40px; height: 40px; object-fit: cover;">
                                                <div class="x-small fw-bold text-dark-green">জনাব হোসাইন মোহাম্মদ সেলিম</div>
                                                <div class="x-small text-muted">মাননীয় এমাম</div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($sec['id'] === 'ideology_section')
                                    <div class="card-visual-preview p-3 rounded text-center border" style="background-color: #f0fdf4;">
                                        <div class="d-flex justify-content-around gap-1 x-small text-dark-green fw-bold">
                                            <div><i class="fas fa-heart text-danger"></i> মানবতার কল্যাণ</div>
                                            <div><i class="fas fa-book-open text-success"></i> সত্য প্রকাশ</div>
                                            <div><i class="fas fa-shield-alt text-primary"></i> জঙ্গিবাদ প্রতিরোধ</div>
                                        </div>
                                    </div>
                                @elseif($sec['id'] === 'about_section')
                                    <div class="card-visual-preview bg-white p-3 rounded border">
                                        <div class="row g-2 text-center text-dark-green x-small fw-bold">
                                            <div class="col-3"><i class="fas fa-users-gear"></i><div>যুব বিভাগ</div></div>
                                            <div class="col-3"><i class="fas fa-venus"></i><div>নারী বিভাগ</div></div>
                                            <div class="col-3"><i class="fas fa-hands-helping"></i><div>স্বেচ্ছাসেবক</div></div>
                                            <div class="col-3"><i class="fas fa-bullhorn"></i><div>প্রচার উইং</div></div>
                                        </div>
                                    </div>
                                @elseif($sec['id'] === 'activities_section')
                                    <div class="card-visual-preview p-3 rounded text-center text-white" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&width=300'); background-size: cover; height: 80px; display: flex; flex-direction: column; justify-content: center;">
                                        <div class="small fw-bold">সেবামূলক ও সচেতনতা কার্যক্রম</div>
                                        <span class="x-small opacity-75">রক্তদান কর্মসূচি, ত্রাণ ও চিকিৎসা সেবা</span>
                                    </div>
                                @elseif($sec['id'] === 'publications_section')
                                    <div class="card-visual-preview bg-white p-3 rounded border text-center">
                                        <span class="small fw-bold text-dark-green"><i class="fas fa-book-open"></i> প্রকাশিত বই ও রিসোর্স আর্কাইভ</span>
                                        <div class="d-flex justify-content-center gap-2 mt-2 x-small">
                                            <span class="badge bg-light text-dark border">এ ইসলাম ইসলামই নয়</span>
                                            <span class="badge bg-light text-dark border">গঠনতন্ত্র পিডিএফ</span>
                                        </div>
                                    </div>
                                @elseif($sec['id'] === 'leadership_section')
                                    <div class="card-visual-preview bg-light p-3 rounded border text-center">
                                        <span class="small fw-bold text-dark-green"><i class="fas fa-newspaper me-1"></i>মিডিয়া সেন্টার (সর্বশেষ সংবাদ ও সাম্প্রতিক অনুষ্ঠান)</span>
                                    </div>
                                @elseif($sec['id'] === 'testimonials_section')
                                    <div class="card-visual-preview bg-dark-green text-white p-3 rounded text-center" style="background-color: #006A4E; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                        <i class="fas fa-quote-left text-white opacity-50"></i>
                                        <span class="x-small text-white">নাগরিক মতামত ও সুধী সমাজের বাণীসমূহ</span>
                                    </div>
                                @elseif($sec['id'] === 'videos_section')
                                    <div class="card-visual-preview bg-dark text-white p-3 rounded text-center" style="background-color: #1e293b; height: 80px; display: flex; flex-direction: column; justify-content: center;">
                                        <i class="fas fa-play-circle fa-lg text-danger mb-1"></i>
                                        <div class="x-small">ভিডিও গ্যালারি ও পরামর্শ মতামত ফরম</div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Hidden Container to hold deleted default sections inputs so their deletion flag gets submitted -->
        <div id="deletedSectionsContainer" class="d-none"></div>
    </form>
</div>
@endsection

@push('scripts')
<!-- Load SortableJS for Drag and Drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    let activeSectionId = null;
    let currentTab = 'content';

    $(document).ready(function() {
        // Initialize SortableJS
        const el = document.getElementById('sortableSections');
        const sortable = new Sortable(el, {
            handle: '.card-handle',
            animation: 200,
            ghostClass: 'bg-light',
            onEnd: function() {
                // Update sort orders when dragging finishes
                updateOrders();
            }
        });
    });

    // Select a Section to edit
    function selectSection(id, name) {
        activeSectionId = id;
        
        // Remove active borders from all preview cards, add to this one
        $('.preview-card').removeClass('active-edit');
        $('#preview-card-' + id).addClass('active-edit');
        
        // Update Title & Badge
        $('#activeSectionTitle').text(name);
        $('#activeSectionIdBadge').text(id);
        
        // Hide placeholder, show settings containers
        $('#noSectionPlaceholder').addClass('d-none');
        $('.section-settings-group').addClass('d-none');
        $('#settings-wrapper-' + id).removeClass('d-none');
        
        // Reset tab view
        switchTab(currentTab);
    }

    // Switch Tabs (Content, Style, Advanced)
    function switchTab(tab) {
        currentTab = tab;
        $('.elementor-tab-link').removeClass('active');
        // Add active style to selected tab button
        $(`button[onclick="switchTab('${tab}')"]`).addClass('active');

        if (!activeSectionId) return;

        // Hide all tabs fields inside active wrapper
        const activeWrapper = $('#settings-wrapper-' + activeSectionId);
        activeWrapper.find('.tab-pane-elementor').addClass('d-none');
        
        // Show selected tab fields
        activeWrapper.find(`[data-tab-type="${tab}"]`).removeClass('d-none');
    }

    // Update orders in hidden fields
    function updateOrders() {
        $('#sortableSections .preview-card').each(function(index) {
            const indexOrder = index + 1;
            $(this).find('.section-order-val').val(indexOrder);
        });
    }

    // Toggle Active State
    function toggleSectionActive(id, isChecked) {
        const card = $('#preview-card-' + id);
        const label = card.find('.form-check-label');
        if (isChecked) {
            card.removeClass('inactive-section');
            label.text('সক্রিয়');
        } else {
            card.addClass('inactive-section');
            label.text('নিষ্ক্রিয়');
        }
    }

    // Real-time Spacing Preview (Padding/Margin)
    function updateSpacingPreview(id, property, val) {
        // Find input value container and update display number
        const wrapper = $('#settings-wrapper-' + id);
        const group = wrapper.find(`[oninput*="'${property}'"]`).closest('.spacing-control');
        group.find('.spacing-value').val(val);
        
        // Apply styling to the preview card on the right (divided by 3 for visual scaling)
        const card = $('#preview-card-' + id);
        const visualVal = val / 3;
        card.css(property, visualVal + 'px');
    }

    // Real-time Color Preview
    function updateColorPreview(id, color) {
        const card = $('#preview-card-' + id);
        card.css('background-color', color);
    }

    // Update custom section title in real-time
    function updateCustomTitle(id, val) {
        $('#title-text-' + id).text(val);
        if (activeSectionId === id) {
            $('#activeSectionTitle').text(val);
        }
        // Also update hidden name input
        $(`input[name="layout[${id}][name]"]`).val(val);
    }

    // Remove background image
    function removeBgImage(id) {
        $('#remove-bg-image-' + id).val('1');
        $('#bg-img-container-' + id).hide();
    }

    // Delete/Remove a Section
    function deleteSection(id, event) {
        event.stopPropagation(); // Prevent select click trigger
        
        if (confirm('আপনি কি নিশ্চিতভাবে এই সেকশনটি মুছে ফেলতে চান?')) {
            if (id.startsWith('custom_')) {
                // Remove custom section elements completely from DOM
                $('#preview-card-' + id).remove();
                $('#settings-wrapper-' + id).remove();
            } else {
                // For default sections, mark as deleted and move to hidden container
                const card = $('#preview-card-' + id);
                card.addClass('d-none');
                $('#deleted-' + id).val('1');
                
                // Move it to hidden container to keep inputs for form submit
                card.appendTo('#deletedSectionsContainer');
            }
            
            updateOrders();
            
            // Clear editor preview if active
            if (activeSectionId === id) {
                activeSectionId = null;
                $('#noSectionPlaceholder').removeClass('d-none');
                $('.section-settings-group').addClass('d-none');
                $('#activeSectionTitle').text('সেকশন এডিটর');
                $('#activeSectionIdBadge').text('কোনোটি সিলেক্ট করা নেই');
            }
        }
    }

    // Add back a deleted default section
    function addSelectedSection() {
        const id = $('#addDefaultSectionSelect').val();
        if (!id) {
            alert('দয়া করে একটি ডিফল্ট সেকশন সিলেক্ট করুন!');
            return;
        }
        
        // Find it in the deleted sections container
        const card = $('#deleted-' + id).closest('.preview-card');
        if (card.length > 0) {
            card.removeClass('d-none');
            $('#deleted-' + id).val('0');
            // Move back to active sections list
            card.appendTo('#sortableSections');
            updateOrders();
            selectSection(id, card.find('.card-info h6').text());
        } else {
            // Already in active sections list
            const activeCard = $('#preview-card-' + id);
            if (activeCard.length > 0 && !activeCard.hasClass('d-none')) {
                alert('এই সেকশনটি ইতিমধ্যে লেআউটে যোগ করা আছে!');
            } else {
                alert('সেকশনটি লোড করা সম্ভব হয়নি!');
            }
        }
        
        $('#addDefaultSectionSelect').val('');
    }

    // Add a new Custom HTML section dynamically
    function addCustomSection() {
        const id = 'custom_' + Date.now();
        const defaultName = 'নতুন কাস্টম সেকশন';
        
        // 1. Create Preview Card
        const cardHtml = `
            <div class="preview-card" 
                 id="preview-card-${id}" 
                 onclick="selectSection('${id}', '${defaultName}')"
                 style="background-color: #ffffff; padding-top: 16px; padding-bottom: 16px; margin-top: 0px; margin-bottom: 0px;">
                
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center">
                        <span class="card-handle me-3"><i class="fas fa-grip-vertical"></i></span>
                        <div class="card-info">
                            <h6 class="fw-bold mb-0 text-dark-green" id="title-text-${id}">${defaultName}</h6>
                            <span class="x-small text-muted">সেকশন আইডি: ${id}</span>
                        </div>
                    </div>
                    
                    <div class="card-actions">
                        <input type="hidden" name="layout[${id}][order]" class="section-order-val" value="99">
                        <input type="hidden" name="layout[${id}][name]" value="${defaultName}">
                        <input type="hidden" name="layout[${id}][is_deleted]" id="deleted-${id}" value="0" class="section-deleted-val">
                        
                        <div class="form-check form-switch m-0 d-inline-block me-3">
                            <input type="checkbox" name="layout[${id}][is_active]" value="1" class="form-check-input" checked onchange="toggleSectionActive('${id}', this.checked)">
                            <label class="form-check-label x-small text-muted text-uppercase fw-bold">সক্রিয়</label>
                        </div>

                        <button type="button" onclick="deleteSection('${id}', event)" class="btn btn-outline-danger btn-xs px-3 rounded-pill fw-bold">
                            <i class="fas fa-trash-alt me-1"></i>মুছে ফেলুন
                        </button>
                    </div>
                </div>

                <div class="card-visual-preview bg-white p-3 rounded border text-center mt-2">
                    <i class="fas fa-code fa-lg text-secondary mb-1"></i>
                    <div class="x-small">কাস্টম টেক্সট / এইচটিএমএল সেকশন (এডিটেবল)</div>
                </div>
            </div>
        `;
        
        // Append preview card
        $('#sortableSections').append(cardHtml);
        
        // 2. Create Settings Wrapper
        const settingsHtml = `
            <div id="settings-wrapper-${id}" class="section-settings-group d-none">
                <!-- TAB 1: CONTENT -->
                <div class="tab-pane-elementor" data-tab-type="content">
                    <div class="form-group-builder">
                        <label>সেকশনের নাম</label>
                        <input type="text" name="layout[${id}][name]" class="form-control" value="${defaultName}" oninput="updateCustomTitle('${id}', this.value)">
                    </div>
                    <div class="form-group-builder">
                        <label>কাস্টম এইচটিএমএল / টেক্সট কন্টেন্ট</label>
                        <textarea name="content[${id}][html_content]" class="form-control text-monospace" rows="10" placeholder="এখানে কাস্টম টেক্সট বা এইচটিএমএল কোড লিখুন..."></textarea>
                    </div>
                </div>

                <!-- TAB 2: STYLE & BACKGROUND -->
                <div class="tab-pane-elementor d-none" data-tab-type="style">
                    <div class="form-group-builder">
                        <label>ব্যাকগ্রাউন্ড রং (Background Color)</label>
                        <input type="color" name="layout[${id}][bg_color]" class="form-control form-control-color w-100" value="#ffffff" oninput="updateColorPreview('${id}', this.value)">
                    </div>
                    <div class="form-group-builder">
                        <label>ব্যাকগ্রাউন্ড ছবি (Background Image)</label>
                        <input type="file" name="layout[${id}][bg_image]" class="form-control">
                    </div>
                </div>

                <!-- TAB 3: ADVANCED (SPACING) -->
                <div class="tab-pane-elementor d-none" data-tab-type="advanced">
                    <div class="form-group-builder">
                        <label>উপরে স্পেসিং (Padding Top - px)</label>
                        <div class="spacing-control">
                            <input type="range" name="layout[${id}][padding_top]" min="0" max="150" step="5" class="spacing-slider" value="50" oninput="updateSpacingPreview('${id}', 'padding-top', this.value)">
                            <input type="number" class="form-control spacing-value" value="50" readonly>
                        </div>
                    </div>
                    <div class="form-group-builder">
                        <label>নিচে স্পেসিং (Padding Bottom - px)</label>
                        <div class="spacing-control">
                            <input type="range" name="layout[${id}][padding_bottom]" min="0" max="150" step="5" class="spacing-slider" value="50" oninput="updateSpacingPreview('${id}', 'padding-bottom', this.value)">
                            <input type="number" class="form-control spacing-value" value="50" readonly>
                        </div>
                    </div>
                    <div class="form-group-builder">
                        <label>বাইরের উপরের মার্জিন (Margin Top - px)</label>
                        <div class="spacing-control">
                            <input type="range" name="layout[${id}][margin_top]" min="0" max="100" step="5" class="spacing-slider" value="0" oninput="updateSpacingPreview('${id}', 'margin-top', this.value)">
                            <input type="number" class="form-control spacing-value" value="0" readonly>
                        </div>
                    </div>
                    <div class="form-group-builder">
                        <label>বাইরের নিচের মার্জিন (Margin Bottom - px)</label>
                        <div class="spacing-control">
                            <input type="range" name="layout[${id}][margin_bottom]" min="0" max="100" step="5" class="spacing-slider" value="0" oninput="updateSpacingPreview('${id}', 'margin-bottom', this.value)">
                            <input type="number" class="form-control spacing-value" value="0" readonly>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Append settings wrapper
        $('#settingsContainer').append(settingsHtml);
        
        updateOrders();
        
        // Highlight and edit new section
        selectSection(id, defaultName);
    }
</script>
@endpush
