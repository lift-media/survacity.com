<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignStep extends Model
{
	protected $table = 'campaign_steps';	
	protected $fillable = ['campaign_name','step_description'];
	
	public function campaign()
    {
        return $this->hasOne('App\Campaign');
    }
}
