<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Models\Addresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthController extends Controller
{
    public function user()
    {
        $user = Auth::user();
    
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'user_role' => $user->user_role,
            'flag' => $user->flag,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    }    

    // Register User
    public function register(Request $request)
    {
        try {
            if (!empty($request->name) && !empty($request->email) && !empty($request->password)) {
                if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                    // Get Email
                    $getEmail = User::where('email', $request->email)->first();
                    if (!$getEmail) {
                        if (strlen($request->password) >= 6) {
                            $user = User::create([
                                'name' => $request->input('name'),
                                'email' => $request->input('email'),
                                'password' => $request->input('password'),
                            ]);

                            $token = $user->createToken('pDE6g70A=ZE7medrby5O3V$S22%3=R&9h')->plainTextToken;
                            $cookie = cookie('jwt', $token, 60 * 24); // 1 Day Cookie Expiration

                            event(new Registered($user));

                            return response([
                                'status' => 'success',
                                'message' => "Successfuly Registered!",
                            ])->withCookie($cookie);
                        } else {
                            return response([
                                'source' => 'passwordShort',
                                'status' => 'error',
                                'message' => "Password is too short or below 6 characters."
                            ]);
                        }
                    } else {
                        return response([
                            'source' => 'emailExist',
                            'status' => 'error',
                            'message' => "This email address is already in use."
                        ]);
                    }
                } else {
                    return response([
                        'source' => 'emailNotValid',
                        'status' => 'error',
                        'message' => "Please enter a valid email address."
                    ]);
                }
            } else {
                return response([
                    'source' => 'emptyField',
                    'status' => 'error',
                    'message' => "Please fill out the field/s."
                ]);
            }
        } catch (Throwable $e) {
            return 'Error Catch: ' . $e->getMessage();
        }
    }
    public function login(Request $request)
    {
        try {
            if (!empty($request->email) && !empty($request->password)) {
                // Check if the user exists
                $user = User::where('email', $request->email)->first();

                if (!$user) {
                    return response([
                        'source' => 'invalidCredentials',
                        'status' => 'error',
                        'message' => "Invalid Email or Password"
                    ]);
                }

                // Check if the user is disabled
                if ($user->flag === "1") {
                    // Attempt authentication
                    if (!Auth::attempt($request->only('email', 'password'))) {
                        return response([
                            'source' => 'invalidCredentials',
                            'status' => 'error',
                            'message' => "Invalid Email or Password"
                        ]);
                    }

                    // Generate token and set cookie
                    $token = $request->user()->createToken('pDE6g70A=ZE7medrby5O3V$S22%3=R&9h')->plainTextToken;
                    $cookie = cookie('jwt', $token, 60 * 24); // 1 Day Cookie Expiration

                    return response([
                        'status' => 'success',
                        'message' => 'Logged in successfully!',
                        'token' => $token,
                    ])->withCookie($cookie);
                } else {
                    return response([
                        'source' => 'accountDisabled',
                        'status' => 'error',
                        'message' => "Your account has been disabled. Please contact the administrator."
                    ]);
                }
            } else {
                return response([
                    'source' => 'emptyField',
                    'status' => 'error',
                    'message' => "Please fill out this field."
                ]);
            }
        } catch (Throwable $e) {
            return response([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    // Logout User
    public function logout()
    {
        try {
            $cookie = Cookie::forget('jwt');

            return response([
                'status' => 'success',
                'message' => 'Logout successfully!'
            ])->withCookie($cookie);
        } catch (Throwable $e) {
            return 'Error Catch: ' . $e->getMessage();
        }
    }
}