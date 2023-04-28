<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStateEmployerDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_details', function (Blueprint $table) {
            $table->integer('state')->unsigned()->nullable()->after('department_id');
            $table->foreign('state')->references('id')->on('states')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('country')->unsigned()->nullable()->after('state');
            $table->foreign('country')->references('id')->on('states')->onDelete('SET NULL')->onUpdate('cascade');
            $table->string('city')->nullable()->default(null)->after('country');
            $table->string('postal_code')->nullable()->default(null)->after('city');

            $table->timestamp('joining_date')->nullable()->default(null)->after('department_id');
            $table->date('last_date')->nullable()->default(null)->after('joining_date');

            $table->dropColumn('job_title');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_details', function (Blueprint $table) {
            
        });
    }
}
