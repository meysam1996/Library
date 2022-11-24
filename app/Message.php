<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Message
 *
 * @property mixed content
 * @property int $id
 * @property string $body
 * @property int $sender
 * @property int $receiver
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $users
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereReceiver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @mixin \Eloquent


 */
class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body', 'sender', 'receiver',
    ];

    /**
     * Foreign Key of User model
     * Relation : one to many
     *
     */
    public function receiver()
    {
        return $this->belongsTo("App\User", "receiver");
    }

    /**
     * Foreign Key of User model
     * Relation : one to many
     *
     */
    public function sender()
    {
        return $this->belongsTo("App\User", "sender");
    }
}
