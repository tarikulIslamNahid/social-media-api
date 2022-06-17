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
    public function PersonFollow($person_id)
    {
        try {

                $following=Follow::where([
                    'follower'=>Auth()->user()->id,
                    'following'=>$person_id
                ])->first();
                if(!$following){
                    $Follow= new Follow;
                    $Follow->follower=Auth()->user()->id;
                    $Follow->following=$person_id;
                    $Follow->type='person';
                    $Follow->save();
                    return response()->json([
                        'success'=>true,
                        'data'=>'Your are now following this person !',
                    ]);
                }else{
                    return response()->json([
                        'success'=>false,
                        'data'=>'Your are already following this person !',
                    ]);
                }
        } catch (Exception $e) {
            return response()->json([
                'success'=>false,
                'data'=>$e->getMessage(),
            ]);
        }
    }


}
