<x-filament-panels::page.simple heading='منصة مار عقار لطلبات الصيانة'>

    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}
            {{ $this->registerAction }}
        </x-slot>
    @endif

    {{-- نقاط التوسعة قبل الفورم --}}
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    {{-- فورم تسجيل الدخول --}}
    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{-- عرض الحقول --}}
        {{ $this->form }}

        {{-- عرض أزرار الفورم --}}
        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{-- ✅ زر العودة للصفحة الرئيسية --}}
    <div style="text-align: center; margin-top: 1.5rem;">
        <a href="https://maraqar.com"
           style="display: inline-block; padding: 10px 25px; background-color: #3b82f6; color: white; border-radius: 8px; text-decoration: none;">
            العودة إلى الصفحة الرئيسية
        </a>
    </div>

    {{-- نقاط التوسعة بعد الفورم --}}
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}

</x-filament-panels::page.simple>
