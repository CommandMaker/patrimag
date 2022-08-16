<?php

namespace App\Http\Controllers\Admin;

use App\Events\UserBannedEvent;
use App\Events\UserSuspendedEvent;
use App\Events\UserUnbannedEvent;
use App\Events\UserUnsuspendedEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function list(Request $request): View
    {
        $users = User::paginate(20);

        if (strtolower($request->get('show')) === 'all') {
            $users = User::withTrashed()->paginate(20);
        }

        return view('pages.admin.user-list', [
            'users' => $users,
        ]);
    }

    public function ban(Request $request, int $id): RedirectResponse
    {
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return back()->with('error', 'L\'utilisateur spécifié n\'existe pas !');
        }

        if ($user->is_admin) {
            return back()->with('error', 'Vous ne pouvez bannir un administrateur');
        }

        event(new UserBannedEvent($user));
        $user->delete();

        return back()->with('success', 'L\'utilisateur a bien été banni !');
    }

    public function unban(Request $request, int $id): RedirectResponse
    {
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return back()->with('error', 'L\'utilisateur spécifié n\'existe pas !');
        }

        if (!$user->deleted_at) {
            return back()->with('error', 'L\'utilisateur n\'es pas banni tu ne peux pas le débannir !');
        }

        event(new UserUnbannedEvent($user));
        $user->restore();

        return back()->with('success', 'L\'utilisateur a bien été débanni !');
    }

    public function suspend(Request $request, int $id): RedirectResponse
    {
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return back()->with('error', 'L\'utilisateur spécifié n\'existe pas !');
        }

        if ($user->trashed()) {
            return back()->with('error', 'Réfléchis 5 minutes ! Si l\'utilisateur est banni tu vas pas le suspendre ça n\'a aucun sens !');
        }

        if ($user->is_admin) {
            return back()->with('error', 'Vous ne pouvez suspendre un administrateur');
        }

        event(new UserSuspendedEvent($user));
        $user->update([
            'is_suspended' => true,
        ]);

        return back()->with('success', 'L\'utilisateur a bien été suspendu !');
    }

    public function unsuspend(Request $request, int $id): RedirectResponse
    {
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return back()->with('error', 'L\'utilisateur spécifié n\'existe pas !');
        }

        if (!$user->is_suspended) {
            return back()->with('error', 'L\'utilisateur n\'es pas suspendu tu ne peux pas le rétablir !');
        }

        event(new UserUnsuspendedEvent($user));
        $user->update([
            'is_suspended' => false,
        ]);

        return back()->with('success', 'L\'utilisateur a bien été rétabli !');
    }
}
