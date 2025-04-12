<?php

namespace App\Imports;

use App\Models\Property;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    private $isFirstRow = true;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function startRow(): int
    {
        return 1; // يبدأ القراءة من السطر الثاني
    }
    public function model(array $row)
    {
        if ($this->isFirstRow) {
            $this->isFirstRow = false;
            return null; // تجاهل السطر الأول
        }

        // return new User([
        //     'name'     => $row[0],
        //     'email'    => $row[1],
        //     'password' => $row[2],
        // ]);

        $user = User::create([
            'name' => $row[0],
            'email' => $row[1],
            'password' => $row[2],
        ]);

        // إنشاء النشاط المرتبط بالمستخدم
        Property::create([
            'user_id' => $user->id,
            'name' => $row[3],
            'number' => $row[4],
        ]);

        return $user;
    }
}
