<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('title');

            $table->uuid('image_id')->nullable();

            $table->boolean('negative');
            $table->boolean('notes_allowed');
            $table->boolean('photos_allowed');
            $table->boolean('issues_allowed');
            $table->boolean('responded');
            $table->boolean('required');

            $table->string('response_type');
            $table->uuid('response_set_uuid')->nullable();
            $table->boolean('multiple_selection');

            $table->foreign('response_set_uuid')
                ->on('response_sets')
                ->references('uuid');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
