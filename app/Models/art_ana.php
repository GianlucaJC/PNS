<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class art_ana extends Model
{
    use HasFactory;
	protected $table="art_ana";
	protected $connection = 'target';
}
