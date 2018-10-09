<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use Doctrine\ORM\EntityNotFoundException;
use App\Entity\Device;
use App\Entity\Flag;
use App\Entity\DeviceFlag;
use App\Entity\PossibleNextFlag;
use App\Services\DeviceService;
use App\Services\FlagService;
use App\Services\RequestHelperService;

/**
 * Device controller.
 *
 * @Route("/api")
 */
class DeviceController extends FOSRestController
{
    /**
     * @var DeviceService
     */
    private $deviceService;

    /**
     * @var FlagService
     */
    private $flagService;

    /**
     * @var RequestHelperService
     */
    private $requestHelperService;

    /**
     * DeviceController constructor.
     * @param DeviceService $deviceService
     * @param FlagService $flagService
     * @param RequestHelperService $requestHelperService
     */
    public function __construct(
        DeviceService $deviceService,
        FlagService $flagService,
        RequestHelperService $requestHelperService
    )
    {
        $this->deviceService = $deviceService;
        $this->flagService = $flagService;
        $this->requestHelperService = $requestHelperService;
    }

    /**
     * @Route("/all", name="all")
     */
    public function all()
    {
        $allFlags = $this->flagService->getAllFlags();

        $allDevices = $this->deviceService->getAllDevices();

        $allDeviceFlags = $this->deviceService->getAllDeviceFlags();

        $allPossibleNextFlags = $this->flagService->getAllPossibleNextFlags();

        dump($allFlags, $allDevices, $allDeviceFlags, $allPossibleNextFlags);
        die();
    }

    /**
     * Create Device.
     *
     * @FOSRest\Post("/device")
     * @param ParamFetcherInterface $paramFetcher
     * @RequestParam(name="serialNumber", default="", strict=true)
     * @RequestParam(name="flagName", default="", strict=true)
     *
     * @return array
     */
    public function postDeviceAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $this->requestHelperService->convertJsonStringToArray();

        $serialNumber = $paramFetcher->get('serialNumber');
        $flagName = $paramFetcher->get('flagName');

        $flag = $this->flagService->getFlagByName($flagName);
        if (!$flag) {
            return new JsonResponse('Flag with name '.$flagName.' does not exist!', Response::HTTP_NOT_FOUND);
        }

        $device = $this->deviceService->getDeviceBySerialNumber($serialNumber);

        if (!$this->flagService->isFirstFlag($flag) && !$device) {
            return new JsonResponse('Devive with serial number '.$serialNumber.' does not exist!', Response::HTTP_NOT_FOUND);
        } elseif ($this->flagService->isFirstFlag($flag) && !$device) {
            $device = $this->deviceService->addDevice($serialNumber);
            $deviceFlag = $this->deviceService->addDeviceFlag(
                $device,
                $flag,
                $request->getClientIp()
            );

            return new JsonResponse($deviceFlag, Response::HTTP_CREATED);
        }

        $currentDeviceFlag = $this->deviceService->getCurrentDeviceFlag($device);

        if ($this->flagService->isFlagCanBeSet($flag, $currentDeviceFlag)) {
            $deviceFlag = $this->deviceService->addDeviceFlag(
                $device,
                $flag,
                $request->getClientIp()
            );

            return new JsonResponse($deviceFlag, Response::HTTP_CREATED);
        } else {
            return new JsonResponse('This flag is not allowed for this device.', Response::HTTP_NOT_ACCEPTABLE);
        }
    }
}
