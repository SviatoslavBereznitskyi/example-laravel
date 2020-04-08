<?php

namespace App\DTO\Response;

use App\DTO\Model\ProfileAllergen;

use OpenApi\Annotations as OA;

/**
 * Class ProfileContactsResponse
 * @package App\DTO\Response
 *
 * @OA\Schema(
 *      schema="ProfileContactsResponse",
 * )
 */
class ProfileContactsResponse extends PaginatedResponse
{
    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/Contact")
     * )
     *
     * @var ProfileAllergen[]
     */
    public $data;
}
