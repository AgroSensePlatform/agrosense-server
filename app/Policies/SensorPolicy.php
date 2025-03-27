<?php

namespace App\Policies;

use App\Models\Sensor;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SensorPolicy
{


    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Sensor $sensor): bool
    {
        return $user->id === $sensor->user_id;
    }



    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Sensor $sensor): bool
    {
        return $user->id === $sensor->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Sensor $sensor): bool
    {
        return $user->id === $sensor->user_id;
    }

}
