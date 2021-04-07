<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_sets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('type', ['Flied', 'Dropdown', 'Checkbox', 'Date', 'Time', 'Datetime']);
            $table->softDeletes();
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
        Schema::dropIfExists('option_sets');
    }
}
