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
        Schema::create('ms_type_wastes', function (Blueprint $table) {
            $table->id();
            $table->string('mtw_name');
            $table->text('mtw_description');
            $table->text('mtw_photo');
            $table->float('mtw_price');
            $table->string('mtw_unit');
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
        Schema::dropIfExists('ms_type_wastes');
    }
};
