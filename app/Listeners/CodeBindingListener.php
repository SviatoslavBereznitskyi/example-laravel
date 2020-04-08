<?php

namespace App\Listeners;

use App\Contracts\Services\SubscriptionService;
use \App\Events\CodeBinding;
use App\Models\Code;
use App\Models\Profile;

/**
 * Class CodeBindingListener
 * @package App\Listeners
 */
class CodeBindingListener
{
    /**
     * @var SubscriptionService
     */
    private $subscriptionService;

    /**
     * Create the event listener.
     *
     * @param SubscriptionService $subscriptionService
     */
    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Handle the event.
     *
     * @param  CodeBinding $event
     * @return void
     */
    public function handle(CodeBinding $event)
    {
        $code = $event->getCode();
        $profile = $code->profile;

        if ($code->subscriptionPlan && $code->getDuration()) {
            $this->subscribeProfile($profile, $code);
        }

        if (!$code->subscriptionPlan && !$profile->subscription && $this->isFirstCodeAttachment($profile)) {
            $this->subscriptionService->subscribeTrial($profile);
        }
    }

    /**
     * @param Profile $profile
     * @param Code $code
     */
    private function subscribeProfile(Profile $profile, Code $code)
    {
        if (false === $profile->isLead()) {
            logger()->warning(sprintf('Subscribed profile %1$s attach code %2$s with subscription', $profile->getKey(), $code->getKey()));
        }

        $this->subscriptionService->subscribe($profile, $code->subscriptionPlan, $code->getDuration());
    }

    /**
     * @param Profile $profile
     * @return bool
     */
    private function isFirstCodeAttachment(Profile $profile): bool
    {
        return 1 === $profile->codes()->general()->count();
    }
}
