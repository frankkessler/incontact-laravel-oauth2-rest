<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncontactTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incontact_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('access_token');
            $table->string('refresh_token');
            $table->string('instance_base_url');
            $table->bigInteger('user_id');
            $table->datetime('expires')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('incontact_tokens');
    }
}
