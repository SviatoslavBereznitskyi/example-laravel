<?php

namespace App\Factories;

use App;
use App\Models\SubscriptionPlan;
use App\Presenters\ContactPresenter;
use App\Presenters\ImportantInfoPresenter;
use App\Presenters\InsurancePresenter;
use App\Presenters\ProfileAllergenPresenter;
use App\Presenters\ProfileMedicalDevicePresenter;
use App\Presenters\ProfileVaccinationPresenter;
use App\Repositories\Contracts\ContactRepository;
use App\Repositories\Contracts\ImportantInfoRepository;
use App\Repositories\Contracts\InsuranceRepository;
use App\Repositories\Contracts\ProfileAllergenRepository;
use App\Repositories\Contracts\ProfileMedicalDeviceRepository;
use App\Repositories\Contracts\ProfileVaccinationRepository;

class ComponentsFactory
{
    protected const REPOSITORIES = [
        SubscriptionPlan::COMPONENT_ALLERGY => ProfileAllergenRepository::class,
        SubscriptionPlan::COMPONENT_CONTACTS => ContactRepository::class,
        SubscriptionPlan::COMPONENT_INSURANCE => InsuranceRepository::class,
        SubscriptionPlan::COMPONENT_MEDICAL_DEVICES => ProfileMedicalDeviceRepository::class,
        SubscriptionPlan::COMPONENT_VACCINATIONS => ProfileVaccinationRepository::class,
        SubscriptionPlan::COMPONENT_IMPORTANT_INFO => ImportantInfoRepository::class,
    ];

    protected const PRESENTERS = [
        SubscriptionPlan::COMPONENT_ALLERGY => ProfileAllergenPresenter::class,
        SubscriptionPlan::COMPONENT_CONTACTS => ContactPresenter::class,
        SubscriptionPlan::COMPONENT_INSURANCE => InsurancePresenter::class,
        SubscriptionPlan::COMPONENT_MEDICAL_DEVICES => ProfileMedicalDevicePresenter::class,
        SubscriptionPlan::COMPONENT_VACCINATIONS => ProfileVaccinationPresenter::class,
        SubscriptionPlan::COMPONENT_IMPORTANT_INFO => ImportantInfoPresenter::class,
    ];

    /**
     * @param $component
     * @return mixed|null
     */
    protected static function getRepository($component)
    {
        if (!array_key_exists($component, self::REPOSITORIES)) {
            return null;
        }

        return App::make(self::REPOSITORIES[$component]);
    }

    /**
     * @param $component
     * @return mixed|null
     */
    protected static function getPresenter($component)
    {
        if (!array_key_exists($component, self::PRESENTERS)) {
            return null;
        }

        return App::make(self::PRESENTERS[$component]);
    }

    /**
     * @param $componentName
     * @return PublicComponentBuilder
     */
    public static function make($componentName)
    {
        return new PublicComponentBuilder(self::getRepository($componentName), self::getPresenter($componentName));
    }
}
