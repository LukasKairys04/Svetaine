<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromoCodeController extends Controller
{
    public function index()
    {
        $codes = PromoCode::latest()->get();
        return view('admin.promo-codes.index', compact('codes'));
    }

    public function create()
    {
        return view('admin.promo-codes.form', ['code' => new PromoCode(['is_active' => true, 'type' => 'percent'])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        PromoCode::create($data);
        return redirect()->route('admin.promo-codes.index')->with('success', 'Promo kodas sukurtas.');
    }

    public function edit(PromoCode $promoCode)
    {
        return view('admin.promo-codes.form', ['code' => $promoCode]);
    }

    public function update(Request $request, PromoCode $promoCode)
    {
        $data = $this->validated($request, $promoCode);
        $promoCode->update($data);
        return redirect()->route('admin.promo-codes.index')->with('success', 'Promo kodas atnaujintas.');
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();
        return back()->with('success', 'Promo kodas pašalintas.');
    }

    protected function validated(Request $request, ?PromoCode $existing = null): array
    {
        $id = $existing?->id;

        $data = $request->validate([
            'code'        => "required|string|max:50|unique:promo_codes,code,{$id}",
            'type'        => 'required|in:percent,fixed',
            'value'       => 'required|numeric|min:0.01|max:100',
            'min_order'   => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at'   => 'nullable|date',
            'expires_at'  => 'nullable|date|after_or_equal:starts_at',
            'is_active'   => 'nullable|boolean',
        ]);

        $data['code']      = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
