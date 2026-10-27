<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display user dashboard (F11).
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        $stats = [
            'total_items' => $user->items()->count(),
            'open_items' => $user->items()->where('status', 'open')->count(),
            'claimed_items' => $user->items()->where('status', 'claimed')->count(),
            'returned_items' => $user->items()->where('status', 'returned')->count(),
            'pending_claims' => $user->claims()->where('status', 'pending')->count(),
        ];

        $myItems = $user->items()
            ->with(['category', 'claims'])
            ->latest()
            ->get();

        $myClaims = $user->claims()
            ->with(['item.category', 'item.user'])
            ->latest()
            ->get();

        return view('dashboard', compact('stats', 'myItems', 'myClaims'));
    }
}
