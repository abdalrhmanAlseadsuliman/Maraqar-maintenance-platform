<x-filament::widget>
    <x-filament::card>
        <div class="grid grid-cols-2 gap-4 text-center">
            {{-- عدد المستخدمين --}}
            <div>
                <div class="text-lg font-semibold text-gray-600 dark:text-gray-300">عدد المستخدمين</div>
                <div class="text-3xl font-bold text-primary-600 dark:text-primary-500 mt-1">
                    {{ $userCount }}
                </div>
            </div>

            {{-- عدد الطلبات --}}
            <div>
                <div class="text-lg font-semibold text-gray-600 dark:text-gray-300">عدد الطلبات</div>
                <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">
                    {{ $orderCount }}
                </div>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>
