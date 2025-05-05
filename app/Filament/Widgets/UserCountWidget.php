<?php
namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class UserCountWidget extends Widget
{
    protected static string $view = 'filament.widgets.user-count-widget';

    protected static ?int $sort = 1; 

    public function getViewData(): array
    {
        return [
            'userCount' => DB::table('users')->count(),
            'orderCount' => DB::table('maintenance_requests')->count(),
        ];
    }

    public static function canView(): bool
    {
        return true;
    }
}
