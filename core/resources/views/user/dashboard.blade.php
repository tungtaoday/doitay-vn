@extends('layouts.app')

@php
$pageTitle = 'Dashboard';
@endphp

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Thống kê của bạn</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Wallet Balance -->
                    <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                        <div class="dashboard-w1 bg--primary b-radius--10 box-shadow">
                            <div class="icon">
                                <i class="la la-wallet"></i>
                            </div>
                            <div class="details">
                                <div class="numbers">
                                    <span class="amount">{{ number_format($user->wallet_balance, 2) }}</span>
                                    <span class="currency-sign">{{ $user->wallet_currency }}</span>
                                </div>
                                <div class="desciption">
                                    <span>Số dư ví</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Views -->
                    <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                        <div class="dashboard-w1 bg--success b-radius--10 box-shadow">
                            <div class="icon">
                                <i class="la la-eye"></i>
                            </div>
                            <div class="details">
                                <div class="numbers">
                                    <span class="amount">{{ number_format($user->total_views) }}</span>
                                </div>
                                <div class="desciption">
                                    <span>Tổng lượt xem</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Appointments -->
                    <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                        <div class="dashboard-w1 bg--info b-radius--10 box-shadow">
                            <div class="icon">
                                <i class="la la-calendar"></i>
                            </div>
                            <div class="details">
                                <div class="numbers">
                                    <span class="amount">{{ number_format($user->total_appointments) }}</span>
                                </div>
                                <div class="desciption">
                                    <span>Tổng lượt đặt lịch</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Feedbacks -->
                    <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                        <div class="dashboard-w1 bg--warning b-radius--10 box-shadow">
                            <div class="icon">
                                <i class="la la-comments"></i>
                            </div>
                            <div class="details">
                                <div class="numbers">
                                    <span class="amount">{{ number_format($user->total_feedbacks) }}</span>
                                </div>
                                <div class="desciption">
                                    <span>Tổng lượt feedback</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Statistics -->
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Thống kê theo công ty</h4>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive--sm table-responsive">
                                    <table class="table table--light style--two">
                                        <thead>
                                            <tr>
                                                <th>Công ty</th>
                                                <th>Lượt xem</th>
                                                <th>Lượt đặt lịch</th>
                                                <th>Lượt feedback</th>
                                                <th>Doanh thu</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->companies as $company)
                                            <tr>
                                                <td>{{ $company['name'] }}</td>
                                                <td>{{ number_format($company['views']) }}</td>
                                                <td>{{ number_format($company['appointments']) }}</td>
                                                <td>{{ number_format($company['feedbacks']) }}</td>
                                                <td>{{ number_format($company['revenue'], 2) }} {{ $user->wallet_currency }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 