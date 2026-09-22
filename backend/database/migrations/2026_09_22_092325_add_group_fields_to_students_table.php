<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('group_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->foreignId('subgroup_id')->nullable()->after('group_id')->constrained()->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropForeign(['subgroup_id']);
            $table->dropColumn(['group_id', 'subgroup_id']);
        });
    }
};
