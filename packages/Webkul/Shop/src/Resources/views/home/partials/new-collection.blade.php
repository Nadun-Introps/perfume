@php
    $title = $data['title'] ?? 'New Arrivals';
@endphp

<!-- Link to your CSS file -->
<link rel="stylesheet" href="{{ asset('css/new-collection.css') }}">

<div class="new-collection container mx-auto py-10">
    <!-- Title with partial underline - Wrapper for proper centering -->
    <div class="title-wrapper text-center mb-8">
        <h2 class="inline-block relative">
            {{ $title }}
            <span class="title-underline"></span>
        </h2>
    </div>

    <div class="grid">
        @foreach ($latestProducts as $product)
            @php
                $mainImage = $product->images->first();
                $productUrl = route('shop.product_or_category.index', $product->url_key);

                // Determine image source with fallback
                if ($mainImage) {
                    // Check public storage first
                    $publicPath = 'storage/' . $mainImage->path;
                    $storagePath = $mainImage->path;

                    if (file_exists(public_path($publicPath))) {
                        $imageSrc = asset($publicPath);
                    } elseif (Storage::exists($storagePath)) {
                        $imageSrc = Storage::url($storagePath);
                    } else {
                        $imageSrc = 'https://via.placeholder.com/200x250.png?text=No+Image';
                    }
                } else {
                    $imageSrc = 'https://via.placeholder.com/200x250.png?text=No+Image';
                }

                // Determine the display price based on product type
                if ($product->type === 'configurable') {
                    $displayPrice = 'As low as $' . number_format($product->price_index_min, 2);
                } else {
                    $displayPrice = '$' . number_format($product->price, 2);
                }
            @endphp

            <div class="product-card">
                <!-- Product Image with Link -->
                <div class="product-image">
                    <a href="{{ $productUrl }}" class="block">
                        <img src="{{ $imageSrc }}" alt="{{ $product->name }}">
                    </a>
                </div>

                <!-- Product Name with Link -->
                <h3 class="font-semibold text-lg mb-2 line-clamp-2 min-h-[3.5rem] flex items-center justify-center">
                    <a href="{{ $productUrl }}" class="hover:text-blue-600 transition-colors">
                        {{ $product->name }}
                    </a>
                </h3>

                <!-- Product Price -->
                <p class="price">
                    {{ $displayPrice }}
                </p>

                <!-- Add to Cart Button - Now redirects to product page -->
                <a href="{{ $productUrl }}"
                    class="bg-black text-white px-6 py-3 rounded-lg transition duration-300 mt-auto font-medium add-to-cart-btn block text-center">
                    Add to Cart
                </a>
            </div>
        @endforeach
    </div>

    <!-- View All Button -->
    <div class="text-center mt-8">
        <a href="{{ route('shop.search.index', ['new' => 1, 'sort' => 'created_at-desc']) }}"
            class="view-all-btn inline-block">
            View all &gt;
        </a>
    </div>
</div>
