<?php

namespace App\Http\Controllers;

use App\Services\CompositeService;
use Illuminate\Http\Request;
use Response;
class CompositeController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['only' => ['checkOut']]);
    }
    public function checkOut(Request $request, CompositeService $compositeService)
    {
        $result = $compositeService->checkOut($request);
        if (isset($result['error']) && $result['error'])
            return Response::json(array('error' => $result['error']), 200);
        return response()->json(array(
            'products' => $result,
        ), 200);
    }
}
