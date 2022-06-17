<?php

namespace App\Http\Controllers;

use App\Follow;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;

class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function PersonFollow(Request $request,$person_id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'following' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false,'data'=>$validator->errors(), 422]);
            } else {
                $following=Follow::where([
                    'follower'=>$person_id,
                    'following'=>$request->following
                ])->first();
                if(!$following){
                    $Follow= new Follow;
                    $Follow->follower=$person_id;
                    $Follow->following=$request->following;
                    $Follow->type='person';
                    $Follow->save();
                    return response()->json([
                        'success'=>true,
                        'data'=>'Your are now following this page !',
                    ]);
                }else{
                    return response()->json([
                        'success'=>false,
                        'data'=>'Your are already following this page !',
                    ]);
                }

            }

        } catch (Exception $e) {
            return response()->json([
                'success'=>false,
                'data'=>$e->getMessage(),
            ]);
        }
    }


}
