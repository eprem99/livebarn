<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientDetails extends BaseModel
{

    protected $fillable = [
        'user_id',
        'address',
        'postal_code',
        'country',
        'state',
        'city',
        'office',
        'cell',
        'note',
    ];

    protected $default = [
        'id',
        'address',
        'country',
        'state',
        'city',
        'postal_code',
        'note',
        'name',
        'email',
    ];

    protected $table = 'client_details';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function countries()
    {
        return $this->belongsTo(Country::class, 'country');
    }
    public function states()
    {
        return $this->belongsTo(State::class, 'state');
    }
}
