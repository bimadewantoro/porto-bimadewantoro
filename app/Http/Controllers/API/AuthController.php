<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register API
     * 
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', Password::min(8)->letters()->numbers()->mixedCase()->symbols()],
            'c_password' => ['required', 'string', 'same:password']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        
        $success['token'] = $user->createToken('PortoBima')->plainTextToken;
        $success['name'] = $user->name;

        return response()->json([
            'success' => $success,
            'message' => 'User successfully registered' 
        ], 200);
    }

    /**
     * Login API
     * 
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('PortoBima')->plainTextToken;
            $success['name'] = $user->name;

            return response()->json([
                'success' => $success,
                'message' => 'User successfully logged in'
            ], 200);
        } else {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Invalid email or password'
            ], 401);
        }
    }

    /**
     * Logout API
     * 
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     * 
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Refresh a token
     * 
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function refresh(Request $request)
    {
        return $this->createNewToken($request->user()->refresh());
    }

    /**
     * Get the token array structure
     * 
     * @param  string $token
     * 
     * @return \Illuminate\Http\Response
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $token->token->expires_at
            )->toDateTimeString()
        ]);
    }
}
