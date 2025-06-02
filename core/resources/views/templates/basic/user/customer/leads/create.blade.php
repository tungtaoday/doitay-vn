@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="container-fluid px-3 px-sm-4 py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">{{ $pageTitle }}</h2>
                    <p class="text-muted mb-0">Tạo yêu cầu dịch vụ để tìm thợ phù hợp</p>
                </div>
                <a href="{{ route('user.customer.leads.index') }}" class="btn btn-outline-primary">
                    <i class="las la-list"></i> Danh sách Leads
                </a>
            </div>
        </div>
    </div>

    <!-- Create Lead Form -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Thông tin yêu cầu dịch vụ</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.customer.leads.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Danh mục dịch vụ <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mức độ khẩn cấp <span class="text-danger">*</span></label>
                                <select name="urgency" class="form-select" required>
                                    <option value="low" {{ old('urgency') == 'low' ? 'selected' : '' }}>Không khẩn cấp</option>
                                    <option value="medium" {{ old('urgency') == 'medium' ? 'selected' : 'selected' }}>Bình thường</option>
                                    <option value="high" {{ old('urgency') == 'high' ? 'selected' : '' }}>Khẩn cấp</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Tiêu đề yêu cầu <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" 
                                   placeholder="VD: Sửa chữa điện nước tại nhà" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Mô tả chi tiết <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="4" 
                                      placeholder="Mô tả chi tiết về công việc cần thực hiện..." required>{{ old('description') }}</textarea>
                        </div>

                        <!-- Location -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                <input type="text" name="district" class="form-control" value="{{ old('district') }}" 
                                       placeholder="VD: Quận 1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                <input type="text" name="ward" class="form-control" value="{{ old('ward') }}" 
                                       placeholder="VD: Phường Bến Nghé" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Thời gian cần hoàn thành</label>
                                <input type="date" name="needed_by" class="form-control" value="{{ old('needed_by') }}" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Địa chỉ cụ thể <span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control" value="{{ old('address') }}" 
                                   placeholder="Số nhà, tên đường..." required>
                        </div>

                        <!-- Budget -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Ngân sách tối thiểu (VNĐ)</label>
                                <input type="number" name="budget_min" class="form-control" value="{{ old('budget_min') }}" 
                                       placeholder="0" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngân sách tối đa (VNĐ)</label>
                                <input type="number" name="budget_max" class="form-control" value="{{ old('budget_max') }}" 
                                       placeholder="0" min="0">
                            </div>
                        </div>

                        <!-- Requirements -->
                        <div class="mb-4">
                            <label class="form-label">Yêu cầu đặc biệt</label>
                            <div id="requirements-container">
                                <div class="input-group mb-2">
                                    <input type="text" name="requirements[]" class="form-control" 
                                           placeholder="VD: Có kinh nghiệm tối thiểu 2 năm">
                                    <button type="button" class="btn btn-outline-success add-requirement">
                                        <i class="las la-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Attachments -->
                        <div class="mb-4">
                            <label class="form-label">File đính kèm (tùy chọn)</label>
                            <input type="file" name="attachments[]" class="form-control" multiple 
                                   accept="image/*,.pdf,.doc,.docx">
                            <small class="form-text text-muted">Có thể đính kèm hình ảnh, PDF, Word. Tối đa 5MB mỗi file.</small>
                        </div>

                        <!-- Submit -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('user.customer.leads.index') }}" class="btn btn-outline-secondary">
                                <i class="las la-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn--base">
                                <i class="las la-paper-plane"></i> Tạo yêu cầu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    // Add more requirements
    $(document).on('click', '.add-requirement', function() {
        const container = $('#requirements-container');
        const newInput = `
            <div class="input-group mb-2">
                <input type="text" name="requirements[]" class="form-control" 
                       placeholder="Nhập yêu cầu khác...">
                <button type="button" class="btn btn-outline-danger remove-requirement">
                    <i class="las la-minus"></i>
                </button>
            </div>
        `;
        container.append(newInput);
    });

    // Remove requirement
    $(document).on('click', '.remove-requirement', function() {
        $(this).closest('.input-group').remove();
    });

    // Budget validation
    $('input[name="budget_max"]').on('input', function() {
        const minBudget = parseFloat($('input[name="budget_min"]').val()) || 0;
        const maxBudget = parseFloat($(this).val()) || 0;
        
        if (maxBudget > 0 && maxBudget < minBudget) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">Ngân sách tối đa phải lớn hơn ngân sách tối thiểu</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });
</script>
@endpush

@push('style')
<style>
    .lead-creation-form .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }
    
    .lead-creation-form .card {
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    
    .input-group .btn {
        border-left: 0;
    }
    
    .requirements-hint {
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
    }
</style>
@endpush 