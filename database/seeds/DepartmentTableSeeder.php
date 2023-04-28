<?php

use App\Team;
use Illuminate\Database\Seeder;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            'Livebarn',
            'Technician'
        ];

        foreach ($departments as $department) {
            Team::create(['team_name' => $department]);
        }
    }
}
