<?php

declare(strict_types=1);

namespace App;

use App\Observers\OrganisationObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Organisation
 *
 * @property int id
 * @property string name
 * @property int owner_user_id
 * @property User $owner
 * @property Carbon trial_end
 * @property bool subscribed
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon|null deleted_at
 *
 * @package App
 */
class Organisation extends Model
{
    use SoftDeletes;

    /**
     * Add Observer on Create Organisation
     */
    public static function boot(): void
    {
        parent::boot();
        self::observe(new OrganisationObserver());
    }

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'owner_user_id',
        'trial_end',
        'subscribed'
    ];


    /**
     * @var string[]
     */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * @param $value
     * @return Carbon
     */
    public function getTrialEndAttribute($value): Carbon
    {
        return Carbon::parse($value);
    }

    //TODO : add action/cron that will update Subscribed status

    /**
     * @param $value
     * @return bool
     */
    public function getSubscribedAttribute($value): bool
    {
        return (bool)$value;
    }

    /**
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    /**
     * @param $query
     * @param $filters
     * @return mixed
     */
    public function scopeFilter($query, $filters)
    {
        // $filters['all'] - is ignored
        if (isset($filters['trial'])) {
            $query->where('subscribed', 1);
        } else if (isset($filters['subbed'])) {
            $query->where('subscribed', 0);
        }
        return $query;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'subscribed' => $this->subscribed,
            'trial_end' => $this->trial_end->timestamp,
        ];
    }
}
