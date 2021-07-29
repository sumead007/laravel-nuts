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
                // addmin
                // "name" => "เขียว",
                // "username" => "sumead009",
                // "password" => bcrypt(12345678),
                // "telephone" => "0689865121",
                // "credit" => "unlimited",
                // "share_percentage" => "0",
                // "position" => "0",
                // "admin_id" => null,

                // agent
                "name" => "รุ้ง",
                "username" => "sumead011",
                "password" => bcrypt(12345678),
                "telephone" => "0898465421",
                "credit" => "0",
                "share_percentage" => "0",
                "position" => "1",
                "admin_id" => "3",
            ]
        ];
        foreach ($users as $key => $value) {
            Admin::create($value);
        }
    }
}
