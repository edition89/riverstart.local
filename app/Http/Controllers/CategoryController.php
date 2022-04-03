<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     *     "name": "Category Name",
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
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->passes()) {
            Category::create($data);

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
     */
    public function update(Request $request, $id)
    {
        //
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
     * Failure:
     * <code>
     * {
     *     "status": 0,
     *     "error": 'Невозможно удалить категорию!'
     * }
     * </code>
     *
     */
    public function destroy($id)
    {
        $jsonData['status'] = 0;

        try {
            Category::whereId($id)->delete();
            $jsonData['status'] = 1;
        } catch (\Illuminate\Database\QueryException $e) {
            $jsonData['error'] = 'Невозможно удалить категорию!';
        }

        return response()->json($jsonData);
    }
}
