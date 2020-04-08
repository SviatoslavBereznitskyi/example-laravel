<?php

namespace App\Services;

use App\Criteria\ContactPhoneCriteria;
use App\Http\Requests\CreateContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use App\Models\Phone;
use App\Models\Profile;
use App\Repositories\Contracts\ContactRepository;
use App\Repositories\Contracts\PhoneRepository;
use App\Traits\TransformDataTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Arr;

class ContactService implements \App\Contracts\Services\ContactService
{
    use TransformDataTrait;

    /**
     * @var ContactRepository
     */
    private $repository;

    /**
     * @var PhoneRepository
     */
    private $phoneRepository;

    /**
     * ContactService constructor.
     * @param ContactRepository $repository
     * @param PhoneRepository $phoneRepository
     */
    public function __construct(ContactRepository $repository, PhoneRepository $phoneRepository)
    {
        $this->repository      = $repository;
        $this->phoneRepository = $phoneRepository;
    }

    /**
     * @param CreateContactRequest $request
     * @param Profile $profile
     * @return Contact
     */
    public function store(CreateContactRequest $request, Profile $profile): Contact
    {
        $data = $request->only(array_keys($request->getMainRules()));

        if (!array_has($data, 'isEmergencyEmail')) {
            $data['isEmergencyEmail'] = false;
        }

        $data['profile_id'] = $profile->getKey();

        $contact = $this->repository->create($this->toSnakeCase($data));

        foreach ($request->get('phones', []) as $phone) {
            $this->storePhone($contact, $phone);
        }

        return $this->repository->with('phones')->find($contact->getKey());
    }

    public function delete(Contact $contact)
    {
        foreach ($contact->phones as $phone) {
            $this->phoneRepository->delete($phone->getKey());
        }

        $this->repository->delete($contact->getKey());
    }

    /**
     * @param Contact $contact
     * @param array $phoneData
     * @return Phone
     */
    private function storePhone(Contact $contact, array $phoneData): Phone
    {
        $availableKeys = [
            'number',
            'type',
        ];

        $safeData = array_only($phoneData, $availableKeys);

        $safeData['owner_id']   = $contact->getKey();
        $safeData['owner_type'] = Phone::OWNER_TYPE_CONTACT;

        return $this->phoneRepository->create($this->toSnakeCase($safeData));
    }

    /**
     * @param Contact $contact
     * @param array $phoneData
     * @return Phone
     */
    private function createOrUpdatePhone(Contact $contact, array $phoneData): Phone
    {
        $availableKeys = [
            'number',
            'type',
        ];

        $safeData = array_only($phoneData, $availableKeys);

        $this->phoneRepository->pushCriteria(new ContactPhoneCriteria($contact));

        $phone = $this->phoneRepository->findWhere(['type' => array_get($phoneData, 'type')])->first();

        if ($phone) {
            return $this->phoneRepository->update($this->toSnakeCase($safeData), $phone->getKey());
        }

        return $this->storePhone($contact, $this->toSnakeCase($safeData));
    }

    /**
     * @param UpdateContactRequest $request
     * @param Contact $contact
     * @return Contact
     * @throws AuthorizationException
     */
    public function update(UpdateContactRequest $request, Contact $contact): Contact
    {
        $data = $request->only(array_keys($request->getMainRules()));

        if (!$request->get('relationId')) {
            $data['relationId'] = null;
        }

        foreach ($request->get('phones') as $phone) {
            $this->createOrUpdatePhone($contact, $phone);
        }

        foreach ($contact->phones as $phone) {
            $existedPhones[] = $phone->getKey();
        }

        $requestedTypes = Arr::pluck($request->get('phones'), 'type');

        $requestedPhoneIds = $contact->phones
            ->whereIn('type', $requestedTypes)
            ->map(
                function (Phone $phone) {
                    return $phone->getKey();
                }
            )->toArray();

        $diff = array_diff($existedPhones, $requestedPhoneIds);

        foreach ($diff as $phoneId) {
            $this->phoneRepository->delete($phoneId);
        }

        $data =  $this->toSnakeCase($data);

        $this->repository->update($data, $contact->getKey());

        return $this->repository->with('phones')->find($contact->getKey());
    }
}
