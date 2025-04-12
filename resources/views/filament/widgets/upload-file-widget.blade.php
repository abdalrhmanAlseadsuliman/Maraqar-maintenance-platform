<x-filament::card>
    <form action="{{ route('upload.file') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="space-y-4">
            <input type="file" name="file" class="block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-blue-50 file:text-blue-700
                hover:file:bg-blue-100
            ">

            <button
                type="submit"
                class="filament-button bg-primary-600 hover:bg-primary-700 text-white"
            >
                رفع الملف
            </button>
        </div>
    </form>
</x-filament::card>
