@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="blog-details-area">
                        <div class="blog-details-thumb">
                            <img class="w-100 rounded-3" src="{{ frontendImage('blog', @$blog->data_values->image, '855x570') }}" alt="">
                        </div>
                        <div class="blog-details-content">
                            <ul class="post-meta mb-1">
                                <li>
                                    <i class="las la-eye"></i> {{ @$blog->views }}
                                </li>
                                <li>
                                    <i class="lar la-calendar-alt"></i> {{ showDateTime($blog->created_at, 'd M, Y') }}
                                </li>
                            </ul>
                            <h3 class="blog-details-title">{{ __(@$blog->data_values->title) }}</h3>

                            @php echo @$blog->data_values->description @endphp

                        </div>
                        <div class="blog-details__share">
                            <h5 class="blog-details__share-title">@lang('Share This') : </h5>
                            <ul class="social-icons">
                                <li class="social-list__item">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                                <li class="social-list__item">
                                    <a href="https://twitter.com/intent/tweet?text={{ @$blog->data_values->title }}&amp;url={{ urlencode(url()->current()) }}" target="_blank">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>
                                <li class="social-list__item">
                                    <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ @$blog->data_values->title }}&amp;summary=dit is de linkedin summary" target="_blank">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                </li>
                                <li class="social-list__item">
                                    <a href="https://www.instagram.com/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="fb-comments" data-href="{{ url()->current() }}" data-numposts="5"></div>
                </div>
                <div class="col-lg-4">
                    <div class="sidebar">
                        <div class="widget">
                            <h5 class="widget__title">@lang('Recent Posts')</h5>
                            <ul class="small-post-list">
                                @forelse($recentBlogs as $blog)
                                    <li class="small-post">
                                        <div class="small-post__thumb">
                                            <img src="{{ frontendImage('blog', 'thumb_' . @$blog->data_values->image, '430x285') }}" alt="">
                                        </div>
                                        <div class="small-post__content">
                                            <h5 class="post__title"><a href="{{ route('blog.details', $blog->slug) }}">{{ strLimit(__(@$blog->data_values->title), 58) }}</a></h5>
                                        </div>
                                    </li>
                                @empty
                                    @include($activeTemplate . 'partials.empty', ['message' => 'Blog not found!'])
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('fbComment')
    @php echo loadExtension('fb-comment') @endphp
@endpush
