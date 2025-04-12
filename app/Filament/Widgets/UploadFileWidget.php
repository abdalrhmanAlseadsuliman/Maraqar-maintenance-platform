<?php
namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class UploadFileWidget extends Widget
{
    protected static string $view = 'filament.widgets.upload-file-widget';

    protected int | string | array $columnSpan = 'full';

    
}
