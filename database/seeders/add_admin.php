<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class add_admin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                "name" => "นํ้าเงิน",
                "username" => "sumead007",
                "password" => bcrypt(12345678),
                "telephone" => "0987987654",
            ]
        ];
        foreach ($users as $key => $value) {
            Admin::create($value);
        }
    }
}
