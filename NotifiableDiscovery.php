<?php

namespace Mgilet\NotificationBundle;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use Metadata\ClassMetadata;

class NotifiableDiscovery
{
    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var array
     */
    private $notifiables = [];


    /**
     * WorkerDiscovery constructor.
     *
     * @param EntityManager $em
     * @param Reader        $annotationReader
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(EntityManager $em, Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
        $this->em = $em;
        $this->discoverNotifiables();
    }

    /**
     * Returns all the workers
     * @throws \InvalidArgumentException
     */
    public function getNotifiables()
    {
        return $this->notifiables;
    }

    /**
     * @param NotifiableInterface $notifiable
     *
     * @return string|null
     */
    public function getNotifiableName(NotifiableInterface $notifiable)
    {
        // fixes the case when the notifiable is a proxy
        $class = ClassUtils::getRealClass(get_class($notifiable));
        $annotation = $this->annotationReader->getClassAnnotation(new \ReflectionClass($class), 'Mgilet\NotificationBundle\Annotation\Notifiable');
        if ($annotation) {
            return $annotation->getName();
        }

        return null;
    }

    /**
     * Discovers workers
     * @throws \InvalidArgumentException
     */
    private function discoverNotifiables()
    {
        /** @var ClassMetadata[] $entities */
        $entities = $this->em->getMetadataFactory()->getAllMetadata();
        foreach ($entities as $entity) {
            $class = $entity->name;
            $annotation = $this->annotationReader->getClassAnnotation(new \ReflectionClass($class), 'Mgilet\NotificationBundle\Annotation\Notifiable');
            if ($annotation) {
                $this->notifiables[$annotation->getName()] = [
                    'class' => $entity->name,
                    'annotation' => $annotation,
                    'identifiers' => $entity->getIdentifier()
                ];
            }
        }
    }
}
