<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PersonalData extends Model
{
    use HasFactory;

    protected $table = 'personal_data';
    protected $primaryKey = 'personal_data_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'invitation_link_id',
        'hiring_date',
        'job_position',
        'first_name',
        'middle_name',
        'last_name',
        'second_last_name',
        'gender',
        'marital_status',
        'birthdate',
        'place_of_birth',
        'eps',
        'blood_group',
        'dni',
        'date_of_issue',
        'place_of_issue',
        'nationality',
        'address',
        'phone_number',
        'email',
    ];

    protected $casts = [
        'hiring_date'     => 'date',
        'birthdate'       => 'date',
        'date_of_issue'   => 'date',
    ];

    public function academicInformation(): HasMany
    {
        return $this->hasMany(AcademicInformation::class, 'personal_data_id', 'personal_data_id');
    }

    public function additionalEducations(): HasMany
    {
        return $this->hasMany(AdditionalEducation::class, 'personal_data_id', 'personal_data_id');
    }

    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(EmergencyContact::class, 'personal_data_id', 'personal_data_id');
    }

    public function familyData(): HasMany
    {
        return $this->hasMany(FamilyData::class, 'personal_data_id', 'personal_data_id');
    }

    public function healthData(): HasOne
    {
        return $this->hasOne(HealthData::class, 'personal_data_id', 'personal_data_id');
    }

    public function invitationLink(): BelongsTo
    {
        return $this->belongsTo(InvitationLink::class, 'invitation_link_id', 'id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PersonalDocument::class, 'personal_data_id', 'personal_data_id');
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(BankAccount::class, 'personal_data_id', 'personal_data_id');
    }
}
