<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request) 
    {        
        $path = $request->file('picture')->store('uploads', 'public');        
        
        if($path) {
            return response()->json(['data' => $path]);
        }
        else {
            return response()->json(['message' => 'Внутренняя ошибка сервера при загрузке изображения'], 500);
        }        
    }
}