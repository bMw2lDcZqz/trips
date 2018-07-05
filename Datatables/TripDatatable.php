<?php

namespace Modules\Trip\Datatables;

use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class TripDatatable extends EntityDatatable
{
    public $entityType = 'trip';
    public $sortCol = 2;

    public function columns()
    {
        return [
            
            [
                'client_name',
                function($model) {
                    return link_to('trips/'.$model->public_id.'/edit', $model->client_name)->toHtml(); 
                },
            ],
            [
                'trip_date',
                function ($model) {
                    return Utils::fromSqlDate($model->trip_date);
                }
            ],
        ];
    }

    public function actions()
    {
        return [
            [
                mtrans('trip', 'edit_trip'),
                function ($model) {
                    return URL::to("trips/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['trip', $model->user_id]);
                }
            ],
        ];
    }

}
