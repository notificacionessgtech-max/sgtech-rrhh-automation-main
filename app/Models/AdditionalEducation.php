<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdditionalEducation extends Model
{
    use HasFactory;

    protected $table = 'additional_education';
    protected $primaryKey = 'additional_education_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'personal_data_id',

        // Especialidad
        'specialty_institution',
        'start_date_specialty',
        'end_date_specialty',
        'course',
        'specialty_level',

        // Metodologías y herramientas
        'methodology_name',
        'proficiency_level',

        // Idiomas
        'language',
        'language_level',
    ];

    protected $casts = [
        'start_date_specialty' => 'date',
        'end_date_specialty' => 'date',
    ];

    public function personalData(): BelongsTo
    {
        return $this->belongsTo(PersonalData::class, 'personal_data_id', 'personal_data_id');
    }
}
