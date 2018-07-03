<?php

namespace Modules\Trip\Datatables;

use Utils;
use URL;
use Auth;
use App\Ninja\Datatables\EntityDatatable;

class TripDatatable extends EntityDatatable
{
    public $entityType = 'trip';
    public $sortCol = 1;

    public function columns()
    {
        return [
            
            [
                'created_at',
                function ($model) {
                    return Utils::fromSqlDateTime($model->created_at);
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
                    return URL::to("trip/{$model->public_id}/edit");
                },
                function ($model) {
                    return Auth::user()->can('editByOwner', ['trip', $model->user_id]);
                }
            ],
        ];
    }

}
