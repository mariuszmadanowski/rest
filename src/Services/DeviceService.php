<?php

namespace App\Services;

use App\Entity\Device;
use App\Entity\DeviceFlag;
use App\Entity\Flag;
use App\Repository\DeviceRepository;
use App\Repository\DeviceFlagRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Mariusz Madanowski
 */
class DeviceService
{
    private $deviceRepository;
    private $deviceFlagRepository;

    /**
     * @author Mariusz Madanowski
     */
    public function __construct(
        DeviceRepository $deviceRepository,
        DeviceFlagRepository $deviceFlagRepository
    )
    {
        $this->deviceRepository = $deviceRepository;
        $this->deviceFlagRepository = $deviceFlagRepository;
    }

    /**
     * @author Mariusz Madanowski
     */
    public function getAllDevices(): ?array
    {
        return $this->deviceRepository->findAll();
    }

    /**
     * @author Mariusz Madanowski
     */
    public function getAllDeviceFlags(): ?array
    {
        return $this->deviceFlagRepository->findAll();
    }

    /**
     * @author Mariusz Madanowski
     */
    public function getDeviceBySerialNumber(string $serialNumber): ?Device
    {
        return $this->deviceRepository->findOneBy(
            array(
                'serialNumber' => $serialNumber
            )
        );
    }

    /**
     * @author Mariusz Madanowski
     */
    public function getCurrentDeviceFlag(Device $device): ?DeviceFlag
    {
        return $this->deviceFlagRepository->findOneBy(
            array(
                'device' => $device,
            ),
            array(
                'created' => 'DESC',
            )
        );
    }

    /**
     * @author Mariusz Madanowski
     */
    public function addDevice(string $serialNumber): ?Device
    {
        $device = new Device();
        $device->setSerialNumber($serialNumber);
        $device->setCreated(new \DateTime('now'));
        $this->deviceRepository->save($device);

        return $device;
    }

    /**
     * @author Mariusz Madanowski
     */
    public function addDeviceFlag(Device $device, Flag $flag, string $ip): ?DeviceFlag
    {
        $deviceFlag = new DeviceFlag();
        $deviceFlag->setDevice($device);
        $deviceFlag->setFlag($flag);
        $deviceFlag->setCreated(new \DateTime('now'));
        $deviceFlag->setIp($ip);
        $this->deviceFlagRepository->save($deviceFlag);
        $this->entityManager->persist($deviceFlag);
        $this->entityManager->flush();

        return $deviceFlag;
    }
}
