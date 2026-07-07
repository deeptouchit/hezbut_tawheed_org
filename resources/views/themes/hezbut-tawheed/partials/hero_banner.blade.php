<!-- Glassmorphic Premium Hero Banner with Mesh Gradients & Floating Shapes -->
<div class="position-relative overflow-hidden text-white py-5 d-flex align-items-center justify-content-center" 
     style="background: radial-gradient(circle at 10% 20%, rgba(0, 77, 56, 1) 0%, rgba(2, 28, 22, 1) 90%); min-height: 280px; z-index: 1;">
    
    <!-- Animated Backdrop Elements -->
    <div class="position-absolute rounded-circle bg-emerald opacity-20 bubble-bg" style="width: 350px; height: 350px; top: -100px; left: -100px; filter: blur(90px); animation: float-bubble 12s infinite alternate;"></div>
    <div class="position-absolute rounded-circle bg-teal opacity-20 bubble-bg" style="width: 300px; height: 300px; bottom: -100px; right: -50px; filter: blur(80px); animation: float-bubble 10s infinite alternate-reverse;"></div>
    
    <!-- Glassmorphic Grid lines -->
    <div class="position-absolute w-100 h-100 top-0 start-0 opacity-5 grid-pattern-mask"></div>

    <div class="container position-relative text-center" style="z-index: 10;">
        @if(!empty($badge_text))
            <span class="badge bg-success bg-opacity-25 border border-success border-opacity-50 text-success-light px-3 py-2 rounded-pill fw-bold mb-3 animate-fade-in" 
                  style="letter-spacing: 0.5px; font-size: 0.8rem; font-family: 'Baloo Da 2', sans-serif;">
                <i class="{{ $badge_icon ?? 'fas fa-info-circle' }} me-2 text-warning"></i> {{ $badge_text }}
            </span>
        @endif
        
        <h1 class="fw-extrabold text-white text-shadow-sm display-5 mb-2" 
            style="font-family: 'Baloo Da 2', sans-serif; font-weight: 800; letter-spacing: -0.5px;">
            {{ $title }}
        </h1>
        
        @if(!empty($subtitle))
            <p class="mx-auto mb-0 text-light opacity-90 max-w-2xl" 
               style="font-family: 'Baloo Da 2', sans-serif; font-size: 1.1rem; max-width: 600px; line-height: 1.6;">
                {{ $subtitle }}
            </p>
        @endif
    </div>
</div>
