<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $pages= Auth()->user()->pages;
                return response()->json([
                    'success'=>true,
                    'data'=>$pages,
                ]);
        }catch(Exception $e){
            return response()->json([
                'success'=>false,
                'data'=>$e->getMessage(),
            ]);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

            try {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:80',
                ]);
                if ($validator->fails()) {
                    return response()->json(['success' => false,'data'=>$validator->errors(), 422]);
                } else {
                    $Page= new Page;
                    $Page->name=$request->name;
                    $Page->user_id=Auth()->user()->id;
                    $Page->slug=Page::uniqueSlug($request->name);
                    $Page->save();
                    return response()->json([
                        'success'=>true,
                        'data'=>'Page Created Successfully !',
                    ]);
                }

            } catch (Exception $e) {
                return response()->json([
                    'success'=>false,
                    'data'=>$e->getMessage(),
                ]);
            }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        //
    }
}
