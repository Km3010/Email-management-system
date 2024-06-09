<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Email extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'subject', 'body', 'sender', 'recipients', 'metadata'
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    // Add any additional relationships or methods here
}
