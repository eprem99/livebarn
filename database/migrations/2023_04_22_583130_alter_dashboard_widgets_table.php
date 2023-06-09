<?php

use App\DashboardWidget;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterDashboardWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dashboard_widgets', function (Blueprint $table) {
            $table->string('dashboard_type')->nullable();
        });

        $dashboardWidgets = DashboardWidget::get();
        foreach ($dashboardWidgets as $dashboardWidget) {
            $dashboardWidget->dashboard_type = 'admin-dashboard';
            $dashboardWidget->save();
        }

        $widgets = [
            ['widget_name' => 'total_clients', 'status' => 1, 'dashboard_type' => 'admin-client-dashboard'],
            ['widget_name' => 'latest_client', 'status' => 1, 'dashboard_type' => 'admin-client-dashboard'],
            ['widget_name' => 'recent_login_activities', 'status' => 1, 'dashboard_type' => 'admin-client-dashboard'],

            ['widget_name' => 'total_project', 'status' => 1, 'dashboard_type' => 'admin-project-dashboard'],
            ['widget_name' => 'total_hours_logged', 'status' => 1, 'dashboard_type' => 'admin-project-dashboard'],
            ['widget_name' => 'total_overdue_project', 'status' => 1, 'dashboard_type' => 'admin-project-dashboard'],
            ['widget_name' => 'status_wise_project', 'status' => 1, 'dashboard_type' => 'admin-project-dashboard'],
            ['widget_name' => 'pending_milestone', 'status' => 1, 'dashboard_type' => 'admin-project-dashboard'],
            
            ['widget_name' => 'total_unresolved_tickets', 'status' => 1, 'dashboard_type' => 'admin-ticket-dashboard'],
            ['widget_name' => 'total_unassigned_ticket', 'status' => 1, 'dashboard_type' => 'admin-ticket-dashboard'],
            ['widget_name' => 'type_wise_ticket', 'status' => 1, 'dashboard_type' => 'admin-ticket-dashboard'],
            ['widget_name' => 'status_wise_ticket', 'status' => 1, 'dashboard_type' => 'admin-ticket-dashboard'],
            ['widget_name' => 'channel_wise_ticket', 'status' => 1, 'dashboard_type' => 'admin-ticket-dashboard'],
            ['widget_name' => 'new_tickets', 'status' => 1, 'dashboard_type' => 'admin-ticket-dashboard'],
        ];

        foreach ($widgets as $widget) {
            DashboardWidget::create($widget);
        }
        
        DB::beginTransaction();
        $dashboards = ['dashboard', 'clientDashboard', 'hrDashboard'];
        $dashboardWithId = array();

        $dashboardMenu = \App\Menu::where('menu_name', 'dashboard')->first();
        $dashboardMenu->route = null;
        $dashboardMenu->save();

        foreach($dashboards as $dashboard){
            $menuData = new \App\Menu();
            $menuData->menu_name = $dashboard;
            $menuData->translate_name = 'app.menu.'.$dashboard;
            $menuData->route = 'admin.'.$dashboard;
            $menuData->module = 'visibleToAll';
            $menuData->icon = null;
            $menuData->setting_menu = 0;
            $menuData->save();
            $dashboardWithId[] = ['id' => $menuData->id];
        }
        
        $menuSettings = \App\MenuSetting::first();
        $decodedMenu = json_decode($menuSettings->getRawOriginal('main_menu'),true);
        foreach ($decodedMenu as $key => $value) {
            if($value['id'] == $dashboardMenu->id){
                $decodedMenu[$key]['children'] = $dashboardWithId;
            }
        }
        $settings = json_encode($decodedMenu);
        $menuSettings->main_menu = $settings;

        $decodedDefaultMenu = json_decode($menuSettings->getRawOriginal('default_main_menu'),true);
        foreach ($decodedDefaultMenu as $key => $value) {
            if($value['id'] == $dashboardMenu->id){
                $decodedDefaultMenu[$key]['children'] = $dashboardWithId;
            }
        }
        $settings = json_encode($decodedDefaultMenu);
        $menuSettings->default_main_menu = $settings;
        $menuSettings->save();
        DB::commit();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('dashboard_widgets');
    }
}
