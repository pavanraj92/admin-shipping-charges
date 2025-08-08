@extends('admin::admin.layouts.master')

@section('title', 'Shipping Methods Management')

@section('page-title', isset($shippingMethod) ? 'Edit Shipping Method' : 'Create Shipping Method')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.shipping_methods.index') }}">Shipping
            Methods Manager</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        {{ isset($shippingMethod) ? 'Edit Shipping Method' : 'Create Shipping Method' }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <form
            action="{{ isset($shippingMethod) ? route('admin.shipping_methods.update', $shippingMethod->id) : route('admin.shipping_methods.store') }}"
            method="POST" id="shippingMethodForm">
            @if (isset($shippingMethod))
                @method('PUT')
            @endif
            @csrf
            <!-- Start Page Content -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Shipping Zone<span class="text-danger">*</span></label>
                                    <select name="zone_id" class="form-control select2" required>
                                        <option value="">Select Zone</option>
                                        @foreach ($shippingZones as $zone)
                                            <option value="{{ $zone->id }}"
                                                {{ ($shippingMethod?->zone_id ?? old('zone_id')) == $zone->id ? 'selected' : '' }}>
                                                {{ $zone->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('zone_id')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name<span class="text-danger">*</span></label>
                                    <input type="text" name="name" placeholder="Enter Shipping Method Name (e.g. Standard Shipping)" class="form-control"
                                        value="{{ $shippingMethod?->name ?? old('name') }}" required>
                                    @error('name')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Carrier<span class="text-danger">*</span></label>
                                    <input type="text" name="carrier" placeholder="Enter Carrier (e.g. FedEx)" class="form-control"
                                        value="{{ $shippingMethod?->carrier ?? old('carrier') }}" required>
                                    @error('carrier')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Delivery Time<span class="text-danger">*</span></label>
                                    <input type="text" name="delivery_time" placeholder="Enter Delivery Time (e.g. 3-5 Business Days)" class="form-control"
                                        value="{{ $shippingMethod?->delivery_time ?? old('delivery_time') }}" required>
                                    @error('delivery_time')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Base Rate<span class="text-danger">*</span></label>
                                    <input type="number" name="base_rate" placeholder="Enter Base Rate (e.g. 10.00)" class="form-control"
                                        value="{{ $shippingMethod?->base_rate ?? old('base_rate') }}" required>
                                    @error('base_rate')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status<span class="text-danger">*</span></label>
                                    <select name="status" class="form-control select2" required>
                                        <option value="1"
                                            {{ ($shippingMethod?->status ?? old('status')) == '1' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0"
                                            {{ ($shippingMethod?->status ?? old('status')) == '0' ? 'selected' : '' }}>InActive
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"
                                id="saveBtn">{{ isset($shippingMethod) ? 'Update' : 'Save' }}</button>
                            <a href="{{ route('admin.shipping_methods.index') }}" class="btn btn-secondary">Back</a>
                        </div>

                    </div>
                </div>
            </div>
            <!-- End PAge Content -->
        </form>
    </div>
@endsection

@push('styles')
    <!-- Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Custom CSS for the page -->
    <link rel="stylesheet" href="{{ asset('backend/custom.css') }}">
@endpush

@push('scripts')
    <!-- Then the jQuery Validation plugin -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <!-- Include the CKEditor script -->
    <!-- Select2 CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for any select elements with the class 'select2'
            $('.select2').select2();

            //jquery validation for the form
            $('#shippingMethodForm').validate({
                ignore: [],
                rules: {
                    zone_id: {
                        required: true
                    },
                    name: {
                        required: true,
                        minlength: 2
                    },
                    carrier: {
                        required: true,
                        minlength: 2
                    },
                    delivery_time: {
                        required: true,
                        minlength: 2
                    },
                    base_rate: {
                        required: true,
                        number: true,
                        min: 1,
                    },
                    status: {
                        required: true
                    }
                },
                messages: {
                    zone_id: {
                        required: "Please select a shipping zone"
                    },
                    name: {
                        required: "Please enter a name",
                        minlength: "Name must be at least 2 characters long"
                    },
                    carrier: {
                        required: "Please enter a carrier",
                        minlength: "Carrier must be at least 2 characters long"
                    },
                    delivery_time: {
                        required: "Please enter delivery time",
                        minlength: "Delivery time must be at least 2 characters long"
                    },
                    base_rate: {
                        required: "Please enter a base rate",
                        number: "Base rate must be a number",
                        min: "Base rate must be at least 1"
                    },
                    status: {
                        required: "Please select a status"
                    }
                },
                submitHandler: function(form) {
                    const $btn = $('#saveBtn');
                    if ($btn.text().trim().toLowerCase() === 'update') {
                        $btn.prop('disabled', true).text('Updating...');
                    } else {
                        $btn.prop('disabled', true).text('Saving...');
                    }
                    form.submit();
                },
                errorElement: 'div',
                errorClass: 'text-danger custom-error',
                errorPlacement: function(error, element) {
                    $('.validation-error').hide();
                    if (element.hasClass('select2')) {
                        error.insertAfter(element.next('.select2')); // Places error after Select2 UI
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
        });
    </script>
@endpush
