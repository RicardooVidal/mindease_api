<?php

namespace App\Http\Controllers;

use App\Data\LoginData;
use App\Exceptions\ErrorCreateUserException;
use App\Helpers\DatabaseHelper;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginData $request)
    {
        $credentials = $request->only('email', 'password')->toArray();

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        /** @var User $user */
        $user = Auth::user();

        /** @var Company $company */
        $company = $user->company()->first();

        $token = $user->createToken($user->name, ['*'], now()->addHour())->plainTextToken;
        DatabaseHelper::changeSchema($company->company);

        return response()->json(['token' => $token]);
    }

    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'company_id' => 'required|integer|exists:companies,id',
                'password' => 'required|string|min:8|confirmed',
            ]);

            /** @var Company $company */
            $company = Company::query()->findOrFail($validatedData['company_id']);

            $user = User::query()
                ->make([
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                ]);

            $user->company()->associate($company);
            $user->save();

            if ($user) {
                return response()->json(['token' => $user->createToken($user->name)->plainTextToken]);
            }
        } catch (ModelNotFoundException) {
            throw new ErrorCreateUserException();
        }
    }
}
