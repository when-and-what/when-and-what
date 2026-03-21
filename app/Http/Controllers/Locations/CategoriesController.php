<?php

namespace App\Http\Controllers\Locations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Locations\CreateCategoryRequest;
use App\Http\Requests\Locations\UpdateCategoryRequest;
use App\Models\Locations\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }

    /**
     * Display all of a user's categories
     *!this is not restricted by the Category::class policy.
     */
    public function index(Request $request): View
    {
        return view('locations.categories.list', [
            'categories' => Category::where('user_id', $request->user()->id)
                ->withCount('locations')
                ->orderBy('name', 'ASC')
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        return view('locations.categories.edit', [
            'category' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request): RedirectResponse
    {
        $category = new Category;
        $category->user_id = $request->user()->id;
        $category->fill($request->validated());
        $category->save();

        return redirect(route('categories.edit', $category));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        return view('locations.categories.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->fill($request->validated());
        $category->save();

        return redirect(route('categories.edit', $category));
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect(route('categories.index'));
    }
}
