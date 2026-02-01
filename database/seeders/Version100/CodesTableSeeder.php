<?php

use Database\DisableForeignKeys;
use Database\TruncateTable;
use Illuminate\Database\Seeder;

class CodesTableSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys("codes");

        $codes = [
            ['name' => 'User Logs', 'lang' => 'user_log', 'is_system_defined' => 1],
            ['name' => 'Auth User Type', 'lang' => 'auth_user_type', 'is_system_defined' => 1],
            ['name' => 'Gender', 'lang' => 'gender', 'is_system_defined' => 0],
            ['name' => 'Status', 'lang' => 'sms_campaign_status', 'is_system_defined' => 1],
            ['name' => 'Access Level', 'lang' => 'access_level', 'is_system_defined' => 1],
        ];
        foreach ($codes as $code) {
            \App\Models\System\Code::updateOrCreate(['name' => $code['name']], [
                'lang' => $code['lang'],
                'is_system_defined' => $code['is_system_defined'],
            ]);
        }
        $this->enableForeignKeys("codes");
    }
}
