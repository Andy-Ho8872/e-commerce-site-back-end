<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialiteColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('provider_id')->nullable();
            $table->string('provider')->nullable();
            $table->string('password')->nullable()->change(); // 改變原本該欄位的屬性
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('provider_id')->nullable();
            $table->dropColumn('provider')->nullable();
            $table->string('password')->change(); // 改變原本該欄位的屬性
        });
    }
}
