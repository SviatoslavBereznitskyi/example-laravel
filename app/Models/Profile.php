<?php

namespace App\Models;

use function Complex\theta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Profile
 *
 * @property User $user
 * @property Language[]|Collection languages
 * @property Phone[]|Collection phones
 * @property string[] emails
 * @property boolean $is_main
 * @property ProfileVaccination[] vaccinations
 * @property string $name
 * @property Country $country
 * @property integer $user_id
 * @property string|null $surname
 * @property string $middle_name
 * @property string $address_line1
 * @property string $address_line2
 * @property string $blood_group
 * @property integer $height
 * @property Carbon $birthday
 * @property string $sex
 * @property string|null $city
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property integer|null $country_id
 * @property string $avatar_path
 * @property string $type
 * @property boolean $allow_quick_access
 * @property string|null $breed
 * @property Subscription|null $subscription
 * @property string|null $animal_age
 * @property string|null $beast
 *
 * @method Builder byUser(User $user)
 * @method Builder human()
 *
 * @package App\Models
 */
class Profile extends Model
{
    public const BLOOD_GROUP_O_PLUS   = 'O+';
    public const BLOOD_GROUP_A_PLUS   = 'A+';
    public const BLOOD_GROUP_B_PLUS   = 'B+';
    public const BLOOD_GROUP_AB_PLUS  = 'AB+';
    public const BLOOD_GROUP_O_MINUS  = 'O-';
    public const BLOOD_GROUP_A_MINUS  = 'A-';
    public const BLOOD_GROUP_B_MINUS  = 'B-';
    public const BLOOD_GROUP_AB_MINUS = 'AB-';

    public const SEX_MAN   = 'm';
    public const SEX_WOMAN = 'f';

    public const ORIGINAL_FILE_DESTINATION = 'avatars';
    public const CROPPED_FILE_DESTINATION  = 'avatars/cropped';

    public const TYPE_HUMAN  = 'human';
    public const TYPE_ANIMAL = 'animal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'middle_name',
        'surname',
        'sex',
        'height',
        'birthday',
        'user_id',
        'city',
        'blood_group',
        'address_line1',
        'address_line2',
        'country_id',
        'avatar_path',
        'is_main',
        'emails',
        'type',
        'breed',
        'allow_quick_access',
        'animal_age',
        'beast',
    ];

    protected $casts = [
        'emails' => 'array',
    ];

    protected $dates = [
        'birthday',
    ];

    /**
     * @return array
     */
    public static function getAvailableBloodGroups(): array
    {
        return [
            self::BLOOD_GROUP_O_PLUS,
            self::BLOOD_GROUP_A_PLUS,
            self::BLOOD_GROUP_B_PLUS,
            self::BLOOD_GROUP_AB_PLUS,
            self::BLOOD_GROUP_O_MINUS,
            self::BLOOD_GROUP_A_MINUS,
            self::BLOOD_GROUP_B_MINUS,
            self::BLOOD_GROUP_AB_MINUS,
        ];
    }

    /**
     * @param Builder $query
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function scopeHuman(Builder $query)
    {
        return $query->where('type', self::TYPE_HUMAN);
    }

    /**
     * @param Builder $query
     * @param User $user
     *
     * @return Builder
     */
    public function scopeByUser(Builder $query, User $user)
    {
        return $query->where('user_id', $user->getKey());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subscription record associated with the user.
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    /**
     * Get all of the post's comments.
     */
    public function phones()
    {
        return $this->morphMany(Phone::class, 'owner');
    }

    /**
     * Get all of the post's comments.
     */
    public function components()
    {
        return $this->hasMany(ComponentVisibility::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    /**
     * Get all of the profile's important infos.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function importantInfos()
    {
        return $this->hasMany(ImportantInfo::class);
    }

    /**
     * @param bool $isMain
     *
     * @return $this
     */
    public function setIsMain(bool $isMain)
    {
        $this->is_main = $isMain;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMain(): bool
    {
        return $this->is_main;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function componentsVisibility()
    {
        return $this->hasMany(ComponentVisibility::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vaccinations()
    {
        return $this->hasMany(ProfileVaccination::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function codes()
    {
        return $this->hasMany(Code::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'language_profile');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middle_name;
    }

    /**
     * @return string
     */
    public function getAddressLine1()
    {
        return $this->address_line1;
    }

    /**
     * @return string
     */
    public function getAddressLine2()
    {
        return $this->address_line2;
    }

    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function getBloodGroup()
    {
        return $this->blood_group;
    }

    /**
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return \Illuminate\Support\Carbon|null
     */
    public function getBirthday(): ?Carbon
    {
        return $this->birthday;
    }

    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @return integer|null
     */
    public function getCountryId(): ?int
    {
        return $this->country_id;
    }

    /**
     * @param string|null $filePath
     * @return $this
     */
    public function setAvatarUrl(?string $filePath)
    {
        $this->avatar_path = $filePath;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAvatarPath(): ?string
    {
        return $this->avatar_path;
    }

    /**
     * @return string|null
     */
    public function getCroppedAvatarPath()
    {
        if (!$this->avatar_path) {
            return null;
        }

        $basename = pathinfo($this->avatar_path, PATHINFO_BASENAME);

        return self::CROPPED_FILE_DESTINATION . DIRECTORY_SEPARATOR . $basename;
    }

    /**
     * @return string[]
     */
    public function getEmails(): array
    {
        return (array)$this->emails;
    }

    /**
     * @return array
     */
    public static function getAvailableTypes(): array
    {
        return [
            self::TYPE_HUMAN  => trans('admin.code.typeHuman'),
            self::TYPE_ANIMAL => trans('admin.code.typeAnimal'),
        ];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getBreed(): ?string
    {
        return $this->breed;
    }

    /**
     * @return bool
     */
    public function isAllowQuickAccess(): bool
    {
        return $this->allow_quick_access;
    }

    /**
     * @return bool
     */
    public function allowedFreeTextInput()
    {
        return $this->subscription && $this->subscription->plan->hasService(SubscriptionPlan::SERVICE_ANY_TEXT);
    }

    /**
     * @return bool
     */
    public function isLead(): bool
    {
        return is_null($this->subscription) || $this->subscription->plan->isLead();
    }

    /**
     * @return bool
     */
    public function isSubscribed()
    {
        return $this->subscription && $this->subscription->isActive() && !$this->subscription->isExpired();
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return
            $this->surname . ' ' .  $this->name . ' ' . $this->middle_name;
    }

    public function getFullNameAttribute()
    {
        return $this->surname . ' ' .  $this->name . ' ' . $this->middle_name;
    }

    /**
     * @return string|null
     */
    public function getAnimalAge(): ?string
    {
        return $this->animal_age;
    }

    /**
     * @return string|null
     */
    public function getBeast(): ?string
    {
        return $this->beast;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    public function getSubscriptionsAttribute($value)
    {
        return $this->subscription?$this->subscription:$value;
    }

    /**
     * @return bool
     */
    public function isAnimal()
    {
        return $this->type == self::TYPE_ANIMAL;
    }


    /**
     * @return bool
     */
    public function isHuman()
    {
        return $this->type == self::TYPE_HUMAN;
    }
}
