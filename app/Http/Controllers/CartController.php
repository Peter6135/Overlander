<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request, Package $package)
    {
        $data = $request->validate([
            'package_plan_id' => 'required|exists:package_plans,id',
            'pax' => 'required|integer|min:1|max:20',
        ]);

        $package->plans()->findOrFail($data['package_plan_id']);

        session(['cart' => [
            'package_id' => $package->id,
            'package_plan_id' => $data['package_plan_id'],
            'pax' => $data['pax'],
        ]]);

        return redirect()->route('cart.show');
    }

    public function show()
    {
        $cart = session('cart');

        if (! $cart) {
            return redirect()->route('packages.index')->with('info', __('cart.empty'));
        }

        $package = Package::with('plans')->findOrFail($cart['package_id']);
        $plan = $package->plans()->findOrFail($cart['package_plan_id']);

        return view('cart.show', compact('package', 'plan', 'cart'));
    }

    public function destroy()
    {
        session()->forget('cart');

        return redirect()->route('packages.index');
    }
}
