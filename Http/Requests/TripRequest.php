<?php

namespace Modules\Trip\Http\Requests;

use App\Http\Requests\EntityRequest;

class TripRequest extends EntityRequest
{
    protected $entityType = 'trip';
}
