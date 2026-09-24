@forelse ($settings as $setting)
    @php
        // ডাটাবেস অবজেক্ট বা অ্যারে উভয় ক্ষেত্রেই যেন ক্রাশ না করে তার সুরক্ষা
        $key = is_object($setting) ? $setting->key : $setting['key'] ?? '';
        $value = is_object($setting) ? $setting->value : $setting['value'] ?? '';

        $isImage = is_string($value) && preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $value);
    @endphp
    <tr>
        <td class="align-middle text-center">{{ $loop->iteration }}</td>
        <td class="align-middle">
            <strong>{{ ucfirst(str_replace(['_', '-'], ' ', $key)) }}</strong>
        </td>
        <td class="align-middle">
            @if ($isImage)
                @php
                    $displayValue = $value;
                    if ($displayValue && is_string($displayValue)) {
                        if (!str_starts_with($displayValue, 'uploads/settings/') && !str_starts_with($displayValue, 'http://') && !str_starts_with($displayValue, 'https://')) {
                            $displayValue = 'uploads/settings/' . $displayValue;
                        }
                    }
                @endphp
                <img src="{{ asset($displayValue) }}"
                     alt="{{ $key }}"
                     style="height: 40px; width: auto;"
                     class="img-thumbnail shadow-sm cursor-pointer image-preview-trigger"
                     data-toggle="modal"
                     data-target="#imageModal"
                     data-image-src="{{ asset($displayValue) }}"
                     data-image-alt="{{ $key }}">
            @else
               <span class="text-break">
                    @php
                        $plainText = is_array($value) ? json_encode($value) : $value;
                    @endphp

                    {{ \Illuminate\Support\Str::limit($plainText, 60, '...') }}
                </span>
            @endif
        </td>
        <td class="text-center align-middle">
            <div class="btn-group">
                @can('general-setting_update')
                    <button class="btn btn-info btn-sm edit-btn"
                            data-toggle="modal"
                            data-target="#editSettingModal"
                            data-key="{{ $key }}"
                            data-value="{{ $value }}">
                        <i class="fa fa-edit mr-1"></i> Edit
                    </button>
                @endcan

                @can('general-setting_delete')
                    <button class="btn btn-danger btn-sm delete-btn"
                            data-id="{{ $key }}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                @endcan
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center py-5 text-muted">
            <i class="fas fa-cog fa-2x mb-3 d-block"></i>
            No settings available yet.
        </td>
    </tr>
@endforelse
