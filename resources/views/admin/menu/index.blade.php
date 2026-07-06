@extends('admin.layouts.master')

@section('page-title', 'মেনু ম্যানেজার')

@push('styles')
<style>
    /* ==========================================================================
       Modern WordPress-Style Menu Editor Styles
       ========================================================================== */
    .menu-container {
        display: flex;
        gap: 30px;
        margin-top: 20px;
    }
    
    .menu-sidebar {
        flex: 0 0 350px;
        width: 350px;
    }
    
    .menu-editor-panel {
        flex: 1;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
    }

    /* Accordion Custom Styling */
    .accordion-item-custom {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    
    .accordion-header-custom {
        padding: 14px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        font-weight: 600;
        color: #1e293b;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        user-select: none;
        transition: background 0.2s;
    }
    
    .accordion-header-custom:hover {
        background: #f1f5f9;
    }
    
    .accordion-header-custom i.chevron {
        transition: transform 0.2s;
        color: #64748b;
    }
    
    .accordion-header-custom.active i.chevron {
        transform: rotate(180deg);
    }
    
    .accordion-content-custom {
        padding: 16px;
        display: none;
        border-top: none;
    }

    /* Search inputs inside accordions */
    .accordion-search {
        position: relative;
        margin-bottom: 12px;
    }
    
    .accordion-search input {
        width: 100%;
        padding: 8px 12px 8px 32px;
        font-size: 13px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        outline: none;
        transition: border-color 0.2s;
    }
    
    .accordion-search input:focus {
        border-color: #017e3d;
    }
    
    .accordion-search i {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 12px;
    }

    /* Checkbox list wrapper */
    .checkbox-list-scroll {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px;
        background: #fafafa;
        margin-bottom: 12px;
    }
    
    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 4px;
        font-size: 13px;
        color: #334155;
        cursor: pointer;
        user-select: none;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .checkbox-item:last-child {
        border-bottom: none;
    }
    
    .checkbox-item input[type="checkbox"] {
        accent-color: #017e3d;
        cursor: pointer;
    }

    /* Selection top area */
    .menu-selection-bar {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .menu-selection-bar label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 0;
        margin-right: 10px;
    }

    /* Menu Drag List Items */
    .sortable-container {
        min-height: 150px;
        padding: 10px 0;
    }
    
    .menu-item-card {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        margin-bottom: 10px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: border-color 0.2s, box-shadow 0.2s, margin-left 0.2s ease;
    }
    
    /* Nested Menu Item Styling */
    .menu-item-card.nested-item {
        margin-left: 35px !important;
        border-left: 4px solid #017e3d !important;
        background: #fcfdfc;
    }
    
    .menu-item-card.nested-item .menu-item-bar {
        background: #f1fbf7;
    }
    
    .nesting-indicator {
        color: #017e3d;
        font-weight: bold;
        font-size: 13px;
        margin-right: 2px;
    }
    
    .indent-action-btn {
        background: none;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        color: #64748b;
        cursor: pointer;
        padding: 2px 6px;
        font-size: 11px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .indent-action-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    
    .menu-item-card:hover {
        border-color: #94a3b8;
    }
    
    .menu-item-card.ui-sortable-helper {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border-color: #017e3d;
        background: #f8fafc;
    }
    
    .menu-item-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        cursor: move;
        background: #f8fafc;
        border-radius: 6px;
        user-select: none;
    }
    
    .menu-item-bar-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .menu-item-drag-handle {
        color: #94a3b8;
        cursor: move;
        font-size: 14px;
    }
    
    .menu-item-title-text {
        font-weight: 600;
        color: #1e293b;
        font-size: 14px;
    }
    
    .menu-item-bar-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .menu-item-type-badge {
        font-size: 11px;
        font-weight: 500;
        background: #e2e8f0;
        color: #475569;
        padding: 2px 8px;
        border-radius: 100px;
        text-transform: uppercase;
        font-family: monospace;
    }
    
    .menu-item-toggle-btn {
        background: none;
        border: none;
        outline: none;
        color: #64748b;
        cursor: pointer;
        padding: 4px;
        font-size: 13px;
        transition: transform 0.2s, color 0.2s;
    }
    
    .menu-item-toggle-btn:hover {
        color: #0f172a;
    }
    
    .menu-item-card.expanded .menu-item-toggle-btn {
        transform: rotate(180deg);
        color: #017e3d;
    }
    
    .menu-item-card.expanded .menu-item-bar {
        border-bottom: 1px solid #cbd5e1;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }
    
    .menu-item-settings {
        padding: 16px 20px;
        background: #ffffff;
        border-bottom-left-radius: 6px;
        border-bottom-right-radius: 6px;
        display: none;
        border-top: none;
    }
    
    .menu-item-settings label {
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 5px;
    }
    
    .menu-item-settings input {
        font-size: 13px;
        border-radius: 6px;
    }
    
    .menu-item-settings input:focus {
        border-color: #017e3d;
        box-shadow: 0 0 0 2px rgba(1, 126, 61, 0.1);
    }
    
    .menu-item-settings-footer {
        margin-top: 15px;
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        font-size: 13px;
    }
    
    .menu-item-remove-link {
        color: #ef4444;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
        cursor: pointer;
    }
    
    .menu-item-remove-link:hover {
        color: #b91c1c;
        text-decoration: underline;
    }
    
    .menu-item-cancel-link {
        color: #64748b;
        text-decoration: none;
        font-weight: 500;
        margin-left: 12px;
        cursor: pointer;
    }
    
    .menu-item-cancel-link:hover {
        color: #334155;
        text-decoration: underline;
    }

    /* Placeholder style when dragging */
    .menu-item-placeholder {
        border: 2px dashed #017e3d !important;
        background: #f0fdf4 !important;
        height: 48px;
        margin-bottom: 10px;
        border-radius: 6px;
    }

    /* Panel Header & Footer */
    .panel-header-custom {
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        background: #ffffff;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .panel-header-title {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    
    .panel-body-custom {
        padding: 20px;
        flex-grow: 1;
        min-height: 350px;
    }
    
    .panel-footer-custom {
        padding: 16px 20px;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Alert Empty State */
    .menu-empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        margin: 15px 0;
    }
    
    .menu-empty-state i {
        font-size: 32px;
        margin-bottom: 10px;
        color: #cbd5e1;
    }

    /* Scrollbar style */
    .checkbox-list-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .checkbox-list-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    .checkbox-list-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .checkbox-list-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Hot badge on frontend */
    .menu-item-featured-badge {
        font-size: 10px;
        background-color: #ef4444;
        color: white;
        padding: 1px 5px;
        border-radius: 4px;
        font-weight: bold;
        margin-left: 5px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- ==========================================================================
       ১. মেনু সেকশন সিলেক্টর বার (WordPress-Style Selection Bar)
       ========================================================================== --}}
    <div class="menu-selection-bar shadow-sm">
        <div class="d-flex align-items-center">
            <label for="menuSelect"><i class="fas fa-edit text-success me-1"></i> সম্পাদনার জন্য মেনু নির্বাচন করুন:</label>
            <select id="menuSelect" class="form-select form-select-sm d-inline-block w-auto" style="min-width: 250px;">
                <option value="desktop_nav" selected>ডেস্কটপ নেভিগেশন মেনু (Desktop Navigation)</option>
                <option value="mobile_nav">মোবাইল নেভিগেশন মেনু (Mobile Navigation)</option>
                <option value="topbar_left">টপবার - বাম দিক (Topbar Left)</option>
                <option value="topbar_right">টপবার - ডান দিক (Topbar Right)</option>
                <option value="footer_quick_links">ফুটার - কুইক লিংক (Footer Quick Links)</option>
                <option value="footer_customer_service">ফুটার - কাস্টমার সার্ভিস (Footer Customer Service)</option>
                <option value="footer_about">ফুটার - আমাদের সম্পর্কে (Footer About)</option>
                <option value="footer_contact">ফুটার - যোগাযোগ তথ্য (Footer Contact)</option>
            </select>
            <button id="selectMenuBtn" class="btn btn-sm btn-primary ms-2 px-3">লোড করুন</button>
        </div>
        <div class="d-flex gap-2">
            <button id="refreshCacheBtn" class="btn btn-sm btn-warning">
                <i class="fas fa-sync-alt"></i> ক্যাশ রিফ্রেশ
            </button>
            <a href="{{ route('admin.menu.preview') }}" class="btn btn-sm btn-info" target="_blank">
                <i class="fas fa-eye"></i> প্রিভিউ
            </a>
        </div>
    </div>

    {{-- ==========================================================================
       ২. মেইন এডিটর লেআউট (Sidebar + Main Panel)
       ========================================================================== --}}
    <div class="menu-container">
        
        {{-- ==========================
           Sidebar Panel (Add Items)
           ========================== --}}
        <div class="menu-sidebar">
            <h5 class="fw-bold mb-3 text-secondary" style="font-size: 15px;"><i class="fas fa-plus-circle text-success me-1"></i> মেনু আইটেম যোগ করুন</h5>
            
            {{-- Accordion 1: Pages --}}
            <div class="accordion-item-custom">
                <div class="accordion-header-custom">
                    <span><i class="fas fa-file-alt text-success me-2"></i> পৃষ্ঠাসমূহ (Pages)</span>
                    <i class="fas fa-chevron-down chevron"></i>
                </div>
                <div class="accordion-content-custom">
                    <div class="checkbox-list-scroll">
                        <label class="checkbox-item">
                            <input type="checkbox" class="page-checkbox" data-name="হোমপেজ" data-url="/" data-type="route" data-icon="fas fa-home">
                            হোমপেজ (/)
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" class="page-checkbox" data-name="আমাদের সম্পর্কে" data-url="about" data-type="route" data-icon="fas fa-info-circle">
                            আমাদের সম্পর্কে (/about)
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" class="page-checkbox" data-name="ব্লগ" data-url="blog" data-type="route" data-icon="fas fa-newspaper">
                            ব্লগ (/blog)
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" class="page-checkbox" data-name="যোগাযোগ" data-url="contact" data-type="route" data-icon="fas fa-envelope">
                            যোগাযোগ (/contact)
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" class="page-checkbox" data-name="প্রাইভেসি পলিসি" data-url="privacy" data-type="route" data-icon="fas fa-shield-alt">
                            প্রাইভেসি পলিসি (/privacy)
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" class="page-checkbox" data-name="শর্তাবলী" data-url="terms" data-type="route" data-icon="fas fa-gavel">
                            শর্তাবলী (/terms)
                        </label>
                    </div>
                    <button class="btn btn-xs btn-outline-success w-100 add-to-menu-btn" data-type="page">
                        <i class="fas fa-plus"></i> মেনুতে যোগ করুন
                    </button>
                </div>
            </div>
            
            {{-- Accordion 2: Categories --}}
            <div class="accordion-item-custom">
                <div class="accordion-header-custom">
                    <span><i class="fas fa-tags text-success me-2"></i> ব্লগ ক্যাটাগরিসমূহ (Blog Categories)</span>
                    <i class="fas fa-chevron-down chevron"></i>
                </div>
                <div class="accordion-content-custom">
                    <div class="accordion-search">
                        <i class="fas fa-search"></i>
                        <input type="text" class="category-search-input" placeholder="ক্যাটাগরি খুঁজুন...">
                    </div>
                    <div class="checkbox-list-scroll category-list-container">
                        @forelse($categories as $category)
                        <label class="checkbox-item category-item-row" data-search-name="{{ strtolower($category->name) }}">
                            <input type="checkbox" class="category-checkbox" 
                                   data-name="{{ $category->name }}" 
                                   data-url="blog.category" 
                                   data-type="route"
                                   data-icon="fas fa-tag"
                                   data-params='{"slug":"{{ $category->slug }}"}'>
                            {{ $category->name }}
                        </label>
                        @empty
                        <small class="text-muted d-block text-center py-2">কোন ক্যাটাগরি পাওয়া যায়নি</small>
                        @endforelse
                    </div>
                    <button class="btn btn-xs btn-outline-success w-100 add-to-menu-btn" data-type="category">
                        <i class="fas fa-plus"></i> মেনুতে যোগ করুন
                    </button>
                </div>
            </div>
            
            {{-- Accordion 4: Custom Links --}}
            <div class="accordion-item-custom">
                <div class="accordion-header-custom">
                    <span><i class="fas fa-link text-success me-2"></i> কাস্টম লিংক (Custom Link)</span>
                    <i class="fas fa-chevron-down chevron"></i>
                </div>
                <div class="accordion-content-custom">
                    <div class="mb-2">
                        <label class="form-label" style="font-size: 11px; font-weight: bold; margin-bottom: 2px;">ইউআরএল (URL):</label>
                        <input type="text" id="customUrl" class="form-control form-control-sm" placeholder="https://example.com বা /custom-path">
                    </div>
                    <div class="mb-2">
                        <label class="form-label" style="font-size: 11px; font-weight: bold; margin-bottom: 2px;">লিংক টেক্সট (Label):</label>
                        <input type="text" id="customLabel" class="form-control form-control-sm" placeholder="মেনু আইটেমের নাম">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 11px; font-weight: bold; margin-bottom: 2px;">আইকন ক্লাস (Icon):</label>
                        <input type="text" id="customIcon" class="form-control form-control-sm" placeholder="fas fa-link (ঐচ্ছিক)">
                    </div>
                    <button id="addCustomLinkBtn" class="btn btn-xs btn-outline-success w-100">
                        <i class="fas fa-plus"></i> মেনুতে যোগ করুন
                    </button>
                </div>
            </div>
        </div>

        {{-- ==========================
           Main Menu Editor Panel
           ========================== --}}
        <div class="menu-editor-panel shadow-sm">
            <div class="panel-header-custom">
                <h6 class="panel-header-title">
                    <i class="fas fa-stream text-success me-1"></i> মেনু স্ট্রাকচার: 
                    <span id="activeMenuLabel" class="text-success fw-bold">ডেস্কটপ নেভিগেশন মেনু</span>
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <button id="copyFromDesktopBtn" class="btn btn-xs btn-outline-success" style="display: none;">
                        <i class="fas fa-copy"></i> ডেস্কটপ থেকে কপি করুন
                    </button>
                    <small class="text-muted">পজিশন এডিট করতে ড্র্যাগ করুন</small>
                </div>
            </div>
            
            <div class="panel-body-custom">
                <p class="text-muted" style="font-size: 13px; border-left: 3px solid #017e3d; padding-left: 10px; margin-bottom: 20px;">
                    বাম পাশ থেকে আইটেম সিলেক্ট করে মেনুতে যুক্ত করুন। এরপর আইটেমগুলোকে উপরে-নিচে টেনে নিয়ে আপনার পছন্দমতো ক্রমানুসারে সাজান। আইটেমের ডান পাশের অ্যারো আইকনে ক্লিক করে আইকন ও নাম এডিট করতে পারবেন।
                </p>
                
                {{-- Empty State Alert --}}
                <div id="menuEmptyState" class="menu-empty-state" style="display: none;">
                    <i class="fas fa-folder-open"></i>
                    <p class="mb-0 fw-bold">এই মেনুতে কোনো আইটেম যুক্ত করা নেই।</p>
                    <small>বাম পাশের প্যানেল থেকে আইটেম যুক্ত করুন।</small>
                </div>

                {{-- Draggable Sorting Container --}}
                <div id="menuItemsList" class="sortable-container">
                    {{-- Dynamically loaded items will go here --}}
                </div>
            </div>
            
            <div class="panel-footer-custom">
                <button id="clearMenuBtn" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-trash-alt"></i> সব ক্লিয়ার করুন
                </button>
                <button id="saveMenuBtn" class="btn btn-sm btn-success px-4 fw-bold">
                    <i class="fas fa-save"></i> মেনু সংরক্ষণ করুন
                </button>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // ==========================================================================
    // State Variables & Initialization
    // ==========================================================================
    let isProcessing = false;
    let menuData = @json($menuData);
    let activeSection = 'desktop_nav';

    // Populate Parent Menu Dropdowns dynamically
    function populateParentDropdowns() {
        let allItems = [];
        $('#menuItemsList .menu-item-card').each(function() {
            let id = $(this).attr('id');
            let name = $(this).find('.item-label-input').val() || $(this).find('.menu-item-title-text').text();
            allItems.push({ id: id, name: name.trim() });
        });

        $('#menuItemsList .menu-item-card').each(function() {
            let cardId = $(this).attr('id');
            let select = $(this).find('.item-parent-select');
            let selectedVal = select.attr('data-selected') || select.val() || '';

            select.empty().append('<option value="">None (Top Level)</option>');

            allItems.forEach(function(item) {
                if (item.id !== cardId) {
                    let option = $('<option></option>').val(item.id).text(item.name);
                    if (item.id === selectedVal) {
                        option.attr('selected', 'selected');
                    }
                    select.append(option);
                }
            });
        });
    }
    
    // Toastr Setup
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": 3000
    };

    // Active accordion toggling
    $('.accordion-header-custom').click(function() {
        let content = $(this).next('.accordion-content-custom');
        let chevron = $(this).find('i.chevron');
        
        $('.accordion-content-custom').not(content).slideUp(200);
        $('.accordion-header-custom').not($(this)).removeClass('active');
        
        $(this).toggleClass('active');
        content.slideToggle(200);
    });

    // Expand first accordion default
    $('.accordion-header-custom').first().addClass('active').next('.accordion-content-custom').show();

    // ==========================================================================
    // Category & Brand Live Filter Searches
    // ==========================================================================
    $('.category-search-input').on('keyup', function() {
        let search = $(this).val().toLowerCase().trim();
        $('.category-item-row').each(function() {
            let name = $(this).data('search-name');
            if (name.indexOf(search) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('.brand-search-input').on('keyup', function() {
        let search = $(this).val().toLowerCase().trim();
        $('.brand-item-row').each(function() {
            let name = $(this).data('search-name');
            if (name.indexOf(search) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // ==========================================================================
    // Render Menu Items from JSON State
    // ==========================================================================
    function renderActiveMenu() {
        let listContainer = $('#menuItemsList');
        listContainer.empty();
        
        // Show/hide Copy from Desktop button for Mobile Nav
        if (activeSection === 'mobile_nav') {
            $('#copyFromDesktopBtn').show();
        } else {
            $('#copyFromDesktopBtn').hide();
        }

        let items = menuData[activeSection] || [];
        
        if (items.length === 0) {
            $('#menuEmptyState').show();
            return;
        } else {
            $('#menuEmptyState').hide();
        }

        // Group items by parent_id and sort them hierarchically to match tree traversal
        let rootItems = [];
        let childrenByParent = {};

        items.forEach(function(item) {
            let pId = item.parent_id;
            if (!pId) {
                rootItems.push(item);
            } else {
                if (!childrenByParent[pId]) {
                    childrenByParent[pId] = [];
                }
                childrenByParent[pId].push(item);
            }
        });

        // Sort root items by position
        rootItems.sort((a, b) => (a.position || 0) - (b.position || 0));

        // Sort children for each parent
        for (let pId in childrenByParent) {
            childrenByParent[pId].sort((a, b) => (a.position || 0) - (b.position || 0));
        }

        // Flatten the tree into flat list order (parent immediately followed by its sorted children)
        let sortedItems = [];
        rootItems.forEach(function(rootItem) {
            sortedItems.push(rootItem);
            let children = childrenByParent[rootItem.id] || [];
            children.forEach(function(child) {
                sortedItems.push(child);
            });
        });

        // Add any orphaned items (fallback safety)
        items.forEach(function(item) {
            if (!sortedItems.find(x => x.id === item.id)) {
                sortedItems.push(item);
            }
        });

        sortedItems.forEach(function(item) {
            let itemHtml = createMenuItemHtml(item);
            listContainer.append(itemHtml);
        });

        // Initialize sortable
        if ($.fn.sortable) {
            listContainer.sortable({
                handle: '.menu-item-bar',
                placeholder: 'menu-item-placeholder',
                cursor: 'move',
                opacity: 0.8,
                stop: function(event, ui) {
                    let containerLeft = $('#menuItemsList').offset().left;
                    let cardLeft = ui.item.offset().left;
                    let relativeLeft = cardLeft - containerLeft;
                    
                    // If dropped aligned to the left, remove nesting. If dropped shifted to the right, make it nested.
                    if (relativeLeft >= 20) {
                        let preceding = ui.item.prevAll('.menu-item-card:not(.nested-item)').first();
                        if (preceding.length > 0) {
                            ui.item.addClass('nested-item');
                        } else {
                            ui.item.removeClass('nested-item');
                        }
                    } else {
                        ui.item.removeClass('nested-item');
                    }
                    
                    updateMenuTreeStructure();
                }
            });
        }
        updateMenuTreeStructure();
    }

    // Update parent-child hierarchy dynamically based on UI nesting states
    function updateMenuTreeStructure() {
        let precedingLevel0Id = null;
        
        $('#menuItemsList .menu-item-card').each(function() {
            let card = $(this);
            let itemId = card.attr('id');
            let nestingIndicator = card.find('.nesting-indicator');
            let indentBtn = card.find('.indent-item-btn');
            let outdentBtn = card.find('.outdent-item-btn');
            let parentSelect = card.find('.item-parent-select');
            
            if (card.hasClass('nested-item')) {
                if (precedingLevel0Id) {
                    card.attr('data-parent-id', precedingLevel0Id);
                    parentSelect.val(precedingLevel0Id).attr('data-selected', precedingLevel0Id);
                    nestingIndicator.show();
                    indentBtn.hide();
                    outdentBtn.show();
                } else {
                    // Preceding item must exist to become child, else force root level
                    card.removeClass('nested-item');
                    card.attr('data-parent-id', '');
                    parentSelect.val('').attr('data-selected', '');
                    nestingIndicator.hide();
                    indentBtn.show();
                    outdentBtn.hide();
                }
            } else {
                precedingLevel0Id = itemId;
                card.attr('data-parent-id', '');
                parentSelect.val('').attr('data-selected', '');
                nestingIndicator.hide();
                indentBtn.show();
                outdentBtn.hide();
            }
        });
        
        // Re-populate dropdown lists to maintain dropdown sync with new positions
        populateParentDropdowns();
    }

    // Copy items from Desktop Navigation to Mobile Navigation
    $('#copyFromDesktopBtn').click(function(e) {
        e.preventDefault();
        
        // Gather current items dynamically from desktop sortable if currently rendered
        // otherwise fall back to original menuData
        let desktopItems = menuData['desktop_nav'] || [];
        
        if (desktopItems.length === 0) {
            toastr.warning('কপি করার জন্য ডেস্কটপ মেনুতে কোনো আইটেম নেই!');
            return;
        }

        Swal.fire({
            title: 'ডেস্কটপ মেনু থেকে কপি করবেন?',
            text: 'এর ফলে আপনার মোবাইল মেনুর বর্তমান সমস্ত আইটেম মুছে যাবে এবং ডেস্কটপ মেনুর আইটেমগুলো এখানে কপি হবে।',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#017e3d',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'হ্যাঁ, কপি করুন',
            cancelButtonText: 'বাতিল'
        }).then((result) => {
            if (result.isConfirmed) {
                let listContainer = $('#menuItemsList');
                listContainer.empty();
                
                desktopItems.forEach(function(item) {
                    // Clone item and assign new unique client-side ID to avoid clashes
                    let newItem = {...item};
                    newItem.id = 'menu_' + Date.now() + '_' + Math.floor(Math.random() * 10000);
                    
                    let itemHtml = createMenuItemHtml(newItem);
                    listContainer.append(itemHtml);
                });
                
                $('#menuEmptyState').hide();
                toastr.success('ডেস্কটপ মেনু থেকে সফলভাবে কপি করা হয়েছে! স্থায়ীভাবে সংরক্ষণ করতে "মেনু সংরক্ষণ করুন" বাটনটি ক্লিক করুন।');
                
                if ($.fn.sortable) {
                    listContainer.sortable("refresh");
                }
            }
        });
    });

    // Generate HTML for menu item bar
    function createMenuItemHtml(item) {
        let itemId = item.id || ('menu_' + Date.now() + '_' + Math.floor(Math.random() * 1000));
        let title = item.name || item.text || '';
        let type = item.type || 'url';
        let url = item.url || '';
        let icon = item.icon || '';
        let isFeatured = item.is_featured === true || item.is_featured === 'true';
        let parentId = item.parent_id || '';
        
        // Serialize params if they exist
        let paramsStr = '';
        if (item.params) {
            paramsStr = typeof item.params === 'string' ? item.params : JSON.stringify(item.params);
        }

        let isRoute = type === 'route';

        let isNested = parentId !== '';

        let html = `
        <div class="menu-item-card ${isNested ? 'nested-item' : ''}" id="${itemId}" data-type="${type}" data-params='${paramsStr}' data-parent-id="${parentId}">
            <div class="menu-item-bar">
                <div class="menu-item-bar-left">
                    <span class="nesting-indicator" style="display: ${isNested ? 'inline-block' : 'none'};"><i class="fas fa-level-up-alt fa-rotate-90"></i></span>
                    <span class="menu-item-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                    <i class="menu-item-icon ${icon || 'fas fa-link'} text-success"></i>
                    <span class="menu-item-title-text">${title}</span>
                    ${isFeatured ? '<span class="menu-item-featured-badge">Featured</span>' : ''}
                </div>
                <div class="menu-item-bar-right">
                    <!-- Indent and Outdent action buttons -->
                    <button class="indent-action-btn outdent-item-btn" title="বাম দিকে আনুন" style="display: ${isNested ? 'inline-flex' : 'none'}; margin-right: 4px;"><i class="fas fa-arrow-left"></i></button>
                    <button class="indent-action-btn indent-item-btn" title="ডান দিকে নিয়ে সাবমেনু করুন" style="display: ${!isNested ? 'inline-flex' : 'none'}; margin-right: 4px;"><i class="fas fa-arrow-right"></i></button>
                    
                    <span class="menu-item-type-badge">${type}</span>
                    <button class="menu-item-toggle-btn"><i class="fas fa-chevron-down"></i></button>
                </div>
            </div>
            
            <div class="menu-item-settings">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>মেনু লেবেল (Navigation Label)</label>
                        <input type="text" class="form-control form-control-sm item-label-input" value="${title}" placeholder="লিংক লেবেল">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>আইকন ক্লাস (Icon Class)</label>
                        <input type="text" class="form-control form-control-sm item-icon-input" value="${icon}" placeholder="যেমন: fas fa-tag">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>ইউআরএল / রাউট নাম (URL / Route Name)</label>
                        <input type="text" class="form-control form-control-sm item-url-input" value="${url}" ${type !== 'url' ? 'readonly style="background: #f1f5f9;"' : ''} placeholder="যেমন: /products বা products.index">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>প্যারেন্ট মেনু (Parent Menu)</label>
                        <select class="form-control form-control-sm item-parent-select" data-selected="${parentId}">
                            <option value="">None (Top Level)</option>
                        </select>
                    </div>
                </div>
                
                ${isRoute ? `
                <div class="row">
                    <div class="col-12 mb-3">
                        <label>রাউট প্যারামিটারস (Parameters - JSON)</label>
                        <input type="text" class="form-control form-control-sm item-params-input" value='${paramsStr}' readonly style="background: #f1f5f9;">
                    </div>
                </div>
                ` : ''}

                <div class="row">
                    <div class="col-12 mb-3 d-flex align-items-center">
                        <label class="d-flex align-items-center gap-2 cursor-pointer mb-0">
                            <input type="checkbox" class="item-featured-checkbox" ${isFeatured ? 'checked' : ''} style="accent-color: #017e3d; cursor: pointer;">
                            <span class="font-weight-normal" style="font-size: 13px;">মেনুতে হাইলাইট করুন (Featured / Hot badge)</span>
                        </label>
                    </div>
                </div>

                <div class="menu-item-settings-footer">
                    <a class="menu-item-remove-link"><i class="fas fa-trash-alt me-1"></i> মুছে ফেলুন</a>
                    <a class="menu-item-cancel-link">বাতিল</a>
                </div>
            </div>
        </div>
        `;
        return html;
    }

    // ==========================================================================
    // Accordion Actions (Adding Checked Items)
    // ==========================================================================
    $('.add-to-menu-btn').click(function() {
        let type = $(this).data('type');
        let checkboxes = [];
        
        if (type === 'page') {
            checkboxes = $('.page-checkbox:checked');
        } else if (type === 'category') {
            checkboxes = $('.category-checkbox:checked');
        } else if (type === 'brand') {
            checkboxes = $('.brand-checkbox:checked');
        }

        if (checkboxes.length === 0) {
            toastr.warning('দয়া করে অন্তত একটি আইটেম সিলেক্ট করুন');
            return;
        }

        checkboxes.each(function() {
            let name = $(this).data('name');
            let url = $(this).data('url');
            let itemType = $(this).data('type');
            let icon = $(this).data('icon');
            let params = $(this).data('params');

            let item = {
                id: 'menu_' + Date.now() + '_' + Math.floor(Math.random() * 10000),
                name: name,
                text: name, // support both keys
                url: url,
                type: itemType,
                icon: icon,
                params: params || null,
                is_featured: false
            };

            // Append item html and uncheck
            let itemHtml = createMenuItemHtml(item);
            $('#menuItemsList').append(itemHtml);
            $(this).prop('checked', false);
        });

        $('#menuEmptyState').hide();
        toastr.success('আইটেমগুলো মেনু তালিকায় যুক্ত করা হয়েছে');
        
        // Reinitialize sortable and update nesting dropdown values
        if ($.fn.sortable) {
            $('#menuItemsList').sortable("refresh");
        }
        updateMenuTreeStructure();
    });

    // Add Custom Link Button
    $('#addCustomLinkBtn').click(function() {
        let url = $('#customUrl').val().trim();
        let label = $('#customLabel').val().trim();
        let icon = $('#customIcon').val().trim();

        if (!url) {
            toastr.warning('দয়া করে ইউআরএল দিন');
            $('#customUrl').focus();
            return;
        }
        if (!label) {
            toastr.warning('দয়া করে লিংক লেবেল দিন');
            $('#customLabel').focus();
            return;
        }

        let item = {
            id: 'menu_' + Date.now() + '_' + Math.floor(Math.random() * 10000),
            name: label,
            text: label,
            url: url,
            type: 'url',
            icon: icon || 'fas fa-link',
            is_featured: false
        };

        let itemHtml = createMenuItemHtml(item);
        $('#menuItemsList').append(itemHtml);
        
        // Clear fields
        $('#customUrl').val('');
        $('#customLabel').val('');
        $('#customIcon').val('');

        $('#menuEmptyState').hide();
        toastr.success('কাস্টম লিংক মেনুতে যুক্ত করা হয়েছে');
        
        // Reinitialize sortable and update nesting dropdown values
        if ($.fn.sortable) {
            $('#menuItemsList').sortable("refresh");
        }
        updateMenuTreeStructure();
    });

    // ==========================================================================
    // Interactive Item Actions (Inside Right Panel list)
    // ==========================================================================
    
    // Toggle expand/collapse card settings
    $(document).on('click', '.menu-item-toggle-btn, .menu-item-title-text', function(e) {
        e.preventDefault();
        let card = $(this).closest('.menu-item-card');
        let settings = card.find('.menu-item-settings');
        
        if (!card.hasClass('expanded')) {
            populateParentDropdowns();
        }
        
        card.toggleClass('expanded');
        settings.slideToggle(200);
    });

    // Cancel link inside settings collapses card
    $(document).on('click', '.menu-item-cancel-link', function(e) {
        e.preventDefault();
        let card = $(this).closest('.menu-item-card');
        let settings = card.find('.menu-item-settings');
        
        card.removeClass('expanded');
        settings.slideUp(200);
    });

    // Indent / Outdent button click actions
    $(document).on('click', '.indent-item-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        let card = $(this).closest('.menu-item-card');
        
        // Only allow nesting if there is a preceding sibling
        let preceding = card.prevAll('.menu-item-card:not(.nested-item)').first();
        if (preceding.length > 0) {
            card.addClass('nested-item');
            updateMenuTreeStructure();
        } else {
            toastr.warning('সাবমেনু করার জন্য এর উপরে অন্তত একটি মূল মেনু থাকতে হবে!');
        }
    });

    $(document).on('click', '.outdent-item-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        let card = $(this).closest('.menu-item-card');
        card.removeClass('nested-item');
        updateMenuTreeStructure();
    });

    // Dropdown parent selector manual change handler
    $(document).on('change', '.item-parent-select', function() {
        let card = $(this).closest('.menu-item-card');
        let val = $(this).val();
        
        if (val !== '') {
            card.addClass('nested-item');
        } else {
            card.removeClass('nested-item');
        }
        
        updateMenuTreeStructure();
    });

    // Remove item link removes item card
    $(document).on('click', '.menu-item-remove-link', function(e) {
        e.preventDefault();
        let card = $(this).closest('.menu-item-card');
        card.fadeOut(300, function() {
            $(this).remove();
            if ($('#menuItemsList .menu-item-card').length === 0) {
                $('#menuEmptyState').show();
            }
            updateMenuTreeStructure();
        });
    });

    // Live Title Syncing: Typing in Navigation label instantly updates card header name
    $(document).on('keyup', '.item-label-input', function() {
        let val = $(this).val().trim();
        let titleEl = $(this).closest('.menu-item-card').find('.menu-item-title-text');
        titleEl.text(val || 'Unnamed Item');
    });

    // Live Icon Syncing: Typing in Icon class instantly updates card header icon
    $(document).on('keyup', '.item-icon-input', function() {
        let val = $(this).val().trim();
        let iconEl = $(this).closest('.menu-item-card').find('.menu-item-icon');
        iconEl.attr('class', 'menu-item-icon text-success ' + (val || 'fas fa-link'));
    });

    // Live Featured Checkbox Syncing: Toggling the Hot/Featured updates header badge representation
    $(document).on('change', '.item-featured-checkbox', function() {
        let card = $(this).closest('.menu-item-card');
        let headerLeft = card.find('.menu-item-bar-left');
        let checked = $(this).is(':checked');
        
        // Remove existing badge if any
        headerLeft.find('.menu-item-featured-badge').remove();
        
        if (checked) {
            headerLeft.append('<span class="menu-item-featured-badge">Featured</span>');
        }
    });

    // ==========================================================================
    // Menu Selector & Section Loading
    // ==========================================================================
    $('#selectMenuBtn').click(function() {
        let val = $('#menuSelect').val();
        let label = $('#menuSelect option:selected').text();
        
        activeSection = val;
        $('#activeMenuLabel').text(label);
        
        renderActiveMenu();
        toastr.info('"' + label + '" মেনু ডাটা লোড করা হয়েছে');
    });

    // Clear all items button
    $('#clearMenuBtn').click(function() {
        Swal.fire({
            title: 'সব মুছে ফেলতে চান?',
            text: 'এই সেকশনের সমস্ত মেনু আইটেম খালি করা হবে (সংরক্ষণ করার আগ পর্যন্ত ডাটা স্থায়ীভাবে ডিলিট হবে না)',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'হ্যাঁ, সব মুছুন',
            cancelButtonText: 'বাতিল'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#menuItemsList').empty();
                $('#menuEmptyState').show();
                toastr.success('মেনু তালিকা খালি করা হয়েছে');
            }
        });
    });

    // ==========================================================================
    // Save Menu (Batch serialization and POST submission)
    // ==========================================================================
    $('#saveMenuBtn').click(function() {
        if (isProcessing) return;

        let items = [];
        let cards = $('#menuItemsList .menu-item-card');
        
        cards.each(function(index) {
            let cardId = $(this).attr('id');
            let type = $(this).data('type');
            let name = $(this).find('.item-label-input').val().trim();
            let url = $(this).find('.item-url-input').val().trim();
            let icon = $(this).find('.item-icon-input').val().trim();
            let isFeatured = $(this).find('.item-featured-checkbox').is(':checked');
            
            let itemParams = null;
            let paramsVal = $(this).find('.item-params-input').val();
            if (paramsVal) {
                try {
                    itemParams = JSON.parse(paramsVal);
                } catch(e) {
                    itemParams = null;
                }
            }

            // Build menu item object
            let itemObj = {
                id: cardId,
                position: index + 1,
                type: type,
                icon: icon,
                is_featured: isFeatured,
                parent_id: $(this).find('.item-parent-select').val() || null
            };

            // Map variables based on section constraints (compatibility)
            if (['topbar_left', 'topbar_right'].includes(activeSection)) {
                itemObj.text = name;
                if (type !== 'text') {
                    itemObj.url = url || '#';
                }
            } else if (activeSection === 'footer_contact') {
                itemObj.name = name;
                if (type !== 'text') {
                    itemObj.url = url || '#';
                }
            } else {
                itemObj.name = name;
                itemObj.url = url || '#';
                if (itemParams) {
                    itemObj.params = itemParams;
                }
            }

            items.push(itemObj);
        });

        isProcessing = true;
        let btn = $(this);
        let originalHtml = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> সংরক্ষণ হচ্ছে...').prop('disabled', true);

        $.ajax({
            url: '{{ route("admin.menu.update") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                section: activeSection,
                items: items
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    // Sync our local state variable
                    menuData[activeSection] = items;
                    setTimeout(() => location.reload(), 600);
                } else {
                    toastr.error(response.message || 'সংরক্ষণ করতে সমস্যা হয়েছে');
                    btn.html(originalHtml).prop('disabled', false);
                    isProcessing = false;
                }
            },
            error: function(xhr) {
                console.error('Save menu error:', xhr);
                let errorMsg = xhr.responseJSON?.message || 'মেনু সংরক্ষণ করতে সমস্যা হয়েছে';
                toastr.error(errorMsg);
                btn.html(originalHtml).prop('disabled', false);
                isProcessing = false;
            }
        });
    });

    // ==========================================================================
    // Refresh Cache AJAX
    // ==========================================================================
    $('#refreshCacheBtn').click(function() {
        let btn = $(this);
        let originalHtml = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> রিফ্রেশ হচ্ছে...').prop('disabled', true);

        $.ajax({
            url: '{{ route("admin.menu.cache.refresh") }}',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                console.error('Cache refresh error:', xhr);
                toastr.error('ক্যাশ রিফ্রেশ করতে সমস্যা হয়েছে');
            },
            complete: function() {
                btn.html(originalHtml).prop('disabled', false);
            }
        });
    });

    // ==========================================================================
    // Load default menu section on start
    // ==========================================================================
    renderActiveMenu();
});
</script>
@endpush
