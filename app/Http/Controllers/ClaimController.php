<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClaimController extends Controller
{
    /**
     * Submit a claim on an item (F7).
     *
     * Business-rule violations redirect back with a friendly error message
     * instead of a bare HTTP error page.
     */
    public function store(Request $request, Item $item)
    {
        if (Auth::id() === $item->user_id) {
            return back()->with('error', 'You cannot claim an item you posted yourself.');
        }

        if ($item->status !== 'open') {
            return back()->with('error', 'This item is no longer accepting claims.');
        }

        if ($item->claims()->where('user_id', Auth::id())->exists()) {
            return back()->with('error', 'You have already sent a claim for this item.');
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'min:20', 'max:1000'],
            'contact' => ['required', 'string', 'max:50'],
        ]);

        $item->claims()->create([
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'contact' => $validated['contact'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Claim sent. The poster will review it and you can track it on your dashboard.');
    }

    /**
     * Approve a claim (F8). Runs in a transaction so the item status
     * and the other claims never end up half-updated.
     */
    public function approve(Claim $claim)
    {
        $this->authorize('review', $claim);

        if ($claim->status !== 'pending') {
            return back()->with('error', 'Only pending claims can be approved.');
        }

        // Lock the item row so two approvals sent at the same moment (e.g. from
        // two browser tabs) can't both succeed.
        $approved = DB::transaction(function () use ($claim) {
            $item = Item::whereKey($claim->item_id)->lockForUpdate()->first();
            $fresh = Claim::whereKey($claim->id)->lockForUpdate()->first();

            if ($item->status !== 'open' || $fresh->status !== 'pending') {
                return false;
            }

            $fresh->update(['status' => 'approved']);
            $item->update(['status' => 'claimed']);

            // Auto-reject the other pending claims on this item
            $item->claims()
                ->where('id', '!=', $fresh->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);

            return true;
        });

        if (! $approved) {
            return back()->with('error', 'This item already has an approved claim.');
        }

        return back()->with('success', 'Claim approved. Contact ' . $claim->user->name . ' at ' . $claim->contact . ' to arrange the handover.');
    }

    /**
     * Reject a claim (F8).
     */
    public function reject(Claim $claim)
    {
        $this->authorize('review', $claim);

        if ($claim->status !== 'pending') {
            return back()->with('error', 'Only pending claims can be rejected.');
        }

        $claim->update(['status' => 'rejected']);

        return back()->with('success', 'Claim rejected.');
    }
}
