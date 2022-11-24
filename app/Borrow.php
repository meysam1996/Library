<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * App\Borrow
 *
 * @property int $id
 * @property int $user_id
 * @property string $status ['reserved', 'delivered', 'returned']
 * @property int $deadline
 * @property string $delivered_at
 * @property string $returned_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Borrow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Borrow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Borrow query()
 * @method static \Illuminate\Database\Eloquent\Builder|Borrow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrow whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrow whereDeliveredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrow whereReturnedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrow whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Borrow whereUserId($value)
 * @mixin \Eloquent
 */
class Borrow extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'status', 'deadline', 'delivered_at', 'returned_at'
    ];

    /**
     * Foreign Key of Book model
     * Relation : many to many
     *
     */
    public function books()
    {
        return $this->belongsToMany("App\Book");
    }

    /**
     * Foreign Key of User model
     * Relation : one to many
     *
     */
    public function users()
    {
        return $this->belongsTo("App\User");
    }

    /**
     * Get the days left
     *
     * @return bool
     */
    public function getDaysLeftAttribute()
    {
        $deadlineDate = Carbon::create($this->created_at)->addDays($this->deadline);
        $daysLeft = now()->diff($deadlineDate);
        return $daysLeft->d;
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['daysLeft'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {

        parent::boot();

        static::addGlobalScope('user_id', function (Builder $builder) {
            if (!Auth::user()->isAdmin()) {
                $builder->where('user_id', '=', Auth::id());
            }

        });
    }
}
