<div class="row">
    @php
        $formData = $formData ?? [];
        $formData = is_string($formData) ? json_decode($formData, true) : $formData;
        $formData = is_iterable($formData) ? $formData : [];
    @endphp
    @foreach($formData as $data)
        @php
            $type = data_get($data, 'type');
            $label = data_get($data, 'label');
            $name = data_get($data, 'name');
            $isRequired = data_get($data, 'is_required') == 'required';
            $instruction = data_get($data, 'instruction');
            $options = data_get($data, 'options', []);
            if (is_string($options)) {
                $decodedOptions = json_decode($options, true);
                $options = json_last_error() === JSON_ERROR_NONE ? $decodedOptions : explode(',', $options);
            }
            $options = is_array($options) ? array_values(array_filter($options, fn ($option) => $option !== null && $option !== '')) : [];
            $extensions = data_get($data, 'extensions', '');
            if (is_string($extensions)) {
                $decodedExtensions = json_decode($extensions, true);
                $extensions = json_last_error() === JSON_ERROR_NONE ? $decodedExtensions : $extensions;
            }
            $extensions = is_array($extensions) ? implode(',', array_filter($extensions)) : $extensions;
        @endphp
        <div class="col-md-{{ data_get($data, 'width', '12') }}">
            <div class="form-group">
                <label class="form-label">{{ __($name) }} @if($instruction) <span data-bs-toggle="tooltip" data-bs-title="{{ __($instruction) }}"><i class="fas fa-info-circle"></i></span> @endif @if($isRequired && ($type == 'checkbox' || $type == 'radio')) <span class="text--danger">*</span> @endif </label>
                @if($type == 'text')
                    <input type="text"
                    class="form-control form--control"
                    name="{{ $label }}"
                    value="{{ old($label) }}"
                    @if($isRequired) required @endif
                    >
                @elseif($type == 'url')
                    <input type="url"
                    class="form-control form--control"
                    name="{{ $label }}"
                    value="{{ old($label) }}"
                    @if($isRequired) required @endif
                    >
                @elseif($type == 'email')
                    <input type="email"
                    class="form-control form--control"
                    name="{{ $label }}"
                    value="{{ old($label) }}"
                    @if($isRequired) required @endif
                    >
                @elseif($type == 'datetime')
                    <input type="datetime-local"
                    class="form-control form--control"
                    name="{{ $label }}"
                    value="{{ old($label) }}"
                    @if($isRequired) required @endif
                    >
                @elseif($type == 'date')
                    <input type="date"
                    class="form-control form--control"
                    name="{{ $label }}"
                    value="{{ old($label) }}"
                    @if($isRequired) required @endif
                    >
                @elseif($type == 'time')
                    <input type="time"
                    class="form-control form--control"
                    name="{{ $label }}"
                    value="{{ old($label) }}"
                    @if($isRequired) required @endif
                    >
                @elseif($type == 'number')
                    <input type="number"
                    class="form-control form--control"
                    name="{{ $label }}"
                    value="{{ old($label) }}"
                    step="any"
                    @if($isRequired) required @endif
                    >
                @elseif($type == 'textarea')
                    <textarea
                        class="form-control form--control"
                        name="{{ $label }}"
                        @if($isRequired) required @endif
                    >{{ old($label) }}</textarea>
                @elseif($type == 'select')
                    <select
                        class="form-select form--control select2" data-minimum-results-for-search="-1"
                        name="{{ $label }}"
                        @if($isRequired) required @endif
                    >
                        <option value="">@lang('Select One')</option>
                        @foreach ($options as $item)
                            <option value="{{ $item }}" @selected($item == old($label))>{{ __($item) }}</option>
                        @endforeach
                    </select>
                @elseif($type == 'checkbox')
                    <div class="d-flex gap-3 flex-wrap">
                        @foreach($options as $option)
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    name="{{ $label }}[]"
                                    type="checkbox"
                                    value="{{ $option }}"
                                    id="{{ $label }}_{{ titleToKey($option) }}"
                                >
                                <label class="form-check-label" for="{{ $label }}_{{ titleToKey($option) }}">{{ $option }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="checkbox-required-error text--danger"></div>
                @elseif($type == 'radio')
                    <div class="d-flex gap-3 flex-wrap">
                        @foreach($options as $option)
                            <div class="form-check">
                                <input
                                class="form-check-input"
                                name="{{ $label }}"
                                type="radio"
                                value="{{ $option }}"
                                id="{{ $label }}_{{ titleToKey($option) }}"
                                @checked($option == old($label))
                                >
                                <label class="form-check-label" for="{{ $label }}_{{ titleToKey($option) }}">{{ $option }}</label>
                            </div>
                        @endforeach
                    </div>
                @elseif($type == 'file')
                    <input
                    type="file"
                    class="form-control form--control"
                    name="{{ $label }}"
                    @if($isRequired) required @endif
                    accept="@foreach(explode(',', $extensions) as $ext) @if($ext).{{ $ext }}, @endif @endforeach"
                    >
                    <pre class="text--base mt-1">@lang('Supported mimes'): {{ $extensions }}</pre>
                @endif
            </div>
        </div>
    @endforeach
</div>
@push('script')
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
@endpush
