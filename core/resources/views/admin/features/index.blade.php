@extends('admin.layouts.app')
@section('panel')
<div class="row justify-content-center">
    {{-- <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Feature List</h1>
        <!-- Nút tạo Feature -->
        <a href="{{ route('admin.feature.create') }}" class="btn btn-primary">Create Feature</a>
    </div> --}}
    <div class="d-flex justify-content-end mb-3">
        <button id="createBtn" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tạo Feature Mới
        </button>
    </div>
    
    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.feature.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="category" class="form-label">Filter by Category</label>
                <select name="category_id" id="category" class="form-select">
                    <option value="">-- All Categories --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>
    <div class="table-responsive--sm table-responsive">
        <table class="table table--light">            
            <thead>
                <tr>
                    <th>@lang('Category')</th>
                    <th>@lang('category_id')</th>
                    <th>@lang('id')</th>
                    <th>@lang('Tên Feature')</th>
                    <th>@lang('Mô tả')</th>
                    <th>@lang('created_at')</th>
                    <th>@lang('updated_at')</th>
                    <th>@lang('action')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($features as $feature)
                    <tr>
                        <td>{{ $feature->category->name ?? 'No Category' }}</td>
                        <td>{{ $feature->category_id ?? 'No Category' }}</td>
                        <td>{{ $feature->id }}</td>
                        <td>{{ $feature->name }}</td>
                        <td>{{ $feature->description }}</td>
                        <td>{{ $feature->created_at }}</td>
                        <td>{{ $feature->updated_at }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline--primary editBtn"
                                data-id="{{ $feature->id }}"
                                data-category-id="{{ $feature->category_id }}"
                                data-category-name="{{ $feature->category?->name ?? 'N/A' }}"
                                data-name="{{ $feature->name }}"
                                data-description="{{ $feature->description }}"
                                data-modal_title="@lang('Edit Feature')"
                                data-has_status="1">
                                <i class="la la-pencil"></i>@lang('Edit')
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="cuModal" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tạo Feature Mới</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>   
                {{-- <form action="{{ route('admin.feature.update', $feature->id) }}" method="POST" id="featureForm"> --}}
                    <form id="featureForm" method="POST">
                        @csrf <!-- Token xác thực bảo mật -->
                        <input type="hidden" name="id" id="featureId"> <!-- ID feature -->
        
                        <div class="modal-body">
                            <div class="form-group">
                                <label>@lang('Category')</label>
                                <select name="category_id" id="categoryId" class="form-control" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ isset($feature) && $feature->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
        
                            <div class="form-group">
                                <label>@lang('Tên feature')</label>
                                <input type="text" name="name" id="featureName" class="form-control" required>
                            </div>
        
                            <div class="form-group">
                                <label>@lang('Description')</label>
                                <textarea name="description" id="featureDescription" class="form-control"></textarea>
                            </div>
        
                            <div class="form-group">
                                <label>@lang('Status')</label>
                                <select name="status" id="featureStatus" class="form-control" required>
                                    <option value="1" {{ isset($feature) && $feature->status == 1 ? 'selected' : '' }}>
                                        @lang('Active')
                                    </option>
                                    <option value="0" {{ isset($feature) && $feature->status == 0 ? 'selected' : '' }}>
                                        @lang('Inactive')
                                    </option>
                                </select>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    <x-confirmation-modal />

@push('script')
    <script>
        document.querySelectorAll('.editBtn').forEach(button => {
        button.addEventListener('click', function () {
        const form = document.getElementById('featureForm');
        const featureId = this.getAttribute('data-id'); // Lấy ID của feature từ nút Edit

        // Thiết lập action cho form
        form.action = `/admin/feature/${featureId}`;
        // form.querySelector('[name="_method"]').value = 'PUT';
            
        
        // Lấy dữ liệu từ nút Edit
        const categoryId = this.getAttribute('data-category-id'); // ID của category
        const categoryName = this.getAttribute('data-category-name'); // Tên của category
        const categoryDropdown = document.getElementById('categoryId');
        
        // Đảm bảo dropdown category được set giá trị đúng
        categoryDropdown.value = categoryId; // Set value của category_id

        // Lấy các dữ liệu khác và điền vào form
        const featureName = this.getAttribute('data-name');
        const featureDescription = this.getAttribute('data-description');
        const featureStatus = this.getAttribute('data-status');

        // Điền dữ liệu vào các trường input, textarea
        document.getElementById('featureId').value = featureId; // ID ẩn trong form
        document.getElementById('featureName').value = featureName; // Tên Feature
        document.getElementById('featureDescription').value = featureDescription; // Mô tả
        document.getElementById('featureStatus').value = featureStatus; // Trạng thái

        // Hiển thị modal
        const modal = new bootstrap.Modal(document.getElementById('cuModal'));
        modal.show();
            });
        });

    document.getElementById('createBtn').addEventListener('click', function () {
    // Reset form và modal cho việc tạo mới
    document.getElementById('featureForm').reset();
    document.getElementById('modalTitle').textContent = 'Tạo Feature Mới';
    const form = document.getElementById('featureForm');
    form.action = "{{ route('admin.feature.store') }}"; // Đặt action về store route

    const modal = new bootstrap.Modal(document.getElementById('cuModal'));
    modal.show();
});
</script>
@endpush
@endsection



{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1> vui vui </h1>
</body>
</html> --}}