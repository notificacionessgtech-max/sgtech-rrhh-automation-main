<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccount extends Model
{
    use HasFactory;

    protected $table = 'bank_accounts';

    // El nombre correcto según la migración
    protected $primaryKey = 'bank_accounts_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'personal_data_id',
        'banking_entity',
        'account_number',
        'account_type',
        'pension_fund',
        'severance_pay_fund',
    ];



    public function personalData(): BelongsTo
    {
        return $this->belongsTo(PersonalData::class, 'personal_data_id', 'personal_data_id');
    }
}
