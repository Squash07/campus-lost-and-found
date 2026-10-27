<?php

namespace App\Http\Controllers;

use App\Models\Claim;
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

        // Pending claims other people sent on MY items: these need my decision.
        $reviewQueue = Claim::with(['user', 'item'])
            ->where('status', 'pending')
            ->whereHas('item', fn ($q) => $q->where('user_id', $user->id)->where('status', 'open'))
            ->oldest()
            ->get();

        $stats = [
            'total_items' => $user->items()->count(),
            'open_items' => $user->items()->where('status', 'open')->count(),
            'claimed_items' => $user->items()->where('status', 'claimed')->count(),
            'returned_items' => $user->items()->where('status', 'returned')->count(),
            'to_review' => $reviewQueue->count(),
            'my_pending_claims' => $user->claims()->where('status', 'pending')->count(),
        ];

        $myItems = $user->items()
            ->with('category')
            ->withCount([
                'claims',
                'claims as pending_claims_count' => fn ($q) => $q->where('status', 'pending'),
            ])
            ->latest()
            ->get();

        $myClaims = $user->claims()
            ->with(['item.category', 'item.user'])
            ->latest()
            ->get();

        return view('dashboard', compact('stats', 'reviewQueue', 'myItems', 'myClaims'));
    }
}
