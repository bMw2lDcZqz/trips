<?php

namespace Modules\Trip\Repositories;

use DB;
use Utils;
use App\Ninja\Repositories\BaseRepository;
use App\Models\Client;
use Modules\Trip\Models\Trip;
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
        $query = DB::table('trips')
                    ->leftJoin('clients', 'trips.client_id', '=', 'clients.id')
                    ->leftJoin('contacts', 'contacts.client_id', '=', 'clients.id')
                    ->where('trips.account_id', '=', \Auth::user()->account_id)
                    ->select(
                        'trips.public_id',
                        DB::raw("COALESCE(NULLIF(clients.name,''), NULLIF(CONCAT(contacts.first_name, ' ', contacts.last_name),''), NULLIF(contacts.email,'')) client_name"),
                        'trips.deleted_at',
                        'trips.created_at',
                        'trips.is_deleted',
                        'trips.client_id',
                        'trips.user_id',
                        'trips.trip_date',
                        'trips.vehicle',
                        'trips.purpose',
                        'trips.start_odometer',
                        'trips.end_odometer',
                        'trips.notes',
                        'clients.public_id as client_public_id',
                        'clients.user_id as client_user_id',
                        'contacts.first_name',
                        'contacts.email',
                        'contacts.last_name'
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

    public function save($publicId, $data, $trip = null)
    {
        if($trip) {
            // do nothing
        } elseif ($publicId) {
            $trip = Trip::scope($publicId)->withTrashed()->firstOrFail();
        } else {
            $trip = $trip ?: Trip::createNew();
        }

        $trip->fill($data);

        if (isset($data['client'])) {
            $trip->client_id = $data['client'] ? Client::getPrivateId($data['client']) : null;
        } elseif (isset($data['client_id'])) {
            $trip->client_id = $data['client_id'] ? Client::getPrivateId($data['client_id']) : null;
        }

        if (isset($data['trip_date_sql'])) {
            $trip->trip_date = $data['trip_date_sql'];
        } elseif (isset($data['trip_date'])) {
            $trip->trip_date = Utils::toSqlDate($data['trip_date']);
        }

        if (isset($data['vehicle'])) {
            $trip->vehicle = trim($data['vehicle']);
        }

        if (isset($data['purpose'])) {
            $trip->purpose = trim($data['purpose']);
        }

        if (isset($data['notes'])) {
            $trip->notes = trim($data['notes']);
        }

        $trip->save();

        /*
        if (!$publicId || intval($publicId) < 0) {
            event(new ClientWasCreated($client));
        } else {
            event(new ClientWasUpdated($client));
        }
        */

        return $trip;
    }

}
