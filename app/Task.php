<?php

namespace App;

use App\Observers\TaskObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Task extends BaseModel
{
    use Notifiable;

    protected static function boot()
    {
        parent::boot();

        static::observe(TaskObserver::class);
    }
    
    public function routeNotificationForMail()
    {
        return $this->user->email;
    }

    protected $dates = ['due_date', 'completed_on', 'start_date', 'created_at'];
    protected $appends = ['due_on', 'create_on'];
    protected $guarded = ['id'];

    public function label()
    {
        return $this->hasMany(TaskLabel::class, 'task_id');
    }

    public function board_column()
    {
        return $this->belongsTo(TaskboardColumn::class, 'board_column_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_users');
    }

    public function notifyusers()
    {
        return $this->belongsToMany(User::class, 'task_users')->where('users.email_notifications', '1');
    }

    public function wotype()
    {
        return $this->belongsTo(WoType::class, 'wo_id');
    }

    public function sporttype()
    {
        return $this->belongsTo(SportType::class, 'sport_id');
    }

    public function labels()
    {
        return $this->belongsTo(TaskLabelList::class, 'site_id');
    }

    public function create_by()
    {
        return $this->belongsTo(User::class, 'created_by')->withoutGlobalScopes(['active']);
    }

    public function category()
    {
        return $this->belongsTo(TaskCategory::class, 'task_category_id');
    }


    public function history()
    {
        return $this->hasMany(TaskHistory::class, 'task_id')->orderBy('id', 'desc');
    }


    public function incompleteSubtasks()
    {
        return $this->hasMany(SubTask::class, 'task_id')->where('sub_tasks.status', 'incomplete');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class, 'task_id')->orderBy('id', 'desc');
    }
    public function notes()
    {
        return $this->hasMany(TaskNote::class, 'task_id')->orderBy('id', 'desc');
    }

    public function files()
    {
        return $this->hasMany(TaskFile::class, 'task_id');
    }

    /**
     * @return string
     */
    public function getDueOnAttribute()
    {
        if (!is_null($this->due_date)) {
            return $this->due_date->format('d M, y');
        }
        return "";
    }
    public function getCreateOnAttribute()
    {
        if (!is_null($this->start_date)) {
            return $this->start_date->format('d M, y');
        }
        return "";
    }

    public function getIsTaskUserAttribute()
    {
        if (auth()->user()) {
            return TaskUser::where('task_id', $this->id)->where('user_id', auth()->user()->id)->first();
        }
    }
 
    /**
     * @return bool
     */
    public function pinned()
    {
        $pin = Pinned::where('user_id', user()->id)->where('task_id', $this->id)->first();
        if(!is_null($pin)) {
            return true;
        }
        return false;
    }
}
