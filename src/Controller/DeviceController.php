<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController;
use Doctrine\ORM\EntityNotFoundException;
use App\Entity\Device;
use App\Entity\Flag;
use App\Entity\DeviceFlag;
use App\Entity\PossibleNextFlag;
use App\Services\DeviceService;
use App\Services\FlagService;

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
     * DeviceController constructor.
     * @param DeviceService $deviceService
     * @param FlagService $flagService
     */
    public function __construct(
        DeviceService $deviceService,
        FlagService $flagService
    )
    {
        $this->deviceService = $deviceService;
        $this->flagService = $flagService;
    }

    /**
     * @Route("/lucky/number/{max}", name="app_lucky_number")
     */
    public function number($max)
    {
        $number = random_int(0, $max);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
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
     * Create Article.
     *
     * @Route("/device/{serialNumber}/{flagName}", name="device")
     *
     * @return View
     */
    public function postDeviceAction(string $serialNumber, string $flagName, Request $request)
    {
        $flag = $this->flagService->getFlagByName($flagName);
        if (!$flag) {
            //throw new EntityNotFoundException('Flag with name '.$flagName.' does not exist!');
            dump('Flag with name '.$flagName.' does not exist!', Response::HTTP_NOT_FOUND, []);
            die();
        }

        $device = $this->deviceService->getDeviceBySerialNumber($serialNumber);

        if ($flag->getId() != 1 && !$device) {
            //throw new EntityNotFoundException('Devive with serial number '.$serialNumber.' does not exist!');
            dump('Devive with serial number '.$serialNumber.' does not exist!', Response::HTTP_NOT_FOUND, []);
            die();
        } elseif ($flag->getId() == 1 && !$device) {
            $device = $this->deviceService->addDevice($serialNumber);
            $deviceFlag = $this->deviceService->addDeviceFlag(
                $device,
                $flag,
                $request->getClientIp()
            );

            dump($deviceFlag, Response::HTTP_CREATED, []);die();
        }

        $currentDeviceFlag = $this->deviceService->getCurrentDeviceFlag($device);
        $possibleNextFlagsIds = $this->flagService->getPossibleNextFlagsIds($currentDeviceFlag);

        if (in_array($flag->getId(), $possibleNextFlagsIds)) {
            $deviceFlag = $this->deviceService->addDeviceFlag(
                $device,
                $flag,
                $request->getClientIp()
            );

            $view = View::create($deviceFlag, Response::HTTP_CREATED , []);
            return $this->handleView($view);
        } else {
            // ta flaga jest zabroniona dla tego urządzenia
            dump('This flag is not allowed for this device.', Response::HTTP_NOT_ACCEPTABLE, []);die();
        }
    }
}
