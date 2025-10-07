@php
    $data = $data ?? [];
    $backgroundImage = $data['background_image'] ?? '';
    $backgroundColor = $data['background_color'] ?? '#f8f9fa';
    $title = $data['title'] ?? '';
    $paragraph = $data['paragraph'] ?? '';
    $buttonText = $data['button_text'] ?? '';
    $buttonLink = $data['button_link'] ?? '';
    $buttonStyle = $data['button_style'] ?? 'secondary'; // primary, secondary, outline
    $textColor = $data['text_color'] ?? '#333333';
    $overlay = $data['overlay'] ?? false;
    $overlayOpacity = $data['overlay_opacity'] ?? 0.2;
    $minHeight = $data['min_height'] ?? '350px';
    $contentWidth = $data['content_width'] ?? '60%';
    $contentPosition = $data['content_position'] ?? 'center'; // center, left, right
    $textAlignment = $data['text_alignment'] ?? 'right'; // center, left, right
@endphp

@push('styles')
    <style>
        .lower-text-banner {
            position: relative;
            width: 100%;
            min-height: {{ $minHeight }};
            display: flex;
            align-items: center;
            justify-content: flex-end;
            /* Always align content to right */
            background-color: {{ $backgroundColor }};

            @if ($backgroundImage)
                background-image: url('{{ $backgroundImage }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            @endif
            overflow: hidden;
        }

        .lower-text-banner__overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, {{ $overlayOpacity }});
            z-index: 1;
        }

        .lower-text-banner__content {
            position: relative;
            z-index: 2;
            width: 50%;
            /* Always take right half */
            padding: 3rem 2rem;
            color: {{ $textColor }};
            text-align: left;
            /* Always left aligned */
        }

        .lower-text-banner__title {
            font-size: 2.5rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            line-height: 1;
            text-shadow: {{ $overlay ? '2px 2px 4px rgba(0, 0, 0, 0.5)' : 'none' }};
            color: #ffffff;
            /* White color for first line */
        }

        .lower-text-banner__title span {
            font-size: 4.5rem;
            /* Larger font for 50% */
            font-weight: 700;
        }

        .lower-text-banner__subtitle {
            font-size: 1.5rem;
            font-weight: 500;
            margin-bottom: 0;
            line-height: 1.2;
            text-shadow: {{ $overlay ? '2px 2px 4px rgba(0, 0, 0, 0.5)' : 'none' }};
            color: #FFD700;
            /* Gold color for second line */
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .lower-text-banner {
                min-height: 300px;
                justify-content: center;
            }

            .lower-text-banner__content {
                width: 90%;
                padding: 2rem 1rem;
                text-align: center;
            }

            .lower-text-banner__title {
                font-size: 2.5rem;
            }

            .lower-text-banner__title span {
                font-size: 3rem;
            }

            .lower-text-banner__subtitle {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .lower-text-banner__title {
                font-size: 1.5rem;
            }

            .lower-text-banner__title span {
                font-size: 2.5rem;
            }

            .lower-text-banner__subtitle {
                font-size: 0.8rem;
            }
        }
    </style>
@endpush

<div class="lower-text-banner">
    <!-- Background Overlay -->
    @if ($overlay && $backgroundImage)
        <div class="lower-text-banner__overlay"></div>
    @endif

    <!-- Content -->
    <div class="lower-text-banner__content">
        <!-- First line: "up to 50%" with "50%" in larger font -->
        <h2 class="lower-text-banner__title">
            up to <span>50%</span>
        </h2>

        <!-- Second line: "All Seasonal Perfume" in gold -->
        <h3 class="lower-text-banner__subtitle">
            All Seasonal Perfume
        </h3>
    </div>
</div>
