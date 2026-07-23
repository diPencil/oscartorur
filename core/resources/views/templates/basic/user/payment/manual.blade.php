@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="container pt-100 pb-100">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card trip-card">
                    <div class="card-body  ">
                        <form action="{{ route('user.deposit.manual.update') }}" method="POST" class="disableSubmission" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-primary">
                                        <p class="mb-2"><i class="las la-info-circle"></i> @lang('You are requesting') <b>{{ showAmount($data['amount']) }}</b> @lang('to deposit.') @lang('Please pay')
                                            <b>{{ showAmount($data['final_amount'], currencyFormat: false) . ' ' . $data['method_currency'] }} </b> @lang('for successful payment.')
                                        </p>
                                        <hr>
                                        <p class="mb-0">
                                            @lang('Your Booking/Payment Reference Code is:'): <b class="text--base fs-5">{{ $data->trx }}</b><br>
                                            <small>@lang('Please use this code as a reference when transferring money.')</small>
                                        </p>
                                    </div>

                                    <div class="mb-3">@php echo  $data->gateway->description @endphp</div>

                                    @if($data->gateway->qr_code)
                                        <div class="text-center mb-4 mt-3">
                                            <h5 class="mb-3">@lang('Scan QR Code to Pay')</h5>
                                            <img src="{{ getImage(getFilePath('gateway').'/'.$data->gateway->qr_code,getFileSize('gateway')) }}" alt="QR Code" class="img-thumbnail" style="max-width: 250px;">
                                        </div>
                                    @endif

                                </div>

                                <x-viser-form identifier="id" identifierValue="{{ $gateway->form_id }}" />

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn--base w-100">@lang('Pay Now')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
