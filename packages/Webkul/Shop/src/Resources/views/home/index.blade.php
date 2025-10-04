<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>

    <!-- Loop over the theme customization -->
    @foreach ($customizations as $customization)
        @php
            $data = $customization->options;
        @endphp

        <!-- Static content -->
        @switch ($customization->type)
            @case ($customization::IMAGE_CAROUSEL)
                <!-- Image Carousel -->
                @if (!empty($data))
                    <x-shop::carousel :options="$data" aria-label="{{ trans('shop::app.home.index.image-carousel') }}" />
                @endif
            @break

            @case ($customization::STATIC_CONTENT)
                <!-- push style -->
                @if (!empty($data['css']))
                    @push('styles')
                        <style>
                            {{ $data['css'] }}
                        </style>
                    @endpush
                @endif

                <!-- render html -->
                @if (!empty($data['html']))
                    {!! $data['html'] !!}
                @endif
            @break

            @case ($customization::CATEGORY_CAROUSEL)
                <!-- Categories carousel -->
                @if (!empty($data))
                    <x-shop::categories.carousel :title="$data['title'] ?? ''" :src="route('shop.api.categories.index', $data['filters'] ?? [])" :navigation-link="route('shop.home.index')"
                        aria-label="{{ trans('shop::app.home.index.categories-carousel') }}" />
                @endif
            @break

            @case ($customization::PRODUCT_CAROUSEL)
                <!-- Product Carousel -->
                @if (!empty($data))
                    <x-shop::products.carousel :title="$data['title'] ?? ''" :src="route('shop.api.products.index', $data['filters'] ?? [])" :navigation-link="route('shop.search.index', $data['filters'] ?? [])"
                        aria-label="{{ trans('shop::app.home.index.product-carousel') }}" />
                @endif
            @break

            @case ($customization::NEW_COLLECTION)
                <!-- New Collection Section -->
                @include('shop::home.partials.new-collection', ['data' => $data ?? []])
            @break

            @case ($customization::BEST_SELLER)
                <!-- Best Seller Section -->
                @include('shop::home.partials.best-seller', ['data' => $data ?? []])
            @break

            @case ($customization::SUMMER_COLLECTION)
                <!-- Summer Collection Section -->
                @include('shop::home.partials.summer-collection', ['data' => $data ?? []])
            @break

            @case ($customization::VIDEO_BANNER)
                <!-- Video Banner -->
                @include('shop::home.partials.video-banner', ['data' => $data ?? []])
            @break

            @case ($customization::TEXT_BANNER)
                <!-- Text Banner -->
                @include('shop::home.partials.text-banner', ['data' => $data ?? []])
            @break
        @endswitch
    @endforeach
</x-shop::layouts>
