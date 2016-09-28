<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
	protected $table = 'email_templates';	
	protected $fillable = ['template_subject','template_body','template_signature'];
}
