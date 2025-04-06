<!-- زر فتح السايدبار يظهر فقط على الجوال -->
<button 
    onclick="document.querySelector('.filament-sidebar').classList.add('is-open'); document.querySelector('.sidebar-overlay').classList.add('is-active');"
    class="lg:hidden p-2 text-white"
>
    ☰
</button>

<!-- خلفية داكنة تظهر عند فتح السايدبار -->
<div class="sidebar-overlay" onclick="document.querySelector('.filament-sidebar').classList.remove('is-open'); this.classList.remove('is-active');"></div>
