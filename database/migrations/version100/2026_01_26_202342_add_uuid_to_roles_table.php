<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = config('permission.table_names.roles');

        Schema::table($tableName, function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->nullable();
        });

        DB::table($tableName)->whereNull('uuid')->cursor()->each(function ($role) use ($tableName) {
            DB::table($tableName)->where('id', $role->id)->update(['uuid' => (string) Str::uuid()]);
        });

        //make it unique and required
        Schema::table($tableName, function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table(config('permission.table_names.roles'), function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
