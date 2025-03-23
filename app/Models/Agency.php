<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Agency extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'address',
        'city',
        'country',
        'postcode',
        'number_of_users',
        'date_of_information',
        'business_type',
        'company_number',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'agency_user')->withTimestamps();
    }

}
