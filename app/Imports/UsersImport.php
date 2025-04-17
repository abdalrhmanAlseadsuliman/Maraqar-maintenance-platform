<?php

namespace App\Imports;

use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Str; 
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    function parseDate($value)
    {
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            logger()->error("Invalid date: $value");
            return null;
        }}
    private $isFirstRow = true;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function startRow(): int
    {
        return 1; 
    }
    // public function model(array $row)
    // {
    //     try {
    //         if ($this->isFirstRow) {
    //             $this->isFirstRow = false;
    //             return null;
    //         }
    //    // Property::create([
    //     //     'user_id' => $user->id,
    //     //     'name' => $row[3],
    //     //     'plan_number' => $row[3],
    //     //     'sale_date'=>$row[9],
    //     // ]);
    //         return User::create([
    //             'name' => $row[0],
    //             'email' => 'en@gmail.com', 
    //             'password' => bcrypt($row[2]),
    //             'phone' => $row[6],
    //         ]);
    //     } catch (\Exception $e) {
    //         logger()->error('Import Error: ' . $e->getMessage());
    //         return null;
    //     }
    // }
    public function model(array $row)
    {
        if ($this->isFirstRow) {
            $this->isFirstRow = false;
            return null; // تجاهل السطر الأول
        }
    
        // توليد بريد إلكتروني عشوائي
        $randomEmail = Str::random(10) . '@example.com';
    
        $user = User::create([
            'name' => $row[0],
            'email' => $randomEmail, // استخدم البريد العشوائي هنا
            'password' => $row[2] ?? bcrypt('default_password'), // تأكد أن كلمة المرور صالحة
            'phone' => $row[6] ?? null,
        ]);
    
        Property::create([
            'user_id' => $user->id,
            'name' => $row[3],
            'plan_number' => $row[3],
            'sale_date' => $this->parseDate($row[9]),
            'property_number' => $row[3],
            'title_deed_number' => $row[4],
            'land_piece_number' => $row[3],
        ]);
    
        return $user;
    }
    
}
