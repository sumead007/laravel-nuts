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
                "name" => "แดง",
                "username" => "sumead008",
                "password" => bcrypt(12345678),
                "telephone" => "0689865121",
                "credit" => "0",
                "share_percentage" => "0",
                "position" => "1",
                "admin_id" => "1",
            ]
        ];
        foreach ($users as $key => $value) {
            Admin::create($value);
        }
    }
}
