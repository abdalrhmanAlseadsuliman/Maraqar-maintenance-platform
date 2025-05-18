<x-filament-widgets::widget>
    <x-filament::section>
        <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200">
            {{-- ✅ رأس القسم --}}
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">🎥 دليل الاستخدام </h3>
                <a href="{{ route('filament.user.resources.maintenance-requests.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-md hover:bg-primary-700 transition">
                    + إنشاء طلب صيانة
                </a>

            </div>

            <br>

            {{-- ✅ محتوى الفيديو --}}
            <div class="rounded-xl overflow-hidden aspect-video border border-gray-300 shadow flex items-center justify-center">
                <video controls class="object-contain w-full h-full" style="height: 900px ">
                    <source src="{{ asset('storage/how_to_submit_maintenance_request.mp4') }}" type="video/mp4">
                    المتصفح لا يدعم تشغيل الفيديو.
                </video>
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
