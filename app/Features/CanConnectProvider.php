<?php

namespace App\Features;

use App\Enums\IntegrationProvider;
use App\Models\User;
use Illuminate\Support\Lottery;
use Laravel\Pennant\Contracts\FeatureScopeable;

class CanConnectProvider implements FeatureScopeable
{
    /**
     * Resolve the feature's initial value.
     */
    public function resolve(User $user, IntegrationProvider $provider): mixed
    {
        return true;
    }
}
