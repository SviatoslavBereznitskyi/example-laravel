<?php

namespace App\Presenters;

use App\Transformers\ContactTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ContactPresenter.
 *
 * @package namespace App\Presenters;
 */
class ContactPresenter extends FractalPresenter
{
    /**
     * @var ContactTransformer
     */
    private $transformer;

    public function __construct(ContactTransformer $transformer)
    {
        $this->transformer = $transformer;

        parent::__construct();
    }

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return $this->transformer;
    }
}
