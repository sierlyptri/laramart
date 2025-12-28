<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user has completed order with this product
        $hasCompletedOrder = Order::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->exists();

        if (!$hasCompletedOrder) {
            return redirect()->back()
                ->with('error', 'You can only review products from completed orders.');
        }

        // Check if already reviewed
        $existingReview = Review::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            // Update existing review
            $existingReview->update($validated);
            $message = 'Review updated successfully!';
        } else {
            // Create new review
            $validated['user_id'] = auth()->id();
            $validated['product_id'] = $product->id;
            Review::create($validated);
            $message = 'Review created successfully!';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Delete review
     */
    public function destroy(Review $review)
    {
        // Check authorization
        if ($review->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $productSlug = $review->product->slug;
        $review->delete();

        return redirect()->route('products.show', $productSlug)
            ->with('success', 'Review deleted successfully!');
    }
}
