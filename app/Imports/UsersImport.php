<?php

namespace App\Imports;

use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
{
    public function collection(Collection $rows)
    {

        $data = $rows->toArray();


        $data = array_slice($data, 2);


        foreach ($data as $index => $row) {
            try {

                if (empty(array_filter($row))) {
                    continue;
                }
                $formattedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $row[9])->format('Y-m-d');

                // طباعة التاريخ بعد التحويل
                // dd($formattedDate);

                $randomEmail = Str::random(10) . '@example.com';


                $user = User::create([
                    'name' => $row[5],
                    'email' => $randomEmail, // استخدم البريد العشوائي هنا
                    'password' => $row[2] ?? bcrypt('default_password'), // تأكد أن كلمة المرور صالحة
                    'phone' => $row[6] ?? null,
                ]);
                logger()->info("تم إنشاء المستخدم برقم ID: {$user->id}");

                // إنشاء العقار
                Property::create([
                    'user_id' => $user->id,
                    'name' => $row[0],
                    'plan_number' => $row[4],
                    'sale_date' => $row[9] ? \Carbon\Carbon::createFromFormat('d/m/Y', $row[9])->format('Y-m-d') : null, // تاريخ البيع
                    'property_number' => $row[1],
                    'title_deed_number' => $row[2],
                    'land_piece_number' => $row[3]
                ]);
            } catch (\Exception $e) {
                logger()->error('Import Error in row #' . $index . ': ' . $e->getMessage());
            }
        }

        logger()->info('Import completed successfully.');

    }


    // لتحويل التاريخ إلى صيغة مناسبة
    private function parseDate($value)
    {
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            logger()->error("Invalid date: $value");
            return null;
        }
    }
}
