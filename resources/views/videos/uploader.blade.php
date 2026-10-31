@extends('layouts.main')
@section('title')
{{ __('Video Upload') }}
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center mt-3">
            <div class="card col-md-8">
                <div class="card-header text-center">
                    {{ __('upload new videos') }}
                </div>
                <div class="card-body">
                    <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('Video Title') }}</label>
                            <input  type="text" class="form-control @error('title') is-invalid @enderror"
                                name="title" id="title" value="{{ old('title') }}" />
                            <div class="invalid-feedback">
                                @error('title')
                                    <strong>{{ $message }}</strong>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 file-area">
                            <label for="image" class="form-label">{{ __('Cover Imgae') }}</label>
                            <input type="file" name="image" id="image"
                                class="form-control @error('title') is-invalid @enderror" accept="Image/*"
                                onchange="readCoverImage(this)" />
                            <div class="input-title">{{ __('Drag and drop you image here or click to select manualy') }}
                            </div>
                            <div class="invalid-feedback">
                                @error('image')
                                    <strong>{{ $message }}</strong>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <img  id="cover-image-thumb" class="col-2" width="100"
                                height="100">
                            <span class="col-6 input-name"></span>
                        </div>
                        <div class="mb-3 file-area">
                            <label for="video" class="form-label">{{ __('Video') }}</label>
                            <input type="file" name="video" id="video"
                                class="form-control @error('title') is-invalid @enderror" accept="Video/*"
                                onchange="readVideo(this)" />
                            <div class="input-title">{{ __('Drag and drop your video here or click to select manualy') }}
                            </div>
                            <div class="invalid-feedback">
                                @error('video')
                                    <strong>{{ $message }}</strong>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <span class="col-6 ">
                                <div class="video-title-wrap alert alert-info d-none">
                                        {{ __('You Choosed') }} <span class="video-title"></span>
                                </div>
                            </span>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <button class="btn btn-secondary " type="submit">{{ __('Upload') }}</button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>
@section('script')
    <script>
        function readCoverImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    $('#cover-image-thumb').attr('src', e.target.result);

                };
                reader.readAsDataURL(input.files[0]);
                $('.input-name').html(input.files[0].name);
            }

        }

        function readVideo(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.readAsDataURL(input.files[0]);
                 $('.video-title-wrap').removeClass('d-none');
                $('.video-title').html(input.files[0].name);
               

            }
        }
    </script>
@endsection
@endsection
