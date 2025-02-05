<?php

namespace App\Http\Controllers\Negotiation\Product;

use App\Http\Controllers\Controller;
use App\Models\Negotiation\Negotiation;
use App\Models\Negotiation\NegotiationProduct;
use App\Models\Product\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
class NegotiationProductController extends Controller {

    public function index(int $negotiationId): View {
        $negotiationProducts = NegotiationProduct::where('negotiation', $negotiationId)->get();
        return view('negotiation.products.index', compact('negotiationProducts', 'negotiationId')
        );
    }

    public function store(Request $request): JsonResponse {
        $validatedData = $request->validate([
            'negotiation' => 'required|integer|exists:negotiations,id',
            'product' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer',
        ]);

        $product = Product::where('id', $request->input('product'))->first();

        if ($request->input('quantity') > $product->stock) {
            return response()->json([
                'message' => 'Quantidade de produtos selecionados não pode ser maior que: ' . $product->stock
            ], 403);
        }

        $negotiationProduct = NegotiationProduct::create([
            'negotiation' => $request->input('negotiation'),
            'product' => $request->input('product'),
            'quantity' => $request->input('quantity'),
            'total' => $product->price * $request->input('quantity'),
        ]);

        $negotiation = Negotiation::where('id', $request->input('negotiation'))->first();

        if ($negotiation) {
            $negotiation->total += $product->price * $request->input('quantity');
            $negotiation->save();
        }

        $product->stock -= $request->input('quantity');
        $product->save();

        return response()->json([
            'message' => 'Produtos adicionados a negociação!', 'negotiation-product' => $negotiationProduct
        ], 201);
    }

    public function destroy(int $negotiationId, int $productId): JsonResponse {
        $negotiationProduct = NegotiationProduct::where('negotiation', $negotiationId)->where('id', $productId)->first();

        $negotiation = Negotiation::where('id', $negotiationId)->first();
        $negotiation->total -= $negotiationProduct->total;
        $negotiation->save();

        $product = Product::where('id', $negotiationProduct->product)->first();
        $product->stock += $negotiationProduct->quantity;
        $product->save();

        $negotiationProduct->delete();

        return response()->json(['message' => 'Produto Excluído com sucesso.'], 204);
    }

}
