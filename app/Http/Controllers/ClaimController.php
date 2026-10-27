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
     */
    public function store(Request $request, Item $item)
    {
        abort_if(Auth::id() === $item->user_id, 403, 'You cannot claim your own item.');
        abort_if($item->status !== 'open', 422, 'This item is not open for claims.');

        $alreadyClaimed = $item->claims()->where('user_id', Auth::id())->exists();
        abort_if($alreadyClaimed, 422, 'You have already submitted a claim on this item.');

        $validated = $request->validate([
            'message' => ['required', 'string', 'min:20'],
            'contact' => ['required', 'string', 'max:50'],
        ]);

        $item->claims()->create([
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'contact' => $validated['contact'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Claim submitted successfully! The poster will review your proof.');
    }

    /**
     * Approve a claim (F8).
     */
    public function approve(Claim $claim)
    {
        $this->authorize('review', $claim);
        abort_unless($claim->status === 'pending', 422, 'Only pending claims can be approved.');

        DB::transaction(function () use ($claim) {
            $claim->update(['status' => 'approved']);
            $claim->item->update(['status' => 'claimed']);

            // Auto-reject other pending claims on this item
            $claim->item->claims()
                ->where('id', '!=', $claim->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);
        });

        return back()->with('success', 'Claim approved! Claimant contact info: ' . $claim->contact);
    }

    /**
     * Reject a claim (F8).
     */
    public function reject(Claim $claim)
    {
        $this->authorize('review', $claim);
        abort_unless($claim->status === 'pending', 422, 'Only pending claims can be rejected.');

        $claim->update(['status' => 'rejected']);

        return back()->with('success', 'Claim has been rejected.');
    }
}
