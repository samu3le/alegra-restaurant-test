<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('token');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->ipAddress('ip_address');
            $table->string('user_agent', 255);

            $table->timestamp('expired_at', $precision = 0);
            $table->timestamp('last_activity', $precision = 0)->default(DB::raw('NOW()'));

            $table->timestamp('created_at', $precision = 0)->default(DB::raw('NOW()'));
            $table->timestamp('updated_at', $precision = 0)->nullable();
            $table->timestamp('deleted_at', $precision = 0)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sessions');
    }
};
