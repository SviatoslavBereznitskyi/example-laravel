<?php

namespace App\Observers;

use App\Contracts\Services\CodeGeneratorInterface;
use App\Contracts\Services\ProfileService;
use App\Contracts\Services\SubscriptionService;
use App\DTO\Response\ErrorResponse;
use App\Exceptions\CodeAttachException;
use App\Models\Profile;

class ProfileObserver
{
    /**
     * @var CodeGeneratorInterface
     */
    private $codeService;

    /**
     * @var SubscriptionService
     */
    private $subscriptionService;

    /**
     * ProfileObserver constructor.
     *
     * @param SubscriptionService $subscriptionService
     * @param CodeGeneratorInterface $codeService
     */
    public function __construct(SubscriptionService $subscriptionService, CodeGeneratorInterface $codeService)
    {
        $this->codeService         = $codeService;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Handle the profile "created" event.
     *
     * @param \App\Models\Profile $profile
     * @return void
     * @throws CodeAttachException
     */
    public function created(Profile $profile)
    {
        // after profile creating we nave no full information about object
        $profile = $profile->fresh();

        $this->codeService->makePersonal($profile);

        if (false === $profile->isMain()) {
            $this->subscriptionService->subscribeFree($profile);
        }
    }
}
