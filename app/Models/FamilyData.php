<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyData extends Model
{
    use HasFactory;

    protected $table = 'family_data';
    protected $primaryKey = 'family_data_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'personal_data_id',
        'relationship',
        'dni',
        'full_name',
        'age',
        'gender',
        'birthdate',
    ];

    // Relación con personal_data
    public function personalData(): BelongsTo
    {
        return $this->belongsTo(PersonalData::class, 'personal_data_id', 'personal_data_id');
    }
}
