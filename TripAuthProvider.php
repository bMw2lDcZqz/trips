<?php

namespace Modules\Trip\;

use App\Providers\AuthServiceProvider;

class TripAuthProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Trip\Models\Trip::class => \Modules\Trip\Policies\TripPolicy::class,
    ];
}
