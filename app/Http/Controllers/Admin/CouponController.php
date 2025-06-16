<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrateur');
    }
    
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }
    
    public function create()
    {
        return view('admin.coupons.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'discount_percent' => 'required_without:discount_amount|nullable|integer|min:1|max:100',
            'discount_amount' => 'required_without:discount_percent|nullable|numeric',
            'code' => 'required|unique:coupons|max:20',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);
        
        Coupon::create($validated);
        
        return redirect()->route('coupons.index')
            ->with('success', 'Coupon créé avec succès.');
    }
    
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }
    
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'discount_percent' => 'required_without:discount_amount|nullable|integer|min:1|max:100',
            'discount_amount' => 'required_without:discount_percent|nullable|numeric',
            'code' => 'required|max:20|unique:coupons,code,' . $coupon->id,
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);
        
        $coupon->update($validated);
        
        return redirect()->route('coupons.index')
            ->with('success', 'Coupon mis à jour avec succès.');
    }
    
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        
        return redirect()->route('coupons.index')
            ->with('success', 'Coupon supprimé avec succès.');
    }
}
