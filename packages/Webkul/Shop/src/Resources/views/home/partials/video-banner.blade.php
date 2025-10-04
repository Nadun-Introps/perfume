@php
    $data = $data ?? [];
    $videoUrl = $data['video_url'] ?? '';
    $posterImage = $data['poster_image'] ?? '';
    $title = $data['title'] ?? '';
    $subtitle = $data['subtitle'] ?? '';
    $buttonText = $data['button_text'] ?? '';
    $buttonLink = $data['button_link'] ?? '';
    $autoplay = $data['autoplay'] ?? true;
    $muted = $data['muted'] ?? true;
    $loop = $data['loop'] ?? true;
    $overlay = $data['overlay'] ?? true;
@endphp

@push('styles')
    <style>
        .video-banner {
            position: relative;
            width: 100%;
            height: 80vh;
            min-height: 600px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
            /* Fallback background */
        }

        .video-banner video {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: 1;
            object-fit: cover;
            /* Ensure video covers the area */
        }

        .video-banner__fallback {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .video-banner__overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 2;
        }

        .video-banner__content {
            position: relative;
            z-index: 3;
            text-align: center;
            color: white;
            max-width: 800px;
            padding: 0 2rem;
        }

        .video-banner__title {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .video-banner__subtitle {
            font-size: 1.5rem;
            margin-bottom: 2.5rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .video-banner__button {
            display: inline-block;
            padding: 1rem 2.5rem;
            background: #000;
            color: white;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: 2px solid #000;
        }

        .video-banner__button:hover {
            background: transparent;
            color: #000;
            border-color: #000;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .video-banner {
                height: 60vh;
                min-height: 400px;
            }

            .video-banner__title {
                font-size: 2.5rem;
            }

            .video-banner__subtitle {
                font-size: 1.2rem;
            }
        }
    </style>
@endpush

@if ($videoUrl)
    <div class="video-banner">
        <!-- Video Element with Error Handling -->
        <video id="bannerVideo" @if ($posterImage) poster="{{ $posterImage }}" @endif
            @if ($autoplay) autoplay @endif @if ($muted) muted @endif
            @if ($loop) loop @endif playsinline onerror="handleVideoError()">
            <source src="{{ $videoUrl }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <!-- Fallback in case video fails to load -->
        <div class="video-banner__fallback" id="videoFallback" style="display: none;">
            Video not available - Showing fallback background
        </div>

        <!-- Overlay -->
        @if ($overlay)
            <div class="video-banner__overlay"></div>
        @endif

        <!-- Content -->
        <div class="video-banner__content">
            @if ($title)
                <h1 class="video-banner__title">{{ $title }}</h1>
            @endif

            @if ($subtitle)
                <p class="video-banner__subtitle">{{ $subtitle }}</p>
            @endif

            @if ($buttonText && $buttonLink)
                <a href="{{ $buttonLink }}" class="video-banner__button">
                    {{ $buttonText }}
                </a>
            @endif
        </div>
    </div>

    <script>
        function handleVideoError() {
            console.log('Video failed to load, showing fallback');
            document.getElementById('videoFallback').style.display = 'flex';
        }

        // Check if video can play
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('bannerVideo');
            if (video) {
                video.addEventListener('error', function(e) {
                    console.error('Video error:', e);
                    handleVideoError();
                });

                video.addEventListener('canplay', function() {
                    console.log('Video can play');
                });

                // Try to play the video programmatically (handles autoplay restrictions)
                video.play().catch(function(error) {
                    console.log('Autoplay prevented:', error);
                    // Video will still show but won't autoplay
                });
            }
        });
    </script>
@endif
