<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'location',
        'price',
        'capacity',
        'image',
        'invitations',
    ];

    protected $casts = [
        'date' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function availableTickets()
    {
        return $this->capacity - $this->bookings()->where('status', 'confirmed')->sum('quantity');
    }
}
