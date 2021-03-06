<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\Device;
use Doctrine\ORM\EntityManagerInterface;

/**
 * DeviceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * @author Mariusz Madanowski
 */
class DeviceRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Device::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param Device $device
     */
    public function save(Device $device): void
    {
        $this->entityManager->persist($device);
        $this->entityManager->flush();
    }
}
