<?php

namespace App\DTO\Model;

use OpenApi\Annotations as OA;

/**
 * Class Profile
 *
 * @package App\DTO\Model
 *
 * @OA\Schema(
 *      schema="Profile",
 *      required={
 *          "id",
 *          "name",
 *          "countryId",
 *          "userId",
 *          "surname",
 *          "middleName",
 *          "addressLine1",
 *          "addressLine2",
 *          "bloodGroup",
 *          "height",
 *          "birthday",
 *          "type",
 *          "sex",
 *          "isMain",
 *          "city",
 *          "avatarUrl",
 *          "createdAt",
 *          "updatedAt",
 *          "breed",
 *          "allowQuickAccess",
 *          "animalAge",
 *          "beast",
 *     }
 * )
 */
class Profile
{
    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $id;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $userId;

    /**
     * @OA\Property()
     *
     * @var integer|null
     */
    public $countryId;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property()
     *
     * @var string|null
     */
    public $surname;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $middleName;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $addressLine1;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $addressLine2;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $bloodGroup;

    /**
     * @OA\Property()
     *
     * @var integer
     */
    public $height;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $birthday;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $sex;

    /**
     * @OA\Property()
     *
     * @var boolean
     */
    public $isMain;

    /**
     * @OA\Property()
     *
     * @var string|null
     */
    public $city;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $avatarUrl;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $type;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $croppedAvatarUrl;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $createdAt;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $updatedAt;

    /**
     *
     * @OA\Property()
     *
     * @var Country|null
     */
    public $country;

    /**
     *
     * @OA\Property()
     *
     * @var User|null
     */
    public $user;

    /**
     *
     * @OA\Property(
     *     type="array",
     *     @OA\Items(
     *          ref="#/components/schemas/Language"
     *      )
     * )
     *
     * @var Language[]|null
     */
    public $languages;

    /**
     *
     * @OA\Property(
     *     type="array",
     *     @OA\Items(
     *          ref="#/components/schemas/Phone"
     *      )
     * )
     *
     * @var Phone[]|null
     */
    public $phones;

    /**
     * @OA\Property()
     *
     * @var string[]
     */
    public $emails;

    /**
     * @OA\Property()
     *
     * @var int|null
     */
    public $age;

    /**
     * @OA\Property()
     *
     * @var string|null
     */
    public $breed;

    /**
     * @OA\Property()
     *
     * @var boolean
     */
    public $allowQuickAccess;

    /**
     * @OA\Property()
     *
     * @var Subscription|null
     */
    public $subscription;

   /**
     * @OA\Property()
     *
     * @var string|null
     */
    public $animalAge;

    /**
     * @OA\Property()
     *
     * @var string|null
     */
    public $beast;
}
