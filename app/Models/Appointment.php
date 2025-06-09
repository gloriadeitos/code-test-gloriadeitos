<?php
// app/Models/Appointment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model {
    use HasFactory;

    protected $fillable = ['patient_id', 'date_time', 'status'];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    public function patient() {
        return $this->belongsTo(Patient::class);
    }
}