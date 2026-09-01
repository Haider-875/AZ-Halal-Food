<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'subcategory']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $s = strtolower($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('sku', 'like', "%{$s}%")
                    ->orWhere('desc', 'like', "%{$s}%");
            });
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::with('subcategories')->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'nullable|string|max:50|unique:products,sku',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:5120',
            'img' => 'nullable|string|max:500',
            'badge' => 'nullable|string|max:50',
            'stock' => 'required|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = $validated['img'] ?? null;

        if ($request->hasFile('image_file')) {
            $uploadDirectory = public_path('uploads/products');
            if (! File::isDirectory($uploadDirectory)) {
                File::makeDirectory($uploadDirectory, 0755, true, true);
            }

            $file = $request->file('image_file');
            $filename = 'product_'.time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
            $file->move($uploadDirectory, $filename);
            $imagePath = 'uploads/products/'.$filename;
        }

        $validated['img'] = $imagePath;
        $validated['sku'] = $validated['sku'] ?: 'AZ-'.strtoupper(Str::random(6));
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['slug'] = Str::slug($validated['name']).'-'.strtolower(Str::random(4));

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::with('subcategories')->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sku' => "required|string|max:50|unique:products,sku,{$product->id}",
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:5120',
            'img' => 'nullable|string|max:500',
            'badge' => 'nullable|string|max:50',
            'stock' => 'required|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = $product->getRawOriginal('img') ?? $product->img;

        if ($request->hasFile('image_file')) {
            $uploadDirectory = public_path('uploads/products');
            if (! File::isDirectory($uploadDirectory)) {
                File::makeDirectory($uploadDirectory, 0755, true, true);
            }

            // Remove old uploaded file if it exists locally
            $rawImg = $product->getRawOriginal('img') ?? $product->img;
            if (! empty($rawImg) && ! str_starts_with($rawImg, 'http') && File::exists(public_path($rawImg))) {
                File::delete(public_path($rawImg));
            }

            $file = $request->file('image_file');
            $filename = 'product_'.time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
            $file->move($uploadDirectory, $filename);
            $imagePath = 'uploads/products/'.$filename;
        } elseif ($request->filled('img')) {
            $imagePath = $request->img;
        }

        $validated['img'] = $imagePath;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $rawImg = $product->getRawOriginal('img') ?? $product->img;
        if (! empty($rawImg) && ! str_starts_with($rawImg, 'http') && File::exists(public_path($rawImg))) {
            File::delete(public_path($rawImg));
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
