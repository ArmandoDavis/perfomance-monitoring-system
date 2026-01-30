<?php

use App\Models\System\CodeValue;
use Database\DisableForeignKeys;
use Database\TruncateTable;
use Illuminate\Database\Seeder;

class CodeValuesTableSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys("code_values");

        $allCodeValues = [
            'User Logs' => [
                ['reference' => 'ULLGI', 'name' => 'Log In'],
                ['reference' => 'ULLGO', 'name' => 'Log Out'],
                ['reference' => 'ULFLI', 'name' => 'Failed Log In'],
                ['reference' => 'ULPRS', 'name' => 'Password Reset'],
                ['reference' => 'ULULC', 'name' => 'User Lockout'],
            ],
            'Auth User Type' => [
                ['reference' => 'USER001', 'name' => 'Super Admin'],
                ['reference' => 'USER002', 'name' => 'Staff']
            ],
            'Gender' => [
                ['reference' => 'GENDER01', 'name' => 'Male', 'is_system_defined' => 0],
                ['reference' => 'GENDER02', 'name' => 'Female', 'is_system_defined' => 0],
            ],
            'Status' => [
                ['reference' => 'SCS001', 'name' => 'Backlog', 'is_system_defined' => 1],
                ['reference' => 'SCS002', 'name' => 'Todo', 'is_system_defined' => 1],
                ['reference' => 'SCS003', 'name' => 'In progress', 'is_system_defined' => 1],
                ['reference' => 'SCS004', 'name' => 'Submitted', 'is_system_defined' => 1],
                ['reference' => 'SCS005', 'name' => 'Done', 'is_system_defined' => 1], // same to complete
                ['reference' => 'SCS006', 'name' => 'Deployed', 'is_system_defined' => 1],
                ['reference' => 'SCS007', 'name' => 'Reject', 'is_system_defined' => 1],
            ]
        ];

        foreach ($allCodeValues as $codeName => $values) {
            $codeId = \App\Models\System\Code::query()->where('name', $codeName)->value('id');
            $sort = 1;

            foreach ($values as $value) {
                CodeValue::withTrashed()->updateOrCreate(
                    ['reference' => $value['reference']],
                    [
                        'code_id' => $codeId,
                        'name' => $value['name'],
                        'lang' => null,
                        'description' => '',
                        'sort' => $sort++,
                        'isactive' => $value['isactive'] ?? 1,
                        'is_system_defined' => $value['is_system_defined'] ?? 1,
                    ]
                );
            }
        }
        $this->enableForeignKeys("code_values");
    }
}
