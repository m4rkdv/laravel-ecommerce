<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }

    public function brands(){
        $brands = Brand::orderBy('id','DESC')->paginate(10);
        return view('admin.brands',compact('brands'));
    }

    public function add_brands()
    {
        return view('admin.brand-add');
    }

    public function edit_brand($id)
    {
        $brand = Brand::find($id);
        return view('admin.brand-edit',compact('brand'));
    }

    public function brand_update(Request $request){
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'. $request->id,
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        
        $brand = Brand::find($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        if ($request->hasFile('image'))
        {
            if (File::exists(public_path('uploads/brands').'/'.$brand->image))
            {
                File::delete(public_path('uploads/brands').'/'.$brand->image);
            }        
            $image = $request->file('image');
            $file_extension = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $this->GenThumbnailsImage($image,$file_name,'brands');
            $brand->image = $file_name;
        }
        
        $brand->save();
        return redirect()->route('admin.brands')->with('status','Brand has been updated succesfully!');
        
    }

    public function delete_brand($id)
    {
        $brand = Brand::find($id);
        if (File::exists(public_path('uploads/brands').'/'.$brand->image))
        {
            File::delete(public_path('uploads/brands').'/'.$brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brands')->with('status','Brand has been deleted successfully!');
    }

    public function brand_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extension;
        $this->GenThumbnailsImage($image,$file_name,'brands');
        $brand->image = $file_name;
        $brand->save();
        return redirect()->route('admin.brands')->with('status','Brand has been added succesfully!');
    }

    public function GenThumbnailsImage($image,$image_name,$type)
    {
        
        $destinationPath = public_path('uploads/'.$type);
        $img = Image::read($image->path());
        $img->cover(124,124,"top");
        $img->resize(124,124,function($constraint)
            {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$image_name);
    }

    public function categories()
    {
        $categories=Category::orderBy('id','DESC')->paginate(10);
        return view('admin.categories',compact('categories'));
    }

    public function add_category()
    {
        return view('admin.category-add');
    }

    public function category_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $image = $request->file('image');
        $file_extension = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp.'.'.$file_extension;
        $this->GenThumbnailsImage($image,$file_name,'categories');
        $category->image = $file_name;
        $category->save();
        return redirect()->route('admin.categories')->with('status','Category has been added succesfully!');
    }

    public function edit_category($id)
    {
        $category = Category::find($id);
        return view('admin.category-edit',compact('category'));
    }

    public function category_update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'. $request->id . ',id',
            'image' => 'mimes:png,jpg,jpeg|max:2048'
        ]);

        
        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        if ($request->hasFile('image'))
        {
            if (File::exists(public_path('uploads/categories').'/'.$category->image))
            {
                File::delete(public_path('uploads/categories').'/'.$category->image);
            }        
            $image = $request->file('image');
            $file_extension = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp.'.'.$file_extension;
            $this->GenThumbnailsImage($image,$file_name,'categories');
            $category->image = $file_name;
        }
        
        $category->save();
        return redirect()->route('admin.categories')->with('status','Category has been updated succesfully!');
        
    }

    public function category_delete($id)
    {
        $category=Category::find($id);
        if(File::exists(public_path('uploads/categories').'/'.$category->image))
        {
            File::delete(public_path('uploads/categories').'/'.$category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status','Category has been deleted succesfully ');
    }

    public function products()
    {
        $products= Product::orderBy('created_at','DESC')->paginate(10);
        return view('admin.products',compact('products'));
    }

    public function add_product()
    {
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-add',compact('categories','brands'));
    }

    public function product_store(Request $request)
    {
        $request->validate([
            'name' =>'required',
            'slug' =>'required|unique:products,slug',
            'short_description' =>'required',
            'description' =>'required',
            'regular_price' =>'required',
            'sale_price' =>'required',
            'SKU' =>'required',
            'stock_status' =>'required',
            'featured' =>'required',
            'quantity' =>'required',
            'image' =>'required|mimes:png,jpg,jpeg|max:2048',
            'category_id' =>'required',
            'brand_id' =>'required'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        if ($request->hasFile('image'))
        {
            $image = $request->file('image');
            $file_name = Carbon::now()->timestamp.'.'.$image->extension();
            $this->GenProductImages($image,$file_name);
            $product->image = $file_name;
        }

        $gallery_arr=array();
        $gallery_images="";
        $cont=1;
        if ($request->hasFile('images'))
        {
            $allowedFileExtension=['jpg','png','jpeg'];
            $files = $request->file('images');
            foreach($files as $file)
            {
                $gExtension = $file->getClientOriginalExtension();
                $gCheck = in_array($gExtension,$allowedFileExtension);
                if($gCheck)
                {
                    $gFileName = Carbon::now()->timestamp . "-" . $cont . "." . $gExtension;
                    $this->GenProductImages($file,$gFileName);
                    array_push($gallery_arr,$gFileName);
                    $cont++;
                }
                $gallery_images = implode(',',$gallery_arr);
            }
        }
        $product->images = $gallery_images;
       
        $product->save();
        return redirect()->route('admin.products')->with('status','Product has been added succesfully!');
    }

    public function GenProductImages($image,$image_name)
    {
        $destinationPath = public_path('uploads/products');
        $destinationPathThumbnails = public_path('uploads/products/thumbnails');
        $img = Image::read($image->path());
        $img->cover(540,689,"top");
        $img->resize(540,689,function($constraint)
            {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$image_name);

        $img->resize(104,104,function($constraint)
            {
                $constraint->aspectRatio();
            })->save($destinationPathThumbnails.'/'.$image_name);
    }

    public function product_edit($id)
    {
        $product=Product::find($id);
        $categories=Category::select('id','name')->orderBy('name')->get();
        $brands=Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-edit',compact('product','categories','brands'));
    }

    public function product_update(Request $request)
    {
        $request->validate([
            'name' =>'required',
            'slug' => 'required|unique:products,slug,' . $request->id . ',id',
            'short_description' =>'required',
            'description' =>'required',
            'regular_price' =>'required',
            'sale_price' =>'required',
            'SKU' =>'required',
            'stock_status' =>'required',
            'featured' =>'required',
            'quantity' =>'required',
            'image' =>'nullable|mimes:png,jpg,jpeg|max:2048',
            'category_id' =>'required',
            'brand_id' =>'required'
        ]);

        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        if ($request->hasFile('image'))
        {
            if (File::exists(public_path('uploads/products').'/'.$product->image))
            {
                File::delete(public_path('uploads/products').'/'.$product->image);
            }
            if (File::exists(public_path('uploads/products/thumbnails').'/'.$product->image))
            {
                File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
            }
            $image = $request->file('image');
            $file_name = Carbon::now()->timestamp.'.'.$image->extension();
            $this->GenProductImages($image,$file_name);
            $product->image = $file_name;
        }

        $gallery_arr=array();
        $gallery_images="";
        $cont=1;
        if ($request->hasFile('images'))
        {
            foreach(explode(',',$product->images) as $oFile)
            {
                if (File::exists(public_path('uploads/products').'/'.$oFile))
                {
                    File::delete(public_path('uploads/products').'/'.$oFile);
                }
                if (File::exists(public_path('uploads/products/thumbnails').'/'.$oFile))
                {
                    File::delete(public_path('uploads/products/thumbnails').'/'.$oFile);
                }
            }
            $allowedFileExtension=['jpg','png','jpeg'];
            $files = $request->file('images');
            foreach($files as $file)
            {
                $gExtension = $file->getClientOriginalExtension();
                $gCheck = in_array($gExtension,$allowedFileExtension);
                if($gCheck)
                {
                    $gFileName = Carbon::now()->timestamp . "-" . $cont . "." . $gExtension;
                    $this->GenProductImages($file,$gFileName);
                    array_push($gallery_arr,$gFileName);
                    $cont++;
                }
                $gallery_images = implode(',',$gallery_arr);
                $product->images = $gallery_images;
            }
        }       
        $product->save();
        return redirect()->route('admin.products')->with('status','Product has been updated succesfully!');
    }

    public function product_delete($id)
    {
        $product = Product::find($id);
        if (File::exists(public_path('uploads/products').'/'.$product->image))
        {
            File::delete(public_path('uploads/products').'/'.$product->image);
        }
        if (File::exists(public_path('uploads/products/thumbnails').'/'.$product->image))
        {
            File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
        }

        foreach(explode(',',$product->images) as $oFile)
        {
            if (File::exists(public_path('uploads/products').'/'.$oFile))
            {
                File::delete(public_path('uploads/products').'/'.$oFile);
            }
            if (File::exists(public_path('uploads/products/thumbnails').'/'.$oFile))
            {
                File::delete(public_path('uploads/products/thumbnails').'/'.$oFile);
            }
        }

        $product->delete();
        return redirect()->route('admin.products')->with('status','Product has been deleted successfully!');
    }

    public function coupons()
    {
        $coupons = Coupon::orderBy('expiry_date','DESC')->paginate(12);
        return view('admin.coupons',compact('coupons'));
    }

    public function add_coupon()
    {
        return view('admin.add-coupons');
    }

    public function store_coupon(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons|max:10',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:1',
            'cart_value' => 'required|numeric|min:1',
            'expiry_date' => 'required|date|after:today',
        ], [
            'code.required' => 'El código del cupón es obligatorio.',
            'code.unique' => 'Este código de cupón ya existe.',
            'type.required' => 'El tipo de cupón es obligatorio.',
            'value.required' => 'El valor del cupón es obligatorio.',
            'value.numeric' => 'El valor del cupón debe ser un número.',
            'cart_value.required' => 'El valor mínimo del carrito es obligatorio.',
            'cart_value.numeric' => 'El valor del carrito debe ser un número.',
            'expiry_date.after' => 'La fecha de expiración debe ser futura.',
        ]);        

        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();

        return redirect()->route('admin.coupons')->with('status','Coupon has been added successfully!');
    }

    public function edit_coupon($id)
    {
        $coupon = Coupon::find($id);
        return view('admin.edit-coupon',compact('coupon'));
    }

    public function update_coupon(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code,' . $request->id . ',id|max:10',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:1',
            'cart_value' => 'required|numeric|min:1',
            'expiry_date' => 'required|date|after:today',
        ], [
            'code.required' => 'El código del cupón es obligatorio.',
            'code.unique' => 'Este código de cupón ya existe.',
            'type.required' => 'El tipo de cupón es obligatorio.',
            'value.required' => 'El valor del cupón es obligatorio.',
            'value.numeric' => 'El valor del cupón debe ser un número.',
            'cart_value.required' => 'El valor mínimo del carrito es obligatorio.',
            'cart_value.numeric' => 'El valor del carrito debe ser un número.',
            'expiry_date.after' => 'La fecha de expiración debe ser futura.',
        ]);        

        $coupon = Coupon::find($request->id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();

        return redirect()->route('admin.coupons')->with('status','Coupon has been updated successfully!');
    }

    public function delete_coupon($id)
    {
        $coupon = Coupon::find($id);
        $coupon->delete();
        return redirect()->route('admin.coupons')->with('status','Coupon has been deleted succesfully!');
    }

    public function orders()
    {
        $orders= Order::orderBy('created_at','DESC')->paginate(12);
        return view('admin.orders',compact('orders'));
    }
    
}
