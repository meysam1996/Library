<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Book
 *
 * @property int $id
 * @property string $name
 * @property string|null $summary
 * @property string|null $description
 * @property int $printer_key
 * @property int $serial_number
 * @property int|null $publisher_id
 * @property int|null $subject_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Book newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book query()
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book wherePrinterKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book wherePublisherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'summary', 'description', 'printer_key', 'serial_number', 'publisher_id', 'subject_id',
    ];

    /**
     * Foreign Key of Publisher model
     * Relation : one to many
     *
     */
    public function publisher()
    {
        return $this->belongsTo("App\Publisher");
    }

    /**
     * Foreign Key of Subject model
     * Relation : one to many
     *
     */
    public function subject()
    {
        return $this->belongsTo("App\Subject");
    }

    /**
     * Foreign Key of Author model
     * Relation : many to many
     *
     */
    public function authors()
    {
        return $this->belongsToMany("App\Author");
    }

    /**
     * Foreign Key of Borrow model
     * Relation : many to many
     *
     */
    public function borrows()
    {
        return $this->belongsToMany("App\Borrow");
    }

    /**
     * Get all of the files for this book
     *
     */
    public function files()
    {
        return $this->morphMany(File::class, "owner");
    }
}
