<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
        try {
            $data = Order::all();
            if (!$data)
                return $this->errorResponse('No orders yet', 404);

            $msg = 'Got you the orders you are looking for';
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
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
            'quantity.*'=>'integer',
            'users_id'=>'required|array',
            'users_id.*'=>'integer'
        ]);
        if($validator->fails())
            return $this->errorResponse($validator->errors(),422);

        $products_id=$request->input('product_id');
        $quantities=$request->input('quantity');
        $users_id=$request->input('users_id');
        if(sizeof($products_id)!=sizeof($quantities)||sizeof($users_id)!=sizeof($quantities))
            return $this->errorResponse('the arrays must be equals',404);
        try {
            $order=Order::create(['user_id'=>auth()->user()->id]);
            for ($i=0;$i<sizeof($quantities);$i++)
                $order->products()->attach($products_id[$i],['quantity'=>$quantities[$i],'user_id'=>$users_id[$i]]);

            $data=$order->with('products')->get();
            return $this->successResponse($data,'order inserted');
        }catch (\Exception $ex){
            return $this->errorResponse($ex->getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        try {
            $data=auth()->user()->with('orders')->get();

            if (!$data)
                return $this->errorResponse('No orders yet', 404);

            $msg = 'Got you the orders you are looking for';
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator=Validator::make($request->all(),[
            'products_id'=>'required|array',
            'products_id.*'=>'integer',
            'quantity'=>'required|array',
            'quantity.*'=>'integer'
        ]);
        if($validator->fails())
            return $this->errorResponse($validator->errors(),422);

        $quantities=$request->input('quantity');
        $products_id=$request->input('products_id');
        if(sizeof($products_id)!=sizeof($quantities))
            return $this->errorResponse('the arrays must be equals',404);
        try {
            $order=Order::find($id);

            foreach ($products_id as $product_id)
                $order->products()->detach($product_id);

            for ($i=0;$i<sizeof($quantities);$i++)
                $order->products()->attach($products_id[$i],['quantity'=>$quantities[$i]]);
//            $products=[];
//            for ($i=0;$i<sizeof($quantities);$i++)
//                $products=[$products_id[$i]=>['quantity'=>$quantities[$i]]];

//            $order->products()->sync($products);
//            $order->save();
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
        try {
            $data = Order::find($id);
            if (!$data)
                return $this->errorResponse('No order with such id', 404);

            $data->delete();
            $msg = 'The order is deleted successfully';
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
}
