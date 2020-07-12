<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    private function validator(array $data)
    {
        $validator = Validator::make($data, [
            'username' => 'required|max:255',
            'password' => 'required|string|min:6|max:255|regex:/^(?=.*?[A-Z])(?=.*?[a-z])([0-9])*(?=.*?[\$\%\&\!\:\.]).{6,}$/',
            ],
            [
                'password.regex' => 'Invalid password',
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

    private function auth(array $data, string $username_type)
    {
        if(Auth::attempt([$username_type => $data['username'], 'password' => $data['password']])){
            $user = Auth::user();
            $user['token'] =  $user->createToken('API-APP')->accessToken;

            return response()->json(['success' => true, 'data' => $user], 200);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Invalid ' . $username_type . ' and/or password'
            ]);
        }
    }

    public function login(Request $request)
    {
        $allRequest = array_map('trim', $request->all());
        $validatorStatus = $this->validator($allRequest);
        if (is_array($validatorStatus) && isset($validatorStatus['success']) && $validatorStatus['success'] == false) {
            return response()->json($validatorStatus);
        }

        $credentials = [
            'username' => $allRequest['username'],
            'password' => $allRequest['password'],
        ];

        if (filter_var($allRequest['username'], FILTER_VALIDATE_EMAIL)) {
            // if username = email
            return $this->auth($credentials, 'email');
        } elseif (filter_var($allRequest['username'], FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^(\+7)[\d]{10}/"]])) {
            // if username = phone
            return $this->auth($credentials, 'phone');
        }

        return response()->json([
            'success' => false,
            'error' => 'Invalid credentials'
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->token()->revoke();
        $user->token()->delete();

        return response()->json(null, 204);
    }
}

