<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\DeviceFlag;
use Doctrine\ORM\EntityManagerInterface;

/**
 * DeviceFlagRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * @author Mariusz Madanowski
 */
class DeviceFlagRepository extends ServiceEntityRepository
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
        parent::__construct($managerRegistry, DeviceFlag::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param DeviceFlag $deviceFlag
     */
    public function save(DeviceFlag $deviceFlag): void
    {
        $this->entityManager->persist($deviceFlag);
        $this->entityManager->flush();
    }
}
