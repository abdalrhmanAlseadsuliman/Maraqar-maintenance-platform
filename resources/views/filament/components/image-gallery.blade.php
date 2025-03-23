{{-- ملف عرض الصور --}}
<div style="display: flex; flex-wrap: wrap; gap: 10px;">
    @foreach ($images as $image)
        <img src="{{ asset('storage/' . $image->image_path) }}" width="100" height="100" style="border-radius: 8px;">
    @endforeach
</div>
