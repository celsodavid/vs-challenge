<?php


namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class ProductController extends BaseController
{
    public function show()
    {
        $products = Product::all();
        return response()->json(['products' => $products]);
    }

    public function get($id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json(['message' => 'Not found product'], 404);
        }

        return response()->json(['product' => $product]);
    }

    public function search(Request $request)
    {
        $filter = explode(':', $request->input('filter'));
        if (strtolower($filter[0]) != 'brand') {
            return response()->json(['message' => 'Brand not exists in products'], 404);
        }

        $products = Product::where(function ($search) use ($request, $filter) {
            $search->orWhere('name', 'like', '%' . $request->input('q') . '%');
            $search->Where($filter[0], 'like', '%' . $filter[1] . '%');
        })->get();

        return response()->json(['products' => $products], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products',
            'brand' => 'required',
            'value' => 'required|regex:' . Product::REGEX,
            'qty_stock' => 'required|numeric|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $product = new Product();
        $product->name = $request->input('name');
        $product->brand = $request->input('brand');
        $product->description = $request->input('description', null);
        $product->value = $request->input('value');
        $product->qty_stock = $request->input('qty_stock');
        $product->save();
        if (!$product->save()) {
            return response()->json(['message' => 'Not created product'], 400);
        }

        return response()->json(['product' => $product], 201);
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'brand' => 'required',
            'value' => 'required|regex:' . Product::REGEX,
            'qty_stock' => 'required|numeric|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json(['message' => 'Not found product'], 404);
        }

        $product->name = $request->input('name');
        $product->brand = $request->input('brand');
        $product->description = $request->input('description', null);
        $product->value = $request->input('value');
        $product->qty_stock = $request->input('qty_stock');
        $product->save();
        if (!$product->save()) {
            return response()->json(['message' => 'Not updated product'], 400);
        }

        return response()->json(['product' => $product], 201);
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json(['message' => 'Not found product'], 404);
        }

        if (!$product->delete()) {
            return response()->json(['message' => 'Not destroy product'], 400);
        }

        return response()->json(['message' => 'Product destroyed'], 200);
    }
}
