<?php

namespace App\Http\Controllers\products;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use function Nette\Utils\isEmail;
use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{
    use GeneralTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $msg = 'all products are Right Here';
            $data = Product::with('category')->get();
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
                'product_name' => 'required|regex:/[a-zA-Z\s]+/',
                'desc' => 'required|string',
                'price' => 'required|numeric',
                'category_id' => 'required|numeric',
                'quantity'=>'required|numeric'
            ]
        );
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            $product = Product::create($request->all());
            $user=User::find(auth()->user()->id);
            $user->products()->attach($product,['quantity'=>$request->input('quantity')]);
            $msg = 'product is created successfully';
            return $this->successResponse($product, $msg, 201);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Poduct $poduct
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $data = Product::with('category')->find($id);
            if (!$data)
                return $this->errorResponse('No product with such id', 404);


            $msg = 'Got you the product you are looking for';
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $poduct
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
                'product_name' => 'required|regex:/[a-zA-Z\s]+/',
                'desc' => 'required|string',
                'price' => 'required|numeric',
                'category_id' => 'required|numeric',
                'quantity'=>'required|numeric'
            ]
        );
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        try {
            $data = Product::find($id);
            if (!$data)
                return $this->errorResponse('No product with such id', 404);

            $data->update($request->all());
            $data->save();
            $user=User::find(auth()->user()->id);
            $user->products()->sync([$data->id=>['quantity'=>$request->input('quantity')]]);

            $msg = 'The product is updated successfully';
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Poduct $poduct
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $data = Product::find($id);
            if (!$data)
                return $this->errorResponse('No product with such id', 404);
            $data->delete();
            $msg = 'The product is deleted successfully';
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    public function filterProductsByCategory($letter)
    {
        try {
            $data = Product::whereRelation('category', 'category_name', 'like', '%' . $letter . '%')->with('category')->get();
            if (!sizeof($data))
                return $this->errorResponse('No product with such category', 404);
            $msg = 'Got data Successfully';
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    public function getAllProducts()
    {
        try {
            $data=Product::all();
            if(!$data)
                return $this->errorResponse('there is no products',404);
            $msg = 'Got data Successfully';
            return $this->successResponse($data, $msg);
        }catch (\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
    public function getProductAndVendors($id)
    {
        try {
            $product=Product::find($id);
            if(!$product)
                return $this->errorResponse('there is no product with this id',404);
            $data=$product->users()->get();
            $msg = 'Got data Successfully';
            return $this->successResponse($data, $msg);
        }catch (\Exception $ex){
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
}
