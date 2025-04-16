


{{-- resources/views/excel-upload.blade.php --}}
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>رفع ملف إكسل</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-md p-6 rounded-lg w-full max-w-md">
        <h1 class="text-xl font-bold mb-4 text-center">رفع ملف إكسل</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('upload.file') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="file" name="file" class="block w-full mb-4 p-2 border rounded" required>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                رفع الملف
            </button>
        </form>
    </div>
</body>
</html>
