<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Regester(Request $request)
    {
        //
        $request->validate([
            'password'=>'required|confirmed'
        ]);
        $user = new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);

        $user->save();
        $token =  $user->createToken('mytoken')->plainTextToken;
        return response()->json(
            [
                'message'=>'created',
                'UserData'=>$user,
                'token'=>$token
            ]
            );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logIn(Request $request)
    {
        //
        $user = User::where('email',$request->email)->first();
        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json(['message'=>'bad Login'],401);
        }
        $token = $user->createToken('mytoken')->plainTextToken;
        return response()->json(
            [
                'mss'=>'signing in',
                'user'=>$user,
                'token'=>$token
            ]
            );
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function LogOut(Request $request)
    {
        //
        auth()->user()->tokens()->delete();
        return response()->json(['message'=>'Signing Out']);
    }

}