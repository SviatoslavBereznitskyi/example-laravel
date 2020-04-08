<?php

namespace App\Transformers;

use App\Models\Contact;
use App\Models\Phone;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class ContactTransformer.
 *
 * @package namespace App\Transformers;
 */
class ContactTransformer extends AbstractTransformer
{

    /**
     * @var RelationTransformer
     */
    private $relationTransformer;

    /**
     * @var PhoneTransformer
     */
    private $phoneTransformer;

    public function __construct(
        NormalizerInterface $serializer,
        RelationTransformer $relationTransformer,
        PhoneTransformer $phoneTransformer
    )
    {
        parent::__construct($serializer);

        $this->relationTransformer = $relationTransformer;
        $this->phoneTransformer    = $phoneTransformer;
    }

    /**
     * Transform the Contact entity.
     *
     * @param \App\Models\Contact $model
     *
     * @return array
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function transform(Contact $model)
    {
        $dto = new \App\DTO\Model\Contact();

        $dto->id               = $model->getKey();
        $dto->firstName        = $model->getFirstName();
        $dto->middleName       = $model->getMiddleName();
        $dto->lastName         = $model->getLastName();
        $dto->relationId       = $model->getRelationId();
        $dto->profileId        = $model->getProfileId();
        $dto->email            = $model->getEmail();
        $dto->isEmergencyEmail = $model->isEmergencyEmail();
        $dto->createdAt        = $model->getCreatedAt()->toIso8601ZuluString();
        $dto->updatedAt        = $model->getUpdatedAt()->toIso8601ZuluString();

        if ($model->relation) {
            $dto->relation = $this->relationTransformer->transform($model->relation);
        }

        if ($model->phones) {
            $dto->phones = $model->phones->map(function (Phone $phone) {
                return $this->phoneTransformer->transform($phone);
            });
        }

        return $this->serializer->normalize($dto);
    }
}
