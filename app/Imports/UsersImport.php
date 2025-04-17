<?php

namespace App\Imports;

use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // اطبع كل محتويات الملف كما هي دفعة وحدة
        dd($rows->toArray());
    }
    public function startRow(): int
{
    return 2; // تجاهل الصف الأول (العناوين)
}
    // لتحويل التاريخ لصيغة مناسبة
    function parseDate($value)
    {
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            logger()->error("Invalid date: $value");
            return null;
        }
    }

    public function model(array $row)
    {
        dd($row);
        // جرب أطبع الأعمدة كالتالي لترى القيم الحقيقية
        // dd($row['رقم_الشقة'], $row['رقم_الصك'], $row['رقم_قطعة_الارض']);

        $randomEmail = Str::random(10) . '@example.com';

        $user = User::create([
            'name' => $randomEmail,
            'email' => $randomEmail,
            'password' => bcrypt('default_password'),
            'phone' => $row['6'] ?? null,
        ]);

        Property::create([
            'user_id' => $user->id,
            'name' => $row['اسم_العقار'] ?? '',
            'plan_number' => $row['رقم_المخطط'] ?? '',
            'sale_date' => $this->parseDate($row['تاريخ_البيع'] ?? null),
            'property_number' => $row['2'] ?? '',
            'title_deed_number' => $row['رقم_الصك'] ?? '',
            'land_piece_number' => $row['رقم_قطعة_الارض'] ?? '',
        ]);

        return $user;
    }
}
