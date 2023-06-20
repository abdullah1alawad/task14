<?php

namespace App\Http\Controllers;

use App\Http\Traits\GeneralTrait;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data =Review::all();
            if (!$data)
                return $this->errorResponse('No reviews yet', 404);
            $msg = 'Got you the reviews you are looking for';
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
                'user_id'=>'required|numeric',
                'product_id'=>'required|numeric',
                'content'=>'required|regex:/[a-zA-Z\s]+/',
                'stars'=>'required|numeric'
            ]
        );
        if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }
        try {
            $review = Review::create($request->all());
            $msg='review is created successfully';
            return $this->successResponse($review,$msg,201);
        }
        catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $data =Product::with('reviews')->find($id);
            if (!$data)
                return $this->errorResponse('No product with such id', 404);


            $msg = 'Got you the reviews you are looking for';
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    public function showUserReviews()
    {
        try {
            $data =auth()->user()->with('reviews')->get();
            if (!$data)
                return $this->errorResponse('No user with such id', 404);


            $msg = 'Got you the reviews you are looking for';
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
                'content' => 'required|regex:/[a-zA-Z\s]+/',
                'stars' => 'required|numeric'
            ]
        );
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        try {
            $data = Review::find($id);
            if (!$data)
                return $this->errorResponse('No review with such id', 404);

            $data->update($request->all());
            $data->save();
            $msg = 'The review is updated successfully';
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $data = Review::find($id);
            if (!$data)
                return $this->errorResponse('No review with such id', 404);

            $data->delete();
            $msg = 'The review is deleted successfully';
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
}
