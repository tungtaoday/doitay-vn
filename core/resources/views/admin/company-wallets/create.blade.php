@extends('admin.layouts.app')

@php
$pageTitle = 'Tạo ví mới';
@endphp

@section('panel')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.company-wallets.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Công ty</label>
                        <select name="company_id" class="form-control" required>
                            <option value="">Chọn công ty</option>
                            @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tiền tệ</label>
                        <select name="currency" class="form-control" required>
                            <option value="USD">USD</option>
                            <option value="VND">VND</option>
                            <option value="EUR">EUR</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Số dư ban đầu</label>
                        <input type="number" step="0.01" class="form-control" name="balance" value="0" required>
                    </div>
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <textarea class="form-control" name="notes"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="is_active" value="1" checked>
                            Hoạt động
                        </label>
                    </div>
                    <button type="submit" class="btn btn--primary">Tạo ví</button>
                    <a href="{{ route('admin.company-wallets.index') }}" class="btn btn--dark">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 