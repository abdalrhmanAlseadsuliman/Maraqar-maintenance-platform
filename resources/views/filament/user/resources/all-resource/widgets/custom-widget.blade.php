<x-filament-widgets::widget>
    <x-filament::section>
        {{-- ✅ تضمين Swiper CSS من CDN --}}
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
        />

        <style>
            .swiper {
                width: 100%;
                height: 300px;
                border-radius: 1rem;
                overflow: hidden;
            }
            .swiper-slide img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
        </style>

        {{-- ✅ السلايدر --}}
        <div class="swiper mySwiper mt-4">
            <div class="swiper-wrapper">
                {{-- ✅ تكرار نفس الصورة للتجربة --}}
                @for ($i = 0; $i < 5; $i++)
                    <div class="swiper-slide">
                        <img src="{{ asset('33.jpg') }}" alt="Slide {{ $i + 1 }}" />
                    </div>
                @endfor
            </div>

            {{-- ✅ أزرار التنقل --}}
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>

            {{-- ✅ النقاط --}}
            <div class="swiper-pagination"></div>
        </div>

        {{-- ✅ تضمين Swiper JS من CDN --}}
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        {{-- ✅ تهيئة السلايدر --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new Swiper('.mySwiper', {
                    loop: true,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    autoplay: {
                        delay: 3000,
                        disableOnInteraction: false,
                    },
                });
            });
        </script>
    </x-filament::section>
</x-filament-widgets::widget>
