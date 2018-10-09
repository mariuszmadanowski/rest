<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @author Mariusz Madanowski
 */
class HelperService
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

    /**
     * @author Mariusz Madanowski
     */
    public function prepareObject($object)
    {
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(array(
                'deviceFlags',
                'childFlags',
                'parentFlags',
                'flags',
                'timezone'
            ));
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $encoder = new JsonEncoder();
        $serializer = new Serializer(array($normalizer), array($encoder));

        return $serializer->serialize($object, 'json');
    }
}
