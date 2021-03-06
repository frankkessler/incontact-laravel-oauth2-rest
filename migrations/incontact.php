<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder as Schema;

class CreateIncontactTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ($connection = Capsule::connection($this->getConnection())) {
            $connection->useDefaultSchemaGrammar();
        } else {
            $app = app();
            $connection = $app['db']->connection($this->getConnection());
        }

        $schema = new Schema($connection);

        if (!$schema->hasTable('incontact_tokens')) {
            $schema->create('incontact_tokens', function (Blueprint $table) {
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
    }
}
