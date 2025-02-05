<?php

namespace App\Http\Controllers\Negotiation;

use App\Enum\Negotiation\NegotiationStatus;
use App\Enum\Party\PartyRole;
use App\Enum\Payment\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Models\Negotiation\Negotiation;
use App\Models\Party\Party;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NegotiationController extends Controller {

    public function index(): View {
        $partyId = Party::where('user', Auth::id())->value('id', 'user');

        $negotiations = Negotiation::where('customer', $partyId)
            ->get()
            ->map(function ($negotiation) {
                if (is_integer($negotiation->supplier)) {
                    $supplier = Party::where('id', $negotiation->supplier)->value('user');
                    $supplierName = User::where('id', $supplier)->first()->name;

                    $negotiation->supplier = $supplierName ?? 'Desconhecido';
                }

                $negotiation->status = NegotiationStatus::values()[$negotiation->status] ?? 'Desconhecido';
                $negotiation->payment_method = PaymentMethod::values()[$negotiation->payment_method] ?? 'Desconhecido';

                return $negotiation;
            });

        $suppliers = Party::where('role', PartyRole::SUPPLIER->value)
            ->with('user:id,name')
            ->get()
            ->toArray();

        $paymentMethods = [];
        $i = 0;
        foreach (PaymentMethod::values() as $key => $value) {
            $paymentMethods[$i] = [
                'id' => $key,
                'name' => $value,
            ];

            $i++;
        }

        return view(
            'negotiation.index',
            compact('negotiations', 'suppliers', 'paymentMethods')
        );
    }

    public function supplierIndex(): View {
        $partyId = Party::where('user', Auth::id())
            ->where('role', PartyRole::SUPPLIER->value)
            ->value('id', 'user');

        $negotiations = Negotiation::where('supplier', $partyId)
            ->get()
            ->map(function ($negotiation) {
                if (is_integer($negotiation->customer)) {
                    $customer = Party::where('id', $negotiation->customer)->value('user');
                    $customerName = User::where('id', $customer)->first()->name;

                    $negotiation->$customer = $customerName ?? 'Desconhecido';
                }

                $negotiation->status = NegotiationStatus::values()[$negotiation->status] ?? 'Desconhecido';
                $negotiation->payment_method = PaymentMethod::values()[$negotiation->payment_method] ?? 'Desconhecido';

                return $negotiation;
            });

        $paymentMethods = [];
        $i = 0;
        foreach (PaymentMethod::values() as $key => $value) {
            $paymentMethods[$i] = [
                'id' => $key,
                'name' => $value,
            ];

            $i++;
        }

        return view(
            'negotiation.supplier.index',
            compact('negotiations', 'paymentMethods')
        );
    }

    public function approve(int $id): JsonResponse {
        if (!$this->validateParty()) {
            return response()->json(['message' => 'Apenas fornecedores podem aprovar negociações.'], 403);
        }

        $negotiation = Negotiation::findOrFail($id);
        $negotiation->status = NegotiationStatus::ACCEPTED->value;
        $negotiation->save();

        return response()->json(['message' => 'Negociação aprovada!'], 200);
    }

    public function decline(int $id): JsonResponse {
        if (!$this->validateParty()) {
            return response()->json(['message' => 'Apenas fornecedores podem recusar negociações.'], 403);
        }

        $negotiation = Negotiation::findOrFail($id);
        $negotiation->status = NegotiationStatus::DECLINED->value;
        $negotiation->save();

        return response()->json(['message' => 'Negociação recusada!'], 200);
    }

    public function store(Request $request): JsonResponse {
        $validatedData = $request->validate([
            'supplier' => [
                'required',
                'exists:parties,id',
                function ($attribute, $value, $fail) {
                    if (!Party::where('id', $value)->where('role', PartyRole::SUPPLIER->value)) {
                        $fail('Você precisa selecionar um fornecedor válido.');
                    }
                },
            ],
            'payment_method' => 'required|integer',
            'shipping_address' => 'required|string|max:255',
        ]);

        $customerId = Party::where('user', Auth::id())->value('id');

        if (!$customerId) {
            return response()->json(['error' => 'Cliente inválido.'], 400);
        }

        $negotiation = Negotiation::create([
            'status' => NegotiationStatus::PENDING,
            'customer' => $customerId,
            'supplier' => $validatedData['supplier'],
            'discount' => 0,
            'payment_method' => $validatedData['payment_method'],
            'shipping_address' => $validatedData['shipping_address'],
            'total' => 0,
        ]);

        return response()->json(['message' => 'Negociação criada com sucesso!', 'negotiation' => $negotiation], 201);
    }


    public function destroy(Negotiation $negotiation): JsonResponse {
        $negotiation->delete();
        return response()->json(['message' => 'Negociação deletada com sucesso.'], 204);
    }

    public function validateParty(): bool {
        $partyId = Party::where('user', Auth::id())
            ->where('role', PartyRole::SUPPLIER->value)
            ->value('id', 'user');

        if (!$partyId) {
            return false;
        }

        return true;
    }
}
