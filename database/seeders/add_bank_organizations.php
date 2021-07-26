<?php

namespace Database\Seeders;

use App\Models\BankOrganization;
use Illuminate\Database\Seeder;

class add_bank_organizations extends Seeder
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
                "number_account" => "3549874154",
                "name_account" => "นายสุนัก ใจดำ",
                "name_bank" => "บัวหลวง",
                "admin_id" => 2,
            ],
            [
                "number_account" => "4987623154",
                "name_account" => "นายสุกร ใจดำ",
                "name_bank" => "ทหารไทย",
                "admin_id" => 1,
            ],
        ];
        foreach ($users as $key => $value) {
            BankOrganization::create($value);
        }
    }
}
