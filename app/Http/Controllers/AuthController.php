<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\ResponseCode;
use App\Http\Helpers\ExternalDataControl;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $errorData = ExternalDataControl::findError($request, 'signup');
        if($errorData){
            return response()->json($errorData, ResponseCode::HTTP_BAD_REQUEST);
        } else {
            $user = new User([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->save();
            return response()->json(['message' => 'Successfully created user!'], ResponseCode::HTTP_CREATED);
        }      
        
    }

    public function login(Request $request)
    {
        $errorData = ExternalDataControl::findError($request, 'login');
        if($errorData){
            return response()->json($errorData, ResponseCode::HTTP_BAD_REQUEST);
        } else {
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return response()->json([ 'message' => 'Unauthorized'], ResponseCode::HTTP_UNAUTHORIZED);
            }
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me) {
                $token->expires_at = Carbon::now()->addWeeks(4);
            }
            $token->save();
            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type'   => 'Bearer',
                'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            ]);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
