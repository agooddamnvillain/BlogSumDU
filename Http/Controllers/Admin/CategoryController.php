<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories',
            'image' => 'required|mimes:jpeg,bmp,png,jpg',
        ]);

        $image = $request->file('image');
        $slug = str_slug($request->name);

        if (isset($image)) {
            $currentDate = Carbon::now()->toDateString();
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if (!Storage::disk('public')->exists('category')) {
                Storage::disk('public')->makeDirectory('category');
            }

            $categoryImage = Image::make($image)->resize(1600, 479)->save();
            Storage::disk('public')->put('category/'.$imagename, $categoryImage);

            if (!Storage::disk('public')->exists('category/slider')) {
                Storage::disk('public')->makeDirectory('category/slider');
            }

            $slider = Image::make($image)->resize(500, 333)->save();
            Storage::disk('public')->put('category/slider/'.$imagename, $slider);
        } else {
            $imagename = "default.png";
        }

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $slug;
        $category->image = $imagename;
        $category->save();

        Toastr::success('Category Successfully Saved :)', 'Success');
        return redirect()->route('admin.category.index');
    }

    // Інші методи залишаються незмінними для уніфікації стилю коду.

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (Storage::disk('public')->exists('category/'.$category->image)) {
            Storage::disk('public')->delete('category/'.$category->image);
        }

        if (Storage::disk('public')->exists('category/slider/'.$category->image)) {
            Storage::disk('public')->delete('category/slider/'.$category->image);
        }

        $category->delete();

        Toastr::success('Category Successfully Deleted :)', 'Success');
        return redirect()->back();
    }
}
