@php
    $breadcrumb = getContent('breadcrumb.content', true);
@endphp
<section class="inner-hero bg_img" style="background-image: url('{{ frontendImage('breadcrumb', @$breadcrumb->data_values->image, '1920x1186') }}');">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="page-title text-white">@lang($pageTitle)</h2>
            </div>
        </div>
    </div>
</section>
