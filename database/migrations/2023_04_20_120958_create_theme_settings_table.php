<?php
/*
 * Project: Livebarn
 * Author: VECTO
 * Email: info@vecto.digital
 * Site: https://vecto.digital/
 * Last Modified: Friday, 28th April 2023
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theme_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('panel');
            $table->string('header_color');
            $table->string('sidebar_color');
            $table->string('sidebar_text_color');
            $table->string('link_color')->default('#ffffff');
            $table->longText('user_css')->nullable();
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
        Schema::dropIfExists('theme_settings');
    }
}
