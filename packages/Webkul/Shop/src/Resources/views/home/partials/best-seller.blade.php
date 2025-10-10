@php
    $title = $data['title'] ?? 'Best Sellers';

    if (!isset($latestProducts)) {
        $latestProducts = app(\Webkul\Product\Repositories\ProductRepository::class)->getLatestProducts();
    }
@endphp

<!-- You can create a separate CSS file or use the same one -->
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
        @foreach ($bestSellerProducts as $product)
            @php
                $mainImage = $product->images->first();
                // Generate product URL using the product's slug
                $productUrl = route('shop.product_or_category.index', $product->url_key);
            @endphp

            <div class="product-card">
                <!-- Product Image with Link -->
                <div class="product-image">
                    <a href="{{ $productUrl }}" class="block">
                        @if ($mainImage)
                            <img src="{{ asset('storage/' . $mainImage->path) }}" alt="{{ $product->name }}">
                        @else
                            <img src="https://via.placeholder.com/200x250.png?text=No+Image" alt="No Image">
                        @endif
                    </a>
                </div>

                <!-- Product Name with Link -->
                <h3 class="font-semibold text-lg mb-2 line-clamp-2 min-h-[3.5rem] flex items-center justify-center">
                    <a href="{{ $productUrl }}" class="hover:text-blue-600 transition-colors">
                        {{ $product->name }}
                    </a>
                </h3>

                <!-- Product Price -->
                <p class="text-gray-600 mb-3 font-medium text-xl">
                    ${{ number_format($product->price, 2) }}
                </p>

                <!-- Add to Cart Button -->
                <button
                    class="bg-black text-white px-6 py-3 rounded-lg transition duration-300 mt-auto font-medium add-to-cart-btn">
                    Add to Cart
                </button>
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
