<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\UserCollection;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $data=User::with('roles')->get();
            $msg='All users are right here';
            return $this->successResponse(new UserCollection($data),$msg);

        }
        catch (\Exception $ex){
            return $this->errorResponse($ex->getMessage(),500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
                'quantity'=>'required|numeric'
            ]
        );
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            $product = Product::find($id);
            $user=User::find(auth()->user()->id);
            $user->products()->attach($product,['quantity'=>$request->input('quantity')]);
            $msg = 'you are a vendor now';
            return $this->successResponse($product, $msg, 201);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
