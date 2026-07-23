<div class="row">
    <!-- Cover Image Section -->
    <div class="col-md-4">
        <div class="card border--dark mb-4">
            <div class="card-header bg--dark">
                <h5 class="card-title text-white mb-0">@lang('Cover Image')</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.hotel.image.store.cover', $hotel->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="image-upload">
                            <div class="thumb">
                                <div class="avatar-preview">
                                    @php
                                        $cover = $hotel->images()->where('is_cover', 1)->first();
                                    @endphp
                                    <div class="profilePicPreview" style="background-image: url({{ $cover ? getImage(getFilePath('hotelImage').'/'.$cover->image, getFileSize('hotelImage')) : getImage(null, getFileSize('hotelImage')) }})">
                                        <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="avatar-edit">
                                    <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                    <label for="profilePicUpload1" class="bg--success">@lang('Upload Cover')</label>
                                    <small class="mt-2 text-facebook">@lang('Supported files'): <b>jpeg, jpg, png</b>.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group text-center">
                        <span class="text-muted fw-bold">-- @lang('OR') --</span>
                    </div>

                    <div class="form-group">
                        <label>@lang('Image URL')</label>
                        <input type="url" name="image_url" class="form-control" placeholder="https://...">
                    </div>

                    <button type="submit" class="btn btn--primary w-100">@lang('Save Cover Image')</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Gallery Section -->
    <div class="col-md-8">
        <div class="card border--dark mb-4">
            <div class="card-header bg--dark d-flex justify-content-between align-items-center">
                <h5 class="card-title text-white mb-0">@lang('Hotel Gallery')</h5>
                <button type="button" class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#addGalleryModal">
                    <i class="las la-plus"></i> @lang('Add Images')
                </button>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @forelse($hotel->images()->where('is_cover', 0)->get() as $image)
                        <div class="col-md-4 col-sm-6">
                            <div class="card">
                                <img src="{{ getImage(getFilePath('hotelImage').'/'.$image->image, getFileSize('hotelImage')) }}" class="card-img-top object-fit-cover" style="height: 150px" alt="{{ $image->title ?? 'Gallery Image' }}">
                                <div class="card-body p-2 text-center">
                                    <span class="badge badge--info mb-2">{{ $image->category ?? 'General' }}</span>
                                    <form action="{{ route('admin.hotel.image.delete', $image->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn--danger w-100"><i class="las la-trash"></i> @lang('Remove')</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p class="text-muted">@lang('No gallery images uploaded yet.')</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Gallery Modal -->
<div class="modal fade" id="addGalleryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Add Gallery Images')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.hotel.image.store.gallery', $hotel->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Category')</label>
                        <select name="category" class="form-control" required>
                            <option value="exterior">@lang('Exterior')</option>
                            <option value="lobby">@lang('Lobby')</option>
                            <option value="restaurant">@lang('Restaurant')</option>
                            <option value="pool">@lang('Pool')</option>
                            <option value="beach">@lang('Beach')</option>
                            <option value="gym">@lang('Gym')</option>
                            <option value="spa">@lang('Spa')</option>
                            <option value="other">@lang('Other')</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>@lang('Title (Optional)')</label>
                        <input type="text" name="title" class="form-control">
                    </div>
                    <div class="form-group border-bottom pb-3">
                        <label>@lang('Upload File(s)')</label>
                        <input type="file" name="images[]" class="form-control" accept=".png, .jpg, .jpeg" multiple>
                        <small class="text-muted">@lang('You can select multiple images at once.')</small>
                    </div>
                    <div class="form-group text-center">
                        <span class="text-muted fw-bold">-- @lang('OR') --</span>
                    </div>
                    <div class="form-group mt-3">
                        <label>@lang('Image URL')</label>
                        <input type="url" name="image_url" class="form-control" placeholder="https://...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary w-100">@lang('Save Images')</button>
                </div>
            </form>
        </div>
    </div>
</div>
