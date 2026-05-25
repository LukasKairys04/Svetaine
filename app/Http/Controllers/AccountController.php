<?php



namespace App\Http\Controllers;



use App\Models\Order;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;



class AccountController extends Controller

{

    public function index()

    {

        $user   = Auth::user();

        $orders = Order::where('user_id', $user->id)->latest()->get();



        return view('account.index', compact('user', 'orders'));

    }



    public function update(Request $request)

    {

        $user = Auth::user();



        $data = $request->validate([

            'name'       => 'required|string|max:255',

            'email'      => 'required|email|max:255|unique:users,email,' . $user->id,

            'phone'      => 'nullable|string|max:50',

            'address'    => 'nullable|string|max:255',

            'city'       => 'nullable|string|max:120',

            'zip'        => 'nullable|string|max:20',

            'country'    => 'nullable|string|max:120',

            'gender'     => 'nullable|in:male,female,other',

            'birthdate'  => 'nullable|date',

            'height_cm'  => 'nullable|numeric|min:50|max:260',

            'weight_kg'  => 'nullable|numeric|min:20|max:350',

        ]);



        $user->update($data);

        return back()->with('success', 'Paskyros informacija atnaujinta.');

    }



    public function password(Request $request)

    {

        $user = Auth::user();



        $data = $request->validate([

            'current_password' => 'required|current_password',

            'password'         => ['required', 'string', 'min:8', 'max:64', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],

        ]);



        $user->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Slaptažodis pakeistas.');

    }



    public function orders()

    {

        return view('account.orders', [

            'orders' => Order::where('user_id', Auth::id())->latest()->paginate(10),

        ]);

    }



    public function order(Order $uzsakymas)

    {

        if ($uzsakymas->user_id !== Auth::id()) abort(403);



        $uzsakymas->load('items');

        return view('account.order', ['uzsakymas' => $uzsakymas]);

    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'password' => 'required|current_password',
        ]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $user->delete();
        return redirect()->route('home')->with('success', 'Paskyra sėkmingai ištrinta.');
    }

}

