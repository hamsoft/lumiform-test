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
        Schema::create('forms_items', function (Blueprint $table) {
            $table->uuid();
            $table->uuid('form_uuid');
            $table->string('element_type');
            $table->uuid('element_uuid');
            $table->uuid('parent_uuid')->nullable();
            $table->uuid('parent_type')->nullable();

            $table->foreign('form_uuid')->on('forms')->references('uuid');

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
        Schema::dropIfExists('forms_items');
    }
};
