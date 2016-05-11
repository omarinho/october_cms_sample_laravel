<?php namespace OmarMarino\EmailSender\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateDealsTable extends Migration
{

    public function up()
    {
        Schema::create('omarmarino_emailsender_deals', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
	    $table->string('title')->nullable();
	    $table->string('description')->nullable();	
            $table->string('address')->nullable();
            $table->string('site')->nullable();
            $table->string('url')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('omarmarino_emailsender_deals');
    }

}
