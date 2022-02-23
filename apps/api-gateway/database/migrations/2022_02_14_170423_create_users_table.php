<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


use App\Models\User;

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
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nickname')->unique();
            $table->string('email')->unique();
            $table->uuid('uuid')->unique();
            $table->smallInteger('role')->default(4);
            $table->string('password');
            $table->boolean('is_active')->default(1);

            $table->timestamp('created_at', $precision = 0)->default(DB::raw('NOW()'));
            $table->timestamp('updated_at', $precision = 0)->nullable();
            $table->timestamp('deleted_at', $precision = 0)->nullable();
        });

        DB::table($this->table)->insert(
            [
                [
                    'nickname' => 'admin',
                    'email' => 'admin@mail.com',
                    'uuid' => (string) Str::uuid(),
                    'password' => Hash::make('12345678'),
                    'role' => 1,
                ],
            ]
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
};
