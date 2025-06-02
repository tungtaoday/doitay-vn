@extends($activeTemplate.'layouts.auth')
@section('content')
    <section class="pt-50 pb-50 contact-section overflow-hidden">
        <div class="shape-one"></div>
        <div class="shape-two"></div>
        <div class="shape-three"></div>
        <div class="container">
            <div class="row justify-content-center mt-4">
                <div class="col-md-12">
                    <div class="card custom--card">
                        <div class="card-header bg--dark">
                            <h5 class="text-white card-title">{{ __($pageTitle) }}</h5>
                        </div>
                        <div class="card-body">
                            <form  action="{{route('ticket.store')}}"  method="post" enctype="multipart/form-data" class="disableSubmission">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">@lang('Name')</label>
                                        <input type="text" name="name" value="{{@$user->firstname . ' '.@$user->lastname}}" class="form-control form--control" required readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">@lang('Email Address')</label>
                                        <input type="email"  name="email" value="{{@$user->email}}" class="form-control form--control" required readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">@lang('Subject')</label>
                                        <input type="text" name="subject" value="{{old('subject')}}" class="form-control form--control" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">@lang('Priority')</label>
                                        <select name="priority" class="form-control form--control select2" data-minimum-results-for-search="-1" required>
                                            <option value="3">@lang('High')</option>
                                            <option value="2">@lang('Medium')</option>
                                            <option value="1">@lang('Low')</option>
                                        </select>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label class="form-label">@lang('Message')</label>
                                        <textarea name="message" id="inputMessage" rows="6" class="form-control form--control" required>{{old('message')}}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <button type="button" class="btn btn-dark btn-sm addAttachment my-2">
                                                <i class="las la-plus"></i> @lang('Add Attachment')
                                            </button>
                                            <p class="mb-2">
                                                <span class="text--info">
                                                    @lang('Max 5 files can be uploaded | Maximum upload size is '.convertToReadableSize(ini_get('upload_max_filesize')) .' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')
                                                </span>
                                            </p>
                                            <div class="row fileUploadsContainer"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn--base btn-sm w-100 my-2" type="submit">
                                                <i class="las la-paper-plane"></i> @lang('Submit')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";

            $('.select2').select2();

            var fileAdded = 0;
            $('.addAttachment').on('click',function(){
                fileAdded++;
                if (fileAdded == 5) {
                    $(this).attr('disabled',true)
                }
                $(".fileUploadsContainer").append(`
                    <div class="col-lg-4 col-md-12 removeFileInput">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="file" name="attachments[]" class="form-control form--control" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                                <button type="button" class="input-group-text removeFile bg--danger border--danger text-white border-0"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                `)
            });
            $(document).on('click','.removeFile',function(){
                $('.addAttachment').removeAttr('disabled',true)
                fileAdded--;
                $(this).closest('.removeFileInput').remove();
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .input-group-text:focus{
            box-shadow: none !important;
        }
    </style>
@endpush
