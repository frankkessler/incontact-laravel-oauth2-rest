<?php

namespace Frankkessler\Incontact\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncontactToken extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function scopeFindByUserId($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }
}
