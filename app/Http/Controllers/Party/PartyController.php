<?php

namespace App\Http\Controllers\Party;

use App\Enum\Party\PartyOrigin;
use App\Enum\Party\PartyRole;
use App\Http\Controllers\Controller;
use App\Models\Party\Party;
use App\Models\User;
use Couchbase\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PartyController extends Controller {
    public function index(): View {
        if (!$this->validateUser()) {
            return view('');
        }

        $parties = Party::all();
        $users = User::all()->where('is_admin', false);
        $origins = PartyOrigin::cases();
        $roles = PartyRole::cases();

        $i = 0;
        foreach ($parties as $party) {
            $party->user = [
                'id' => $party->user,
                'name' => User::where('id', $party->user)->value('name'),
            ];
            $party->origin = PartyOrigin::values()[$party->origin] ?? 'N/A';
            $party->role = PartyRole::values()[$party->role] ?? 'N/A';

            $i++;
        }

        return view('parties.index', compact('parties', 'users', 'origins', 'roles'));
    }

    public function store(Request $request): JsonResponse|View {
        if (!$this->validateUser()) {
            return view('');
        }

        $validatedData = $request->validate([
            'user' => 'required|integer|exists:users,id',
            'origin' => 'required|integer|min:1|max:3',
            'role' => 'required|integer|min:1|max:2',
        ]);

        $party = Party::create([
            'user' => $validatedData['user'],
            'origin' => $validatedData['origin'],
            'role' => $validatedData['role'],
        ]);

        return response()->json(['message' => 'Pessoa cadastrada com sucesso!', 'party' => $party], 201);
    }

    public function destroy(Party $party): JsonResponse {
        $party->delete();
        return response()->json(['message' => 'Cadastro deletado com sucesso.'], 204);
    }

    public function validateUser(): bool {
        $loggedUser = User::where('id', Auth::id())->first();

        if (!$loggedUser || !$loggedUser->is_admin === true) {
            return false;
        }

        return true;
    }
}
