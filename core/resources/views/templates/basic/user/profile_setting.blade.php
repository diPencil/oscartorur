@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card trip-card">
                        <div class="card-body">
                            <form class="profile-area disableSubmission" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="user-profile">
                                            <div class="thumb">
                                                <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}" alt="user">
                                            </div>
                                            <div class="content">
                                                <h5 class="title">{{ $user->fullname }}</h5>
                                                <span>@lang('Username'): {{ $user->username }}</span>
                                            </div>
                                            <div class="mt-3">
                                                <div class="remove-image btn btn--sm btn--danger w-100 text-center mb-3">
                                                    <i class="las la-times"></i> @lang('Remove')
                                                </div>
                                                <label class="show-image btn btn--base w-100 text-center" for="profile-image">@lang('Change Profile Photo')</label>
                                                <input class="form-control form--control" id="profile-image" name="image" type="file" accept="image/*" hidden>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="user-profile-form row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form--label">@lang('First Name')</label>
                                                    <input type="text" class="form-control form--control" name="firstname" value="{{ $user->firstname }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form--label">@lang('Last Name')</label>
                                                    <input type="text" class="form-control form--control" name="lastname" value="{{ $user->lastname }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form--label">@lang('Email Address')</label>
                                                    <input class="form-control form--control" value="{{ $user->email }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form--label">@lang('Mobile Number')</label>
                                                    <input class="form-control form--control" value="{{ $user->mobile }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form--label">@lang('Address')</label>
                                                    <input type="text" class="form-control form--control" name="address" value="{{ @$user->address }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form--label">@lang('State')</label>
                                                    <input type="text" class="form-control form--control" name="state" value="{{ @$user->state }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form--label">@lang('Zip Code')</label>
                                                    <input type="text" class="form-control form--control" name="zip" value="{{ @$user->zip }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form--label">@lang('City')</label>
                                                    <input type="text" class="form-control form--control" name="city" value="{{ @$user->city }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form--label">@lang('Country')</label>
                                                    <input class="form-control form--control" value="{{ @$user->country_name }}" disabled>
                                                </div>
                                            </div>

                                            <div class="col-12  mt-3">
                                                <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        "use strict"
        var prevImg = $('.user-profile .thumb').html();

        function proPicURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = $('.user-profile').find('.thumb');
                    preview.html(`<img src="${e.target.result}" alt="user">`);
                    preview.addClass('has-image');
                    preview.hide();
                    preview.fadeIn(650);
                    $(".remove-image").show();
                    $(".show-image").hide();
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#profile-image").on('change', function() {
            proPicURL(this);
        });
        $(".remove-image").on('click', function() {
            $(".user-profile .thumb").html(prevImg);
            $(".user-profile .thumb").removeClass('has-image');
            $(this).hide();
            $(".show-image").show();
        })
    </script>
@endpush
