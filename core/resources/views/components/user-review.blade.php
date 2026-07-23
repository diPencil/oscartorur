<div class="single-rating">
    <div class="single-rating__thumb">
        <img src="{{ avatar(@$item->user->image ? getFilePath('userProfile') . '/' . @$item->user->image : null, false) }}" alt="user">
    </div>
    <div class="single-rating__content">
        <div class="d-flex align-items-center flex-wrap gap-1">
            <h5 class="name">{{ $item->user->fullname }}</h5> 
            <small class="text-muted ms-2 fs--12px">{{ diffForHumans($item->created_at) }}</small>
        </div>
        <div class="d-flex align-items-center mt-1">
            <div class="ratings d-flex align-items-center justify-content-end fs--18px">
                @php
                    echo rating($item->rating);
                @endphp
            </div>
        </div>
        <p class="mt-2">{{ @$item->review }}</p>
    </div>
</div>
