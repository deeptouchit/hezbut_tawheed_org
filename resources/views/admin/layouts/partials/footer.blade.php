<footer class="app-footer">
    <div class="float-end d-none d-sm-inline">
        ডিজাইন ও ডেভেলপমেন্ট: <a href="https://deeptouchit.com" target="_blank"
            class="text-decoration-none fw-medium">Deeptouch IT</a>
    </div>
    <strong>
        Copyright &copy; {{ date('Y') }}
        <a href="{{ url('/') }}"
            class="text-decoration-none">{{ $setting->getSetting('company_name', 'হেযবুত তওহীদ') }}</a>.
    </strong>
    সর্বস্বত্ব সংরক্ষিত।
</footer>
