<?php

namespace App\DTO\Request;

use App\DTO\Model\Phone;
use OpenApi\Annotations as OA;

/**
 * Class UpdateContactRequest
 * @package App\DTO\Request
 *
 * @OA\Schema(
 *     schema="UpdateContactRequest",
 *     required={
 *          "firstName",
 *          "middleName",
 *          "lastName"
 *     }
 * )
 *
 */
class UpdateContactRequest
{
    /**
     * @OA\Property()
     *
     * @var string
     */
    public $firstName;

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
    public $lastName;

    /**
     * @OA\Property()
     *
     * @var int
     */
    public $relationId;

    /**
     * @OA\Property()
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property()
     *
     * @var boolean
     */
    public $isEmergencyEmail;

    /**
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
}
