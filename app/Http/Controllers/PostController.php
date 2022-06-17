<?php

namespace App\Http\Controllers;

use App\Page;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
class PostController extends Controller
{

    public function personPostStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string'
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false,'data'=>$validator->errors(), 422]);
            } else {
                $Page= new Post;
                $Page->content=$request->content;
                $Page->author_id=Auth()->user()->id;
                $Page->type='person';
                $Page->save();
                return response()->json([
                    'success'=>true,
                    'data'=>'Post Created Successfully !',
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'success'=>false,
                'data'=>$e->getMessage(),
            ]);
        }
    }
    public function pagePostStore(Request $request,$page_id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string'
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false,'data'=>$validator->errors(), 422]);
            } else {
                $checkPage=Page::where([
                    'user_id'=>Auth()->user()->id,
                    'id'=>$page_id
                    ])->first();
                if($checkPage){
                    $Page= new Post;
                    $Page->content=$request->content;
                    $Page->author_id=$page_id;
                    $Page->type='page';
                    $Page->save();
                    return response()->json([
                        'success'=>true,
                        'data'=>'Post Created Successfully !',
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
