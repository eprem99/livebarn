<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SetTaskCategoryIdColumnNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `tasks` DROP FOREIGN KEY `tasks_task_category_id_foreign`;");
        DB::statement("ALTER TABLE `tasks` ADD CONSTRAINT `tasks_task_category_id_foreign` FOREIGN KEY (`task_category_id`) REFERENCES `task_category`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
