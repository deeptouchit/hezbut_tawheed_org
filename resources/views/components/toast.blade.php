@if(session('success'))

<div id="global-toast-notification"
 class="fixed top-20 right-4 left-4 sm:left-auto
  sm:right-5 z-[99999] transform translate-x-[150%]
   sm:translate-x-[120%] transition-all
   duration-500 ease-out
    flex items-center gap-2.5 sm:gap-3
     bg-white border border-emerald-500
      px-4 py-3 sm:px-5 sm:py-4 w-auto sm:max-w-sm">


      
        <div class="flex-shrink-0 w-5 h-5 sm:w-6 sm:h-6 text-emerald-600 flex items-center justify-center">
            <i class="fa-solid fa-circle-check text-sm sm:text-base"></i>
        </div>

        <div class="pr-1 sm:pr-2">
            <p class="text-[10px] sm:text-[11px] text-emerald-600 font-normal uppercase tracking-wider">Success</p>
            <p class="text-xs sm:text-sm font-normal text-gray-800 mt-1 leading-tight">{{ session('success') }}</p>
        </div>

        <button onclick="closeComponentToast()" class="ml-auto text-gray-400 hover:text-emerald-600 transition-colors cursor-pointer p-1">
            <i class="fa-solid fa-xmark text-[10px] sm:text-xs"></i>
        </button>
    </div>

    <script>
        // ⚡ DOMContentLoaded এর ঝামেলা বাদ দিয়ে সরাসরি আইসোলেটেড ফাংশনে রান করানো হলো
        (function() {
            const toast = document.getElementById('global-toast-notification');
            if (toast) {
                // ০.১ সেকেন্ড পর ডানপাশ থেকে স্মুথলি স্ক্রিনে স্লাইড করবে
                setTimeout(() => {
                    // ফিক্সড: রেসপন্সিভ কনফ্লিক্ট এড়াতে সব ট্রান্সলেট ক্লাস রিমুভ করে ট্র্যাকিং জিরো করা হলো
                    toast.classList.remove('translate-x-[150%]', 'translate-x-[120%]', 'sm:translate-x-[120%]');
                    toast.classList.add('translate-x-0');
                }, 100);

                // ৩ সেকেন্ড পর নিজে নিজেই আবার ডানপাশে হারিয়ে যাবে
                setTimeout(() => {
                    closeComponentToast();
                }, 3000);
            }
        })();

        // নোটিফিকেশন স্ক্রিনের বাইরে স্লাইড করে বন্ধ করার ফাংশন
        function closeComponentToast() {
            const toast = document.getElementById('global-toast-notification');
            if (toast) {
                toast.classList.remove('translate-x-0');

                // স্ক্রিন সাইজ চেক করে সঠিক দিকে স্লাইড করানো
                if (window.innerWidth < 640) {
                    toast.classList.add('translate-x-[150%]');
                } else {
                    toast.classList.add('translate-x-[120%]');
                }
                setTimeout(() => { toast.remove(); }, 500);
            }
        }
    </script>
@endif
