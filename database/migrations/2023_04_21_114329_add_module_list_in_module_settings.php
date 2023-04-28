<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\ModuleSetting;

class AddModuleListInModuleSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('module_settings');

        Schema::create('module_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module_name');
            $table->enum('status', ['active', 'deactive']);
            $table->timestamps();
        });

        $modules = [
            ['module_name' => 'clients', 'status' => 'active'],
            ['module_name' => 'employees', 'status' => 'active'],
            ['module_name' => 'tasks', 'status' => 'active'],
            ['module_name' => 'tickets', 'status' => 'active'],
            ['module_name' => 'messages', 'status' => 'active'],
            ['module_name' => 'notices', 'status' => 'active'],
        ];

        ModuleSetting::insert($modules);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::drop('module_settings');
    }
}
