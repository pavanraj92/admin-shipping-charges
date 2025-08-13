@extends('admin::admin.layouts.master')

@section('title', 'Shipping Rates Management')

@section('page-title', 'Shipping Rate Details')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.shipping_rates.index') }}">Shipping Rates
            Manager</a></li>
    <li class="breadcrumb-item active" aria-current="page">Shipping Rate Details</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="card-title mb-0">{{ $shippingRate->method->name ?? 'N/A' }} - Rate
                                #{{ $shippingRate->id }}</h4>
                            <div>
                                <a href="{{ route('admin.shipping_rates.index') }}" class="btn btn-secondary ml-2">
                                    Back
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Shipping Rate Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Method:</label>
                                                    <p>{{ $shippingRate->method->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Based On:</label>
                                                    <p>{!! $shippingRate->based_on
                                                        ? config('shipping_charges.constants.shippingRatesBasedOn')[$shippingRate->based_on] ?? 'N/A'
                                                        : 'N/A' !!}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Min Value:</label>
                                                    <p>{{ $shippingRate->min_value ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Max Value:</label>
                                                    <p>{{ $shippingRate->max_value ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Rate:</label>
                                                    <p>{{ $shippingRate->rate ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Status:</label>
                                                    <p>
                                                        {!! $shippingRate->status ? config('admin.constants.aryPageStatusLabel')[$shippingRate->status] ?? 'N/A' : 'N/A' !!}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Created At:</label>
                                                <p>
                                                    {{ $shippingRate->created_at
                                                        ? $shippingRate->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                                        : '—' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header bg-primary">
                                            <h5 class="mb-0 text-white font-bold">Quick Actions</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex flex-column">
                                                @admincan('shipping_rates_manager_edit')
                                                <a href="{{ route('admin.shipping_rates.edit', $shippingRate) }}"
                                                    class="btn btn-warning mb-2">
                                                    <i class="mdi mdi-pencil"></i> Edit Shipping Rate
                                                </a>
                                                @endadmincan
                                                @admincan('shipping_rates_manager_delete')
                                                    <button type="button" class="btn btn-danger delete-btn delete-record"
                                                        title="Delete this record"  
                                                        data-url="{{ route('admin.shipping_rates.destroy', $shippingRate) }}"
                                                        data-redirect="{{ route('admin.shipping_rates.index') }}"
                                                        data-text="Are you sure you want to delete this record?"
                                                        data-method="DELETE"
                                                        >
                                                        <i class="mdi mdi-delete"></i> Delete Shipping Rate
                                                    </button>
                                                @endadmincan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Container fluid  -->
    @endsection
