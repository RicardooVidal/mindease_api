<?php

namespace App\Http\Controllers;

use App\Data\CompanyData;
use App\Data\CompanyDataCollection;
use App\Data\LoginData;
use App\Exceptions\ErrorCreateUserException;
use App\Helpers\DatabaseHelper;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
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
        DatabaseHelper::changeSchema(schema: $company->uuid);

        return response()->json(['token' => $token]);
    }

    public function logout(Request $request): void
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'company_id' => 'required|integer|exists:companies,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        /** @var Company $company */
        $company = Company::query()->find($validatedData['company_id']);

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
    }

    public function companies(Request $request): CompanyDataCollection
    {
        return (new CompanyDataCollection(CompanyData::class, Company::query()
            ->select([
                'id',
                'uuid',
                'name',
            ])
            ->with('users', fn(HasMany $query) => $query
                ->select([
                    'id',
                    'name',
                ])
                ->where('id', $request->user()->id))
            ->get()
            ->map(fn(Company $company) => ['uuid' => $company->uuid, 'company' => $company->name])
        ))->wrap('data');
    }
}
