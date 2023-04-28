<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('menu_name',100);
            $table->string('translate_name')->nullable();
            $table->string('route',100)->nullable();
            $table->string('module')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('setting_menu')->nullable();
            $table->timestamps();
        });

        Schema::create('menu_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('main_menu')->nullable();
            $table->longText('default_main_menu')->nullable();
            $table->longText('setting_menu')->nullable();
            $table->longText('default_setting_menu')->nullable();
            $table->timestamps();
        });

        $menus =
            [
                [
                    'menu_name' => 'dashboard',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.dashboard',
                    'route' => 'admin.dashboard',
                    'icon' => 'icon-speedometer',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'customers',
                    'module' => 'customers',
                    'translate_name' => 'app.menu.customers',
                    'route' => null,
                    'icon' => 'icon-people',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'clients',
                    'module' => 'clients',
                    'translate_name' => 'app.menu.clients',
                    'route' => 'admin.clients.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'module' => 'hr',
                    'menu_name' => 'hr',
                    'translate_name' => 'app.menu.hr',
                    'route' => null,
                    'icon' => 'ti-user',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'employees',
                    'module' => 'employees',
                    'translate_name' => 'app.menu.employeeList',
                    'route' => 'admin.employees.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'department',
                    'module' => 'employees',
                    'translate_name' => 'app.department',
                    'route' => 'admin.department.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'work',
                    'module' => 'work',
                    'translate_name' => 'app.menu.work',
                    'route' => null,
                    'icon' => 'icon-layers',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'tasks',
                    'module' => 'tasks',
                    'translate_name' => 'app.menu.tasks',
                    'route' => 'admin.all-tasks.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'taskCalendar',
                    'module' => 'tasks',
                    'translate_name' => 'app.menu.taskCalendar',
                    'route' => 'admin.task-calendar.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'taskNew',
                    'module' => 'tasks',
                    'translate_name' => 'app.menu.newwork',
                    'route' => 'admin.all-tasks.create',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'taskBoard',
                    'module' => 'tasks',
                    'translate_name' => 'modules.tasks.taskBoard',
                    'route' => 'admin.taskboard.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],

                [
                    'menu_name' => 'sites',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.taskLabel',
                    'route' => null,
                    'icon' => 'icon-doc',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'sites',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.browsetaskLabel',
                    'route' => 'admin.site.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'sites',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.newtaskLabel',
                    'route' => 'admin.site.create',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                

                [
                    'menu_name' => 'messages',
                    'module' => 'messages',
                    'translate_name' => 'app.menu.messages',
                    'route' => 'admin.user-chat.index',
                    'icon' => 'icon-envelope',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'noticeBoard',
                    'module' => 'notices',
                    'translate_name' => 'app.menu.noticeBoard',
                    'route' => 'admin.notices.index',
                    'icon' => 'ti-layout-media-overlay',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'settings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.settings',
                    'route' => 'admin.settings.index',
                    'icon' => 'ti-settings',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'accountSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.accountSettings',
                    'route' => 'admin.settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'profileSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.profileSettings',
                    'route' => 'admin.profile-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'notificationSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.notificationSettings',
                    'route' => 'admin.email-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'emailSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.emailSettings',
                    'route' => 'admin.email-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'slackSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.slackSettings',
                    'route' => 'admin.slack-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'pushNotifications',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.pushNotifications',
                    'route' => 'admin.push-notification-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'pusherSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.pusherSettings',
                    'route' => 'admin.pusher-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'currencySettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.currencySettings',
                    'route' => 'admin.currency.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'replyTemplates',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.replyTemplates',
                    'route' => 'admin.replyTemplates.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'menuSetting',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.menuSetting',
                    'route' => 'admin.menu-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'moduleSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.moduleSettings',
                    'route' => 'admin.module-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'adminModule',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.adminModule',
                    'route' => 'admin.module-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'employeeModule',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.employeeModule',
                    'route' => 'admin.module-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'clientModule',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.clientModule',
                    'route' => 'admin.module-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'rolesPermission',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.rolesPermission',
                    'route' => 'admin.role-permission.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'messageSettings',
                    'module' => 'messages',
                    'translate_name' => 'app.menu.messageSettings',
                    'route' => 'admin.message-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'storageSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.storageSettings',
                    'route' => 'admin.storage-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'languageSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.languageSettings',
                    'route' => 'admin.language-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'taskSettings',
                    'module' => 'tasks',
                    'translate_name' => 'app.menu.taskSettings',
                    'route' => 'admin.task-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'themeSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.themeSettings',
                    'route' => 'admin.theme-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'countrySettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.country',
                    'route' => 'admin.theme-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'browsecountry',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.browsecountry',
                    'route' => 'admin.country.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'newcountry',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.newcountry',
                    'route' => 'admin.country.create',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'state',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.state',
                    'route' => 'admin.state.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'newstate',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.newstate',
                    'route' => 'admin.state.create',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'woType',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.wotype',
                    'route' => 'admin.wotype.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'sportType',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.sporttype',
                    'route' => 'admin.sporttype.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
            ];

        foreach($menus as $menu) {
            $menuData = new \App\Menu();
            $menuData->menu_name = $menu['menu_name'];
            $menuData->translate_name = $menu['translate_name'];
            $menuData->route = $menu['route'];
            $menuData->module = $menu['module'];
            $menuData->icon = $menu['icon'];
            $menuData->setting_menu = $menu['setting_menu'];
            $menuData->save();
        }



        $mainMenu = [
            ['id' => 1],
            [
                'id' => 2,
                'children' => [
                    ['id' => 3],
                    ['id' => 5],
                    ['id' => 4],
                    ['id' => 6],
                ]
            ],
            [
                'id' => 7,
                'children' => [
                    ['id' => 8],
                    ['id' => 10],
                    ['id' => 9],
                    ['id' => 11],
                ]
            ],
            [
                'id' => 12,
                'children' => [
                    ['id' => 13],
                    ['id' => 14],
                ]
            ],
            ['id' => 17],
        ];

        $settingMenu = [
            ['id' => 18],
            ['id' => 19],
            [
                'id' => 20,
                'children' => [
                    ['id' => 21],
                    ['id' => 22],
                    ['id' => 23],
                    ['id' => 24],
                ]
            ],
            ['id' => 25],
            ['id' => 26],
            ['id' => 27],
            [
                'id' => 28,
                'children' => [
                    ['id' => 29],
                    ['id' => 30],
                    ['id' => 31],
                    ['id' => 36],
                ]
            ],
            ['id' => 32],
            ['id' => 33],
            ['id' => 34],
            ['id' => 35],
            ['id' => 37],
            [
                'id' => 38,
                'children' => [
                    ['id' => 39],
                    ['id' => 40],
                    ['id' => 41],
                    ['id' => 42]
                ]
            ],
            ['id' => 43],
            ['id' => 44],
        ];

        $menuSetting = new \App\MenuSetting();
        $menuSetting->main_menu = json_encode($mainMenu);
        $menuSetting->default_main_menu = json_encode($mainMenu);
        $menuSetting->setting_menu = json_encode($settingMenu);
        $menuSetting->default_setting_menu = json_encode($settingMenu);
        $menuSetting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
