<div class="single-review">
    <p class="star"><i class="las la-star text--base"></i> {{ $rating }}</p>
    <div class="progress">
        <div class="progress-bar" role="progressbar" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $percentage }}%"></div>
    </div>
    <span class="percentage">{{ $percentage }}%</span>
</div>