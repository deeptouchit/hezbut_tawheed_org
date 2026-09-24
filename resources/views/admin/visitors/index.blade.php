@extends('admin.layouts.master')

@section('page-title', 'Visitor Logs')

@section('filter_input')
    <div class="row px-3 text-center">
        <div class="col">
            <!-- Period Filters -->
            <div class="btn-group shadow-sm" role="group" aria-label="Period Filters">
                <a href="{{ route('admin.visitors.index', ['period' => 'today']) }}"
                    class="btn btn-outline-primary {{ $period === 'today' ? 'active' : '' }}">আজ</a>
                <a href="{{ route('admin.visitors.index', ['period' => 'week']) }}"
                    class="btn btn-outline-primary {{ $period === 'week' ? 'active' : '' }}">এই সপ্তাহ</a>
                <a href="{{ route('admin.visitors.index', ['period' => 'month']) }}"
                    class="btn btn-outline-primary {{ $period === 'month' ? 'active' : '' }}">এই মাস</a>
                <a href="{{ route('admin.visitors.index', ['period' => 'year']) }}"
                    class="btn btn-outline-primary {{ $period === 'year' ? 'active' : '' }}">এই বছর</a>
                <a href="{{ route('admin.visitors.index', ['period' => 'all']) }}"
                    class="btn btn-outline-primary {{ $period === 'all' || empty($period) ? 'active' : '' }}">সব</a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid pt-3">

        <!-- Alert messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Left Column: Visitor Logs Table -->
            <div class="col-lg-8 mb-4">
                <form action="{{ route('admin.visitors.bulk-delete') }}" method="POST" id="bulk-delete-form">
                    @csrf

                    <div class="card shadow-sm border-0" style="border-radius: 10px; overflow: hidden;">
                        <div class="card-header">
                            <h5 class="card-title fw-bold text-dark mb-0">
                                <i class="fas fa-users text-primary me-2"></i>ভিজিটরদের তালিকা
                            </h5>
                            <div class="card-tools ">
                                <!-- Selected Counter -->
                                <span class="badge bg-primary d-none me-2" id="selected-count">0 টি নির্বাচিত</span>

                                <!-- Bulk Delete Button -->
                                <button type="submit" class="btn btn-danger btn-sm shadow-sm" id="bulk-delete-btn" disabled
                                    onclick="return confirm('আপনি কি নিশ্চিত যে নির্বাচিত রেকর্ডগুলো মুছে ফেলতে চান?')">
                                    <i class="fas fa-trash-alt me-1"></i> নির্বাচিতগুলো মুছুন
                                </button>

                                <!-- Clear All Logs Button -->
                                <button type="button" class="btn btn-outline-danger btn-sm shadow-sm"
                                    onclick="if(confirm('আপনি কি নিশ্চিত যে সমস্ত ভিজিটর লগ মুছে ফেলতে চান?')) document.getElementById('clear-all-form').submit();">
                                    <i class="fas fa-broom me-1"></i> সব মুছুন
                                </button>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-0">
                            <table class="table table-bordered table-striped table-hover table-sm text-nowrap mb-0"
                                style="width: 100%;">
                                <thead>
                                    <tr class="text-center bg-light">
                                        <th style="width: 40px;" class="text-center">
                                            <input type="checkbox" id="check-all" class="form-check-input">
                                        </th>
                                        <th>আইপি (IP)</th>
                                        <th>দেশের নাম ও শহর</th>
                                        <th>আইএসপি (ISP)</th>
                                        <th>ওএস / ব্রাউজার</th>
                                        <th class="text-center">সময়</th>
                                        <th class="text-center" style="width: 90px;">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($visitors as $visitor)
                                        <tr class="text-center align-middle">
                                            <td>
                                                <input type="checkbox" name="ids[]" value="{{ $visitor->id }}"
                                                    class="form-check-input visitor-checkbox">
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary font-monospace"
                                                    style="font-size: 0.85rem;">{{ $visitor->ip_address }}</span>
                                            </td>
                                            <td class="text-start">
                                                <span class="text-dark fw-semibold">
                                                    <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                                    {{ $visitor->country && $visitor->country !== 'Unknown' ? $visitor->country : 'Bangladesh' }}
                                                    @if ($visitor->city && $visitor->city !== 'Unknown')
                                                        <small class="text-muted">({{ $visitor->city }})</small>
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="text-start">
                                                <small class="text-secondary fw-semibold">
                                                    <i class="fas fa-network-wired text-info me-1"></i>
                                                    {{ $visitor->isp && $visitor->isp !== 'Unknown' ? $visitor->isp : 'Local ISP' }}
                                                </small>
                                            </td>
                                            <td class="text-start">
                                                <small class="text-dark d-block fw-semibold">{{ $visitor->platform }} /
                                                    {{ $visitor->browser }}</small>
                                            </td>
                                            <td class="text-center text-muted">
                                                <small>{{ $visitor->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td class="p-1">
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button"
                                                        class="btn btn-info text-white show-visitor-btn me-1" title="দেখুন"
                                                        style="border-radius: 4px;" data-ip="{{ $visitor->ip_address }}"
                                                        data-device="{{ $visitor->device }}"
                                                        data-platform="{{ $visitor->platform }}"
                                                        data-browser="{{ $visitor->browser }}"
                                                        data-url="{{ $visitor->url }}"
                                                        data-referrer="{{ $visitor->referrer ?? 'Direct' }}"
                                                        data-country="{{ $visitor->country && $visitor->country !== 'Unknown' ? $visitor->country : 'Bangladesh' }}"
                                                        data-city="{{ $visitor->city && $visitor->city !== 'Unknown' ? $visitor->city : 'Dhaka' }}"
                                                        data-isp="{{ $visitor->isp && $visitor->isp !== 'Unknown' ? $visitor->isp : 'Local ISP' }}"
                                                        data-time="{{ $visitor->created_at->format('d M, Y h:i A') }} ({{ $visitor->created_at->diffForHumans() }})">
                                                        <i class="fas fa-eye text-white"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-delete-single"
                                                        title="ডিলিট" style="border-radius: 4px;"
                                                        onclick="if(confirm('আপনি কি এই ভিজিটর লগটি মুছে ফেলতে চান?')) document.getElementById('single-delete-form-{{ $visitor->id }}').submit();">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">কোন ভিজিটর লগ পাওয়া
                                                যায়নি</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Footer -->
                        @if ($visitors->hasPages())
                            <div class="card-footer bg-transparent border-0 py-3">
                                <div class="d-flex justify-content-center">
                                    {!! $visitors->links('pagination::bootstrap-5') !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </form>

                <!-- Single Row Delete Forms -->
                @foreach ($visitors as $visitor)
                    <form id="single-delete-form-{{ $visitor->id }}"
                        action="{{ route('admin.visitors.delete', $visitor->id) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach

                <!-- Clear All Form -->
                <form id="clear-all-form" action="{{ route('admin.visitors.clear-all') }}" method="POST"
                    class="d-none">
                    @csrf
                </form>
            </div>

            <!-- Right Column: Country Summary -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0 h-100" style="border-radius: 10px; overflow: hidden;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title fw-bold text-dark mb-0"><i class="fas fa-globe text-primary me-2"></i>দেশ
                            ভিত্তিক সামারি</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @php
                                $totalUnique = $countrySummary->sum('unique_visitors');
                            @endphp
                            @forelse($countrySummary as $country)
                                @php
                                    $percent =
                                        $totalUnique > 0 ? round(($country->unique_visitors / $totalUnique) * 100) : 0;
                                    $countryName =
                                        $country->country && $country->country !== 'Unknown'
                                            ? $country->country
                                            : 'Bangladesh';
                                @endphp
                                <li class="list-group-item py-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold text-dark">
                                            <i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $countryName }}
                                        </span>
                                        <span
                                            class="badge bg-primary rounded-pill">{{ number_format($country->unique_visitors) }}
                                            জন ({{ $percent }}%)</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                            style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center py-4 text-muted">কোন দেশ ভিত্তিক তথ্য পাওয়া যায়নি
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visitor Detail Modal -->
    <div class="modal fade" id="visitorDetailModal" tabindex="-1" aria-labelledby="visitorDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold" id="visitorDetailModalLabel"><i
                            class="fas fa-info-circle me-2"></i>ভিজিটর বিস্তারিত তথ্য</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 35%;" class="bg-light">IP Address</th>
                                <td id="m-ip" class="font-monospace fw-semibold text-primary"></td>
                            </tr>
                            <tr>
                                <th class="bg-light">দেশের নাম</th>
                                <td id="m-country" class="fw-semibold"></td>
                            </tr>
                            <tr>
                                <th class="bg-light">শহর</th>
                                <td id="m-city"></td>
                            </tr>
                            <tr>
                                <th class="bg-light">আইএসপি (ISP)</th>
                                <td id="m-isp" class="fw-semibold text-info"></td>
                            </tr>
                            <tr>
                                <th class="bg-light">ডিভাইস টাইপ</th>
                                <td id="m-device"></td>
                            </tr>
                            <tr>
                                <th class="bg-light">অপারেটিং সিস্টেম</th>
                                <td id="m-platform" class="fw-semibold"></td>
                            </tr>
                            <tr>
                                <th class="bg-light">ব্রাউজার</th>
                                <td id="m-browser"></td>
                            </tr>
                            <tr>
                                <th class="bg-light">ভিজিট করা লিংক</th>
                                <td class="font-monospace text-xs" style="word-break: break-all;" id="m-url"></td>
                            </tr>
                            <tr>
                                <th class="bg-light">রেফারার</th>
                                <td class="font-monospace text-xs text-muted" style="word-break: break-all;"
                                    id="m-referrer"></td>
                            </tr>
                            <tr>
                                <th class="bg-light">সময়কাল</th>
                                <td id="m-time" class="text-muted"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal"
                        style="border-radius: 6px;">বন্ধ করুন</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // 1. Show Details in Modal
            $('.show-visitor-btn').on('click', function() {
                var ip = $(this).data('ip');
                var country = $(this).data('country');
                var city = $(this).data('city');
                var isp = $(this).data('isp');
                var device = $(this).data('device');
                var platform = $(this).data('platform');
                var browser = $(this).data('browser');
                var url = $(this).data('url');
                var referrer = $(this).data('referrer');
                var time = $(this).data('time');

                $('#m-ip').text(ip);
                $('#m-country').text(country);
                $('#m-city').text(city);
                $('#m-isp').text(isp);
                $('#m-device').text(device);
                $('#m-platform').text(platform);
                $('#m-browser').text(browser);
                $('#m-url').text(url);
                $('#m-referrer').text(referrer);
                $('#m-time').text(time);

                $('#visitorDetailModal').modal('show');
            });

            // 2. Check All Checkboxes
            $('#check-all').on('change', function() {
                var isChecked = $(this).is(':checked');
                $('.visitor-checkbox').prop('checked', isChecked);
                toggleBulkDeleteBtn();
            });

            // 3. Row Checkbox Toggle
            $('.visitor-checkbox').on('change', function() {
                toggleBulkDeleteBtn();
                if (!$(this).is(':checked')) {
                    $('#check-all').prop('checked', false);
                }
            });

            function toggleBulkDeleteBtn() {
                var checkedCount = $('.visitor-checkbox:checked').length;
                if (checkedCount > 0) {
                    $('#bulk-delete-btn').prop('disabled', false);
                    $('#selected-count').removeClass('d-none').text(checkedCount + ' টি নির্বাচিত');
                } else {
                    $('#bulk-delete-btn').prop('disabled', true);
                    $('#selected-count').addClass('d-none');
                    $('#check-all').prop('checked', false);
                }
            }
        });
    </script>
@endpush
