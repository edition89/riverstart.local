<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductFilter $request)
    {
        $products = Product::filter($request)->get();

        return view('products', compact(['products']));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     *
     *  * Example:
     * <code>
     * {
     *     "name": "Product Name",
     *     "price": 199,
     *     "category": [ 1, 2 ],
     *     "is_published": 1
     *
     * }
     * </code>
     *
     * Success:
     * <code>
     * {
     *     "status": 1
     * }
     * </code>
     * Failure:
     * <code>
     * {
     *     "status": 0
     * }
     * </code>
     *
     */
    public function store(Request $request)
    {
        $status = 0;
        $data = $request->json()->all();

        $rules = [
            'name' => 'string',
            'price' => 'regex:/^\d*(\.\d{2})?$/',
            'category' => 'array',
            'category.*' => 'integer',
            'is_published' => 'digits_between:0,1|nullable'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->passes()) {
            $category = $data['category'];
            unset($data['category']);
            $product = Product::create($data);

            $product->categories()->attach($category);

            $status = 1;

        } else {
            //error list
            //$validator->errors()->all()
        }

        $jsonData = [
            'status' => $status,
        ];

        return response()->json($jsonData);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     *
     * Example:
     * <code>
     * {
     *     "name": "Product Name New",
     *     "price": 199,
     *     "category": [ 1, 2 ],
     *     "is_published": 0
     * }
     * </code>
     *
     * Success:
     * <code>
     * {
     *     "status": 1
     * }
     * </code>
     * Failure:
     * <code>
     * {
     *     "status": 0
     * }
     * </code>
     *
     */
    public function update(Request $request, $id)
    {
        $status = 0;
        $data = $request->json()->all();

        $rules = [
            'name' => 'string|nullable',
            'price' => 'regex:/^\d*(\.\d{2})?$/|nullable',
            'category' => 'array|nullable',
            'category.*' => 'integer',
            'is_published' => 'digits_between:0,1|nullable'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->passes()) {
            $category = $data['category'];
            unset($data['category']);
            $product = Product::find($id);
            $product->update($data);
            $product->categories()->sync($category);
            $status = 1;
        } else {
            //error list
            //$validator->errors()->all()
        }

        $jsonData = [
            'status' => $status,
        ];

        return response()->json($jsonData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     *
     * Success:
     * <code>
     * {
     *     "status": 1
     * }
     * </code>
     *
     */
    public function destroy($id)
    {
        Product::whereId($id)->update(['is_deleted' => 1]);

        $jsonData = [
            'status' => 1,
        ];

        return response()->json($jsonData);
    }
}
