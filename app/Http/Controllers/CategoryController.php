<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\GeneralTrait;

class CategoryController extends Controller
{

    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $category=Category::all();
            if(!$category)
                return $this->errorResponse('there is no categories yet',404);
            return $this->successResponse($category,'all categories');
        }catch (\Exception $ex){
            return $this->errorResponse($ex->getMessage(),500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
                'category_name'=>'required|regex:/[a-zA-Z\s]+/',
                'desc'=>'required|string',
            ]
        );
        if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }
        try {
            $category = Category::create($request->all());
            $data=$category;
            $msg='category is created successfully';
            return $this->successResponse($data,$msg,201);
        }
        catch (\Exception $ex)
        {
            return $this->errorResponse($ex->getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $data = Category::find($id);
            if (!$data)
                return $this->errorResponse('No category with such id', 404);


            $msg = 'Got you the category you are looking for';
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
                'category_name' => 'required|regex:/[a-zA-Z\s]+/',
                'desc' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        try {
            $data = Category::find($id);
            if (!$data)
                return $this->errorResponse('No product with such id', 404);

            $data->update($request->all());
            $data->save();
            $msg = 'The category is updated successfully';
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
            $data = Category::find($id);
            if (!$data)
                return $this->errorResponse('No category with such id', 404);

            $data->delete();
            $msg = 'The category is deleted successfully';
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
}
