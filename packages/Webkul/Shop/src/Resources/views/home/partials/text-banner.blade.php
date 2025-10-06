@php
    $data = $data ?? [];
    $backgroundImage = $data['background_image'] ?? '';
    $backgroundColor = $data['background_color'] ?? '#ffffff';
    $title = $data['title'] ?? '';
    $paragraph = $data['paragraph'] ?? '';
    $buttonText = $data['button_text'] ?? '';
    $buttonLink = $data['button_link'] ?? '';
    $buttonStyle = $data['button_style'] ?? 'primary'; // primary, secondary, outline
    $textColor = $data['text_color'] ?? '#333333';
    $overlay = $data['overlay'] ?? false;
    $overlayOpacity = $data['overlay_opacity'] ?? 0.3;
    $minHeight = $data['min_height'] ?? '400px';
    $contentWidth = $data['content_width'] ?? '50%';
@endphp

@push('styles')
    <style>
        .text-banner {
            position: relative;
            width: 100%;
            min-height: {{ $minHeight }};
            display: flex;
            align-items: flex-end;
            /* Changed from center to flex-end to push content lower */
            justify-content: flex-end;
            background-color: {{ $backgroundColor }};

            @if ($backgroundImage)
                background-image: url('{{ $backgroundImage }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            @endif
            overflow: hidden;
        }

        .text-banner__overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, {{ $overlayOpacity }});
            z-index: 1;
        }

        .text-banner__content {
            position: relative;
            z-index: 2;
            width: {{ $contentWidth }};
            padding: 4rem 2rem 6rem;
            /* Increased bottom padding to push content lower */
            color: {{ $textColor }};
            text-align: left;
        }

        .text-banner__title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            text-shadow: {{ $overlay ? '2px 2px 4px rgba(0, 0, 0, 0.5)' : 'none' }};
        }

        .text-banner__paragraph {
            font-size: 0.8rem;
            line-height: 1.6;
            margin-bottom: 0;
            text-shadow: {{ $overlay ? '1px 1px 2px rgba(0, 0, 0, 0.5)' : 'none' }};
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .text-banner {
                min-height: 400px;
                justify-content: center;
                text-align: center;
                align-items: flex-end;
                /* Keep content at bottom on mobile too */
            }

            .text-banner__content {
                width: 90%;
                padding: 2rem 1rem 4rem;
                /* Adjusted padding for mobile */
            }

            .text-banner__title {
                font-size: 1.8rem;
            }

            .text-banner__paragraph {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .text-banner__title {
                font-size: 1.2rem;
            }

            .text-banner__paragraph {
                font-size: 0.8rem;
            }
        }
    </style>
@endpush

<div class="text-banner">
    <!-- Background Overlay -->
    @if ($overlay && $backgroundImage)
        <div class="text-banner__overlay"></div>
    @endif

    <!-- Content -->
    <div class="text-banner__content">
        @if ($title)
            <h1 class="text-banner__title">{{ $title }}</h1>
        @endif

        @if ($paragraph)
            <p class="text-banner__paragraph">{{ $paragraph }}</p>
        @endif

        <!-- Button section completely removed -->
    </div>
</div>
