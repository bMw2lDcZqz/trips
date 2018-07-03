<?php

namespace Modules\Trip\Transformers;

use Modules\Trip\Models\Trip;
use App\Ninja\Transformers\EntityTransformer;

/**
 * @SWG\Definition(definition="Trip", @SWG\Xml(name="Trip"))
 */

class TripTransformer extends EntityTransformer
{
    /**
    * @SWG\Property(property="id", type="integer", example=1, readOnly=true)
    * @SWG\Property(property="user_id", type="integer", example=1)
    * @SWG\Property(property="account_key", type="string", example="123456")
    * @SWG\Property(property="updated_at", type="integer", example=1451160233, readOnly=true)
    * @SWG\Property(property="archived_at", type="integer", example=1451160233, readOnly=true)
    */

    /**
     * @param Trip $trip
     * @return array
     */
    public function transform(Trip $trip)
    {
        return array_merge($this->getDefaults($trip), [
            
            'id' => (int) $trip->public_id,
            'updated_at' => $this->getTimestamp($trip->updated_at),
            'archived_at' => $this->getTimestamp($trip->deleted_at),
        ]);
    }
}
