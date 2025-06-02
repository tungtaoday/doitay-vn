@extends('admin.layouts.app')

@php
$pageTitle = 'Chỉnh sửa ví';
@endphp

@section('panel')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.company-wallets.update', $wallet->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Công ty</label>
                        <select name="company_id" class="form-control" required disabled>
                            <option value="{{ $wallet->company->id }}" selected>{{ $wallet->company->name }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tiền tệ</label>
                        <select name="currency" class="form-control" required>
                            <option value="USD" {{ $wallet->currency == 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="VND" {{ $wallet->currency == 'VND' ? 'selected' : '' }}>VND</option>
                            <option value="EUR" {{ $wallet->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Số dư hiện tại</label>
                        <input type="text" class="form-control" value="{{ number_format($wallet->balance, 2) }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <textarea class="form-control" name="notes">{{ $wallet->notes }}</textarea>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ $wallet->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Hoạt động</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn--primary">Cập nhật</button>
                    <a href="{{ route('admin.company-wallets.index') }}" class="btn btn--dark">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 