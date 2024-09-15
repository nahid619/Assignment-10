<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function getProducts()
    {
        $products = Product::all(); // Or use ->select() for specific columns
    
        return DataTables::of($products)
            ->addColumn('actions', function ($product) {
                return '<button class="edit-btn btn btn-info" data-id="' . $product->id . '">Edit</button>
                        <button class="delete-btn btn btn-danger" data-id="' . $product->id . '">Delete</button>';
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $product = Product::create($request->all());
        return response()->json(['success' => true, 'product' => $product]);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return response()->json(['success' => true, 'product' => $product]);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['success' => true]);
    }
}
