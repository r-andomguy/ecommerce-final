<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CategoryController extends Controller {

    public function index(): View {
        $categories = Category::all();

        return view('categories.index', compact('categories'));
    }

    /**
     * @return Response
     */
    public function create(): Response {
        return response(view('category.new_category'));
    }

    public function store(Request $request): false|string {
        $messages = [
            'name.required' => 'Campo obrigatório',
            'name.unique' => 'Essa categoria já existe.',
            'name.max' => 'O nome da categoria não pode ter mais de 60 caracteres.',
        ];

        $request->validate([
            'name' => 'required|unique:categories|max:60',
        ], $messages);

        $category = new Category();
        $category->name = $request->input('name');
        $category->save();

        return json_encode($category);
    }

    public function show(Category $category): View {
        return view('categories.show', compact('category'));
    }

    /**
     * @param string $id
     * @return Response
     */
    public function edit(string $id): Response {
        $category = Category::find($id);

        if (isset($category)) {
            return response(view('category.edit_category', compact('category')));
        }

        return response(redirect('/categories'));
    }

    /**
     * @param Request $request
     * @param string $id
     * @return Response|string|false
     */
    public function update(Request $request, string $id): Response|string|false {
        $category = Category::find($id);

        if (isset($category)) {
            $category->name = $request->input('name');
            $category->save();

            return json_encode($category);
        }

        return response('Categoria não encontrada', 404);
    }


    public function destroy(string $id): void {
        $category = Category::find($id);
        $category?->delete();
    }

}
