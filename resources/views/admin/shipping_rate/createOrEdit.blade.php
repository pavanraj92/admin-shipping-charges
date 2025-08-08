@extends('admin::admin.layouts.master')

@section('title', 'Shipping Rates Management')

@section('page-title', isset($shippingRate) ? 'Edit Shipping Rate' : 'Create Shipping Rate')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.shipping_rates.index') }}">Shipping Rates
            Manager</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        {{ isset($shippingRate) ? 'Edit Shipping Rate' : 'Create Shipping Rate' }}</li>
@endsection

@section('content')
    <div class="container-fluid">
        <form
            action="{{ isset($shippingRate) ? route('admin.shipping_rates.update', $shippingRate->id) : route('admin.shipping_rates.store') }}"
            method="POST" id="shippingRateForm">
            @if (isset($shippingRate))
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
                                    <label>Shipping Method<span class="text-danger">*</span></label>
                                    <select name="method_id" class="form-control select2" required>
                                        <option value="">Select Method</option>
                                        @foreach ($shippingMethods as $method)
                                            <option value="{{ $method->id }}"
                                                {{ ($shippingRate?->method_id ?? old('method_id')) == $method->id ? 'selected' : '' }}>
                                                {{ $method->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('method_id')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Based On<span class="text-danger">*</span></label>
                                    <select name="based_on" class="form-control select2" required>
                                        @foreach (config('shipping_charges.constants.shippingRatesBasedOn', []) as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ (isset($shippingRate) && $shippingRate->based_on == $key) || old('based_on') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('based_on')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Min Value<span class="text-danger">*</span></label>
                                    <input type="text" name="min_value" class="form-control"
                                        value="{{ $shippingRate?->min_value ?? old('min_value') }}" required>
                                    @error('min_value')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Max Value<span class="text-danger">*</span></label>
                                    <input type="text" name="max_value" class="form-control"
                                        value="{{ $shippingRate?->max_value ?? old('max_value') }}" required>
                                    @error('max_value')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Rate<span class="text-danger">*</span></label>
                                    <input type="text" name="rate" class="form-control"
                                        value="{{ $shippingRate?->rate ?? old('rate') }}" required>
                                    @error('rate')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"
                                id="saveBtn">{{ isset($shippingRate) ? 'Update' : 'Save' }}</button>
                            <a href="{{ route('admin.shipping_rates.index') }}" class="btn btn-secondary">Back</a>
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
    <!-- Select2 CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for any select elements with the class 'select2'
            $('.select2').select2();

            //jquery validation for the form
            $('#shippingRateForm').validate({
                ignore: [],
                rules: {
                    method_id: {
                        required: true
                    },
                    shippingRatesBasedOn: {
                        required: true
                    },
                    min_value: {
                        required: true,
                        number: true
                    },
                    max_value: {
                        required: true,
                        number: true
                    },
                    rate: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    method_id: {
                        required: "Please select a shipping method"
                    },
                    shippingRatesBasedOn: {
                        required: "Please select the basis for shipping rates"
                    },
                    min_value: {
                        required: "Please enter the minimum value",
                        number: "Minimum value must be a number"
                    },
                    max_value: {
                        required: "Please enter the maximum value",
                        number: "Maximum value must be a number"
                    },
                    rate: {
                        required: "Please enter the rate",
                        number: "Rate must be a number"
                    }
                },
                submitHandler: function(form) {
                    const $btn = $('#saveBtn');
                    if ($btn.text().trim().toLowerCase() === 'update') {
                        $btn.prop('disabled', true).text('Updating...');
                    } else {
                        $btn.prop('disabled', true).text('Saving...');
                    }

                    // Now submit
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
