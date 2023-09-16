<?php

namespace App\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User\ProfileUser;
use PhpParser\Node\Stmt\Global_;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Http\Resources\GlobalResources;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Http\Resources\auth\LoginResources;
use App\Http\Resources\auth\RegisterResources;
use App\Http\Requests\admin\GenerateAccessRequest;
use App\Http\Requests\admin\sendCredentialRequest;
use App\Http\Controllers\thirdparty\SendWaController;

class ApiAuthController extends Controller
{
    public function login(LoginRequest $request){

        $credential = $request->only('email','password');
        if(Auth::attempt($credential)){
            $user = User::where('email',$credential)->first();
            $token =  $user->createToken('token')->plainTextToken;
            $data = [
                'id'=>$user->id,
                'name'=>$user->name,
                'email'=>$user->email,
                'role'=>$user->role,
                'token'=>$token

            ];
            return new GlobalResources(true,'Login Berhasil',$data,null);
        }else{
            return response()->json([
                "success"=> false,
                "message"=>'Email atau password salah',
                "data"=>null
            ],401);
        }
    }

    public function register(RegisterRequest $request){
        try{
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role'=>$request->role,
                'password' => Hash::make($request->password),
            ]);

            return new GlobalResources(true,'Register Berhasil',null,null);
        }catch(\Exception $e){

            return response()->json([
                "success"=> false,
                "message"=>'Terjadi kesalah',
                "data"=>$e->getMessage()
            ],500);
        }

    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return new GlobalResources(true,'Logout Berhasil',null,null);
    }


}
