@extends('admin::admin.layouts.master')

@section('title', 'Shipping Methods Management')

@section('page-title', 'Shipping Method Details')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.shipping_methods.index') }}">Shipping
            Methods Manager</a></li>
    <li class="breadcrumb-item active" aria-current="page">Shipping Method Details</li>
@endsection

@section('content')
    <!-- Container fluid  -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="card-title mb-0">{{ $shippingMethod->name ?? 'N/A' }}</h4>
                            <div>
                                <a href="{{ route('admin.shipping_methods.index') }}" class="btn btn-secondary ml-2">
                                    <i class="mdi mdi-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header bg-primary">
                                        <h5 class="mb-0 text-white font-bold">Shipping Method Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Zone:</label>
                                                    <p>{{ $shippingMethod->zone->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Carrier:</label>
                                                    <p>{{ $shippingMethod->carrier ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Delivery Time:</label>
                                                    <p>{{ $shippingMethod->delivery_time ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Base Rate:</label>
                                                    <p>{{ $shippingMethod->base_rate ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Created At:</label>
                                                    <p>
                                                        {{ $shippingMethod->created_at
                                                            ? $shippingMethod->created_at->format(config('GET.admin_date_time_format') ?? 'Y-m-d H:i:s')
                                                            : '—' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Status:</label>
                                                    <p>
                                                        {!! $shippingMethod->status
                                                            ? config('shipping_charges.constants.aryShippingStatusLabel')[$shippingMethod->status] ?? 'N/A'
                                                            : 'N/A' !!}
                                                    </p>
                                                </div>
                                            </div>
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
                                            <a href="{{ route('admin.shipping_methods.edit', $shippingMethod) }}" class="btn btn-warning mb-2">
                                                <i class="mdi mdi-pencil"></i> Edit Shipping Method
                                            </a>
                                            @admincan('shipping_methods_delete')
                                            <button type="button" class="btn btn-danger delete-btn"
                                                data-url="{{ route('admin.shipping_methods.destroy', $shippingMethod) }}">
                                                <i class="mdi mdi-delete"></i> Delete Shipping Method
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
