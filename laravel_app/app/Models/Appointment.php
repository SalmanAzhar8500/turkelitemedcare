<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_guide_id',
        'service_id',
        'type',
        'name',
        'email',
        'phone',
        'appointment_date',
        'message',
        'head_image',
    ];

    public function patientGuide()
    {
        return $this->belongsTo(PatientGuide::class, 'patient_guide_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
