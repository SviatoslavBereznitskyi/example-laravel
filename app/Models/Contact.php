<?php

namespace App\Models;

use App\Traits\WithAllTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Contact.
 *
 * @property int profile_id
 * @property int|null relation_id
 * @property string first_name
 * @property string middle_name
 * @property string|null last_name
 * @property string|null email
 * @property bool is_emergency_email
 * @property Phone[]|Collection phones
 * @property Relation|null relation
 * @property Profile profile
 * @property Carbon created_at
 * @property Carbon updated_at
 * @package namespace App\Models;
 */
class Contact extends Model implements Transformable
{
    use TransformableTrait,
        Notifiable,
        WithAllTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_id',
        'relation_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'is_emergency_email',
        'phones',
        'relation',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array
     */
    protected $relationship = [
        'relation'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relation()
    {
        return $this->belongsTo(Relation::class);
    }

    /**
     * Get all of the contact's phones.
     */
    public function phones()
    {
        return $this->morphMany(Phone::class, 'owner');
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getMiddleName(): string
    {
        return (string)$this->middle_name;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return bool
     */
    public function isEmergencyEmail(): bool
    {
        return $this->is_emergency_email;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    /**
     * @return int|null
     */
    public function getRelationId(): ?int
    {
        return $this->relation_id;
    }

    /**
     * @return int
     */
    public function getProfileId(): int
    {
        return $this->profile_id;
    }
}
