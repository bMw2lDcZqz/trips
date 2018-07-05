<?php

namespace Modules\Trip\Http\ApiControllers;

use App\Http\Controllers\BaseAPIController;
use Modules\Trip\Repositories\TripRepository;
use Modules\Trip\Http\Requests\TripRequest;
use Modules\Trip\Http\Requests\CreateTripRequest;
use Modules\Trip\Http\Requests\UpdateTripRequest;

class TripApiController extends BaseAPIController
{
    protected $TripRepo;
    protected $entityType = 'trip';

    public function __construct(TripRepository $tripRepo)
    {
        parent::__construct();

        $this->tripRepo = $tripRepo;
    }

    /**
     * @SWG\Get(
     *   path="/trips",
     *   summary="List trip",
     *   operationId="listTrips",
     *   tags={"trip"},
     *   @SWG\Response(
     *     response=200,
     *     description="A list of trip",
     *      @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Trip"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function index()
    {
        $data = $this->tripRepo->all();

        return $this->listResponse($data);
    }

    /**
     * @SWG\Get(
     *   path="/trips/{trip_id}",
     *   summary="Individual Trip",
     *   operationId="getTrip",
     *   tags={"trip"},
     *   @SWG\Parameter(
     *     in="path",
     *     name="trip_id",
     *     type="integer",
     *     required=true
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="A single trip",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Trip"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function show(TripRequest $request)
    {
        return $this->itemResponse($request->entity());
    }

    /**
     * @SWG\Post(
     *   path="/trips",
     *   summary="Create a trip",
     *   operationId="createTrip",
     *   tags={"trip"},
     *   @SWG\Parameter(
     *     in="body",
     *     name="trip",
     *     @SWG\Schema(ref="#/definitions/Trip")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="New trip",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Trip"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function store(CreateTripRequest $request)
    {
        $trip = $this->tripRepo->save($request->input());

        return $this->itemResponse($trip);
    }

    /**
     * @SWG\Put(
     *   path="/trips/{trip_id}",
     *   summary="Update a trip",
     *   operationId="updateTrip",
     *   tags={"trip"},
     *   @SWG\Parameter(
     *     in="path",
     *     name="trip_id",
     *     type="integer",
     *     required=true
     *   ),
     *   @SWG\Parameter(
     *     in="body",
     *     name="trip",
     *     @SWG\Schema(ref="#/definitions/Trip")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Updated trip",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Trip"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function update(UpdateTripRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }

        $trip = $this->tripRepo->save($request->input(), $request->entity());

        return $this->itemResponse($trip);
    }


    /**
     * @SWG\Delete(
     *   path="/trips/{trip_id}",
     *   summary="Delete a trip",
     *   operationId="deleteTrip",
     *   tags={"trip"},
     *   @SWG\Parameter(
     *     in="path",
     *     name="trip_id",
     *     type="integer",
     *     required=true
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Deleted trip",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Trip"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function destroy(UpdateTripRequest $request)
    {
        $trip = $request->entity();

        $this->tripRepo->delete($trip);

        return $this->itemResponse($trip);
    }

}
