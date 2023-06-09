<?php
/*
 * Project: Livebarn
 * Author: VECTO
 * Email: info@vecto.digital
 * Site: https://vecto.digital/
 * Last Modified: Friday, 28th April 2023
 */
namespace App\Http\Controllers\Admin;

use App\Country;
use App\State;

class AdminProfileSettingsController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-user';
        $this->pageTitle = 'app.menu.profileSettings';
    }

    public function index()
    {
        $this->userDetail = $this->user;
        $this->employeeDetail = $this->user->employee_details;
        $this->countries = Country::all();
        $this->states = State::all();

        return view('admin.profile.index', $this->data);
    }


}
