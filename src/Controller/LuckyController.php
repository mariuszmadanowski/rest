<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityNotFoundException;
use App\Entity\Device;
use App\Entity\Flag;
use App\Entity\DeviceFlag;

/**
 * Device controller.
 *
 * @Route("/api")
 */
class LuckyController extends Controller
{
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
         * Create Article.
         *
         * @Route("/device/{serialNumber}/{flagName}", name="device")
         *
         * @return array
         */
        public function postDeviceAction(string $serialNumber, string $flagName, Request $request)
        {
            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Flag::class);
            $flag = $repository->findOneBy(
                array(
                    'name' => $flagName,
                )
            );
            if (!$flag) {
                throw new EntityNotFoundException('Flag with name '.$flagName.' does not exist!');
            }

            $repository2 = $this->getDoctrine()->getRepository(Device::class);
            $device = $repository2->findOneBy(
                array(
                    'serialNumber' => $serialNumber,
                )
            );

            if ($flag->getId() != 1 && !$device) {
                throw new EntityNotFoundException('Devive with serial number '.$serialNumber.' does not exist!');
            } elseif ($flag->getId() == 1 && !count($device)) {
                $device = new Device();
                $device->setSerialNumber($serialNumber);
                $device->setCreated(new \DateTime('now'));
                $em->persist($device);
                $em->flush();
            }
dump($device);die();
            $repository3 = $this->getDoctrine()->getRepository(DeviceFlag::class);
            $lastDeviceFlag = $repository3->findOneBy(
                array(
                    'device' => $device,
                ),
                array(
                    'created' => 'DESC',
                )
            );

            $possibleNextFlagsIds = [];
            foreach ($lastDeviceFlag->getFlag()->getChildFlags() as $object) {
                $possibleNextFlagsIds[] = $object->getChildFlag()->getId();
            }

            if (in_array($flag->getId(), $possibleNextFlagsIds)) {
                $deviceFlag = new DeviceFlag();
                $deviceFlag->setDevice($device);
                $deviceFlag->setFlag($flag);
                $deviceFlag->setCreated(new \DateTime('now'));
                $deviceFlag->setIp($request->getClientIp());
                $em->persist($deviceFlag);
                $em->flush();
                dump($deviceFlag, Response::HTTP_CREATED, []);
            } else {
                // ta flaga jest zabroniona dla tego urządzenia
                dump('This flag is not allowed for this device.', Response::HTTP_NOT_ACCEPTABLE, []);
            }
        }
}
