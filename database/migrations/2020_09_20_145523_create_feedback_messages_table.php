<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('content')->comment('Содержимое сообщения');
            $table->timestamp('create_at')->comment('Время создание сообщения');
            $table->string('client_ip')->comment('Ip адрес с которого было отправлено сообщение');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedback_messages');
    }
}
