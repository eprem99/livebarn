<?php
/*
 * Project: Livebarn
 * Author: VECTO
 * Email: info@vecto.digital
 * Site: https://vecto.digital/
 * Last Modified: Friday, 28th April 2023
 */
namespace App\Http\Controllers\Member;

use App\Helper\Reply;
use App\Pinned;
use Illuminate\Http\Request;

class MemberPinnedController extends MemberBaseController
{

    public function __construct() {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            if (!in_array('tasks', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pinned = new Pinned();
        $pinned->task_id = $request->task_id;
        $pinned->save();

        return Reply::success(__('messages.pinnedSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->type == 'task'){
            Pinned::where('task_id', $id)->where('user_id', user()->id)->delete();
        }

        return Reply::success(__('messages.pinnedRemovedSuccess'));
    }
}
