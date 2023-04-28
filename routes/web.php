<?php
/*
 * Project: Livebarn
 * Author: VECTO
 * Email: info@vecto.digital
 * Site: https://vecto.digital/
 * Last Modified: Friday, 28th April 2023
 */


Route::get('/taskboard-data', ['uses' => 'HomeController@taskBoardData'])->name('front.taskBoardData');
Route::get('/taskboard/{encrypt}', ['uses' => 'HomeController@taskboard'])->name('front.taskboard');

Route::get('/task-detail/history/{taskid}', ['uses' => 'HomeController@history'])->name('front.task-history');
Route::get('/task-files/{id}', ['uses' => 'HomeController@taskFiles'])->name('front.task-files');
Route::get('/task-detail/{id}', ['uses' => 'HomeController@taskDetail'])->name('front.task-detail');
Route::get('/task-share/{id}', ['uses' => 'HomeController@taskShare'])->name('front.task-share');

Route::get('/', ['uses' => 'HomeController@login']);

Route::group(
    ['namespace' => 'Client', 'prefix' => 'client', 'as' => 'client.'], function () {
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    // Admin routes
    Route::group(
        ['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['role:admin']], function () {

        Route::get('/dashboard', 'AdminDashboardController@index')->name('dashboard');
        Route::get('/dashboard/filter', 'AdminDashboardController@filter')->name('dashboard.filter');
        Route::get('/client-dashboard', 'AdminDashboardController@clientDashboard')->name('clientDashboard');
        Route::get('/hr-dashboard', 'AdminDashboardController@hrDashboard')->name('hrDashboard');
        Route::get('/ticket-dashboard', 'AdminDashboardController@ticketDashboard')->name('ticketDashboard');
        Route::post('/dashboard/widget/{dashboardType}', 'AdminDashboardController@widget')->name('dashboard.widget');

        Route::get('clients/export/{status?}/{client?}', ['uses' => 'ManageClientsController@export'])->name('clients.export');
        Route::get('clients/create/{clientID?}', ['uses' => 'ManageClientsController@create'])->name('clients.create');
        Route::get('clients/country/{id}', ['uses' => 'ManageClientsController@country'])->name('clients.country');
        Route::get('clients/tasks/{clientID?}/{hideCompleted}', ['uses' => 'ManageClientsController@tasks'])->name('clients.tasks');
        Route::resource('clients', 'ManageClientsController', ['except' => ['create']]);

        Route::group(
            ['prefix' => 'sites'], function () {
        Route::post('site/store-label', ['uses' => 'ManageTaskLabelController@storeLabel'])->name('site.store-label');
        Route::get('site/create-label', ['uses' => 'ManageTaskLabelController@createLabel'])->name('site.create-label');
        Route::get('show/{id}', ['uses' => 'ManageTaskLabelController@show'])->name('sites.show');
        Route::get('site/country/{id}', ['uses' => 'ManageTaskLabelController@country'])->name('site.country');
        Route::resource('site', 'ManageTaskLabelController');
        });

        Route::group(
            ['prefix' => 'country'], function () {
                Route::resource('CountryCategory', 'CountryController');
                Route::get('index', ['uses' => 'CountryController@index'])->name('country.index');
                Route::get('data', ['uses' => 'CountryController@data'])->name('country.data');
                Route::get('create', ['uses' => 'CountryController@create'])->name('country.create');
                Route::get('{id}/edit', ['uses' => 'CountryController@edit'])->name('country.edit');
                Route::post('country/store', ['uses' => 'CountryController@store'])->name('country.store');
                Route::post('update/{id}', ['uses' => 'CountryController@update'])->name('country.update');
                Route::delete('destroy/{id}', ['uses' => 'CountryController@destroy'])->name('country.destroy');
            });

            Route::group(
                ['prefix' => 'state'], function () {
                    Route::get('index', ['uses' => 'StatesController@index'])->name('state.index');
                    Route::get('data', ['uses' => 'StatesController@data'])->name('state.data');
                    Route::get('create', ['uses' => 'StatesController@create'])->name('state.create');
                    Route::get('{id}/edit', ['uses' => 'StatesController@edit'])->name('state.edit');
                    Route::post('country/store', ['uses' => 'StatesController@store'])->name('state.store');
                    Route::post('country/{id}', ['uses' => 'StatesController@country'])->name('state.country');
                    Route::post('update/{id}', ['uses' => 'StatesController@update'])->name('state.update');
                    Route::delete('destroy/{id}', ['uses' => 'StatesController@destroy'])->name('state.destroy');
                });

            Route::group(
                    ['prefix' => 'wotype'], function () {
                Route::get('wotype/quick-create', ['uses' => 'ManageWotypeController@quickCreate'])->name('wotype.quick-create');
                Route::post('wotype/quick-store', ['uses' => 'ManageWotypeController@quickStore'])->name('wotype.quick-store');
                Route::resource('wotype', 'ManageWotypeController');
            });

            Route::group(
                    ['prefix' => 'sporttype'], function () {
                Route::get('sporttype/quick-create', ['uses' => 'ManageSporttypeController@quickCreate'])->name('sporttype.quick-create');
                Route::post('sporttype/quick-store', ['uses' => 'ManageSporttypeController@quickStore'])->name('sporttype.quick-store');
                Route::resource('sporttype', 'ManageSporttypeController');
            });

        Route::group(
            ['prefix' => 'employees'], function () {
                Route::get('employees/leaveTypeEdit/{id}', ['uses' => 'ManageEmployeesController@leaveTypeEdit'])->name('employees.leaveTypeEdit');
            Route::get('employees/free-employees', ['uses' => 'ManageEmployeesController@freeEmployees'])->name('employees.freeEmployees');
            Route::get('employees/docs-create/{id}', ['uses' => 'ManageEmployeesController@docsCreate'])->name('employees.docs-create');
            Route::get('employees/tasks/{userId}/{hideCompleted}', ['uses' => 'ManageEmployeesController@tasks'])->name('employees.tasks');
            Route::get('employees/export/{status?}/{employee?}/{role?}', ['uses' => 'ManageEmployeesController@export'])->name('employees.export');
            Route::post('employees/assignRole', ['uses' => 'ManageEmployeesController@assignRole'])->name('employees.assignRole');
            Route::post('employees/assignProjectAdmin', ['uses' => 'ManageEmployeesController@assignProjectAdmin'])->name('employees.assignProjectAdmin');
            Route::get('employees/country/{id}', ['uses' => 'ManageEmployeesController@country'])->name('employees.country');
            
            Route::resource('employees', 'ManageEmployeesController');

            Route::get('department/quick-create', ['uses' => 'ManageTeamsController@quickCreate'])->name('department.quick-create');
            Route::post('department/quick-store', ['uses' => 'ManageTeamsController@quickStore'])->name('department.quick-store');
            Route::resource('department', 'ManageTeamsController');

            Route::resource('employee-teams', 'ManageEmployeeTeamsController');

            Route::get('employee-docs/download/{id}', ['uses' => 'EmployeeDocsController@download'])->name('employee-docs.download');
            Route::resource('employee-docs', 'EmployeeDocsController');
        });

        Route::resource('pinned', 'ManagePinnedController', ['only' => ['store', 'destroy']]);

        Route::group(
            ['prefix' => 'clients'], function() {
            Route::get('projects/{id}', ['uses' => 'ManageClientsController@showProjects'])->name('clients.projects');
        });


        Route::post('taskCategory/store-cat', ['uses' => 'ManageTaskCategoryController@storeCat'])->name('taskCategory.store-cat');
        Route::get('taskCategory/create-cat', ['uses' => 'ManageTaskCategoryController@createCat'])->name('taskCategory.create-cat');
        Route::resource('taskCategory', 'ManageTaskCategoryController');

        Route::get('notices/export/{startDate}/{endDate}', ['uses' => 'ManageNoticesController@export'])->name('notices.export');
        Route::resource('notices', 'ManageNoticesController');

        Route::get('settings/change-language', ['uses' => 'OrganisationSettingsController@changeLanguage'])->name('settings.change-language');
        Route::resource('settings', 'OrganisationSettingsController', ['only' => ['edit', 'update', 'index', 'change-language']]);
        Route::group(
            ['prefix' => 'settings'], function () {
            Route::get('email-settings/sent-test-email', ['uses' => 'EmailNotificationSettingController@sendTestEmail'])->name('email-settings.sendTestEmail');
            Route::post('email-settings/updateMailConfig', ['uses' => 'EmailNotificationSettingController@updateMailConfig'])->name('email-settings.updateMailConfig');
            Route::resource('email-settings', 'EmailNotificationSettingController');
            Route::resource('profile-settings', 'AdminProfileSettingsController');
            Route::resource('menu-settings', 'MenuSettingController')->only(['index','update','edit']);

            Route::get('currency/exchange-key', ['uses' => 'CurrencySettingController@currencyExchangeKey'])->name('currency.exchange-key');
            Route::post('currency/exchange-key-store', ['uses' => 'CurrencySettingController@currencyExchangeKeyStore'])->name('currency.exchange-key-store');
            Route::resource('currency', 'CurrencySettingController');
            Route::get('currency/exchange-rate/{currency}', ['uses' => 'CurrencySettingController@exchangeRate'])->name('currency.exchange-rate');
            Route::get('currency/update/exchange-rates', ['uses' => 'CurrencySettingController@updateExchangeRate'])->name('currency.update-exchange-rates');
            Route::resource('currency', 'CurrencySettingController');

            Route::post('theme-settings/activeTheme', ['uses' => 'ThemeSettingsController@activeTheme'])->name('theme-settings.activeTheme');
            Route::post('theme-settings/roundedTheme', ['uses' => 'ThemeSettingsController@roundedTheme'])->name('theme-settings.roundedTheme');
            Route::post('theme-settings/logo_background_color', ['uses' => 'ThemeSettingsController@logoBackgroundColor'])->name('theme-settings.logo_background_color');
            Route::resource('theme-settings', 'ThemeSettingsController');
            Route::resource('time-settings', 'TimeSettingsController');

            Route::resource('task-settings', 'TaskSettingsController',  ['only' => ['index', 'store']]);

            Route::get('slack-settings/sendTestNotification', ['uses' => 'SlackSettingController@sendTestNotification'])->name('slack-settings.sendTestNotification');
            Route::post('slack-settings/updateSlackNotification/{id}', ['uses' => 'SlackSettingController@updateSlackNotification'])->name('slack-settings.updateSlackNotification');
            Route::resource('slack-settings', 'SlackSettingController');

            Route::get('push-notification-settings/sendTestNotification', ['uses' => 'PushNotificationController@sendTestNotification'])->name('push-notification-settings.sendTestNotification');
            Route::post('push-notification-settings/updatePushNotification/{id}', ['uses' => 'PushNotificationController@updatePushNotification'])->name('push-notification-settings.updatePushNotification');
            Route::resource('push-notification-settings', 'PushNotificationController');

            Route::post('ticket-agents/update-group/{id}', ['uses' => 'TicketAgentsController@updateGroup'])->name('ticket-agents.update-group');
            Route::resource('ticket-agents', 'TicketAgentsController');
            Route::resource('ticket-groups', 'TicketGroupsController');

            Route::get('ticketTypes/createModal', ['uses' => 'TicketTypesController@createModal'])->name('ticketTypes.createModal');
            Route::resource('ticketTypes', 'TicketTypesController');

            Route::get('ticket-channels/create-modal', ['uses' => 'TicketChannelsController@createModal'])->name('ticketChannels.createModal');
            Route::resource('ticketChannels', 'TicketChannelsController');

            Route::post('replyTemplates/fetch-template', ['uses' => 'TicketReplyTemplatesController@fetchTemplate'])->name('replyTemplates.fetchTemplate');
            Route::resource('replyTemplates', 'TicketReplyTemplatesController');

            Route::get('data', ['uses' => 'AdminCustomFieldsController@getFields'])->name('custom-fields.data');
            Route::resource('custom-fields', 'AdminCustomFieldsController');

            // Message settings
            Route::resource('message-settings', 'MessageSettingsController');

            // Storage settings
            Route::post('storage-settings-awstest', ['uses' => 'StorageSettingsController@awsTest'])->name('storage-settings.awstest');
            Route::resource('storage-settings', 'StorageSettingsController');

            // Storage settings
            Route::post('language-settings/update-data/{id?}', ['uses' => 'LanguageSettingsController@updateData'])->name('language-settings.update-data');
            Route::resource('language-settings', 'LanguageSettingsController');

            // Module settings
            Route::resource('module-settings', 'ModuleSettingsController');

            Route::resource('pusher-settings', 'PusherSettingsController');
        });

        Route::group(
            ['prefix' => 'projects'], function () {
            Route::post('project-members/save-group', ['uses' => 'ManageProjectMembersController@storeGroup'])->name('project-members.storeGroup');
            Route::resource('project-members', 'ManageProjectMembersController');

            Route::post('tasks/data/{startDate?}/{endDate?}/{hideCompleted?}/{projectId?}', ['uses' => 'ManageTasksController@data'])->name('tasks.data');
            Route::get('tasks/export/{startDate?}/{endDate?}/{projectId?}/{hideCompleted?}', ['uses' => 'ManageTasksController@export'])->name('tasks.export');
            Route::post('tasks/sort', ['uses' => 'ManageTasksController@sort'])->name('tasks.sort');
            Route::post('tasks/change-status', ['uses' => 'ManageTasksController@changeStatus'])->name('tasks.changeStatus');
            Route::post('tasks/change-date', ['uses' => 'ManageTasksController@changeDate'])->name('tasks.changeDate');
            Route::get('tasks/check-task/{taskID}', ['uses' => 'ManageTasksController@checkTask'])->name('tasks.checkTask');
            Route::get('tasks/kanban-board/{id}', ['uses' => 'ManageTasksController@kanbanboard'])->name('tasks.kanbanboard');
            Route::resource('tasks', 'ManageTasksController');

            Route::post('files/store-link', ['uses' => 'ManageProjectFilesController@storeLink'])->name('files.storeLink');
            Route::get('files/download/{id}', ['uses' => 'ManageProjectFilesController@download'])->name('files.download');
            Route::get('files/thumbnail', ['uses' => 'ManageProjectFilesController@thumbnailShow'])->name('files.thumbnail');
            Route::post('files/multiple-upload', ['uses' => 'ManageProjectFilesController@storeMultiple'])->name('files.multiple-upload');
            Route::resource('files', 'ManageProjectFilesController');

        });

         // task routes
        Route::resource('task', 'ManageAllTasksController', ['only' => ['edit', 'update', 'index']]); // hack to make left admin menu item active
        Route::group(
            ['prefix' => 'task'], function () {      

            Route::get('all-tasks/export/{startDate?}/{endDate?}/{projectId?}/{hideCompleted?}', ['uses' => 'ManageAllTasksController@export'])->name('all-tasks.export');
            Route::get('all-tasks/dependent-tasks/{projectId}/{taskId?}', ['uses' => 'ManageAllTasksController@dependentTaskLists'])->name('all-tasks.dependent-tasks');
            Route::get('all-tasks/members/{projectId}', ['uses' => 'ManageAllTasksController@membersList'])->name('all-tasks.members');
            Route::get('all-tasks/ajaxCreate/{columnId?}', ['uses' => 'ManageAllTasksController@ajaxCreate'])->name('all-tasks.ajaxCreate');
            Route::get('all-tasks/reminder/{taskid}', ['uses' => 'ManagerAllTasksController@remindForTask'])->name('all-tasks.reminder');
            Route::get('all-tasks/files/{taskid}', ['uses' => 'ManageAllTasksController@showFiles'])->name('all-tasks.show-files');
            Route::get('all-tasks/history/{taskid}', ['uses' => 'ManageAllTasksController@history'])->name('all-tasks.history');
            Route::get('all-tasks/pinned-task', ['uses' => 'ManageAllTasksController@pinnedItem'])->name('all-tasks.pinned-task');
            Route::get('all-tasks/download-task/{id}', ['uses' => 'ManageAllTasksController@download'])->name('all-tasks.download-task');
            Route::resource('all-tasks', 'ManageAllTasksController');


            // taskboard resource
            Route::post('taskboard/updateIndex', ['as' => 'taskboard.updateIndex', 'uses' => 'AdminTaskboardController@updateIndex']);
            Route::resource('taskboard', 'AdminTaskboardController');

            // task calendar routes
            Route::resource('task-calendar', 'AdminCalendarController');

            Route::get('task-files/download/{id}', ['uses' => 'TaskFilesController@download'])->name('task-files.download');
            Route::post('task-files/update/{id}', ['uses' => 'TaskFilesController@update'])->name('task-files.updates');
            Route::post('task-files/alldownload-zip/{id}', ['uses' => 'TaskFilesController@downloadzip'])->name('task-files.alldownloadzip');
            Route::get('public/downloadfiles/{id}', ['uses' => 'TaskFilesController@downloadzip'])->name('task-files.downloadzip');
            Route::resource('task-files', 'TaskFilesController');

        });

        Route::resource('sticky-note', 'ManageStickyNotesController');


        Route::resource('reports', 'TaskReportController', ['only' => ['edit', 'update', 'index']]); // hack to make left admin menu item active
        Route::group(
            ['prefix' => 'reports'], function () {
            Route::post('task-report/data', ['uses' => 'TaskReportController@data'])->name('task-report.data');
            Route::get('task-report/export/{startDate?}/{endDate?}/{employeeId?}/{projectId?}', ['uses' => 'TaskReportController@export'])->name('task-report.export');
            Route::resource('task-report', 'TaskReportController');
            Route::resource('income-expense-report', 'IncomeVsExpenseReportController');
        });

        Route::resource('search', 'AdminSearchController');

        //Ticket routes
        Route::get('tickets/export/{startDate?}/{endDate?}/{agentId?}/{status?}/{priority?}/{channelId?}/{typeId?}', ['uses' => 'ManageTicketsController@export'])->name('tickets.export');
        Route::post('tickets/refresh-count', ['uses' => 'ManageTicketsController@refreshCount'])->name('tickets.refreshCount');
        Route::get('tickets/reply-delete/{id?}', ['uses' => 'ManageTicketsController@destroyReply'])->name('tickets.reply-delete');
        Route::post('tickets/updateOtherData/{id}', ['uses' => 'ManageTicketsController@updateOtherData'])->name('tickets.updateOtherData');

        Route::resource('tickets', 'ManageTicketsController');

        //Ticket Custom Embed From
        Route::post('ticket-form/sortFields', ['as' => 'ticket-form.sortFields', 'uses' => 'TicketCustomFormController@sortFields']);
        Route::resource('ticket-form', 'TicketCustomFormController');

        Route::get('ticket-files/download/{id}', ['uses' => 'TicketFilesController@download'])->name('ticket-files.download');
        Route::resource('ticket-files', 'TicketFilesController');

        // User message
        Route::post('message-submit', ['as' => 'user-chat.message-submit', 'uses' => 'AdminChatController@postChatMessage']);
        Route::get('user-search', ['as' => 'user-chat.user-search', 'uses' => 'AdminChatController@getUserSearch']);
        Route::resource('user-chat', 'AdminChatController');

        //Event Calendar
        Route::post('events/removeAttendee', ['as' => 'events.removeAttendee', 'uses' => 'AdminEventCalendarController@removeAttendee']);
        Route::resource('events', 'AdminEventCalendarController');


        // Role permission routes
        Route::post('role-permission/assignAllPermission', ['as' => 'role-permission.assignAllPermission', 'uses' => 'ManageRolePermissionController@assignAllPermission']);
        Route::post('role-permission/removeAllPermission', ['as' => 'role-permission.removeAllPermission', 'uses' => 'ManageRolePermissionController@removeAllPermission']);
        Route::post('role-permission/assignRole', ['as' => 'role-permission.assignRole', 'uses' => 'ManageRolePermissionController@assignRole']);
        Route::post('role-permission/detachRole', ['as' => 'role-permission.detachRole', 'uses' => 'ManageRolePermissionController@detachRole']);
        Route::post('role-permission/storeRole', ['as' => 'role-permission.storeRole', 'uses' => 'ManageRolePermissionController@storeRole']);
        Route::post('role-permission/deleteRole', ['as' => 'role-permission.deleteRole', 'uses' => 'ManageRolePermissionController@deleteRole']);
        Route::get('role-permission/showMembers/{id}', ['as' => 'role-permission.showMembers', 'uses' => 'ManageRolePermissionController@showMembers']);
        Route::resource('role-permission', 'ManageRolePermissionController');

        //sub task routes
        Route::post('sub-task/changeStatus', ['as' => 'sub-task.changeStatus', 'uses' => 'ManageSubTaskController@changeStatus']);
        Route::resource('sub-task', 'ManageSubTaskController');

        //task comments
        Route::resource('task-comment', 'AdminTaskCommentController');
        //task Note
        Route::resource('task-note', 'AdminNoteCommentController');
    });

    // Employee routes
    Route::group(
        ['namespace' => 'Member', 'prefix' => 'member', 'as' => 'member.', 'middleware' => ['role:employee']], function () {

        Route::get('dashboard', ['uses' => 'MemberDashboardController@index'])->name('dashboard');

        Route::post('profile/updateOneSignalId', ['uses' => 'MemberProfileController@updateOneSignalId'])->name('profile.updateOneSignalId');
        Route::get('profile/country/{id}', ['uses' => 'MemberProfileController@country'])->name('profile.country');
        Route::resource('profile', 'MemberProfileController');

        Route::resource('site', 'TaskLabelController');

        //Pinned route
        Route::resource('pinned', 'MemberPinnedController', ['only' => ['store', 'destroy']]);

        Route::group(
            ['prefix' => 'projects'], function () {
            Route::resource('project-members', 'MemberProjectsMemberController');

            Route::post('tasks/data/{startDate?}/{endDate?}/{hideCompleted?}/{projectId?}', ['uses' => 'MemberTasksController@data'])->name('tasks.data');
            Route::post('tasks/sort', ['uses' => 'MemberTasksController@sort'])->name('tasks.sort');
            Route::post('tasks/change-status', ['uses' => 'MemberTasksController@changeStatus'])->name('tasks.changeStatus');
            Route::get('tasks/check-task/{taskID}', ['uses' => 'MemberTasksController@checkTask'])->name('tasks.checkTask');
            Route::resource('tasks', 'MemberTasksController');

            Route::post('files/store-link', ['uses' => 'MemberProjectFilesController@storeLink'])->name('files.storeLink');
            Route::get('files/download/{id}', ['uses' => 'MemberProjectFilesController@download'])->name('files.download');
            Route::get('files/thumbnail', ['uses' => 'MemberProjectFilesController@thumbnailShow'])->name('files.thumbnail');
            Route::post('files/multiple-upload', ['uses' => 'MemberProjectFilesController@storeMultiple'])->name('files.multiple-upload');
            Route::resource('files', 'MemberProjectFilesController');

        });

        //sticky note
        Route::resource('sticky-note', 'MemberStickyNoteController');

        // User message
        Route::post('message-submit', ['as' => 'user-chat.message-submit', 'uses' => 'MemberChatController@postChatMessage']);
        Route::get('user-search', ['as' => 'user-chat.user-search', 'uses' => 'MemberChatController@getUserSearch']);
        Route::resource('user-chat', 'MemberChatController');

     //   Notice
        Route::get('notices/data', ['uses' => 'MemberNoticesController@data'])->name('notices.data');
        Route::resource('notices', 'MemberNoticesController');

        // task routes
        Route::resource('task', 'MemberAllTasksController', ['only' => ['edit', 'update', 'index']]); // hack to make left admin menu item active
        Route::group(
            ['prefix' => 'task'], function () {

            Route::get('all-tasks/dependent-tasks/{projectId}/{taskId?}', ['uses' => 'MemberAllTasksController@dependentTaskLists'])->name('all-tasks.dependent-tasks');
            Route::post('all-tasks/data/{startDate?}/{endDate?}/{hideCompleted?}/{projectId?}', ['uses' => 'MemberAllTasksController@data'])->name('all-tasks.data');
            Route::get('all-tasks/members/{projectId}', ['uses' => 'MemberAllTasksController@membersList'])->name('all-tasks.members');
            Route::get('all-tasks/ajaxCreate/{columnId?}', ['uses' => 'MemberAllTasksController@ajaxCreate'])->name('all-tasks.ajaxCreate');
            Route::get('all-tasks/reminder/{taskid}', ['uses' => 'MemberAllTasksController@remindForTask'])->name('all-tasks.reminder');
            Route::get('all-tasks/history/{taskid}', ['uses' => 'MemberAllTasksController@history'])->name('all-tasks.history');
            Route::get('all-tasks/files/{taskid}', ['uses' => 'MemberAllTasksController@showFiles'])->name('all-tasks.show-files');
            Route::get('all-tasks/pinned-task', ['uses' => 'MemberAllTasksController@pinnedItem'])->name('all-tasks.pinned-task');
            Route::get('all-tasks/download-task/{id}', ['uses' => 'MemberAllTasksController@download'])->name('all-tasks.download-task');
            Route::resource('all-tasks', 'MemberAllTasksController');

            // taskboard resource
            Route::post('taskboard/updateIndex', ['as' => 'taskboard.updateIndex', 'uses' => 'MemberTaskboardController@updateIndex']);
            Route::resource('taskboard', 'MemberTaskboardController');

            // task calendar routes
            Route::resource('task-calendar', 'MemberCalendarController');

            Route::get('task-files/download/{id}', ['uses' => 'TaskFilesController@download'])->name('task-files.download');
            
            Route::post('task-files/update/{id}', ['uses' => 'TaskFilesController@update'])->name('task-files.updates');
            Route::post('task-files/alldownload-zip/{id}', ['uses' => 'TaskFilesController@downloadzip'])->name('task-files.alldownloadzip');
            Route::get('public/downloadfiles/{id}', ['uses' => 'TaskFilesController@downloadzip'])->name('task-files.downloadzip');
            
            Route::resource('task-files', 'TaskFilesController');

        });

        // events
        Route::post('events/removeAttendee', ['as' => 'events.removeAttendee', 'uses' => 'MemberEventController@removeAttendee']);
        Route::resource('events', 'MemberEventController');

        Route::get('clients/data', ['uses' => 'MemberClientsController@data'])->name('clients.data');
        Route::get('clients/create/{clientID?}', ['uses' => 'MemberClientsController@create'])->name('clients.create');
        Route::resource('clients', 'MemberClientsController', ['except' => ['create']]);
       
        Route::get('employees/free-employees', ['uses' => 'ManageEmployeesController@freeEmployees'])->name('employees.freeEmployees');
        Route::get('employees/docs-create/{id}', ['uses' => 'MemberEmployeesController@docsCreate'])->name('employees.docs-create');
        Route::get('employees/tasks/{userId}/{hideCompleted}', ['uses' => 'MemberEmployeesController@tasks'])->name('employees.tasks');
        Route::get('employees/time-logs/{userId}', ['uses' => 'MemberEmployeesController@timeLogs'])->name('employees.time-logs');
        Route::get('employees/data', ['uses' => 'MemberEmployeesController@data'])->name('employees.data');
        Route::get('employees/export', ['uses' => 'MemberEmployeesController@export'])->name('employees.export');
        Route::post('employees/assignRole', ['uses' => 'MemberEmployeesController@assignRole'])->name('employees.assignRole');
        Route::post('employees/assignProjectAdmin', ['uses' => 'MemberEmployeesController@assignProjectAdmin'])->name('employees.assignProjectAdmin');
        Route::resource('employees', 'MemberEmployeesController');

        Route::get('employee-docs/download/{id}', ['uses' => 'MemberEmployeeDocsController@download'])->name('employee-docs.download');
        Route::resource('employee-docs', 'MemberEmployeeDocsController');

        //sub task routes
        Route::post('sub-task/changeStatus', ['as' => 'sub-task.changeStatus', 'uses' => 'MemberSubTaskController@changeStatus']);
        Route::resource('sub-task', 'MemberSubTaskController');

        //task comments
        Route::resource('task-comment', 'MemberTaskCommentController');

        //task notes
        Route::resource('task-note', 'MemberTaskNoteController');

     //   change language
        Route::get('language/change-language', ['uses' => 'MemberProfileController@changeLanguage'])->name('language.change-language');

    });
    // Client routes
    Route::group(
        ['namespace' => 'Client', 'prefix' => 'client', 'as' => 'client.', 'middleware' => ['role:client']], function () {

        Route::resource('dashboard', 'ClientDashboardController');
        Route::get('dashboard/filter', ['uses' => 'ClientDashboardController@filter'] )->name('dashboard.filter');

        Route::resource('profile', 'ClientProfileController');
        Route::get('profile/state/{id}', ['uses' => 'ClientProfileController@state'])->name('profile.state');
       
        // Project section
        Route::get('country/{id}', ['uses' => 'StatesClientController@country'])->name('state.country');
        Route::get('clients/create/{clientID?}', ['uses' => 'ClientClientsController@create'])->name('clients.create');
        Route::get('clients/state/{id}', ['uses' => 'ClientClientsController@state'])->name('clients.state');
       // Route::get('clients/store', ['uses' => 'ClientClientsController@store'])->name('clients.store');
        Route::resource('clients', 'ClientClientsController', ['except' => ['create']]);
        
         Route::group(
             ['prefix' => 'tasks'], function () {
            
            Route::post('tasks/change-status', ['uses' => 'ClientTasksController@changeStatus'])->name('tasks.changeStatus');
            Route::get('tasks/check-task/{taskID}', ['uses' => 'ClientTasksController@checkTask'])->name('tasks.checkTask');
            Route::resource('tasks', 'ClientTasksController');

         });


        Route::resource('task', 'ClientAllTasksController', ['only' => ['edit', 'update', 'index', 'add_tasks']]);

        Route::group(
            ['prefix' => 'task'], function () {
            
            Route::get('all-tasks/dependent-tasks/{projectId}/{taskId?}', ['uses' => 'ClientAllTasksController@dependentTaskLists'])->name('all-tasks.dependent-tasks');
            Route::post('all-tasks/data/{startDate?}/{endDate?}/{hideCompleted?}/{projectId?}', ['uses' => 'ClientAllTasksController@data'])->name('all-tasks.data');
            Route::get('all-tasks/clients/{projectId}', ['uses' => 'ClientAllTasksController@clienwtsList'])->name('all-tasks.clients');
            Route::get('all-tasks/ajaxCreate/{columnId?}', ['uses' => 'ClientAllTasksController@ajaxCreate'])->name('all-tasks.ajaxCreate');
            Route::get('all-tasks/reminder/{taskid}', ['uses' => 'ClientAllTasksController@remindForTask'])->name('all-tasks.reminder');
            Route::get('all-tasks/history/{taskid}', ['uses' => 'ClientAllTasksController@history'])->name('all-tasks.history');
            Route::get('all-tasks/files/{taskid}', ['uses' => 'ClientAllTasksController@showFiles'])->name('all-tasks.show-files');
            Route::get('all-tasks/pinned-task', ['uses' => 'ClientAllTasksController@pinnedItem'])->name('all-tasks.pinned-task');

            Route::get('all-tasks/download-task/{taskid}', ['uses' => 'ClientAllTasksController@download'])->name('all-tasks.download-task');
            Route::resource('all-tasks', 'ClientAllTasksController');

            Route::post('taskCategory/store-cat', ['uses' => 'ClientTaskCategoryController@storeCat'])->name('taskCategory.store-cat');
            Route::get('taskCategory/create-cat', ['uses' => 'ClientTaskCategoryController@createCat'])->name('taskCategory.create-cat');
            Route::resource('taskCategory', 'ClientTaskCategoryController');

            // taskboard resource
            Route::post('taskboard/updateIndex', ['as' => 'taskboard.updateIndex', 'uses' => 'ClientAllTaskboardController@updateIndex']);
            Route::resource('taskboard', 'ClientTaskboardController');

            // task calendar routes
            Route::resource('task-calendar', 'ClientCalendarController');

            Route::get('task-files/download/{id}', ['uses' => 'TaskFilesController@download'])->name('task-files.download');
            Route::post('task-files/update/{id}', ['uses' => 'TaskFilesController@update'])->name('task-files.updates');
            Route::post('task-files/alldownload-zip/{id}', ['uses' => 'TaskFilesController@downloadzip'])->name('task-files.alldownloadzip');
            Route::get('public/downloadfiles/{id}', ['uses' => 'TaskFilesController@downloadzip'])->name('task-files.downloadzip');
            Route::resource('task-files', 'TaskFilesController');

        });

        Route::group(
            ['prefix' => 'sites'], function () {
        Route::post('site/store-label', ['uses' => 'ClientTaskLabelController@storeLabel'])->name('site.store-label');
        Route::get('site/create-label', ['uses' => 'ClientTaskLabelController@createLabel'])->name('site.create-label');
        Route::get('show/{id}', ['uses' => 'ClientTaskLabelController@show'])->name('sites.show');
        Route::get('site/country/{id}', ['uses' => 'ClientTaskLabelController@country'])->name('site.country');
        Route::resource('site', 'ClientTaskLabelController');
        });

        Route::resource('reports', 'ClientTaskReportController', ['only' => ['edit', 'update', 'index']]); // hack to make left admin menu item active
        Route::group(
            ['prefix' => 'reports'], function () {
            Route::post('task-report/data', ['uses' => 'ClientTaskReportController@data'])->name('task-report.data');
            Route::get('task-report/export/{startDate?}/{endDate?}/{employeeId?}/{projectId?}', ['uses' => 'ClientTaskReportController@export'])->name('task-report.export');
            Route::resource('task-report', 'ClientTaskReportController');
            //endregion
        });

        //sticky note
        Route::resource('sticky-note', 'ClientStickyNoteController');

         // change language
        Route::get('language/change-language', ['uses' => 'ClientProfileController@changeLanguage'])->name('language.change-language');


        //Tickets routes
        Route::get('tickets/data', ['uses' => 'ClientTicketsController@data'])->name('tickets.data');
        Route::post('tickets/close-ticket/{id}', ['uses' => 'ClientTicketsController@closeTicket'])->name('tickets.closeTicket');
        Route::post('tickets/open-ticket/{id}', ['uses' => 'ClientTicketsController@reopenTicket'])->name('tickets.reopenTicket');
        Route::resource('tickets', 'ClientTicketsController');

        Route::resource('events', 'ClientEventController');
        // User message
        Route::post('message-submit', ['as' => 'user-chat.message-submit', 'uses' => 'ClientChatController@postChatMessage']);
        Route::get('user-search', ['as' => 'user-chat.user-search', 'uses' => 'ClientChatController@getUserSearch']);
        Route::resource('user-chat', 'ClientChatController');

        //task comments
        Route::resource('task-comment', 'ClientTaskCommentController');
        //task note
        Route::resource('task-note', 'ClientTaskNoteController');

        //Notice
        Route::get('notices/data', ['uses' => 'ClientNoticesController@data'])->name('notices.data');
        Route::resource('notices', 'ClientNoticesController');

    });

    // Mark all notifications as read
    Route::post('show-admin-notifications', ['uses' => 'NotificationController@showAdminNotifications'])->name('show-admin-notifications');
    Route::post('show-user-notifications', ['uses' => 'NotificationController@showUserNotifications'])->name('show-user-notifications');
    Route::post('show-client-notifications', ['uses' => 'NotificationController@showClientNotifications'])->name('show-client-notifications');
    Route::post('mark-notification-read', ['uses' => 'NotificationController@markAllRead'])->name('mark-notification-read');
    Route::get('show-all-member-notifications', ['uses' => 'NotificationController@showAllMemberNotifications'])->name('show-all-member-notifications');
    Route::get('show-all-client-notifications', ['uses' => 'NotificationController@showAllClientNotifications'])->name('show-all-client-notifications');
    Route::get('show-all-admin-notifications', ['uses' => 'NotificationController@showAllAdminNotifications'])->name('show-all-admin-notifications');
    
});
