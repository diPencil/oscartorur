@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <form>
                        <div class="mb-3 search-inner-form">
                            <div class="input-group">
                                <input class="form-control form--control" name="search" type="search" value="{{ request()->search }}" placeholder="@lang('Search by trx|amount|plan')">
                                <button class="input-group-text bg--base text-white">
                                    <i class="las la-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div>
                        @include($activeTemplate . 'partials.payment')
                        @if ($deposits->hasPages())
                            <div class="mt-3">
                                {{ paginateLinks($deposits) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
