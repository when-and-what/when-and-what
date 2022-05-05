<?php

namespace App\Http\Controllers\Locations;

use App\Http\Controllers\Controller;
use App\Models\Locations\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
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
        return view('locations.categories.edit', [
            'category' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required|unique:location_categories',
            'emoji' => 'nullable',
        ]);

        $category = new Category();
        $category->user_id = $request->user()->id;
        $category->fill($valid);
        $category->save();
        return redirect(route('categories.edit', $category));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locations\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Locations\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('locations.categories.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Locations\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $valid = $request->validate([
            'name' => ['required', Rule::unique('location_categories')->ignore($category)],
            'emoji' => 'nullable',
        ]);

        $category->fill($valid);
        $category->save();
        return redirect(route('categories.edit', $category));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locations\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
