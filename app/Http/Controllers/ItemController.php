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

        $filters = $request->only(['q', 'type', 'category', 'status']);

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
        $validated = $request->validate([
            'type' => ['required', 'in:lost,found'],
            'title' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:150'],
            'event_date' => ['required', 'date', 'before_or_equal:today'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

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
            ->with('success', 'Item posted successfully!');
    }

    /**
     * Display the specified resource (F3).
     */
    public function show(Item $item)
    {
        $item->load(['user', 'category']);

        $isOwner = Auth::check() && Auth::id() === $item->user_id;

        $claims = $isOwner
            ? $item->claims()->with('user')->latest()->get()
            : collect();

        $userClaim = Auth::check() && !$isOwner
            ? $item->claims()->where('user_id', Auth::id())->first()
            : null;

        return view('items.show', compact('item', 'isOwner', 'claims', 'userClaim'));
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

        $validated = $request->validate([
            'type' => ['required', 'in:lost,found'],
            'title' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:150'],
            'event_date' => ['required', 'date', 'before_or_equal:today'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

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
            ->with('success', 'Item updated successfully!');
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
            ->with('success', 'Item deleted successfully.');
    }

    /**
     * Mark an item as returned (F9).
     */
    public function markReturned(Item $item)
    {
        abort_unless(Auth::id() === $item->user_id, 403, 'Unauthorized action.');
        abort_unless($item->status === 'claimed', 422, 'Only claimed items can be marked as returned.');

        $item->update(['status' => 'returned']);

        return back()->with('success', 'Item has been marked as returned!');
    }
}
