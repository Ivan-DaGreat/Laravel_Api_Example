<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entries extends Model
{
    protected $table = 'entries';
	protected $fillable = ['title', 'slug', 'body', 'user_id', 'type_id'];
}
