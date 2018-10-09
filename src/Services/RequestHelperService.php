<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Mariusz Madanowski
 */
class RequestHelperService
{
    private $request;

    /**
     * @author Mariusz Madanowski
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @author Mariusz Madanowski
     */
    public function convertJsonStringToArray()
    {
        if ($this->request->getContent()) {
            $data = json_decode($this->request->getContent(), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new BadRequestHttpException('Invalid json body: ' . json_last_error_msg());
            }
            $this->request->request->replace(is_array($data) ? $data : array());
        }
    }
}
