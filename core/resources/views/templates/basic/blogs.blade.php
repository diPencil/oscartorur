@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row gy-4 justify-content-center">
                @forelse($blogs as $blog)
                    <div class="col-lg-4 col-md-6">
                        <div class="post-card wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                            <div class="post-card__thumb">
                                <a class="w-100 h-100" href="{{ route('blog.details', $blog->slug) }}"><img src="{{ frontendImage('blog', 'thumb_' . @$blog->data_values->image, '430x285') }}" alt="image"></a>
                            </div>
                            <div class="post-card__content">
                                <ul class="post-card__meta mb-1">
                                    <li>
                                        <a href="javascript:void(0)">
                                            <i class="las la-calendar"></i>
                                            <span>{{ showDateTime($blog->created_at, 'd M, Y') }}</span>
                                        </a>
                                    </li>
                                </ul>
                                <h5 class="post-card__title"><a href="{{ route('blog.details', $blog->slug) }}">{{ __(@$blog->data_values->title) }}</a></h5>
                                <p class="mt-4"> @php echo __(strLimit(strip_tags($blog->data_values->description), 90));@endphp</p>
                                <a class="text--btn text-decoration-underline mt-3" href="{{ route('blog.details', $blog->slug) }}">@lang('Read More')</a>
                            </div>
                        </div><!-- post-card end -->
                    </div>
                @empty
                    @include($activeTemplate . 'partials.empty', ['message' => 'Blog not found!'])
                @endforelse
                {{ paginateLinks($blogs) }}
            </div>
        </div>
    </section>
    <!-- blog section end -->

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
