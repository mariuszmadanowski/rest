<?php

namespace App\Services;

use App\Entity\Flag;
use App\Entity\PossibleNextFlag;
use App\Entity\DeviceFlag;
use App\Repository\FlagRepository;
use App\Repository\PossibleNextFlagRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Mariusz Madanowski
 */
class FlagService
{
    private $flagRepository;
    private $possibleNextFlagRepository;

    /**
     * @author Mariusz Madanowski
     */
    public function __construct(
        FlagRepository $flagRepository,
        PossibleNextFlagRepository $possibleNextFlagRepository
    )
    {
        $this->flagRepository = $flagRepository;
        $this->possibleNextFlagRepository = $possibleNextFlagRepository;
    }

    /**
     * @author Mariusz Madanowski
     */
    public function getAllFlags(): ?array
    {
        return $this->flagRepository->findAll();
    }

    /**
     * @author Mariusz Madanowski
     */
    public function getAllPossibleNextFlags(): ?array
    {
        return $this->possibleNextFlagRepository->findAll();
    }

    /**
     * @author Mariusz Madanowski
     */
    public function getFlagByName(string $flagName): ?Flag
    {
        return $this->flagRepository->findOneBy(
            array(
                'name' => $flagName
            )
        );
    }

    /**
     * @author Mariusz Madanowski
     */
    private function getPossibleNextFlagsIds(DeviceFlag $currentDeviceFlag): array
    {
        $possibleNextFlagsIds = [];
        foreach ($currentDeviceFlag->getFlag()->getChildFlags() as $object) {
            $possibleNextFlagsIds[] = $object->getChildFlag()->getId();
        }
        return $possibleNextFlagsIds;
    }

    /**
     * @author Mariusz Madanowski
     */
    public function isFirstFlag(Flag $flag): bool
    {
        return ($flag->getId() == 1);
    }

    /**
     * @author Mariusz Madanowski
     */
    public function isFlagCanBeSet(Flag $flag, DeviceFlag $currentDeviceFlag): bool
    {
        $possibleNextFlagsIds = $this->getPossibleNextFlagsIds($currentDeviceFlag);
        return (in_array($flag->getId(), $possibleNextFlagsIds));
    }
}
