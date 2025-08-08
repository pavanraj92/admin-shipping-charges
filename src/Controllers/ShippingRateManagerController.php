<?php

namespace admin\shipping_charges\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use admin\shipping_charges\Requests\ShippingRateCreateRequest;
use admin\shipping_charges\Requests\ShippingRateUpdateRequest;
use admin\shipping_charges\Models\ShippingRate;
use admin\shipping_charges\Models\ShippingMethod;

class ShippingRateManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('admincan_permission:shipping_rates_manager_list')->only(['index']);
        $this->middleware('admincan_permission:shipping_rates_manager_create')->only(['create', 'store']);
        $this->middleware('admincan_permission:shipping_rates_manager_edit')->only(['edit', 'update']);
        $this->middleware('admincan_permission:shipping_rates_manager_view')->only(['show']);
        $this->middleware('admincan_permission:shipping_rates_manager_delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        try {
            $shippingRates = ShippingRate::filter($request->query('keyword'))
                ->sortable()
                ->latest()
                ->paginate(ShippingRate::getPerPageLimit())
                ->withQueryString();

            return view('shipping_charges::admin.shipping_rate.index', compact('shippingRates'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load shipping rates: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $shippingMethods = ShippingMethod::get();
            return view('shipping_charges::admin.shipping_rate.createOrEdit', compact('shippingMethods'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load shipping charges: ' . $e->getMessage());
        }
    }

    public function store(ShippingRateCreateRequest $request)
    {
        try {
            $requestData = $request->validated();

            $shipping_rate = ShippingRate::create($requestData);
            return redirect()->route('admin.shipping_rates.index')->with('success', 'Shipping rate created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load shipping rates: ' . $e->getMessage());
        }
    }

    /**
     * show page details
     */
    public function show(ShippingRate $shippingRate)
    {
        try {
            return view('shipping_charges::admin.shipping_rate.show', compact('shippingRate'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load shipping rates: ' . $e->getMessage());
        }
    }

    public function edit(ShippingRate $shippingRate)
    {
        try {
            $shippingMethods = ShippingMethod::get();
            return view('shipping_charges::admin.shipping_rate.createOrEdit', compact('shippingRate', 'shippingMethods'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load shipping rate for editing: ' . $e->getMessage());
        }
    }

    public function update(ShippingRateUpdateRequest $request, ShippingRate $shippingRate)
    {
        try {
            $requestData = $request->validated();

            $shippingRate->update($requestData);
            return redirect()->route('admin.shipping_rates.index')->with('success', 'Shipping rate updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load shipping rate for editing: ' . $e->getMessage());
        }
    }

    public function destroy(ShippingRate $shippingRate)
    {
        try {
            $shippingRate->delete();
            return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'error' => $e->getMessage()], 500);
        }
    }
}
