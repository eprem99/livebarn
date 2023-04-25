<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskboardColumn extends BaseModel
{
    protected $fillable = ['column_name', 'slug', 'label_color', 'priority', 'role_id'];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'board_column_id')->orderBy('column_priority');
    }

    public function membertasks()
    {
        return $this->hasMany(Task::class, 'board_column_id')->where('user_id', auth()->user()->id)->orderBy('column_priority');
    }

    public static function incompletedColumn()
    {
        return cache()->remember(
            'taskboard-complete', 60*60*24, function () {
                return TaskboardColumn::where('slug', 'incompleted')->first();
            }
        );
    }
    public static function assignedColumn()
    {
        return cache()->remember(
            'taskboard-complete', 60*60*24, function () {
                return TaskboardColumn::where('slug', 'assigned')->first();
            }
        );
    }
    public static function scheduledColumn()
    {
        return cache()->remember(
            'taskboard-complete', 60*60*24, function () {
                return TaskboardColumn::where('slug', 'scheduled')->first();
            }
        );
    }
    public static function techColumn()
    {
        return cache()->remember(
            'taskboard-complete', 60*60*24, function () {
                return TaskboardColumn::where('slug', 'tech-Off-Site')->first();
            }
        );
    }
    public static function techoffColumn()
    {
        return cache()->remember(
            'taskboard-complete', 60*60*24, function () {
                return TaskboardColumn::where('slug', 'tech-Off-Site')->first();
            }
        );
    }
    public static function techonColumn()
    {
        return cache()->remember(
            'taskboard-complete', 60*60*24, function () {
                return TaskboardColumn::where('slug', 'tech-on-Site')->first();
            }
        );
    }

    public static function incompleteColumn()
    {
        return cache()->remember(
            'taskboard-complete', 60*60*24, function () {
                return TaskboardColumn::where('slug', 'incomplete')->first();
            }
        );
    }
    public static function offsiteColumn()
    {
        return cache()->remember(
            'taskboard-complete', 60*60*24, function () {
                return TaskboardColumn::where('slug', 'off-site-complete')->first();
            }
        );
    }
    public static function completeColumn()
    {
        return cache()->remember(
            'taskboard-complete', 60*60*24, function () {
                return TaskboardColumn::where('slug', 'completed')->first();
            }
        );
    }

    public static function closedColumn()
    {
        return cache()->remember(
            'taskboard-complete', 60*60*24, function () {
                return TaskboardColumn::where('slug', 'closed')->first();
            }
        );
    }
    public static function canceledColumn()
    {
        return cache()->remember(
            'taskboard-complete', 60*60*24, function () {
                return TaskboardColumn::where('slug', 'cancelled')->first();
            }
        );
    }
}
