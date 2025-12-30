<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InvitationLink extends Model
{
    use HasFactory;

    protected $table = 'invitation_links';

    protected $fillable = [
        'uuid',
        'email',
        'status',
        'expires_at',
        'used_at',
        'verified_at', // importante agregarlo
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'verified_at' => 'datetime', // también castear aquí
    ];

    public function personalData(): HasOne
    {
        return $this->hasOne(
            PersonalData::class,
            'invitation_link_id',
            'id'
        );
    }
}
