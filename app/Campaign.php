<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
	protected $table = 'campaigns';	
	protected $fillable = ['campaign_name','user_id'];
	
	public function campaignStep()
    {
        return $this->hasMany('App\CampaignStep');
    }
}
