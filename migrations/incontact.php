<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIncontactTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('incontact_tokens')) {
            Schema::create('incontact_tokens', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->longText('access_token');
                $table->string('refresh_token');
                $table->string('instance_base_url');
                $table->string('refresh_instance_url');
                $table->string('scope');
                $table->integer('agent_id');
                $table->integer('team_id');
                $table->integer('business_unit');
                $table->bigInteger('user_id');
                $table->datetime('expires')->nullable();
                $table->timestamps();
                $table->softDeletes();
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
        Schema::drop('incontact_tokens');
    }
}
