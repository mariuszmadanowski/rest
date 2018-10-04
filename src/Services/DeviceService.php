<?php

namespace App\Services;

use App\Entity\Device;
use App\Repository\DeviceRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Mariusz Madanowski
 */
class DeviceService
{
    private $deviceRepository;

    /**
     * @author Mariusz Madanowski
     */
    public function __construct(DeviceRepository $deviceRepository)
    {
        $this->deviceRepository = $deviceRepository;
    }

    public function getAllDevices(): ?array
    {
        return $this->deviceRepository->findAll();
    }

    /**
     * @author Mariusz Madanowski
     */
    // public function removePosition($id)
    // {
    //     $repository = $this->entityManager->getRepository($this->instanceClassName);
	// 	$dictionaryInstance = $repository->findOneBy(array('id' => $id));
    //
    //     if (is_null($dictionaryInstance)) {
    //         throw new NotFoundHttpException('Nie istnieje taka pozycja.');
    //     }
    //
    //     if (strpos($this->instanceClassName, 'Instance')) {
    //         $removedOrder = $dictionaryInstance->getOrder();
    //         $dictionaryInstance->setOrder(0);
    //     }
    //
    //     $dictionaryInstance->setVisibility(false);
    //     $dictionaryInstance->setRemoved(new \DateTime('now', new \DateTimeZone('Europe/Warsaw')));
    //     $this->entityManager->persist($dictionaryInstance);
    //     $this->entityManager->flush();
    //
    //     if (strpos($this->instanceClassName, 'Instance')) {
    //         $dictionaryInstances = $repository->findBy(array('visibility' => true));
    //         foreach ($dictionaryInstances as $dictionaryInstance) {
    //             if (($order = $dictionaryInstance->getOrder()) > $removedOrder) {
    //                 $order--;
    //                 $dictionaryInstance->setOrder($order);
    //                 $this->entityManager->persist($dictionaryInstance);
    //                 $this->entityManager->flush();
    //             }
    //         }
    //     }
    //
    //     $this->session->getFlashBag()->add(
    //         'success',
    //         'Usunięto.'
    //     );
    //
    //     return new RedirectResponse(
    //         $this->router->generate($this->dictionaryPath, array(
    //             'name' => $this->instanceName
    //         ))
    //     );
    // }

}
