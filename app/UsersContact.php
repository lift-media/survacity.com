<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersContact extends Model
{
	protected $table = 'users_contacts';	
	protected $fillable = ['user_id','group_id'];
}
