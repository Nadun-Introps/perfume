@php
    $title = $data['title'];
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
        @foreach ($summerProducts as $product)
            @php
                $mainImage = $product->images->first();
            @endphp

            <div class="product-card">
                <!-- Product Image -->
                <div class="product-image">
                    @if ($mainImage)
                        <img src="{{ asset('storage/' . $mainImage->path) }}" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/200x250.png?text=No+Image" alt="No Image">
                    @endif
                </div>

                <!-- Product Name -->
                <h3 class="font-semibold text-lg mb-2 line-clamp-2 min-h-[3.5rem] flex items-center justify-center">
                    {{ $product->name }}
                </h3>

                <!-- Product Price -->
                <p class="text-gray-600 mb-3 font-medium text-xl">
                    ${{ number_format($product->price, 2) }}
                </p>

                <!-- Add to Cart Button -->
                <button
                    class="bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition duration-300 mt-auto font-medium">
                    Add to Cart
                </button>
            </div>
        @endforeach
    </div>

    <!-- View All Button -->
    <div class="text-center mt-8">
        <button class="view-all-btn">
            View all &gt;
        </button>
    </div>
</div>
