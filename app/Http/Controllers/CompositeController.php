<?php

namespace App\Http\Controllers;

use App\Services\CompositeService;
use Illuminate\Http\Request;

class CompositeController extends Controller
{
    public function checkOut(Request $request, CompositeService $compositeService){
       $result = $compositeService->checkOut($request);

        return response()->json(array(
            'products' => $result,
        ), 200);
    }
}
