<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DaoDetail extends Model
{
    protected $table = 'dao_details';

    protected $fillable = [
        'dao_type',
        'dao_name',
        'package_amount',
        'max_members',
        'rank_required',
        'days_limit',
        'pool_percent',
        'capping_multiplier',
        'status',
    ];

    /**
     * Get active member count for this DAO tier from user_details
     */
    public function getActiveMembersCountAttribute()
    {
        return UserDetails::where('is_dao', $this->dao_type)->count();
    }
}
