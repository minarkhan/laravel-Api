<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Model\Product;
use Illuminate\Http\Request;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductsCollection;
use App\Exceptions\ProductNotBeLongsToUser;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  ProductsCollection::Collection (Product::paginate(5));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $product = new Product;

        $product->name = $request->name;
        $product->detail = $request->detail;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->discount = $request->discount;
        $product->user_id = Auth::id();
         $product->save();
        return response([
            'data' => new ProductResource($product),
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // return $product;
        return new ProductResource($product);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return response([
            'data' => new ProductResource($product),
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $this->ProductUserCheck($product);
        $product->delete();
        return response(null, 204);
    }

    public function ProductUserCheck($product){
        if(Auth::id() !== $product->user_id){
            throw new ProductNotBeLongsToUser;
        }
    }
}
