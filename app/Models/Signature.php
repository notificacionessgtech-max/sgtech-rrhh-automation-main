<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_data_id',
        'file_path',
        'signed_at',
        'ip_address',
    ];

    public function personalData()
    {
        return $this->belongsTo(PersonalData::class, 'personal_data_id', 'personal_data_id');
    }
}
