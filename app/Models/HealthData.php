<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthData extends Model
{
    use HasFactory;

    protected $table = 'health_data';
    protected $primaryKey = 'health_data_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'personal_data_id',
        'allergies',
        'diseases',
        'medications',
        'additional_information',
    ];

    // Relación con personal_data
    public function personalData(): BelongsTo
    {
        return $this->belongsTo(PersonalData::class, 'personal_data_id', 'personal_data_id');
    }
}
