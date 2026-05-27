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
        // rodomas visų promo kodų sąrašas
        $codes = PromoCode::latest()->get();

        return view('admin.promo-codes.index', compact('codes'));
    }

    public function create()
    {
        // naujo promo kodo forma su pradinėmis reikšmėmis
        return view('admin.promo-codes.form', ['code' => new PromoCode(['is_active' => true, 'type' => 'percent'])]);
    }

    public function store(Request $request)
    {
        // sukuriamas naujas promo kodas
        $data = $this->validated($request);
        PromoCode::create($data);

        return redirect()->route('admin.promo-codes.index')->with('success', 'Promo kodas sukurtas.');
    }

    public function edit(PromoCode $promoCode)
    {
        // promo kodo redagavimo forma
        return view('admin.promo-codes.form', ['code' => $promoCode]);
    }

    public function update(Request $request, PromoCode $promoCode)
    {
        // atnaujinamas pasirinktas promo kodas
        $data = $this->validated($request, $promoCode);
        $promoCode->update($data);

        return redirect()->route('admin.promo-codes.index')->with('success', 'Promo kodas atnaujintas.');
    }

    public function destroy(PromoCode $promoCode)
    {
        // ištrinamas pasirinktas promo kodas
        $promoCode->delete();

        return back()->with('success', 'Promo kodas pašalintas.');
    }

    protected function validated(Request $request, ?PromoCode $existing = null): array
    {
        $id = $existing?->id;

        // bendras promo kodo validavimas kūrimui ir redagavimui
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

        // kodas sutvarkomas į didžiąsias raides
        $data['code'] = strtoupper(trim($data['code']));

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
