<div class="{{ $col }}">
    <div class="trip-card">
        <div class="trip-card__thumb">
            <a href="{{ route('plan.details', [$plan->id, slug($plan->name)]) }}" class="w-100 h-100">
                <img src="{{ getImage(getFilePath('plan') . '/' . @$plan->images[0], getFileSize('plan')) }}" alt="image">
            </a>
            <div class="trip-card__price"><span class="fs--14px"></span> {{ showAmount($plan->price) }}</div>
        </div>
        <div class="trip-card__content">
            <h5 class="trip-card__title"><a href="{{ route('plan.details', [$plan->id, slug($plan->name)]) }}">@lang($plan->name)</a></h5>
            <ul class="trip-card__meta mt-2">
                <li>
                    <i class="las la-map-marked-alt"></i>
                    <p>{{ __(@$plan->location->name) }}</p>
                </li>
                <li>
                    <i class="las la-clock"></i>
                    <p>{{ showDateTime(@$plan->departure_time) }}</p>
                </li>
                <li>
                    <i class="las la-user"></i>
                    <p>{{ @$plan->capacity }} @lang('Seat')</p>
                </li>
            </ul>
        </div>
    </div>
</div>
