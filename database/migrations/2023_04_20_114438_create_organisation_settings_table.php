<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganisationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organisation_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name');
            $table->string('company_email');
            $table->string('company_phone');
            $table->string('logo')->nullable();
            $table->string('login_background')->nullable();
            $table->text('address');
            $table->string('website')->nullable();
            $table->enum('active_theme', ['default', 'custom'])->default('default');
            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('locale')->default('en');
            $table->decimal('latitude', 10, 8)->default('26.9124336');
            $table->decimal('longitude', 11, 8)->default('75.78727090000007');
            
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
        Schema::dropIfExists('organisation_settings');
    }
}
