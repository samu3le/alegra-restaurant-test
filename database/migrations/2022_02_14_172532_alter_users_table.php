<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    private $table = 'users';

    public function up()
    {
        Schema::table($this->table, function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles');
        });

        DB::table($this->table)->where('id', 1)->update(['role_id' => 1]);
        DB::table($this->table)->where('id', 2)->update(['role_id' => 2]);

        Schema::table($this->table, function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->table, function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
