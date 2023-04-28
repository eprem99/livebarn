<?php
/*
 * Project: Livebarn
 * Author: VECTO
 * Email: info@vecto.digital
 * Site: https://vecto.digital/
 * Last Modified: Friday, 28th April 2023
 */

use Illuminate\Database\Seeder;
use App\ModuleSetting;

class ModuleSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
//        ModuleSetting::query()->truncate();

        // Employee Modules
        $module = new ModuleSetting();
        $module->type = 'employee';
        $module->module_name = 'dashboard';
        $module->status = 'active';
        $module->save();

        $module = new ModuleSetting();
        $module->type = 'employee';
        $module->module_name = 'task calendar';
        $module->status = 'active';
        $module->save();

        $module = new ModuleSetting();
        $module->type = 'employee';
        $module->module_name = 'messages';
        $module->status = 'active';
        $module->save();

        $module = new ModuleSetting();
        $module->type = 'employee';
        $module->module_name = 'sticky note';
        $module->status = 'active';
        $module->save();

        $module = new ModuleSetting();
        $module->type = 'employee';
        $module->module_name = 'notices';
        $module->status = 'active';
        $module->save();


        // Client Modules
        $module = new ModuleSetting();
        $module->type = 'client';
        $module->module_name = 'dashboard';
        $module->status = 'active';
        $module->save();

        $module = new ModuleSetting();
        $module->type = 'client';
        $module->module_name = 'sticky note';
        $module->status = 'active';
        $module->save();

    }

}
