<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\GeneralTrait;

class OrderController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'product_id'=>'required|array',
            'product_id.*'=>'integer',
            'quantity'=>'required|array',
            'quantity.*'=>'integer'
        ]);
        if($validator->fails())
            return $this->errorResponse($validator->errors(),422);

        $products_id=$request->input('product_id');
        $quantities=$request->input('quantity');
        if(sizeof($products_id)!=sizeof($quantities))
            return $this->errorResponse('the arrays must be equals',404);
        try {
            $order=Order::create(['user_id'=>auth()->user()->id]);
            for ($i=0;$i<sizeof($quantities);$i++)
                $order->products()->attach($products_id[$i],['quantity'=>$quantities[$i]]);

            $data=$order->with('products')->get();
            return $this->successResponse($data,'order inserted');
        }catch (\Exception $ex){
            return $this->errorResponse($ex->getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator=Validator::make($request->all(),[
            'product_id'=>'required|array',
            'product_id.*'=>'integer',
            'quantity'=>'required|array',
            'quantity.*'=>'integer'
        ]);
        if($validator->fails())
            return $this->errorResponse($validator->errors(),422);

        $products_id=$request->input('product_id');
        $quantities=$request->input('quantity');
        if(sizeof($products_id)!=sizeof($quantities))
            return $this->errorResponse('the arrays must be equals',404);
        try {
            $order=Order::find($id);
            for ($i=0;$i<sizeof($quantities);$i++)
                $order->products()->sync();
            $data=$order->with('products')->get();
            return $this->successResponse($data,'order updated');
        }catch (\Exception $ex){
            return $this->errorResponse($ex->getMessage(),500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
