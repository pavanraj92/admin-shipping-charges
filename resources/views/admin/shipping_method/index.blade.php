@extends('admin::admin.layouts.master')

@section('title', 'Shipping Method Management')

@section('page-title', 'Shipping Method Manager')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Shipping Method Manager</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <h4 class="card-title">Filter</h4>
                    <form action="{{ route('admin.shipping_methods.index') }}" method="GET" id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" name="keyword" id="keyword" class="form-control"
                                        value="{{ app('request')->query('keyword') }}" placeholder="Enter title">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control select2">
                                        <option value="">All</option>
                                        <option value="draft"
                                            {{ app('request')->query('status') == 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                        <option value="published"
                                            {{ app('request')->query('status') == 'published' ? 'selected' : '' }}>Published
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-auto mt-1 text-right">
                                <div class="form-group">
                                    <label for="created_at">&nbsp;</label>
                                    <button type="submit" form="filterForm" class="btn btn-primary mt-4">Filter</button>
                                    <a href="{{ route('admin.shipping_methods.index') }}"
                                        class="btn btn-secondary mt-4">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @admincan('shipping_methods_manager_create')
                            <div class="text-right">
                                <a href="{{ route('admin.shipping_methods.create') }}" class="btn btn-primary mb-3">Create New
                                    Shipping Method</a>
                            </div>
                        @endadmincan

                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">S. No.</th>
                                        <th>@sortablelink('shipping_zone', 'Shipping Zone', [], ['class' => 'text-dark'])</th>
                                        <th>@sortablelink('name', 'Name', [], ['class' => 'text-dark'])</th>
                                        <th>@sortablelink('carrier', 'Carrier', [], ['class' => 'text-dark'])</th>
                                        <th>@sortablelink('status', 'Status', [], ['class' => 'text-dark'])</th>
                                        <th>@sortablelink('created_at', 'Created At', [], ['class' => 'text-dark'])</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($shipping_methods) && $shipping_methods->count() > 0)
                                        @php
                                            $i =
                                                ($shipping_methods->currentPage() - 1) * $shipping_methods->perPage() +
                                                1;
                                        @endphp
                                        @foreach ($shipping_methods as $shipping_method)
                                            <tr>
                                                <th scope="row">{{ $i }}</th>
                                                <td>{{ $shipping_method?->zone?->name ?? 'N/A' }}</td>
                                                <td>{{ $shipping_method->name ?? 'N/A' }}</td>
                                                <td>{{ $shipping_method->carrier ?? 'N/A' }}</td>
                                                <td>
                                                    <!-- create update status functionality-->
                                                    @if ($shipping_method->status == '1')
                                                        <a href="javascript:void(0)" data-toggle="tooltip"
                                                            data-placement="top" title="Click to change status to inactive"
                                                            data-url="{{ route('admin.shipping_methods.updateStatus') }}"
                                                            data-method="POST" data-status="0"
                                                            data-id="{{ $shipping_method->id }}"
                                                            class="btn btn-success btn-sm update-status">Active</a>
                                                    @else
                                                        <a href="javascript:void(0)" data-toggle="tooltip"
                                                            data-placement="top" title="Click to change status to active"
                                                            data-url="{{ route('admin.shipping_methods.updateStatus') }}"
                                                            data-method="POST" data-status="1"
                                                            data-id="{{ $shipping_method->id }}"
                                                            class="btn btn-warning btn-sm update-status">InActive</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $shipping_method->created_at
                                                        ? $shipping_method->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                                        : '—' }}
                                                </td>
                                                <td style="width: 10%;">
                                                    @admincan('shipping_methods_manager_view')
                                                        <a href="{{ route('admin.shipping_methods.show', $shipping_method) }}"
                                                            data-toggle="tooltip" data-placement="top" title="View this record"
                                                            class="btn btn-warning btn-sm"><i class="mdi mdi-eye"></i></a>
                                                    @endadmincan
                                                    @admincan('shipping_methods_manager_edit')
                                                        <a href="{{ route('admin.shipping_methods.edit', $shipping_method) }}"
                                                            data-toggle="tooltip" data-placement="top" title="Edit this record"
                                                            class="btn btn-success btn-sm"><i class="mdi mdi-pencil"></i></a>
                                                    @endadmincan
                                                    @admincan('shipping_methods_manager_delete')
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top"
                                                            title="Delete this record"
                                                            data-url="{{ route('admin.shipping_methods.destroy', $shipping_method) }}"
                                                            data-text="Are you sure you want to delete this record?"
                                                            data-method="DELETE" class="btn btn-danger btn-sm delete-record"><i
                                                                class="mdi mdi-delete"></i></a>
                                                    @endadmincan
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center">No records found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <!--pagination move the right side-->
                            @if ($shipping_methods->count() > 0)
                                {{ $shipping_methods->links('admin::pagination.custom-admin-pagination') }}
                            @endif

                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->
@endsection
