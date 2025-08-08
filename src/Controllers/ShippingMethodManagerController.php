<?php

namespace admin\shipping_charges\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use admin\shipping_charges\Requests\ShippingMethodCreateRequest;
use admin\shipping_charges\Requests\ShippingMethodUpdateRequest;
use admin\shipping_charges\Models\ShippingMethod;
use admin\shipping_charges\Models\ShippingZone;

class ShippingMethodManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('admincan_permission:shipping_methods_manager_list')->only(['index']);
        $this->middleware('admincan_permission:shipping_methods_manager_create')->only(['create', 'store']);
        $this->middleware('admincan_permission:shipping_methods_manager_edit')->only(['edit', 'update']);
        $this->middleware('admincan_permission:shipping_methods_manager_view')->only(['show']);
        $this->middleware('admincan_permission:shipping_methods_manager_delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        try {
            $shipping_methods = ShippingMethod::filter($request->query('keyword'))
                ->filterByStatus($request->query('status'))
                ->sortable()
                ->latest()
                ->paginate(ShippingMethod::getPerPageLimit())
                ->withQueryString();

            return view('shipping_charges::admin.shipping_method.index', compact('shipping_methods'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load shipping methods: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $shippingZones = ShippingZone::get();
            return view('shipping_charges::admin.shipping_method.createOrEdit', compact('shippingZones'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load shipping methods: ' . $e->getMessage());
        }
    }

    public function store(ShippingMethodCreateRequest $request)
    {
        try {
            $requestData = $request->validated();

            $shipping_method = ShippingMethod::create($requestData);
            return redirect()->route('admin.shipping_methods.index')->with('success', 'Shipping method created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load shipping methods: ' . $e->getMessage());
        }
    }

    /**
     * show page details
     */
    public function show(ShippingMethod $shippingMethod)
    {
        try {
            return view('shipping_charges::admin.shipping_method.show', compact('shippingMethod'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load shipping methods: ' . $e->getMessage());
        }
    }

    public function edit(ShippingMethod $shippingMethod)
    {
        try {
            $shippingZones = ShippingZone::get();
            return view('shipping_charges::admin.shipping_method.createOrEdit', compact('shippingMethod', 'shippingZones'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load shipping method for editing: ' . $e->getMessage());
        }
    }

    public function update(ShippingMethodUpdateRequest $request, ShippingMethod $shippingMethod)
    {
        try {
            $requestData = $request->validated();

            $shippingMethod->update($requestData);
            return redirect()->route('admin.shipping_methods.index')->with('success', 'Shipping method updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load shipping method for editing: ' . $e->getMessage());
        }
    }

    public function destroy(ShippingMethod $shippingMethod)
    {
        try {
            $shippingMethod->delete();
            return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'error' => $e->getMessage()], 500);
        }
    }

   public function updateStatus(Request $request)
    {
        try {
            $shippingMethod = ShippingMethod::findOrFail($request->id);
            $shippingMethod->status = $request->status;
            $shippingMethod->save();

            // create status html dynamically
            $dataStatus = $shippingMethod->status == '1' ? '0' : '1';
            $label = $shippingMethod->status == '1' ? 'Active' : 'InActive';
            $btnClass = $shippingMethod->status == '1' ? 'btn-success' : 'btn-warning';
            $tooltip = $shippingMethod->status == '1' ? 'Click to change status to inactive' : 'Click to change status to active';

            $strHtml = '<a href="javascript:void(0)"'
                . ' data-toggle="tooltip"'
                . ' data-placement="top"'
                . ' title="' . $tooltip . '"'
                . ' data-url="' . route('admin.shipping_methods.updateStatus') . '"'
                . ' data-method="POST"'
                . ' data-status="' . $dataStatus . '"'
                . ' data-id="' . $shippingMethod->id . '"'
                . ' class="btn ' . $btnClass . ' btn-sm update-status">' . $label . '</a>';

            return response()->json(['success' => true, 'message' => 'Status updated to ' . $label, 'strHtml' => $strHtml]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete record.', 'error' => $e->getMessage()], 500);
        }
    }
}
