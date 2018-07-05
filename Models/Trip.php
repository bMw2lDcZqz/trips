<?php

namespace Modules\Trip\Models;

use App\Models\EntityModel;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends EntityModel
{
    use PresentableTrait;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $presenter = 'Modules\Trip\Presenters\TripPresenter';

    /**
     * @var string
     */
    protected $fillable = [
        'client_id',
        'trip_date',
        'vehicle',
        'purpose',
        'start_odometer',
        'end_odometer',
        'notes'
    ];

    /**
     * @var string
     */
    protected $table = 'trips';

    public function getEntityType()
    {
        return 'trip';
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Client')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account');
    }
}

// Trip::created(function ($trip) {
//     event(new TripWasCreated($trip));
// });

// Trip::updated(function ($trip) {
//     event(new TripWasUpdated($trip));
// });