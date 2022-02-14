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
    private $table = 'roles';
    public function up()
    {
        Schema::table($this->table, function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
        });

        DB::table($this->table)->where('id', 1)->update(['created_by' => 1]);
        DB::table($this->table)->where('id', 2)->update(['created_by' => 1]);

        Schema::table($this->table, function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable(false)->change();
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
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};
