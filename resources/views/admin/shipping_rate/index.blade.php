@extends('admin::admin.layouts.master')

@section('title', 'Shipping Rates Management')

@section('page-title', 'Shipping Rates Manager')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Shipping Rates Manager</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    <h4 class="card-title">Filter</h4>
                    <form action="{{ route('admin.shipping_rates.index') }}" method="GET" id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" name="keyword" id="keyword" class="form-control"
                                        value="{{ app('request')->query('keyword') }}" placeholder="Enter title">
                                </div>
                            </div>
                            <div class="col-auto mt-1 text-right">
                                <div class="form-group">
                                    <label for="created_at">&nbsp;</label>
                                    <button type="submit" form="filterForm" class="btn btn-primary mt-4">Filter</button>
                                    <a href="{{ route('admin.shipping_rates.index') }}"
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
                        @admincan('shipping_rates_manager_create')
                            <div class="text-right">
                                <a href="{{ route('admin.shipping_rates.create') }}" class="btn btn-primary mb-3">Create New
                                    Shipping Rate</a>
                            </div>
                        @endadmincan

                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">S. No.</th>
                                        <th>@sortablelink('shipping_method', 'Shipping Method', [], ['class' => 'text-dark'])</th>
                                        <th>@sortablelink('based_on', 'Based On', [], ['class' => 'text-dark'])</th>
                                        <th>@sortablelink('rate', 'Rate', [], ['class' => 'text-dark'])</th>
                                        <th>@sortablelink('created_at', 'Created At', [], ['class' => 'text-dark'])</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($shippingRates) && $shippingRates->count() > 0)
                                        @php
                                            $i = ($shippingRates->currentPage() - 1) * $shippingRates->perPage() + 1;
                                        @endphp
                                        @foreach ($shippingRates as $shippingRate)
                                            <tr>
                                                <th scope="row">{{ $i }}</th>
                                                <td>{{ $shippingRate?->method?->name }}</td>
                                                <td>{!! $shippingRate->based_on ? config('shipping_charges.constants.shippingRatesBasedOn')[$shippingRate->based_on] ?? 'N/A' : 'N/A' !!}</td>
                                                <td>{{ $shippingRate->rate }}</td>
                                                <td>
                                                    {{ $shippingRate->created_at
                                                        ? $shippingRate->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                                        : '—' }}
                                                </td>
                                                <td style="width: 10%;">
                                                    @admincan('shipping_rates_manager_view')
                                                        <a href="{{ route('admin.shipping_rates.show', $shippingRate) }}"
                                                            data-toggle="tooltip" data-placement="top" title="View this record"
                                                            class="btn btn-warning btn-sm"><i class="mdi mdi-eye"></i></a>
                                                    @endadmincan
                                                    @admincan('shipping_rates_manager_edit')
                                                        <a href="{{ route('admin.shipping_rates.edit', $shippingRate) }}"
                                                            data-toggle="tooltip" data-placement="top" title="Edit this record"
                                                            class="btn btn-success btn-sm"><i class="mdi mdi-pencil"></i></a>
                                                    @endadmincan
                                                    @admincan('shipping_rates_manager_delete')
                                                        <a href="javascript:void(0)" data-toggle="tooltip" data-placement="top"
                                                            title="Delete this record"
                                                            data-url="{{ route('admin.shipping_rates.destroy', $shippingRate) }}"
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
                                            <td colspan="5" class="text-center">No records found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <!--pagination move the right side-->
                            @if ($shippingRates->count() > 0)
                                {{ $shippingRates->links('admin::pagination.custom-admin-pagination') }}
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
