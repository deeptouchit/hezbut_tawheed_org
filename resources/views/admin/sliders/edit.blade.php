@extends('admin.layouts.master')

@section('page-title', 'স্লাইডার সম্পাদনা - ' . ($slider->title ?? 'নামহীন'))

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0" style="border-radius: 8px; overflow: hidden;">
        <div class="card-header bg-white border-bottom py-3">
            <h3 class="card-title font-weight-bold text-dark mb-0">
                <i class="fas fa-edit text-primary mr-2"></i> স্লাইডার সম্পাদনা: {{ $slider->title ?? 'নামহীন' }}
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: 4px;">
                    <i class="fas fa-arrow-left mr-1"></i> স্লাইডার তালিকায় ফিরুন
                </a>
            </div>
        </div>

        <form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data" id="sliderForm">
            @csrf
            @method('PUT')

            <div class="card-body bg-white p-4">
                <div class="row">
                    <!-- বাম পাশ: স্লাইডার কন্টেন্ট ও তথ্য -->
                    <div class="col-lg-8">
                        <h5 class="text-dark font-weight-bold border-bottom pb-2 mb-3">
                            <i class="fas fa-info-circle text-muted mr-1"></i> স্লাইডার বেসিক তথ্য
                        </h5>
                        
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-secondary">শিরোনাম <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $slider->title) }}" placeholder="স্লাইডারের শিরোনাম লিখুন (যেমন: অর্গানিক চাল-ডাল অফার)" required>
                            @error('title') <span class="invalid-feedback font-weight-bold">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-secondary">উপশিরোনাম (ঐচ্ছিক)</label>
                            <input type="text" name="sub_title" class="form-control @error('sub_title') is-invalid @enderror"
                                   value="{{ old('sub_title', $slider->sub_title) }}" placeholder="স্লাইডারের ছোট ডেসক্রিপশন বা স্লোগান">
                            @error('sub_title') <span class="invalid-feedback font-weight-bold">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label font-weight-bold text-secondary">লিংক URL (ঐচ্ছিক)</label>
                                    <input type="text" name="link" class="form-control @error('link') is-invalid @enderror"
                                           value="{{ old('link', $slider->link) }}" placeholder="যেমন: /books অথবা https://hezbuttawheed.org/books">
                                    @error('link') <span class="invalid-feedback font-weight-bold">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label font-weight-bold text-secondary">লিংক টেক্সট (ঐচ্ছিক)</label>
                                    <input type="text" name="link_text" class="form-control @error('link_text') is-invalid @enderror"
                                           value="{{ old('link_text', $slider->link_text) }}" placeholder="যেমন: বিস্তারিত দেখুন">
                                    @error('link_text') <span class="invalid-feedback font-weight-bold">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label font-weight-bold text-secondary">বাটন টেক্সট (ঐচ্ছিক)</label>
                                    <input type="text" name="button_text" class="form-control @error('button_text') is-invalid @enderror"
                                           value="{{ old('button_text', $slider->button_text) }}" placeholder="যেমন: বিস্তারিত জানুন">
                                    @error('button_text') <span class="invalid-feedback font-weight-bold">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label font-weight-bold text-secondary">বাটন লিংক (ঐচ্ছিক)</label>
                                    <input type="text" name="button_link" class="form-control @error('button_link') is-invalid @enderror"
                                           value="{{ old('button_link', $slider->button_link) }}" placeholder="যেমন: /offers বা https://bograbazar.com/offers">
                                    @error('button_link') <span class="invalid-feedback font-weight-bold">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label font-weight-bold text-secondary">বাটনের রঙ</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text p-0" style="width: 40px; height: 38px; overflow: hidden; border: 1px solid #ced4da; border-right: none; border-radius: 4px 0 0 4px;">
                                                <input type="color" name="button_color" id="button_color"
                                                       value="{{ old('button_color', $slider->button_color ?? '#007bff') }}"
                                                       style="width: 100%; height: 100%; border: none; padding: 0; cursor: pointer; transform: scale(1.4);">
                                            </span>
                                        </div>
                                        <input type="text" name="button_color_text" id="button_color_text" class="form-control"
                                               value="{{ old('button_color', $slider->button_color ?? '#007bff') }}" placeholder="#007bff" style="border-radius: 0 4px 4px 0;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label font-weight-bold text-secondary">লিংক টার্গেট</label>
                                    <select name="target" class="form-control form-select">
                                        <option value="_self" {{ old('target', $slider->target) == '_self' ? 'selected' : '' }}>একই ট্যাব (_self)</option>
                                        <option value="_blank" {{ old('target', $slider->target) == '_blank' ? 'selected' : '' }}>নতুন ট্যাব (_blank)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-secondary">Alt টেক্সট (SEO এর জন্য)</label>
                            <input type="text" name="alt_text" class="form-control"
                                   value="{{ old('alt_text', $slider->alt_text) }}" placeholder="ছবির অল্টারনে티브 ডেসক্রিপশন">
                        </div>
                    </div>

                    <!-- ডান পাশ: ব্যানার মিডিয়া ও সেটিংস -->
                    <div class="col-lg-4">
                        <h5 class="text-dark font-weight-bold border-bottom pb-2 mb-3">
                            <i class="fas fa-photo-video text-muted mr-1"></i> ব্যানার মিডিয়া ও ছবি
                        </h5>

                        <!-- ডেস্কটপ ইমেজ আপলোড জোন -->
                        <div class="card border-dashed mb-3 bg-light p-3 text-center" style="border: 2px dashed #ced4da; border-radius: 6px;">
                            <label class="form-label font-weight-bold text-secondary d-block text-start mb-2">মূল ছবি (ডেস্কটপ) <span class="text-danger">*</span></label>
                            <div class="preview-container mb-2 text-center">
                                @if($slider->image && file_exists(public_path($slider->image)))
                                    <img id="image-preview" src="{{ asset($slider->image) }}" alt="Preview" class="img-thumbnail" style="max-height: 120px; object-fit: contain;">
                                    <div id="image-placeholder" class="py-3" style="display: none;">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-1"></i>
                                        <p class="mb-0 text-sm text-secondary font-weight-bold">ছবি ফাইল আপলোড করুন</p>
                                        <span class="text-xs text-muted" style="font-size: 11px;">JPG, PNG, WEBP (সর্বোচ্চ ২ মেগাবাইট)</span>
                                    </div>
                                @else
                                    <img id="image-preview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 120px; object-fit: contain; display: none;">
                                    <div id="image-placeholder" class="py-3">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-1"></i>
                                        <p class="mb-0 text-sm text-secondary font-weight-bold">ছবি ফাইল আপলোড করুন</p>
                                        <span class="text-xs text-muted" style="font-size: 11px;">JPG, PNG, WEBP (সর্বোচ্চ ২ মেগাবাইট)</span>
                                    </div>
                                @endif
                            </div>
                            <label for="image" class="btn btn-outline-primary btn-xs px-3 mb-0" style="font-size: 12px; cursor: pointer;">
                                <i class="fas fa-folder-open me-1"></i> ছবি পরিবর্তন করুন
                            </label>
                            <input type="file" name="image" id="image" class="d-none" accept="image/jpeg,image/png,image/jpg,image/webp">
                            @error('image') <div class="text-danger text-sm font-weight-bold mt-1">{{ $message }}</div> @enderror
                            
                            @if($slider->image)
                                <div class="form-check mt-2 text-start">
                                    <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
                                    <label class="form-check-label text-danger font-weight-bold text-xs" for="remove_image" style="cursor: pointer;">
                                        <i class="fas fa-trash-alt mr-1"></i> বিদ্যমান ছবি মুছে ফেলুন
                                    </label>
                                </div>
                            @endif
                        </div>

                        <!-- মোবাইল ইমেজ আপলোড জোন -->
                        <div class="card border-dashed mb-3 bg-light p-3 text-center" style="border: 2px dashed #ced4da; border-radius: 6px;">
                            <label class="form-label font-weight-bold text-secondary d-block text-start mb-2">মোবাইল ছবি (ঐচ্ছিক)</label>
                            <div class="preview-container mb-2 text-center">
                                @if($slider->mobile_image && file_exists(public_path($slider->mobile_image)))
                                    <img id="mobile-preview" src="{{ asset($slider->mobile_image) }}" alt="Preview" class="img-thumbnail" style="max-height: 100px; object-fit: contain;">
                                    <div id="mobile-placeholder" class="py-3" style="display: none;">
                                        <i class="fas fa-mobile-alt fa-2x text-muted mb-1"></i>
                                        <p class="mb-0 text-sm text-secondary font-weight-bold">মোবাইল ছবি আপলোড</p>
                                        <span class="text-xs text-muted" style="font-size: 11px;">ফাঁকা রাখলে মূল ছবি ব্যবহৃত হবে</span>
                                    </div>
                                @else
                                    <img id="mobile-preview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 100px; object-fit: contain; display: none;">
                                    <div id="mobile-placeholder" class="py-3">
                                        <i class="fas fa-mobile-alt fa-2x text-muted mb-1"></i>
                                        <p class="mb-0 text-sm text-secondary font-weight-bold">মোবাইল ছবি আপলোড</p>
                                        <span class="text-xs text-muted" style="font-size: 11px;">ফাঁকা রাখলে মূল ছবি ব্যবহৃত হবে</span>
                                    </div>
                                @endif
                            </div>
                            <label for="mobile_image" class="btn btn-outline-secondary btn-xs px-3 mb-0" style="font-size: 12px; cursor: pointer;">
                                <i class="fas fa-folder-open me-1"></i> ফাইল বেছে নিন
                            </label>
                            <input type="file" name="mobile_image" id="mobile_image" class="d-none" accept="image/jpeg,image/png,image/jpg,image/webp">
                        </div>

                        <!-- পাবলিশিং সেটিংস -->
                        <h5 class="text-dark font-weight-bold border-bottom pb-2 mt-4 mb-3">
                            <i class="fas fa-sliders-h text-muted mr-1"></i> প্রকাশনা সেটিংস
                        </h5>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-secondary">পজিশন</label>
                            <select name="position" class="form-control form-select" required>
                                <option value="homepage" {{ old('position', $slider->position) == 'homepage' ? 'selected' : '' }}>হোমপেজ স্লাইডার</option>
                                <option value="banner" {{ old('position', $slider->position) == 'banner' ? 'selected' : '' }}>প্রমোショナル ব্যানার</option>
                                <option value="popup" {{ old('position', $slider->position) == 'popup' ? 'selected' : '' }}>পপআপ ব্যানার</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold text-secondary">সর্ট অর্ডার (ক্রম)</label>
                            <input type="number" name="sort_order" class="form-control"
                                   value="{{ old('sort_order', $slider->sort_order) }}" min="0">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label font-weight-bold text-secondary" style="font-size: 13px;">শুরুর তারিখ</label>
                                    <input type="date" name="start_date" class="form-control"
                                           value="{{ old('start_date', $slider->start_date ? $slider->start_date->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label font-weight-bold text-secondary" style="font-size: 13px;">শেষ তারিখ</label>
                                    <input type="date" name="end_date" class="form-control"
                                           value="{{ old('end_date', $slider->end_date ? $slider->end_date->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3 mb-2">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="status" class="custom-control-input" id="status" {{ old('status', $slider->status) ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-bold text-success" for="status" style="cursor: pointer;">
                                    <i class="fas fa-check-circle mr-1"></i> স্লাইডারটি সক্রিয় রাখুন
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light border-top py-3 text-center">
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm" id="submitBtn" style="border-radius: 4px; font-size: 15px;">
                    <i class="fas fa-save mr-1"></i> পরিবর্তন সংরক্ষণ করুন
                </button>
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary btn-lg px-4 ml-2" style="border-radius: 4px; font-size: 15px;">
                    <i class="fas fa-times mr-1"></i> বাতিল
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Desktop Image preview
    $('#image').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result).show();
                $('#image-placeholder').hide();
            }
            reader.readAsDataURL(file);
        }
    });

    // Mobile image preview
    $('#mobile_image').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#mobile-preview').attr('src', e.target.result).show();
                $('#mobile-placeholder').hide();
            }
            reader.readAsDataURL(file);
        }
    });

    // Color picker sync
    $('#button_color').on('input', function() {
        $('#button_color_text').val($(this).val());
    });
    $('#button_color_text').on('input', function() {
        var color = $(this).val();
        if(/^#[0-9A-F]{6}$/i.test(color)) {
            $('#button_color').val(color);
        }
    });

    // Form submit loading state
    $('#sliderForm').on('submit', function() {
        $('#submitBtn').html('<i class="fas fa-spinner fa-spin mr-1"></i> সংরক্ষণ হচ্ছে...').prop('disabled', true);
    });
});
</script>
@endpush
