<?php



namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;

use App\Models\NutritionGoal;

use App\Models\NutritionPlan;

use Illuminate\Http\Request;

use Illuminate\Support\Str;



class NutritionPlanController extends Controller

{

    public function index()

    {

        $plans = NutritionPlan::with('goal')->latest()->paginate(20);

        return view('admin.nutrition.index', compact('plans'));

    }



    public function create()

    {

        return view('admin.nutrition.form', [

            'plan' => new NutritionPlan(['is_active' => true]),

            'goals' => NutritionGoal::orderBy('name')->get(),

        ]);

    }



    public function store(Request $request)

    {

        NutritionPlan::create($this->validated($request));

        return redirect()->route('admin.nutrition-plans.index')->with('success', 'Planas sukurtas.');

    }



    public function edit(NutritionPlan $plan)

    {

        return view('admin.nutrition.form', [

            'plan' => $plan,

            'goals' => NutritionGoal::orderBy('name')->get(),

        ]);

    }



    public function update(Request $request, NutritionPlan $plan)

    {

        $plan->update($this->validated($request, $plan));

        return redirect()->route('admin.nutrition-plans.index')->with('success', 'Planas atnaujintas.');

    }



    public function destroy(NutritionPlan $plan)

    {

        $plan->delete();

        return back()->with('success', 'Planas pašalintas.');

    }



    protected function validated(Request $request, ?NutritionPlan $plan = null): array

    {

        $id = $plan?->id;

        $data = $request->validate([

            'name' => 'required|string|max:255',

            'slug' => "nullable|string|max:255|unique:nutrition_plans,slug,{$id}",

            'goal_id' => 'nullable|exists:nutrition_goals,id',

            'short_description' => 'nullable|string|max:500',

            'description' => 'nullable|string',

            'pros' => 'nullable|string',

            'cons' => 'nullable|string',

            'image' => 'nullable|string|max:500',

            'is_active' => 'nullable|boolean',

        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $data['pros'] = $this->splitLines($data['pros'] ?? null);

        $data['cons'] = $this->splitLines($data['cons'] ?? null);

        $data['is_active'] = $request->boolean('is_active');

        return $data;

    }



    protected function splitLines(?string $text): array

    {

        if (!$text) return [];

        return array_values(array_filter(array_map('trim', preg_split('/\r?\n/', $text))));

    }

}

