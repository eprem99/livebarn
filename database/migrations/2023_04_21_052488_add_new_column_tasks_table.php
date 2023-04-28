<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dateTime('start_date')->after('completed_on');
            $table->integer('client_id')->unsigned()->nullable()->after('task_category_id');
            $table->integer('site_id')->unsigned()->nullable()->after('client_id');
            $table->foreign('site_id')->references('id')->on('task_label_list')->onDelete('SET NULL')->onUpdate('cascade');
            $table->integer('wo_id')->unsigned()->nullable()->after('site_id');
            $table->foreign('wo_id')->references('id')->on('wo_type')->onDelete('SET NULL')->onUpdate('cascade');
            $table->integer('sport_id')->unsigned()->nullable()->after('wo_id');
            $table->foreign('sport_id')->references('id')->on('sport_type')->onDelete('SET NULL')->onUpdate('cascade');
            $table->integer('qty')->unsigned()->nullable()->after('sport_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['start_date']);
            $table->dropColumn(['client_id']);
            $table->dropColumn(['site_id']);
            $table->dropColumn(['wo_id']);
            $table->dropColumn(['sport_id']);
            $table->dropColumn(['qty']);
        });
    }
}
