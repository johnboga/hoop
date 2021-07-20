<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Rules\GeonamesCodeExists;
use App\Rules\RealName;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function self()
    {
        return request()->user();
    }

    public function show(User $user): User
    {
        return $user;
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100', new RealName],
            'last_name' => ['required', 'string', 'max:100', new RealName],
            'username' => [
                'string',
                'required',
                'max:255',
            ],
            'date_of_birth' => [
                'required',
                'date',
                'after:' . today()->subYears(100)->toDateString(),
                'before:' . today()->subYears(5)->toDateString()
            ],
            'gender' => ['required', 'string', 'in:m,f'],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($request->user()),
            ],
            'locality' => ['nullable', new GeonamesCodeExists],
        ]);

        $request->user()->update($validated);

        return response($request->user(), 200);
    }


}
