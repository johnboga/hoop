<?php

namespace App\Http\Controllers\Api\Auth;

use App\Rules\RealName;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterParentController extends Controller
{
    public function store(Request $request)
    {
        if ($request->user()->hasRegisteredParent()) {
            return response(message(__('auth.parent_registration_already_completed')), 409);
        }

        $validated = $request->validate([
            'parent_phone' => 'required|string|phone',
            'parent_phone_country' => 'required_with:phone',
            'parent_first_name' => ['required', 'string', 'max:100', new RealName],
            'parent_last_name' => ['required', 'string', 'max:100', new RealName],
        ]);

        $validated['parent_phone'] = phone(
            $request->parent_phone,
            $request->parent_phone_country
        )->formatE164();

        $request->user()->update($validated);
    }
}
