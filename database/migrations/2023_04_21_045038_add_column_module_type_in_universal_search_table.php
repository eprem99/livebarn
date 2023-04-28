<?php

use App\ClientDetails;
use App\CreditNotes;
use App\EmployeeDetails;
use App\Estimate;
use App\Invoice;
use App\Lead;
use App\Notice;
use App\Project;
use App\Proposal;
use App\Task;
use App\Ticket;
use App\UniversalSearch;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnModuleTypeInUniversalSearchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('universal_search', function (Blueprint $table) {
            $table->enum('module_type', ['notice', 'task', 'client', 'employee'])->nullable()->default(null)->after('searchable_id');
        });

        $universalSearches = UniversalSearch::all();
        if ($universalSearches->count() > 0){
            foreach ($universalSearches as $universalSearch){
                UniversalSearch::destroy($universalSearch->id);
            }
        }
        $tickets = Ticket::all();
        if ($tickets->count() > 0){
            foreach ($tickets as $ticket){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $ticket->id;
                $universalSearch->title = 'Ticket: '.$ticket->subject;
                $universalSearch->route_name = 'admin.tickets.edit';
                $universalSearch->module_type = 'ticket';
                $universalSearch->save();
            }
        }


        $notices = Notice::all();
        if ($notices->count() > 0){
            foreach ($notices as $notice){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $notice->id;
                $universalSearch->title = 'Notice: '.$notice->heading;
                $universalSearch->route_name = 'admin.notices.edit';
                $universalSearch->module_type = 'notice';
                $universalSearch->save();
            }
        }

        $tasks = Task::all();
        if ($tasks->count() > 0){
            foreach ($tasks as $task){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $task->id;
                $universalSearch->title = 'Task: '.$task->heading;
                $universalSearch->route_name = 'admin.all-tasks.edit';
                $universalSearch->module_type = 'task';
                $universalSearch->save();
            }
        }

        $clients = ClientDetails::all();
        if ($clients->count() > 0){
            foreach ($clients as $client){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $client->user_id;
                $universalSearch->title = 'Client '.$client->name;
                $universalSearch->route_name = 'admin.clients.edit';
                $universalSearch->module_type = 'client';
                $universalSearch->save();

                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $client->user_id;
                $universalSearch->title = 'Client '.$client->email;
                $universalSearch->route_name = 'admin.clients.edit';
                $universalSearch->module_type = 'client';
                $universalSearch->save();

                if ($client->company_name){
                    $universalSearch = new UniversalSearch();
                    $universalSearch->searchable_id = $client->user_id;
                    $universalSearch->title = 'Client '.$client->company_name;
                    $universalSearch->route_name = 'admin.clients.edit';
                    $universalSearch->module_type = 'client';
                    $universalSearch->save();
                }
            }
        }
        $employees = EmployeeDetails::with('user')->get();
        if ($employees->count() > 0){
            foreach ($employees as $employee){
                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $employee->user_id;
                $universalSearch->title = 'Employee '.$employee->user->name;
                $universalSearch->route_name = 'admin.employees.show';
                $universalSearch->module_type = 'employee';
                $universalSearch->save();

                $universalSearch = new UniversalSearch();
                $universalSearch->searchable_id = $employee->user_id;
                $universalSearch->title = 'Employee '.$employee->user->email;
                $universalSearch->route_name = 'admin.employees.show';
                $universalSearch->module_type = 'employee';
                $universalSearch->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('universal_search', function (Blueprint $table) {
            $table->dropColumn('module_type');
        });
    }
}
