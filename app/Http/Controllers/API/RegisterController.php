<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function __construct()
    {
        $this->middleware('guest');
    }

    private function validator(array $data)
    {
        $validator = Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|max:255|unique:users|regex:/^(\+7)[\d]{10}/',
            'password' => 'required|string|min:6|max:255|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])([0-9])*(?=.*?[\$\%\&\!\:\.]).{6,}$/',
            ],
            [
                'phone.regex' => 'The phone format: +70000000000',
                'password.regex' => 'The password format is invalid. minimum 1 special character, lowercase and uppercase letter',
            ]
        );

        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->errors()->first()
            ];
        } else {
            return ['success' => true];
        }
    }

    public function register(Request $request)
    {
        $allRequest = array_map('trim', $request->all());
        $validatorStatus = $this->validator($allRequest);
        if (is_array($validatorStatus) && isset($validatorStatus['success']) && $validatorStatus['success'] == false) {
            return response()->json($validatorStatus);
        }

        $user = User::create([
            'full_name' => $allRequest['full_name'],
            'email' => $allRequest['email'],
            'phone' => $allRequest['phone'],
            'password' => Hash::make($allRequest['password']),
        ]);

        $this->guard()->login($user);
        $user['token'] = $user->createToken('API-APP')->accessToken;

        return response()->json(['success' => true, 'data' => $user], 201);
    }
}
