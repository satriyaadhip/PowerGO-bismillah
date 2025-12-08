<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Show registration page.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle registration.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => ['required','string','max:255'],
            'email'         => ['required','string','email','max:255','unique:users'],
            'password'      => ['required','confirmed', Rules\Password::defaults()],
            'daya_va'       => ['required','in:450,900,1300,2200'],
            'billing_type'  => ['required','in:prabayar,pascabayar'],
        ]);

        DB::beginTransaction();

        try {

            // 1. Create user (Breeze default)
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // 2. Generate pelanggan ID 10 digit unik
            do {
                $pelangganId = (string) random_int(1000000000, 9999999999);
            } while (Customer::where('pelanggan_id', $pelangganId)->exists());

            // 3. Create customer linked to user
            Customer::create([
                'user_id'      => $user->id,
                'pelanggan_id' => $pelangganId,
                'daya_va'      => (int) $request->daya_va,
                'max_watt'     => (int) $request->daya_va,
                'billing_type' => $request->billing_type,
            ]);

            event(new Registered($user));
            Auth::login($user);

            DB::commit();

            return redirect(RouteServiceProvider::HOME);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('REGISTER ERROR: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Register gagal: '.$e->getMessage()]);
        }
    }
}
