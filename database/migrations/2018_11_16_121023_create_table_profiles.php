<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('profiles')) {
            Schema::create('profiles', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('nickname')->nullable();
                $table->string('name')->nullable();
                $table->string('surname')->nullable();
                $table->string('last_name')->nullable();
                $table->string('country')->nullable();
                $table->string('city')->nullable();
                $table->string('marital_status')->nullable();
                $table->string('number')->nullable();
                $table->text('photo')->nullable();
                $table->integer('gender')->nullable();
                $table->integer('age')->nullable();
                $table->string('email');
                $table->integer('confirm_email')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
