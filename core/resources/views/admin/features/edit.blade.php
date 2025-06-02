@extends('admin.layouts.master')

@section('content')
<div class="page-wrapper default-version">
    @include('admin.partials.sidenav')
    @include('admin.partials.topnav')
    <div class="container-fluid px-3 px-sm-0">
        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                @include('admin.partials.breadcrumb')

                <div class="card">
                    <div class="card-header">
                        <h5>Edit Feature: {{ $feature->name }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('feature.update', ['category' => $category->id, 'feature' => $feature->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Feature Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $feature->name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description">{{ $feature->description }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1" {{ $feature->status ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$feature->status ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Feature</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
