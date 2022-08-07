<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('user_id');
            $table->text('uri')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->boolean('is_bookmarekd')->default(false);
            $table->boolean('is_read')->default(false);
            $table->jsonb('comments')->nullable();
            $table->bigInteger('feed_id');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('feed_id')
            ->references('id')
            ->on('feeds');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
};
