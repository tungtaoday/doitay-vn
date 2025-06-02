@extends('admin.layouts.app')

@php
$pageTitle = 'Quản lý ví công ty';
@endphp

@section('panel')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">{{ $pageTitle }}</h4>
                <a href="{{ route('admin.company-wallets.create') }}" class="btn btn-sm btn-outline--primary">
                    <i class="la la-plus"></i> Tạo ví mới
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Công ty</th>
                                <th>Số dư</th>
                                <th>Tiền tệ</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wallets as $wallet)
                            <tr>
                                <td>{{ $wallet->id }}</td>
                                <td>{{ $wallet->company->name }}</td>
                                <td>{{ number_format($wallet->balance, 2) }}</td>
                                <td>{{ $wallet->currency }}</td>
                                <td>
                                    @php echo $wallet->is_active ? '<span class="badge badge--success">Hoạt động</span>' : '<span class="badge badge--danger">Không hoạt động</span>' @endphp
                                </td>
                                <td>
                                    <a href="{{ route('admin.company-wallets.edit', $wallet->id) }}" class="btn btn-sm btn-outline--primary">
                                        <i class="la la-edit"></i> Chỉnh sửa
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline--success" data-bs-toggle="modal" data-bs-target="#addFundsModal{{ $wallet->id }}">
                                        <i class="la la-plus"></i> Thêm tiền
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline--warning" data-bs-toggle="modal" data-bs-target="#deductFundsModal{{ $wallet->id }}">
                                        <i class="la la-minus"></i> Trừ tiền
                                    </button>
                                </td>
                            </tr>

                            <!-- Add Funds Modal -->
                            <div class="modal fade" id="addFundsModal{{ $wallet->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.company-wallets.add-funds', $wallet->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Thêm tiền vào ví</h5>
                                                <button type="button" class="close" data-bs-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Số tiền</label>
                                                    <input type="number" step="0.01" class="form-control" name="amount" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Ghi chú</label>
                                                    <textarea class="form-control" name="notes"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                <button type="submit" class="btn btn-primary">Thêm tiền</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Deduct Funds Modal -->
                            <div class="modal fade" id="deductFundsModal{{ $wallet->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.company-wallets.deduct-funds', $wallet->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Trừ tiền từ ví</h5>
                                                <button type="button" class="close" data-bs-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Số tiền</label>
                                                    <input type="number" step="0.01" class="form-control" name="amount" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Ghi chú</label>
                                                    <textarea class="form-control" name="notes"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                <button type="submit" class="btn btn-primary">Trừ tiền</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $wallets->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 