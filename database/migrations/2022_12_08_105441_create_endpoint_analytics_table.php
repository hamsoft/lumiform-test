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
        Schema::create('endpoint_analytics', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('path');
            $table->string('method');
            $table->string('name')->nullable();
            $table->uuid('user_uuid')->nullable();
            $table->timestamps();

            $table->index(['path', 'method']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('endpoint_analytics');
    }
};
