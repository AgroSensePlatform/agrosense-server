<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'farm_id',
        'code',
        'lat',
        'lon',
    ];

    protected $appends = ['last_measurement'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function measurements()
    {
        return $this->hasMany(Measurement::class)->orderBy('timestamp', 'desc');
    }

    public function latestMeasurement()
    {
        return $this->hasOne(Measurement::class)->latest('timestamp');
    }

    public function getLastMeasurementAttribute()
    {
        $measurement = $this->latestMeasurement()->first();

        if (!$measurement) {
            return null;
        }

        return [
            'humidity' => $measurement->humidity,
            'timestamp' => $measurement->timestamp,
            // 'temperature' => $measurement->temperature,
            // 'soil_moisture' => $measurement->soil_moisture,
            // etc.
        ];
    }
}
