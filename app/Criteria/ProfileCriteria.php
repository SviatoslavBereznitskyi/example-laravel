<?php

namespace App\Criteria;

use App\Models\Profile;
use App\Models\ProfileVaccination;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class ProfileCriteriaCriteria.
 *
 * @package namespace App\Criteria;
 */
class ProfileCriteria implements CriteriaInterface
{

    /**
     * @var Profile
     */
    private $profile;

    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Apply criteria in query repository
     *
     * @param ProfileVaccination $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('profile_id', $this->profile->getKey());
    }
}
