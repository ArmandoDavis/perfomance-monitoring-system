<?php
use App\Models\Label;
use Database\DisableForeignKeys;
use Database\TruncateTable;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;
    public function run(): void
    {
        $this->disableForeignKeys('labels');

        $labels = [
            ['name' => 'Confirmed', 'color' => '#37B24D'],
            ['name' => 'Estimate', 'color' => '#AE3EC9'],
            ['name' => 'Blocked', 'color' => '#F03E3E'],
            ['name' => 'Bug', 'color' => '#D6336C'],
            ['name' => 'Rework', 'color' => '#F76707'],
        ];

        foreach ($labels as $label) {
            Label::updateOrCreate(
                ['name' => $label['name']],
                array_merge($label, [
                    'created_at' => now(),
                    'updated_at' => now(),
                    'is_active' => true,
                    'deleted_at' => null
                ])
            );
        }

        $this->enableForeignKeys('labels');
    }
}
