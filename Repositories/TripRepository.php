<?php

namespace Modules\Trip\Repositories;

use DB;
use Modules\Trip\Models\Trip;
use App\Ninja\Repositories\BaseRepository;
//use App\Events\TripWasCreated;
//use App\Events\TripWasUpdated;

class TripRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'Modules\Trip\Models\Trip';
    }

    public function all()
    {
        return Trip::scope()
                ->orderBy('created_at', 'desc')
                ->withTrashed();
    }

    public function find($filter = null, $userId = false)
    {
        $query = DB::table('trip')
                    ->where('trip.account_id', '=', \Auth::user()->account_id)
                    ->select(
                        
                        'trip.public_id',
                        'trip.deleted_at',
                        'trip.created_at',
                        'trip.is_deleted',
                        'trip.user_id'
                    );

        $this->applyFilters($query, 'trip');

        if ($userId) {
            $query->where('clients.user_id', '=', $userId);
        }

        /*
        if ($filter) {
            $query->where();
        }
        */

        return $query;
    }

    public function save($data, $trip = null)
    {
        $entity = $trip ?: Trip::createNew();

        $entity->fill($data);
        $entity->save();

        /*
        if (!$publicId || intval($publicId) < 0) {
            event(new ClientWasCreated($client));
        } else {
            event(new ClientWasUpdated($client));
        }
        */

        return $entity;
    }

}
