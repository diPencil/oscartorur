@extends($activeTemplate . 'layouts.app')
@section('panel')
    @php
        $banned = getContent('banned.content', true);
    @endphp
    <div class="maintenance-page">
        <div class="container">
            <div class="maintenance-content">
                <img class="maintenance-image" src="{{ frontendImage('banned', @$banned->data_values->image, '700x400') }}" alt="@lang('image')">
                <h4 class="text--danger maintenance-text">{{ __(@$banned->data_values->heading) }}</h4>
                <p class="maintenance-reason text-white">{{ __($user->ban_reason) }} </p>
                <a class="btn--base btn btn--sm mt-2" href="{{ route('home') }}"> @lang('Go to Home') </a>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        body {
            background-color: #0c2846;
            display: flex;
            align-items: center;
            height: 100vh;
            justify-content: center;
        }

        .maintenance-content {
            max-width: 700px;
            width: 100%;
            margin: 0 auto;
            text-align: center;
        }

        .maintenance-image {
            max-width: 500px;
            width: 100%;
            margin: 0 auto 24px;
        }

        .maintenance-text {
            margin: 0;
            margin-bottom: 12px;
        }
    </style>
@endpush
