<?php

namespace App\Filament\User\Resources\AllResource\Widgets;

use Filament\Widgets\Widget;

class CustomWidget extends Widget
{
    protected static string $view = 'filament.user.resources.all-resource.widgets.custom-widget';
    protected int | string | array $columnSpan = 'full'; // أو 6 إذا أردته نصف الصفحة

    protected static ?string $pollingInterval = null; 
}
