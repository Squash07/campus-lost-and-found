<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource (F2, F6).
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        // Validate the query string so odd input (e.g. ?q[]=x) can't crash the page.
        $validator = validator($request->only(['q', 'type', 'category', 'status']), [
            'q' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'in:lost,found'],
            'category' => ['nullable', 'integer'],
            'status' => ['nullable', 'in:open,claimed,returned'],
        ]);
        if ($validator->fails()) {
            return redirect()->route('items.index');
        }
        $filters = $validator->validated();

        $items = Item::with(['user', 'category'])
            ->filter($filters)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('items.index', compact('items', 'categories', 'filters'));
    }

    /**
     * Show the form for creating a new resource (F1).
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('items.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage (F1).
     */
    public function store(Request $request)
    {
        $validated = $this->validateItem($request);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('items', 'uploads');
        }

        $item = Auth::user()->items()->create([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'event_date' => $validated['event_date'],
            'photo_path' => $photoPath,
            'status' => 'open',
        ]);

        return redirect()->route('items.show', $item)
            ->with('success', 'Item posted. You can follow responses from your dashboard.');
    }

    /**
     * Display the specified resource (F3).
     */
    public function show(Item $item)
    {
        $item->load(['user', 'category']);

        // Used by the Posted → Claimed → Returned timeline
        $approvedClaim = $item->claims()->where('status', 'approved')->first();

        $isOwner = Auth::check() && Auth::id() === $item->user_id;

        $claims = $isOwner
            ? $item->claims()->with('user')->latest()->get()
            : collect();

        $userClaim = Auth::check() && !$isOwner
            ? $item->claims()->where('user_id', Auth::id())->first()
            : null;

        return view('items.show', compact('item', 'isOwner', 'claims', 'userClaim', 'approvedClaim'));
    }

    /**
     * Show the form for editing the specified resource (F4).
     */
    public function edit(Item $item)
    {
        $this->authorize('update', $item);

        $categories = Category::orderBy('name')->get();
        return view('items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified resource in storage (F4).
     */
    public function update(Request $request, Item $item)
    {
        $this->authorize('update', $item);

        $validated = $this->validateItem($request);

        if ($request->hasFile('photo')) {
            if ($item->photo_path && Storage::disk('uploads')->exists($item->photo_path)) {
                Storage::disk('uploads')->delete($item->photo_path);
            }
            $item->photo_path = $request->file('photo')->store('items', 'uploads');
        }

        $item->update([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'event_date' => $validated['event_date'],
            'photo_path' => $item->photo_path,
        ]);

        return redirect()->route('items.show', $item)
            ->with('success', 'Changes saved.');
    }

    /**
     * Remove the specified resource from storage (F5).
     */
    public function destroy(Item $item)
    {
        $this->authorize('delete', $item);

        if ($item->photo_path && Storage::disk('uploads')->exists($item->photo_path)) {
            Storage::disk('uploads')->delete($item->photo_path);
        }

        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Post deleted.');
    }

    /**
     * Mark an item as returned (F9).
     */
    public function markReturned(Item $item)
    {
        $this->authorize('update', $item);

        if ($item->status !== 'claimed') {
            return back()->with('error', 'Approve a claim first. Only claimed items can be marked as returned.');
        }

        $item->update(['status' => 'returned']);

        return back()->with('success', 'Marked as returned. Thanks for helping it get home.');
    }

    /**
     * Validation shared by store and update (F1, F4), with readable field
     * names so errors say "The category field is required." and not "category id".
     */
    private function validateItem(Request $request): array
    {
        return $request->validate([
            'type' => ['required', 'in:lost,found'],
            'title' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:2000'],
            'location' => ['required', 'string', 'max:150'],
            'event_date' => ['required', 'date', 'before_or_equal:today'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'type.required' => 'Choose whether you lost or found this item.',
            'event_date.before_or_equal' => 'The date can\'t be in the future.',
            'photo.max' => 'The photo must be 2 MB or smaller.',
        ], [
            'title' => 'item name',
            'category_id' => 'category',
            'event_date' => 'date',
        ]);
    }
}
