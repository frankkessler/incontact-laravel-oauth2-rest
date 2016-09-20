<?php

namespace Frankkessler\Incontact\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * inContact Token Model to store incontact oauth tokens in a database via Eloquent
 *
 * @property integer $id
 * @property string $access_token
 * @property string $refresh_token
 * @property string $instance_base_url
 * @property string refresh_instance_url
 * @property string scope
 * @property integer agent_id
 * @property integer team_id
 * @property integer business_unit
 * @property integer $user_id
 * @property \DateTimeInterface $expires
 * @property \DateTimeInterface $created_at
 * @property \DateTimeInterface $updated_at
 * @property \DateTimeInterface $deleted_at
 */

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
