<?php

namespace App\Http\Controllers\Product;

use App\Enum\Party\PartyRole;
use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Negotiation\Negotiation;
use App\Models\Party\Party;
use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ProductController extends Controller {
    public function index(): View {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function getSupplierProductsView(): View {
        $party = Party::where('user', Auth::id())->first();

        if (!$party || $party->role !== PartyRole::SUPPLIER->value) {
            abort(403, 'Usuário sem permissão para acessar esta página.');
        }

        $products = Product::where('supplier', $party->id)->get();

        $i = 0;
        foreach ($products as $product) {
            $category = Category::where('id', $product->category)->first();
            $products[$i]['category'] = [
                'id' => $product->category,
                'name' => $category->name
            ];
            $i++;
        }

        $categories = Category::all();

        return view('products.supplier.products', compact('products', 'categories'));
    }

    public function store(Request $request): JsonResponse {
        $party = Party::where('user', Auth::id())->first();
        $user = User::where('id', Auth::id())->first();

        if (
            !$party ||
            $party->role !== PartyRole::SUPPLIER->value
        ) {
            return response()->json(['message' => 'Usuário sem permissão para acessar essa página.'], 403);
        }

        $validator = $this->validateProduct($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::create([
            'name' => $request->input('name'),
            'supplier' => $party->id,
            'stock' => $request->input('stock'),
            'price' => $request->input('price'),
            'category' => $request->input('category'),
            'image_url' => $request->input('image_url'),
        ]);

        return response()->json(['message' => 'Produto criado com sucesso!'], 201);
    }

    public function show(Product $product): JsonResponse|View {
        $party = Party::where('user', Auth::id())->first();

        if (!$party || $party->role !== PartyRole::CUSTOMER->value) {
            return response()->json(['message' => 'Usuário sem permissão para acessar essa página.'], 403);
        }

        $negotiations = Negotiation::where('customer', $party->id)->get();

        $supplierNegotiations = $negotiations->filter(function ($negotiation) use ($product) {
            return $negotiation->supplier === $product->supplier;
        });

        if ($supplierNegotiations->isEmpty()) {
            return response()->json([
                'message' => 'Não há negociações ativas com esse fornecedor. Volte para a tela de negociação e crie uma nova negociação.'
            ], 403);
        }

        $i = 0;
        foreach ($supplierNegotiations as $supplierNegotiation) {
            $party = Party::where('id', $supplierNegotiation->supplier)->first();
            $user =  User::where('id', $party->user)->first();

            $supplierNegotiations[$i]['supplier'] = [
                'id' => $supplierNegotiation->supplier,
                'name' => $user->name
            ];

            $i++;
        }

        return view('products.show', [
            'product' => $product,
            'negotiations' => $supplierNegotiations
        ]);
    }


    public function update(Request $request, int $id): JsonResponse {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }

        $validator = $this->validateProduct($request, $id);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product->update($request->only(['name', 'stock', 'price', 'category', 'image_url']));

        return response()->json($product, 200);
    }

    public function destroy(Product $product): JsonResponse {
        $product->delete();
        return response()->json(['message' => 'Produto deletado com sucesso.'], 204);
    }

    private function validateProduct(Request $request, int $id = null): \Illuminate\Contracts\Validation\Validator {
        $rules = [
            'name' => 'required|string|max:200',
            'stock' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'category' => 'required|exists:categories,id',
            'image_url' => 'required|string|max:254|url',
        ];

        $messages = [
            'name.required' => 'O nome do produto é obrigatório.',
            'name.max' => 'O nome do produto não pode ter mais de 200 caracteres.',
            'stock.required' => 'A quantidade em estoque é obrigatória.',
            'stock.numeric' => 'A quantidade em estoque deve ser um número.',
            'stock.min' => 'A quantidade em estoque deve ser pelo menos 0.',
            'price.required' => 'O preço é obrigatório.',
            'price.numeric' => 'O preço deve ser um valor numérico.',
            'price.min' => 'O preço deve ser um valor positivo.',
            'category.required' => 'A categoria é obrigatória.',
            'category.exists' => 'A categoria selecionada não é válida.',
            'image_url.required' => 'O link da imagem é obrigatório.',
            'image_url.string' => 'O link da imagem deve ser um texto válido.',
            'image_url.url' => 'O link da imagem deve ser uma URL válida.',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }
}
