<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $table = 'emergency_contacts';
    protected $primaryKey = 'emergency_contact_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'personal_data_id',
        'full_name',
        'phone_number',
        'relationship',
    ];

    public function personalData(): BelongsTo
    {
        return $this->belongsTo(PersonalData::class, 'personal_data_id', 'personal_data_id');
    }
}
