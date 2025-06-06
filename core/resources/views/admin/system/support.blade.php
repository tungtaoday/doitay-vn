@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <p class="text-center">@lang('For technical support and assistance, please contact our development team through alternative channels.')</p>
                    <!-- ViserLab support removed for security -->
              </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
<style>
  td{

    font-size: 22px !important;
  }
  .table td {
      white-space: nowrap;
  }
</style>
@endpush
