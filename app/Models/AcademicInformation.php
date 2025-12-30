<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicInformation extends Model
{
    use HasFactory;

    protected $table = 'academic_information';
    protected $primaryKey = 'academic_information_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'personal_data_id',
        'academic_institution',
        'start_date_school',
        'end_date_school',
        'university_career',
        'degree',
        'card_number',
    ];

    protected $casts = [
        'start_date_school' => 'date',
        'end_date_school' => 'date',
    ];

    public function personalData(): BelongsTo
    {
        return $this->belongsTo(PersonalData::class, 'personal_data_id', 'personal_data_id');
    }
}
