<?php

namespace App\Http\Controllers\API;

use App\Contracts\Services\ContactService;
use App\Criteria\ProfileCriteria;
use App\Http\Requests\CreateContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Profile;
use App\Presenters\ContactPresenter;
use App\Repositories\Contracts\ContactRepository;
use App\Repositories\Contracts\ProfileRepository;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    /**
     * @var ContactRepository
     */
    private $repository;

    /**
     * @var ContactPresenter
     */
    private $presenter;

    /**
     * @var ProfileRepository
     */
    private $profileRepository;

    /**
     * ContactController constructor.
     * @param ContactRepository $repository
     * @param ProfileRepository $profileRepository
     * @param ContactPresenter $presenter
     */
    public function __construct(
        ContactRepository $repository,
        ProfileRepository $profileRepository,
        ContactPresenter $presenter
    )
    {
        $this->repository = $repository;
        $this->presenter  = $presenter;
        $this->profileRepository = $profileRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/profiles/{profileId}/contacts",
     *      tags={"Contacts"},
     *      summary="Get profile contacts",
     *      description="Returns profile contacts",
     *      @OA\Parameter(
     *          name="profileId",
     *            in="path",
     *            required=true,
     *            description="Profile ID",
     *          @OA\Schema(
     *             type="integer",
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful response",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/ProfileContactsResponse"
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated response",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthenticatedResponse")
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Unauthorized response",
     *          @OA\JsonContent(ref="#/components/schemas/ForbiddenResponse")
     *      ),
     *      security={
     *          {
     *              "passport": {}
     *          }
     *      }
     * )
     *
     * @param int $profileId
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(int $profileId)
    {
        $profile = $this->getProfile($profileId);

        $this->authorize('view', $profile);

        $this->repository->pushCriteria(new ProfileCriteria($profile));

        $data = $this->repository->paginate();

        return response()->json($this->presenter->present($data));
    }

    /**
     * @OA\POST(
     *      path="/api/profiles/{profileId}/contacts",
     *      tags={"Contacts"},
     *      summary="Create profile contact",
     *      description="Returns profile contact",
     *      @OA\Parameter(
     *          name="profileId",
     *            in="path",
     *            required=true,
     *            description="Profile ID",
     *          @OA\Schema(
     *             type="integer",
     *         )
     *      ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/CreateContactRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful response",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Contact"
     *          )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated response",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthenticatedResponse")
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Unauthorized response",
     *          @OA\JsonContent(ref="#/components/schemas/ForbiddenResponse")
     *      ),
     *      security={
     *          {
     *              "passport": {}
     *          }
     *      }
     * )
     *
     * @param CreateContactRequest $request
     * @param ContactService $contactService
     * @param int $profileId
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function store(
        CreateContactRequest $request,
        ContactService $contactService,
        int $profileId
    )
    {
        $this->authorize('create', [\App\Models\Contact::class, $profileId]);

        $contact = $contactService->store($request, $this->getProfile($profileId));

        return response()->json($this->presenter->present($contact), JsonResponse::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *      path="/api/profiles/{profileId}/contacts/{contactId}",
     *      tags={"Contacts"},
     *      summary="Update profile contact",
     *      description="Update profile contact",
     *      @OA\Parameter(
     *          name="profileId",
     *            in="path",
     *            required=true,
     *            description="Profile ID",
     *          @OA\Schema(
     *             type="integer",
     *         )
     *      ),
     *      @OA\Parameter(
     *          name="contactId",
     *            in="path",
     *            required=true,
     *            description="Contact ID",
     *          @OA\Schema(
     *             type="integer",
     *         )
     *      ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/UpdateContactRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful response",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Contact"
     *          )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated response",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthenticatedResponse")
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Unauthorized response",
     *          @OA\JsonContent(ref="#/components/schemas/ForbiddenResponse")
     *      ),
     *      security={
     *          {
     *              "passport": {}
     *          }
     *      }
     * )
     *
     * @param UpdateContactRequest $request
     * @param ContactService $contactService
     * @param int $profileId
     * @param int $contactId
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(
        UpdateContactRequest $request,
        ContactService $contactService,
        int $profileId,
        int $contactId
    )
    {
        $this->repository->pushCriteria(new ProfileCriteria($this->getProfile($profileId)));

        $contact = $this->repository->find($contactId);

        $this->authorize('update', $contact);

        $updatedContact = $contactService->update($request, $contact);

        return response()->json($this->presenter->present($updatedContact));
    }

    /**
     * @OA\Delete(
     *      path="/api/profiles/{profileId}/contacts/{contactId}",
     *      tags={"Contacts"},
     *      summary="Delete profile contact",
     *      description="Delete profile contact",
     *      @OA\Parameter(
     *          name="profileId",
     *            in="path",
     *            required=true,
     *            description="Profile ID",
     *          @OA\Schema(
     *             type="integer",
     *         )
     *      ),
     *      @OA\Parameter(
     *          name="contactId",
     *            in="path",
     *            required=true,
     *            description="Contact ID",
     *          @OA\Schema(
     *             type="integer",
     *         )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful response",
     *          @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated response",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthenticatedResponse")
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Unauthorized response",
     *          @OA\JsonContent(ref="#/components/schemas/ForbiddenResponse")
     *      ),
     *      security={
     *          {
     *              "passport": {}
     *          }
     *      }
     * )
     *
     * @param ContactService $contactService
     * @param int $profileId
     * @param int $contactId
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(
        ContactService $contactService,
        int $profileId,
        int $contactId
    )
    {
        $this->repository->pushCriteria(new ProfileCriteria($this->getProfile($profileId)));

        $contact = $this->repository->find($contactId);

        $this->authorize('delete', $contact);

        $contactService->delete($contact);

        return response()->json([], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @param $profileId
     * @return Profile
     */
    private function getProfile($profileId): Profile
    {
        return $this->profileRepository->skipCriteria()->find($profileId);
    }
}
