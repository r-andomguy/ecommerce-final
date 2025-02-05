<?php

namespace App\Http\Controllers\User;

use App\Enum\User\UserDocumentType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
class UserController extends Controller {
    public function index(): View {
        $loggedUser = User::where('id', Auth::id())->first();

        if(!$loggedUser || !$loggedUser->is_admin === true){
            return view('home');
        }

        $users = User::all()->where('is_admin', false);

        foreach ($users as $user) {
            $user->document_type = UserDocumentType::values()[$user->document_type] ?? 'N/A';
        }

        return view('users.index', compact('users'));
    }
}
