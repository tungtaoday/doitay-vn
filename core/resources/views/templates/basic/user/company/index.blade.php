@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-100 pb-100 contact-section overflow-hidden section--bg">
        <div class="shape-one"></div>
        <div class="shape-two"></div>
        <div class="shape-three"></div>
        <div class="container">
            <div class="custom--card">
                <div class="card-header bg--base text-white">
                    <h5 class="mb-0">@lang('Danh sách công ty')</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table--responsive--lg">
                            <thead>
                                <tr>
                                    <th>@lang('STT')</th>
                                    <th>@lang('Tên công ty')</th>
                                    <th>@lang('Địa chỉ')</th>
                                    <th>@lang('Đánh giá')</th>
                                    <th>@lang('Trạng thái')</th>
                                    <th>@lang('Thao tác')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($companies as $company)
                                    <tr>
                                        <td data-label="@lang('STT')">{{ $companies->firstItem() + $loop->index }}</td>
                                        <td data-label="@lang('Tên công ty')">
                                            <div class="d-flex align-items-center">
                                                <div class="company-thumb me-2">
                                                    <img src="{{ getImage(getFilePath('company') . '/' . ($company->image ?? 'default.png'), getFileSize('company')) }}" alt="@lang('Company')">
                                                </div>
                                                <div class="company-info">
                                                    <a href="@if (@$company->status == 1) {{ route('company.details', [$company->id, $company->name]) }} @endif"
                                                        class="text--base">
                                                        {{ __(@$company->name) }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="@lang('Địa chỉ')">
                                            <div class="d-flex flex-column">
                                                <span>{{ @$company->address }}</span>
                                                <small class="text-muted">{{ @$company->phone }}</small>
                                            </div>
                                        </td>
                                        <td data-label="@lang('Đánh giá')">
                                            <div class="d-flex align-items-center">
                                                <span class="text--base me-2">
                                                    @php
                                                        echo avgRating(@$company->avg_rating);
                                                    @endphp
                                                </span>
                                                <small class="text-muted">({{ @$company->reviews_count }})</small>
                                            </div>
                                        </td>
                                        <td data-label="@lang('Trạng thái')">
                                            <div class="d-flex align-items-center">
                                                @php echo $company->statusBadge @endphp
                                                @if ($company->admin_feedback && $company->status != 0)
                                                    <button class="btn btn--info btn-sm ms-2 feedback" data-bs-toggle="modal"
                                                        data-bs-target="#companyFeedBackModal" title="@lang('Thông tin phản hồi')"
                                                        data-feedback="{{ $company->admin_feedback }}">
                                                        <i class="la la-info-circle"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td data-label="@lang('Thao tác')">
                                            <div class="d-flex gap-2">
                                                @if ($company->status == 1)
                                                    <button class="btn btn--primary btn-sm infoScript" data-bs-toggle="modal" title="@lang('Sao chép mã')"
                                                        data-bs-target="#scriptModal" data-name="{{ $company->name }}" data-id="{{ $company->id }}"
                                                        data-sitename="{{ gs('site_name') }}" data-url="{{ route('company.rating', encrypt($company->id)) }}"
                                                        data-redirectURL="{{ route('company.details', [$company->id, slug($company->name)]) }}">
                                                        <i class="la la-code"></i>
                                                    </button>
                                                @endif
                                                <a href="{{ route('user.company.edit', $company->id) }}" class="btn btn--base btn-sm" title="@lang('Chỉnh sửa')">
                                                    <i class="la la-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="las la-building text--base" style="font-size: 48px;"></i>
                                                <h4 class="mt-3">@lang('Chưa có công ty nào')</h4>
                                                <p class="text-muted">@lang('Bạn chưa tạo bất kỳ công ty nào.')</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if ($companies->hasPages())
                <div class="mt-4">
                    {{ paginateLinks($companies) }}
                </div>
            @endif

            <div class="has--link">
                <div class="d-flex justify-content-center mt-5">
                    @php echo getAdvertisement('728x90'); @endphp
                </div>
            </div>
        </div>
    </section>

    <!-- Feedback Modal -->
    <div class="modal fade" id="companyFeedBackModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Phản hồi từ quản trị viên')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body admin-feedback">
                </div>
            </div>
        </div>
    </div>

    <!-- Script Modal -->
    <div class="modal fade" id="scriptModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Mã đánh giá')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="copyURL" class="companyScript"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--base copytext border-0 copyBoard" id="copyBoard">
                        <i class="la la-copy"></i> @lang('Sao chép')
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
    .custom--card {
        border: none;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        border-radius: 10px;
        overflow: hidden;
    }
    .company-thumb {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
    }
    .company-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .company-info {
        flex: 1;
    }
    .table--responsive--lg {
        width: 100%;
        margin-bottom: 0;
    }
    .table--responsive--lg thead th {
        background: #f8f9fa;
        padding: 15px;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #dee2e6;
    }
    .table--responsive--lg tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #dee2e6;
    }
    .table--responsive--lg tbody tr:last-child td {
        border-bottom: none;
    }
    .companyScript {
        width: 100%;
        height: 107px;
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis;
        background: #FFFCD7;
        padding: 15px;
        border-radius: 5px;
        font-family: monospace;
    }
    .empty-state {
        text-align: center;
        padding: 20px;
    }
    .btn--base {
        background: var(--base);
        color: white;
    }
    .btn--primary {
        background: var(--base);
        color: white;
    }
    .btn--info {
        background: #17a2b8;
        color: white;
    }
    @media (max-width: 991px) {
        .table--responsive--lg {
            display: block;
            width: 100%;
            overflow-x: auto;
        }
        .table--responsive--lg thead {
            display: none;
        }
        .table--responsive--lg tbody tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        .table--responsive--lg tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            border: none;
            border-bottom: 1px solid #dee2e6;
        }
        .table--responsive--lg tbody td:last-child {
            border-bottom: none;
        }
        .table--responsive--lg tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            margin-right: 10px;
        }
    }
</style>
@endpush

@push('script')
    <script>
        "use strict";
        $(document).ready(function() {
            $(".feedback").on('click', function() {
                $(".admin-feedback").text($(this).data('feedback'))
            })

            $(".infoScript").on('click', function() {
                $(".companyScript").empty();

                let cid = $(this).data('id');
                let cName = $(this).data('name');
                let url = $(this).data('url');
                let siteUrl = $(this).data('redirecturl');
                let siteName = $(this).data('sitename');
                let halfStar = '{{ getImage('assets/images/half-star.svg') }}';
                let fullStar = '{{ getImage('assets/images/full-star.svg') }}';
                let blankStar = '{{ getImage('assets/images/blank-star.svg') }}';

                let scriptData =
                    `&lt;div title="${cName}" class=&quot;rating--here-${cid}&quot; style=&quot;text-align: center; margin: 30px auto 30px;&quot;&gt;&lt;/div&gt;&lt;script&gt;fetch(&quot;${url}&quot;).then((t=&gt;t.json())).then((t=&gt;{let a=t.rating?t.rating:0,s=0,e=&quot;&quot;,l=document.getElementsByClassName(&quot;rating--here-${cid}&quot;),n=t=&gt;e+=&apos;&lt;img width=&quot;25px&quot; style=&quot;margin: 5px auto 5px;&quot; src=&quot;&apos;+t+&apos;&quot;/&gt;&apos;;for(;s&lt;5;)n(a-s&gt;=1?&quot;${fullStar}&quot;:a-s&gt;0?&quot;${halfStar}&quot;:&quot;${blankStar}&quot;),s++;for(let a=0;a&lt;l.length;a++)l[a].innerHTML=&quot;&lt;div &gt;&quot;+e+&quot;&lt;/div&gt; &lt;h6&gt;&quot;+t.rating+t.outOf+&apos;&lt;/h6&gt;&lt;a href=&quot;${siteUrl}&quot; style=&quot;color: #d38a04;&quot; target=&quot;_blank&quot; class=&quot;text--base&quot;&gt;Powered By : ${siteName} &lt;/a&gt;&apos;})).catch((function(t){console.warn(&quot;Something went wrong in script.&quot;,t)})); &lt;/script&gt;`;

                $(".companyScript").append(scriptData);
            });

            $('.copyBoard').on("click", function() {
                var range = document.createRange();
                range.selectNode(document.getElementById("copyURL"));
                window.getSelection().removeAllRanges();
                window.getSelection().addRange(range);
                document.execCommand("copy");
                window.getSelection().removeAllRanges();
                notify('success', "@lang('Đã sao chép mã thành công')");
            });
        });
    </script>
@endpush
