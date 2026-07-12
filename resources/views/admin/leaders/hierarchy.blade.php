@extends('admin.layouts.master')

@section('page-title', 'সাংগঠনিক হায়ারার্কি ডায়াগ্রাম সাজান')

@push('styles')
<style>
    .leaders-list-container {
        max-width: 850px;
        margin: 0 auto;
    }
    .leader-item-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 10px;
        padding: 10px 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        transition: transform 0.2s, box-shadow 0.2s, margin-left 0.2s, border-left-color 0.2s;
    }
    .leader-item-card.ui-sortable-helper {
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: #cbd5e1;
        opacity: 0.95;
    }
    
    /* Multilevel hierarchical indentation & border colors */
    .leader-item-card.level-0 {
        margin-left: 0px;
        border-left: 5px solid #64748b !important; /* Root: Slate Grey */
    }
    .leader-item-card.level-1 {
        margin-left: 40px;
        border-left: 5px solid #006A4E !important; /* Level 1: Deep Green */
    }
    .leader-item-card.level-2 {
        margin-left: 80px;
        border-left: 5px solid #3b82f6 !important; /* Level 2: Royal Blue */
    }
    .leader-item-card.level-3 {
        margin-left: 120px;
        border-left: 5px solid #f59e0b !important; /* Level 3: Amber Orange */
    }
    .leader-item-card.level-4 {
        margin-left: 160px;
        border-left: 5px solid #ec4899 !important; /* Level 4: Pink */
    }
    .leader-item-card.level-5 {
        margin-left: 200px;
        border-left: 5px solid #8b5cf6 !important; /* Level 5: Purple */
    }

    .leader-item-placeholder {
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        margin-bottom: 10px;
        height: 58px;
    }
    .leader-item-drag-handle {
        cursor: move;
        color: #94a3b8;
        padding-right: 15px;
    }
    .nesting-indicator {
        color: #64748b;
        margin-right: 10px;
    }
    .indent-action-btn {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s;
    }
    .indent-action-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .text-dark-green {
        color: #006A4E !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header Page Actions -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="mb-1 fw-bold text-dark-green"><i class="fas fa-sitemap me-1"></i> সাংগঠনিক হায়ারার্কি ডায়াগ্রাম সাজান</h3>
            <p class="text-muted small mb-0">নেতৃবৃন্দকে ড্রাগ করে একে অপরের অধীনে সাজিয়ে সাংগঠনিক কাঠামো তৈরি করুন।</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.leaders.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-list me-1"></i> নেতৃত্ব তালিকায় ফিরে যান
            </a>
            <button id="save-hierarchy-btn" class="btn btn-success btn-sm">
                <i class="fas fa-save me-1"></i> হায়ারার্কি সংরক্ষণ করুন
            </button>
        </div>
    </div>

    <!-- Info card instructions -->
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <h6 class="fw-bold mb-1"><i class="fas fa-info-circle"></i> ব্যবহারের নির্দেশনাবলী:</h6>
        <ul class="mb-0 ps-3 small">
            <li>যেকোনো মেম্বারকে ড্র্যাগ হ্যান্ডেল (<i class="fas fa-grip-vertical"></i>) ধরে টেনে উপরে-নিচে নামিয়ে পজিশন বা সিরিয়াল পরিবর্তন করতে পারবেন।</li>
            <li>ডান পাশের অ্যারো বোতাম (<i class="fas fa-arrow-right"></i>) দিয়ে মেম্বারকে ডানে সরিয়ে (Indent) যেকোনো নেতার অধীনস্থ বা সাব-লিডার করতে পারবেন।</li>
            <li>বাম পাশের অ্যারো বোতাম (<i class="fas fa-arrow-left"></i>) দিয়ে মেম্বারকে বামে সরিয়ে (Outdent) ওপরের স্তরে উন্নীত করতে পারবেন।</li>
            <li>হায়ারার্কি সাজানো শেষ হলে ডানদিকের <strong>"হায়ারার্কি সংরক্ষণ করুন"</strong> বাটনে ক্লিক করে সেভ করুন।</li>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <!-- List Area -->
    <div class="card shadow-sm border border-light">
        <div class="card-header bg-white py-3 border-bottom border-light">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-6 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" id="leader-search-input" class="form-control bg-light border-start-0" placeholder="নেতৃত্বের নাম বা পদবি দিয়ে খুঁজুন...">
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 text-md-end mt-2 mt-md-0">
                    <span class="badge bg-secondary py-2 px-3 fs-7" id="showing-count-badge">মোট সদস্য: {{ count($leaders) }} জন</span>
                </div>
            </div>
        </div>
        <div class="card-body bg-light">
            <div id="leadersList" class="leaders-list-container">
                @forelse($leaders as $leader)
                    <div class="leader-item-card level-{{ $leader->level }}" 
                         data-id="{{ $leader->id }}" 
                         data-parent-id="{{ $leader->parent_id }}"
                         data-level="{{ $leader->level }}">
                        
                        <div class="d-flex align-items-center">
                            <!-- Drag Handle -->
                            <span class="leader-item-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                            
                            <!-- Nesting Indicator arrow -->
                            <span class="nesting-indicator" style="display: {{ $leader->parent_id ? 'inline-block' : 'none' }};">
                                <i class="fas fa-level-up-alt fa-rotate-90"></i>
                            </span>

                            <!-- Profile avatar -->
                            <img src="{{ $leader->image_url }}" alt="{{ $leader->name }}" class="rounded me-3 border" style="width: 38px; height: 38px; object-fit: cover;">
                            
                            <!-- Name and Designation -->
                            <div>
                                <span class="fw-bold text-dark d-block">{{ $leader->name }}</span>
                                <span class="text-muted small">
                                    {{ $leader->designation }} 
                                    <span class="badge bg-light text-muted border ms-1 level-badge" style="font-size: 9px;">স্তর {{ $leader->level + 1 }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Indent/Outdent Action Buttons -->
                        <div class="d-flex gap-1">
                            <button class="indent-action-btn outdent-item-btn" title="বাম দিকে আনুন" style="display: {{ $leader->parent_id ? 'inline-flex' : 'none' }};">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <button class="indent-action-btn indent-item-btn" title="ডান দিকে নিয়ে সাব-লিডার করুন">
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>

                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-users-slash fa-3x mb-3 text-secondary"></i>
                        <p class="mb-0">কোনো নেতৃত্ব পাওয়া যায়নি। আগে নেতৃত্ব তৈরি করুন।</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    // Initialize JQuery UI Sortable
    if ($.fn.sortable) {
        $('#leadersList').sortable({
            handle: '.leader-item-drag-handle',
            placeholder: 'leader-item-placeholder',
            cursor: 'move',
            opacity: 0.8,
            stop: function(event, ui) {
                // When dragged to a new position, recalculate structural levels
                adjustDraggingLevel(ui.item);
                updateLeadersTreeStructure();
            }
        });
    }

    // Helper to calculate initial level offset on drag stop
    function adjustDraggingLevel(item) {
        let containerLeft = $('#leadersList').offset().left;
        let cardLeft = item.offset().left;
        let relativeLeft = cardLeft - containerLeft;

        // Estimate level based on indent spacing (40px per level)
        let estimatedLevel = Math.max(0, Math.min(5, Math.round(relativeLeft / 40)));
        
        // Enforce structural rules: cannot be more than preceding item's level + 1
        let preceding = item.prev('.leader-item-card');
        let precedingLevel = preceding.length ? (parseInt(preceding.attr('data-level')) || 0) : -1;
        
        let targetLevel = Math.min(estimatedLevel, precedingLevel + 1);
        
        // Update item classes and attributes
        setItemLevel(item, targetLevel);
    }

    // Set level on element
    function setItemLevel(item, level) {
        // Remove old level classes
        item.removeClass('level-0 level-1 level-2 level-3 level-4 level-5');
        item.addClass('level-' + level);
        item.attr('data-level', level);
        item.find('.level-badge').text('স্তর ' + (level + 1));
    }

    // Update parent-child hierarchy dynamically based on UI nesting states
    function updateLeadersTreeStructure() {
        let lastSeenIdAtLevel = {};

        $('#leadersList .leader-item-card').each(function() {
            let card = $(this);
            let itemId = card.data('id');
            let level = parseInt(card.attr('data-level')) || 0;

            // Enforce structural check: Level cannot skip preceding level
            let preceding = card.prev('.leader-item-card');
            let precedingLevel = preceding.length ? (parseInt(preceding.attr('data-level')) || 0) : -1;
            
            if (level > precedingLevel + 1) {
                level = precedingLevel + 1;
                setItemLevel(card, level);
            }

            // Parent ID is the last seen ID at (level - 1)
            let parentId = null;
            if (level > 0) {
                parentId = lastSeenIdAtLevel[level - 1] || null;
            }

            // Set parent-id attribute
            card.attr('data-parent-id', parentId || '');
            
            // Register this node in tracking map
            lastSeenIdAtLevel[level] = itemId;

            // Delete higher tracking levels (breaking branch chain)
            for (let i = level + 1; i <= 10; i++) {
                delete lastSeenIdAtLevel[i];
            }

            // Control show/hide indicators
            let nestingIndicator = card.find('.nesting-indicator');
            let indentBtn = card.find('.indent-item-btn');
            let outdentBtn = card.find('.outdent-item-btn');

            if (level > 0) {
                nestingIndicator.show();
                outdentBtn.show();
            } else {
                nestingIndicator.hide();
                outdentBtn.hide();
            }

            // Show Indent button only if we can nest it further (level <= precedingLevel)
            if (precedingLevel >= level && level < 5) {
                indentBtn.show();
            } else {
                indentBtn.hide();
            }
        });
    }

    // Indent button click actions: Nest one level deeper
    $(document).on('click', '.indent-item-btn', function(e) {
        e.preventDefault();
        let card = $(this).closest('.leader-item-card');
        let level = parseInt(card.attr('data-level')) || 0;
        
        let preceding = card.prev('.leader-item-card');
        let precedingLevel = preceding.length ? (parseInt(preceding.attr('data-level')) || 0) : -1;

        if (level <= precedingLevel && level < 5) {
            setItemLevel(card, level + 1);
            updateLeadersTreeStructure();
        } else {
            toastr.warning('অধীনস্থ করার জন্য এর উপরে অন্তত একজন মূল নেতৃত্ব থাকতে হবে!');
        }
    });

    // Outdent button click actions: Promote one level higher
    $(document).on('click', '.outdent-item-btn', function(e) {
        e.preventDefault();
        let card = $(this).closest('.leader-item-card');
        let level = parseInt(card.attr('data-level')) || 0;
        
        if (level > 0) {
            setItemLevel(card, level - 1);
            updateLeadersTreeStructure();
        }
    });

    // Save hierarchy button POST AJAX submission
    $('#save-hierarchy-btn').on('click', function() {
        let items = [];
        $('#leadersList .leader-item-card').each(function(index) {
            items.push({
                id: $(this).data('id'),
                parent_id: $(this).attr('data-parent-id') || null,
                position: index + 1
            });
        });

        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> সংরক্ষণ হচ্ছে...');

        $.ajax({
            url: "{{ route('admin.leaders.hierarchy.update') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                items: items
            },
            success: function(response) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> হায়ারার্কি সংরক্ষণ করুন');
                if (response.success) {
                    toastr.success(response.message || 'সাংগঠনিক হায়ারার্কি সফলভাবে সেভ করা হয়েছে!');
                } else {
                    toastr.error(response.message || 'সংরক্ষণ করতে ব্যর্থ হয়েছে!');
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> হায়ারার্কি সংরক্ষণ করুন');
                var msg = 'সার্ভারে সমস্যা হয়েছে!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                toastr.error(msg);
            }
        });
    });

    // Search filter listener (real-time client side search)
    $('#leader-search-input').on('keyup', function() {
        let query = $(this).val().toLowerCase().trim();
        let visibleCount = 0;
        let totalCount = $('#leadersList .leader-item-card').length;
        
        if (query === '') {
            $('#leadersList .leader-item-card').show();
            $('#showing-count-badge').text('মোট সদস্য: ' + totalCount + ' জন');
            // Enable sortable when search is empty
            if ($.fn.sortable) {
                $('#leadersList').sortable('enable');
            }
        } else {
            // Disable sortable when search is active to prevent sorting glitches
            if ($.fn.sortable) {
                $('#leadersList').sortable('disable');
            }
            
            $('#leadersList .leader-item-card').each(function() {
                let card = $(this);
                let name = card.find('.fw-bold.text-dark').text().toLowerCase();
                let designation = card.find('.text-muted.small').text().toLowerCase();
                
                if (name.includes(query) || designation.includes(query)) {
                    card.show();
                    visibleCount++;
                } else {
                    card.hide();
                }
            });
            $('#showing-count-badge').text('ফলাফল: ' + visibleCount + ' জন');
        }
    });

    // Initialize layout structure
    updateLeadersTreeStructure();
});
</script>
@endpush
