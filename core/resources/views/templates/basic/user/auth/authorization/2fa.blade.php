@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="container pt-100 pb-100">
        <div class="d-flex justify-content-center">
            <div class="verification-code-wrapper">
                <div class="verification-area">
                    <form class="submit-form account-form" action="{{ route('user.2fa.verify') }}" method="POST">
                        @csrf

                        @include($activeTemplate . 'partials.verification_code')

                        <div class="form--group">
                            <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .account-form .form--label {
            color: #000 !important;
        }

        .verification-code span {
            background: transparent;
            border: solid 1px #{{ gs('base_color') }}70;
            color: #{{ gs('base_color') }};
        }
    </style>
@endpush
