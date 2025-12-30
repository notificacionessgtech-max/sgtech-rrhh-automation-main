<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalDocument extends Model
{
    use HasFactory;

    protected $table = 'personal_documents';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'personal_data_id',
        'document_type',
        'file_path',
    ];

    public function personalData(): BelongsTo
    {
        return $this->belongsTo(PersonalData::class, 'personal_data_id', 'personal_data_id');
    }
}
