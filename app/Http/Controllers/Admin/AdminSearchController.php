<?php
/*
 * Project: Livebarn
 * Author: VECTO
 * Email: info@vecto.digital
 * Site: https://vecto.digital/
 * Last Modified: Friday, 28th April 2023
 */
namespace App\Http\Controllers\Admin;

use App\UniversalSearch;
use Illuminate\Http\Request;

class AdminSearchController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'Search results';
        $this->pageIcon = 'icon-magnifier';
    }

    public function store(Request $request) {
        $key = $request->search_key;
        $key = str_replace("#","",$key);
        if(trim($key) == ''){
            return redirect()->back();
        }

        return redirect(route('admin.search.show', $key));
    }

    public function show($key) {
        $this->searchResults = UniversalSearch::where('title', 'like', '%'.$key.'%')->get();
        $this->searchKey = $key;
        return view('admin.search.show', $this->data);
    }
}
