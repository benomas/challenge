<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Trade;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TradeController extends BaseController{

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
    public function index(Request $request){
        $tradeBuilder = Trade::where('id','>',0);

        if($request->has('type'))
            $tradeBuilder->whereType($request->input('type'));

        if($request->has('user_id'))
            $tradeBuilder->whereUserId($request->input('user_id'));

        $trades = $tradeBuilder->get()->map(function ($trade){
            $trade->timestamp = Carbon::createFromFormat('Y-m-d H:i:s', $trade->timestamp)->toJSON();
            return $trade;
        })->toArray();

        return response()->json($trades,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $trade = Trade::whereId($id)->first();

        if(!$trade)
            return response()->json('ID not found',404);

        $trade->timestamp = Carbon::createFromFormat('Y-m-d H:i:s', $trade->timestamp)->toJSON();

        return response()->json($trade,200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $trade = new Trade;
        $trade->type      = $request->input('type');
        $trade->user_id   = $request->input('user_id');
        $trade->symbol    = $request->input('symbol');
        $trade->shares    = $request->input('shares');
        $trade->price     = $request->input('price');
        $trade->timestamp = Carbon::createFromTimestamp($request->input('timestamp') / 1000);
        $trade->save();

        $trade->timestamp = strtotime($trade->timestamp) * 1000;

        return response()->json($trade,201);
    }
}
