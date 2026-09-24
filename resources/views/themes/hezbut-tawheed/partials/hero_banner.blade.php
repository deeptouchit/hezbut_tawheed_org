<!-- Glassmorphic Premium Hero Banner with High-Contrast Typography & Glow -->
<div class="position-relative overflow-hidden text-white py-5 d-flex align-items-center justify-content-center" 
     style="background: radial-gradient(circle at 15% 30%, #005a42 0%, #022018 75%, #01140e 100%); min-height: 290px; z-index: 1;">
    
    <!-- Animated Backdrop Light Orbs -->
    <div class="position-absolute rounded-circle bubble-bg" 
         style="width: 380px; height: 380px; top: -120px; left: -100px; background: rgba(0, 168, 120, 0.22); filter: blur(95px); pointer-events: none;"></div>
    <div class="position-absolute rounded-circle bubble-bg" 
         style="width: 320px; height: 320px; bottom: -120px; right: -60px; background: rgba(217, 119, 6, 0.18); filter: blur(85px); pointer-events: none;"></div>
    
    <!-- Subtle Grid Overlay -->
    <div class="position-absolute w-100 h-100 top-0 start-0 opacity-10 pointer-events-none" 
         style="background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px); background-size: 24px 24px;"></div>

    <div class="container position-relative text-center px-3" style="z-index: 10;">
        @if(!empty($badge_text))
            <div class="mb-3">
                <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 fw-bold shadow-sm animate-fade-in" 
                      style="background: #ffffff; color: #004d38; font-size: 0.85rem; font-family: 'Hind Siliguri', sans-serif; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.25);">
                    <i class="{{ $badge_icon ?? 'fas fa-info-circle' }}" style="color: #006a4e;"></i> {{ $badge_text }}
                </span>
            </div>
        @endif
        
        <h1 class="fw-extrabold text-white mb-2" 
            style="font-family: 'Baloo Da 2', sans-serif; font-weight: 800; font-size: clamp(1.8rem, 4vw, 2.6rem); letter-spacing: -0.3px; text-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);">
            {{ $title }}
        </h1>
        
        <!-- Gold Accent Line Divider -->
        <div class="mx-auto my-2.5 rounded-pill" style="width: 50px; height: 3px; background: linear-gradient(90deg, #f59e0b 0%, #fbbf24 100%);"></div>

        @if(!empty($subtitle))
            <p class="mx-auto mb-0" 
               style="font-family: 'Hind Siliguri', sans-serif; color: #f1f5f9; font-size: 1.05rem; max-width: 650px; line-height: 1.7; font-weight: 400; text-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);">
                {{ $subtitle }}
            </p>
        @endif

        @if(!empty($extra_html))
            <div class="mt-3">
                {!! $extra_html !!}
            </div>
        @endif
    </div>
</div>
