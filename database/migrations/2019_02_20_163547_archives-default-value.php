<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ArchivesDefaultValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->string('url')->nullable()->change();
            $table->dateTime('time_begin')->nullable()->change();
            $table->dateTime('time_end')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->string('url')->nullable(false)->change();
            $table->dateTime('time_begin')->nullable(false)->change();
            $table->dateTime('time_end')->nullable(false)->change();
        });
    }
}
