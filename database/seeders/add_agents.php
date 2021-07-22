<?php

namespace Database\Seeders;

use App\Models\Agent;
use Illuminate\Database\Seeder;

class add_agents extends Seeder
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
                "username" => "sumead007",
                "password" => bcrypt(12345678),
                "wallet" => "0",
                "telephone" => "0845213574",
                "share_percentage" => "0",
                "admin_id" => 1,
            ]
        ];
        foreach ($users as $key => $value) {
            Agent::create($value);
        }
    }
}
