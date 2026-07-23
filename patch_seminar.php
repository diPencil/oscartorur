<?php
$file = 'core/resources/views/admin/seminar/form.blade.php';
$content = file_get_contents($file);

$html_to_insert = <<<HTML
                            <div class="col-md-12 mt-3 mb-3">
                                <div class="card border--dark">
                                    <h5 class="card-header bg--dark d-flex justify-content-between">@lang('External Image URLs (Optional)')
                                        <button class="btn btn-sm btn-outline-light addUrlBtn" type="button">
                                            <i class="las la-plus"></i>@lang('Add URL')
                                        </button>
                                    </h5>
                                    <div class="card-body">
                                        <div class="row addedUrlsInfo">
                                            <!-- Dynamic URLs will be appended here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
HTML;

$js_to_insert = <<<JS
            $('.addUrlBtn').on('click', function() {
                $(".addedUrlsInfo").append(`
                <div class="col-md-6 removeUrlInfo">
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control" placeholder="@lang('https://example.com/image.jpg')" name="image_urls[]" type="url" required>
                            <button class="btn input-group-text btn--danger border--danger removeUrlRow" type="button"><i class="fas fa-times text-white"></i></button>
                        </div>
                    </div>
                </div>
                `);
            });

            $(document).on('click', '.removeUrlRow', function() {
                $(this).closest('.removeUrlInfo').remove();
            });
JS;

// Insert HTML before <div class="col-lg-12"> that contains nav-tabs
$content = str_replace('<div class="col-lg-12">
                                <ul class="nav nav-tabs nav-tabs--style1" role="tablist">', $html_to_insert . "\n\n                            <div class=\"col-lg-12\">\n                                <ul class=\"nav nav-tabs nav-tabs--style1\" role=\"tablist\">", $content);

// Insert JS before })(jQuery);
$content = str_replace('})(jQuery);', $js_to_insert . "\n\n        })(jQuery);", $content);

file_put_contents($file, $content);
echo "Patched successfully!";
?>
