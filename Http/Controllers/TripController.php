<?php

namespace Modules\Trip\Http\Controllers;

use Auth;
use Input;
use Redirect;
use Session;
use View;
use App\Http\Controllers\BaseController;
use App\Models\Client;
use App\Services\DatatableService;
use Modules\Trip\Datatables\TripDatatable;
use Modules\Trip\Repositories\TripRepository;
use Modules\Trip\Http\Requests\TripRequest;
use Modules\Trip\Http\Requests\CreateTripRequest;
use Modules\Trip\Http\Requests\UpdateTripRequest;

class TripController extends BaseController
{
    protected $TripRepo;
    //protected $entityType = 'trip';

    public function __construct(TripRepository $tripRepo)
    {
        //parent::__construct();

        $this->tripRepo = $tripRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('list_wrapper', [
            'entityType' => 'trip',
            'datatable' => new TripDatatable(),
            'title' => mtrans('trip', 'trip_list'),
        ]);
    }

    public function datatable(DatatableService $datatableService)
    {
        $search = request()->input('sSearch');
        $userId = Auth::user()->filterId();

        $datatable = new TripDatatable();
        $query = $this->tripRepo->find($search, $userId);

        return $datatableService->createDatatable($datatable, $query);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(TripRequest $request, $clientPublicId = 0)
    {
        $data = [
            'trip' => null,
            'clientPublicId' => Input::old('client') ? Input::old('client') : ($request->client_id ?: 0),
            'entityType' => 'trip',
            'method' => 'POST',
            'url' => 'trips',
            'title' => mtrans('trip', 'new_trip'),
            'datetimeFormat' => Auth::user()->account->getMomentDateTimeFormat(),
        ];

        $data = array_merge($data, self::getViewModel());

        return View::make('trip::edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateTripRequest $request)
    {
        $trip = $this->save($request);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(TripRequest $request)
    {
        $trip = $request->entity();

        $data = [
            'trip' => $trip,
            'method' => 'PUT',
            'url' => 'trips/' . $trip->public_id,
            'title' => mtrans('trip', 'edit_trip'),
        ];

        return view('trip::edit', $data);
    }

    /**
     * Show the form for editing a resource.
     * @return Response
     */
    public function show(TripRequest $request)
    {
        return redirect()->to("trips/{$request->trip}/edit");
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateTripRequest $request)
    {
        $trip = $this->tripRepo->save($request->input(), $request->entity());

        return redirect()->to($trip->present()->editUrl)
            ->with('message', mtrans('trip', 'updated_trip'));
    }

    /**
     * Update multiple resources
     */
    public function bulk()
    {
        $action = request()->input('action');
        $ids = request()->input('public_id') ?: request()->input('ids');
        $count = $this->tripRepo->bulk($ids, $action);

        return redirect()->to('trips')
            ->with('message', mtrans('trip', $action . '_trip_complete'));
    }

    /**
     * @return array
     */
    private static function getViewModel($trip = false)
    {
        return [
            'clients' => Client::scope()->withActiveOrSelected($trip ? $trip->client_id : false)->with('contacts')->orderBy('name')->get(),
            'account' => Auth::user()->account,
        ];
    }

    /**
     * @param null $publicId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function save($request, $publicId = null)
    {
        $action = Input::get('action');

        if (in_array($action, ['archive', 'delete', 'restore'])) {
            return self::bulk();
        }

        $trip = $this->tripRepo->save($publicId, $request->input());

        if ($publicId) {
            Session::flash('message', mtrans('trip', 'texts.updated_trip'));
        } else {
            Session::flash('message', mtrans('trip', 'texts.created_trip'));
        }

        return Redirect::to("trips/{$trip->public_id}/edit");
    }
}
