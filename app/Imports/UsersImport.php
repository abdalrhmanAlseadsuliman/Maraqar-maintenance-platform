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
        // تحويل البيانات إلى مصفوفة والتخزين المؤقت
        $data = $rows->toArray();
        
        // تجاهل أول صفين: الأول فارغ والثاني فيه عناوين
        $data = array_slice($data, 2);
        
        // loop على كل صف داخل الملف
        foreach ($data as $index => $row) {
            try {
                // تأكد أن الصف يحتوي على بيانات حقيقية
                if (empty(array_filter($row))) {
                    continue;
                }
        
                // توليد بريد عشوائي
                $randomEmail = Str::random(10) . '@example.com';
            
                // إنشاء المستخدم
                $user = User::create([
                    'name' => $randomEmail,
                    'email' => $randomEmail,
                    'password' => bcrypt('default_password'),
                    'phone' => $row[6] ?? null, // رقم الموبايل
                ]);
                logger()->info("تم إنشاء المستخدم برقم ID: {$user->id}");
                
                // إنشاء العقار
                Property::create([
                    'user_id' => $user->id,
                    'name' => $row[0] ?? '', // اسم العقار
                    'plan_number' => $row[4] ?? '', // رقم المخطط
                    'sale_date' => $this->parseDate($row[9] ?? null), // تاريخ البيع
                    'property_number' => $row[1] ?? '', // رقم الشقة
                    'title_deed_number' => $row[2] ?? '', // رقم الصك
                    'land_piece_number' => $row[3] ?? '', // رقم قطعة الأرض
                ]);
            } catch (\Exception $e) {
                logger()->error('Import Error in row #' . $index . ': ' . $e->getMessage());
            }
        }
    
        // فقط للتأكيد بعد الإدخال
       
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
