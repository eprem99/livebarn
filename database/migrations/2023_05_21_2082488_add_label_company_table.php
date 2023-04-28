<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLabelCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_label_list', function (Blueprint $table) {
            $table->string('company')->nullable()->default(null)->after('label_name');
            $table->integer('user_id')->nullable()->default(null)->after('company');
            $table->string('notification')->nullable()->default(null)->after('company');
            $table->text('contacts')->nullable()->default(null)->after('company');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_label_list', function (Blueprint $table) {
            $table->dropColumn(['company']);
            $table->dropColumn(['user_id']);
        });
    }
}
