<?php

namespace Database\Seeders;

use App\Models\BusinessSetting;
use Illuminate\Database\Seeder;

class BusinessSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'type'   => 'language',
                'value' => '[{"id":"1","name":"english","direction":"ltr","code":"en","status":1,"default":true}]',
            ],
            [
                'type'   => 'pnc_language',
                'value' => '["en"]',
            ],
        ];
        BusinessSetting::INSERT($data);
    }
}
