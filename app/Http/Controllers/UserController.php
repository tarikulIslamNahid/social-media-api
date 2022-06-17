<?php

namespace App\Http\Controllers;

use App\Follow;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(Request $request){

        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false,'data'=>$validator->errors(), 422]);
            } else {
                $user= new User;
                $user->first_name=$request->first_name;
                $user->last_name=$request->last_name;
                $user->slug=User::uniqueSlug($request->first_name.$request->last_name);
                $user->email=$request->email;
                $user->password=Hash::make($request->password);
                $user->save();
                return response()->json([
                    'success'=>true,
                    'data'=>'Account Created Successfully !',
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'success'=>false,
                'data'=>$e->getMessage(),
            ]);
        }

    }
    public function login(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'email'=>'bail|required|email',
                'password'=>'bail|required|min:6',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false,'data'=>$validator->errors(), 422]);
            } else {
                if ( Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    $token=Auth()->user()->createToken('token')->plainTextToken;
                    return $this->respondWithToken($token);

                }else{
                    return response()->json([
                        'msg' => 'Incorrect login details',
                    ], 401);
                }


            }
        }catch (Exception $e) {
            return response()->json([
                'success'=>false,
                'data'=>$e->getMessage(),
            ]);
        }
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'success' => 'Login Successfully',
            'user' => Auth()->user(),
        ]);
    }

    public function user(Request $request){
        $user = $request->user();
        return response()->json([$user]);
    }
    public function logout(Request $request){
        $id=$request->user()->id;
       $logout = auth()->user()->tokens()->where('tokenable_id',$id)->delete();
       if($logout){
        return response()->json([
            'success'=>true,
            'data'=>'logout successfully !',
        ]);
       }
    }

    public function feed(){
        try {
          $following=Follow::where('follower',Auth()->user()->id)->orderBy('created_at', 'desc')->get();
          $posts=[];
          foreach ($following as $follow) {
           if($follow->type=='person'){
            $post=Post::where([
                'type'=>'person',
                'author_id'=>$follow->following
            ])->orderBy('created_at', 'desc')->get();
            if(Count($post)>0){
                array_push($posts,$post);
            }
           }else if($follow->type=='page'){
            $post=Post::where([
                'type'=>'page',
                'author_id'=>$follow->following
            ])->orderBy('created_at', 'desc')->get();
            if(Count($post)>0){
                array_push($posts,$post);
            }
           }
          }
        //   $pagePosts
          return response()->json([
            'success'=>true,
            'data'=>$posts,
        ]);
        //   if(Count($following)>0){
        //     return response()->json([
        //         'success'=>true,
        //         'data'=>$following,
        //     ]);
        // }else{
        //     return response()->json([
        //         'success'=>true,
        //         'data'=>'You catch all post\'s ',
        //     ]);
        // }
        } catch (Exception $e) {
            return response()->json([
                'success'=>false,
                'data'=>$e->getMessage(),
            ]);
        }
    }

}
