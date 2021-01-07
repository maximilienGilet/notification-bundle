<?php

namespace Mgilet\NotificationBundle\Manager;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityNotFoundException;
use Mgilet\NotificationBundle\Entity\NotifiableEntity;
use Mgilet\NotificationBundle\Entity\NotifiableNotification;
use Mgilet\NotificationBundle\Entity\Notification;
use Mgilet\NotificationBundle\Entity\NotificationInterface;
use Mgilet\NotificationBundle\Event\NotificationEvent;
use Mgilet\NotificationBundle\MgiletNotificationEvents;
use Mgilet\NotificationBundle\NotifiableDiscovery;
use Mgilet\NotificationBundle\NotifiableInterface;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class NotificationManager
 * Manager for notifications
 * @package Mgilet\NotificationBundle\Manager
 */
class NotificationManager
{
    protected $container;
    protected $discovery;
    protected $om;
    protected $dispatcher;
    protected $notifiableRepository;
    protected $notificationRepository;
    protected $notifiableNotificationRepository;

    /**
     * NotificationManager constructor.
     *
     * @param Container           $container
     * @param NotifiableDiscovery $discovery
     */
    public function __construct(Container $container, NotifiableDiscovery $discovery)
    {
        $this->container = $container;
        $this->discovery = $discovery;
        $this->om = $container->get('doctrine.orm.entity_manager');
        $this->dispatcher = $container->get('event_dispatcher');
        $this->notifiableRepository = $this->om->getRepository('MgiletNotificationBundle:NotifiableEntity');
        $this->notificationRepository = $this->om->getRepository('MgiletNotificationBundle:Notification');
        $this->notifiableNotificationRepository = $this->om->getRepository('MgiletNotificationBundle:NotifiableNotification');
    }

    /**
     * Returns a list of available workers.
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getDiscoveryNotifiables()
    {
        return $this->discovery->getNotifiables();
    }

    /**
     * Returns one notifiable by name
     *
     * @param $name
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getNotifiable($name)
    {
        $notifiables = $this->getDiscoveryNotifiables();
        if (isset($notifiables[$name])) {
            return $notifiables[$name];
        }

        throw new \RuntimeException('Notifiable not found.');
    }

    /**
     * Get the name of the notifiable
     *
     * @param NotifiableInterface $notifiable
     *
     * @return string|null
     */
    public function getNotifiableName(NotifiableInterface $notifiable)
    {
        return $this->discovery->getNotifiableName($notifiable);
    }

    /**
     * @param NotifiableInterface $notifiable
     *
     * @return array
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getNotifiableIdentifier(NotifiableInterface $notifiable)
    {
        $name = $this->getNotifiableName($notifiable);

        return $this->getNotifiable($name)['identifiers'];
    }

    /**
     * Get the identifier mapping for a NotifiableEntity
     *
     * @param NotifiableEntity $notifiableEntity
     *
     * @return array
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getNotifiableEntityIdentifiers(NotifiableEntity $notifiableEntity)
    {
        $discoveryNotifiables = $this->getDiscoveryNotifiables();
        foreach ($discoveryNotifiables as $notifiable) {
            if ($notifiable['class'] === $notifiableEntity->getClass()) {
                return $notifiable['identifiers'];
            }
        }
        throw new \RuntimeException('Unable to get the NotifiableEntity identifiers. This could be an Entity mapping issue');
    }

    /**
     * Generates the identifier value to store a NotifiableEntity
     *
     * @param NotifiableInterface $notifiable
     *
     * @return string
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function generateIdentifier(NotifiableInterface $notifiable)
    {
        $Notifiableidentifiers = $this->getNotifiableIdentifier($notifiable);
        $identifierValues = array();
        foreach ($Notifiableidentifiers as $identifier) {
            $method = sprintf('get%s', ucfirst($identifier));
            $identifierValues[] = $notifiable->$method();
        }

        return implode('-', $identifierValues);
    }

    /**
     * Get a NotifiableEntity form a NotifiableInterface
     *
     * @param NotifiableInterface $notifiable
     *
     * @return NotifiableEntity
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getNotifiableEntity(NotifiableInterface $notifiable)
    {
        $identifier = $this->generateIdentifier($notifiable);
        $class = ClassUtils::getRealClass(get_class($notifiable));
        $entity = $this->notifiableRepository->findOneBy(array(
            'identifier' => $identifier,
            'class' => $class
        ));

        if (!$entity) {
            $entity = new NotifiableEntity($identifier, $class);
            $this->om->persist($entity);
            $this->om->flush();
        }

        return $entity;
    }

    /**
     * @param NotifiableEntity $notifiableEntity
     *
     * @return NotifiableInterface
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getNotifiableInterface(NotifiableEntity $notifiableEntity)
    {
        return $this->notifiableRepository->findNotifiableInterface(
            $notifiableEntity,
            $this->getNotifiableEntityIdentifiers($notifiableEntity)
        );
    }

    /**
     * @param $id
     *
     * @return NotifiableEntity|null
     */
    public function getNotifiableEntityById($id)
    {
        return $this->notifiableRepository->findOneById($id);
    }

    /**
     * @param NotifiableInterface   $notifiable
     * @param NotificationInterface $notification
     *
     * @return NotifiableNotification|null
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function getNotifiableNotification(NotifiableInterface $notifiable, NotificationInterface $notification)
    {
        return $this->notifiableNotificationRepository->findOne(
            $notification->getId(),
            $this->getNotifiableEntity($notifiable)
        );
    }

    /**
     * Avoid code duplication
     *
     * @param $flush
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function flush($flush)
    {
        if ($flush) {
            $this->om->flush();
        }
    }

    /**
     * Get all notifications
     *
     * @param string $order
     *
     * @return Notification[]
     */
    public function getAll($order = 'DESC')
    {
        return $this->notificationRepository->createQueryBuilder('n')->orderBy('n.id', $order)->getQuery()->getResult();
    }

    /**
     * @param NotifiableInterface $notifiable
     * @param string              $order
     * @param null|int            $limit
     * @param null|int            $offset
     *
     * @return array
     */
    public function getNotifications(NotifiableInterface $notifiable, $order = 'DESC', $limit = null,
                                     $offset = null)
    {
        return $this->notifiableNotificationRepository->findAllByNotifiable(
            $this->generateIdentifier($notifiable),
            ClassUtils::getRealClass(get_class($notifiable)),
            null,
            $order,
            $limit,
            $offset
        );
    }

    /**
     * @param NotifiableInterface $notifiable
     * @param string              $order
     * @param null|int            $limit
     * @param null|int            $offset
     *
     * @return array
     */
    public function getUnseenNotifications(NotifiableInterface $notifiable, $order = 'DESC', $limit = null,
                                           $offset = null)
    {
        return $this->notifiableNotificationRepository->findAllByNotifiable(
            $this->generateIdentifier($notifiable),
            ClassUtils::getRealClass(get_class($notifiable)),
            false,
            $order,
            $limit,
            $offset
        );
    }

    /**
     * @param NotifiableInterface $notifiable
     * @param string              $order
     * @param null|int            $limit
     * @param null|int            $offset
     *
     * @return array
     */
    public function getSeenNotifications(NotifiableInterface $notifiable, $order = 'DESC', $limit = null,
                                         $offset = null)
    {
        return $this->notifiableNotificationRepository->findAllByNotifiable(
            $this->generateIdentifier($notifiable),
            ClassUtils::getRealClass(get_class($notifiable)),
            true,
            $order,
            $limit,
            $offset
        );
    }


    /**
     * Get one notification by id
     *
     * @param $id
     *
     * @return Notification
     */
    public function getNotification($id)
    {
        return $this->notificationRepository->findOneById($id);
    }

    /**
     * @param string $subject
     * @param string $message
     * @param string $link
     *
     * @return Notification
     */
    public function createNotification($subject, $message = null, $link = null)
    {
        $notificationClass = $this->container->getParameter('mgilet_notification.notification_class');
        $notification = new $notificationClass();
        $notification
            ->setSubject($subject)
            ->setMessage($message)
            ->setLink($link);

        $event = new NotificationEvent($notification);
        $this->dispatcher->dispatch(MgiletNotificationEvents::CREATED, $event);

        return $notification;
    }

    /**
     * Add a Notification to a list of NotifiableInterface entities
     *
     * @param NotifiableInterface[] $notifiables
     * @param NotificationInterface $notification
     * @param bool                  $flush
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function addNotification($notifiables, NotificationInterface $notification, $flush = false)
    {
        foreach ($notifiables as $notifiable) {
            $entity = $this->getNotifiableEntity($notifiable);

            $notifiableNotification = new NotifiableNotification();
            $entity->addNotifiableNotification($notifiableNotification);
            $notification->addNotifiableNotification($notifiableNotification);

            $event = new NotificationEvent($notification, $notifiable);
            $this->dispatcher->dispatch(MgiletNotificationEvents::ASSIGNED, $event);
        }

        $this->flush($flush);
    }

    /**
     * Deletes the link between a Notifiable and a Notification
     *
     * @param array                 $notifiables
     * @param NotificationInterface $notification
     * @param bool                  $flush
     *
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function removeNotification(array $notifiables, NotificationInterface $notification, $flush = false)
    {
        $repo = $this->om->getRepository('MgiletNotificationBundle:NotifiableNotification');
        foreach ($notifiables as $notifiable) {
            $repo->createQueryBuilder('nn')
                ->delete()
                ->where('nn.notifiableEntity = :entity')
                ->andWhere('nn.notification = :notification')
                ->setParameter('entity', $this->getNotifiableEntity($notifiable))
                ->setParameter('notification', $notification)
                ->getQuery()
                ->execute();

            $event = new NotificationEvent($notification, $notifiable);
            $this->dispatcher->dispatch(MgiletNotificationEvents::REMOVED, $event);
            $this->flush($flush);
        }

        $this->deleteNotification($notification);
        $this->flush($flush);
    }

    /**
     * @param NotificationInterface $notification
     *
     * @param bool         $flush
     *
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteNotification(NotificationInterface $notification, $flush = false)
    {
        $this->om->remove($notification);
        $this->flush($flush);

        $event = new NotificationEvent($notification);
        $this->dispatcher->dispatch(MgiletNotificationEvents::DELETED, $event);
    }

    /**
     * @param NotifiableInterface   $notifiable
     * @param NotificationInterface $notification
     * @param bool                  $flush
     *
     * @throws EntityNotFoundException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function markAsSeen(NotifiableInterface $notifiable, NotificationInterface $notification, $flush = false)
    {
        $nn = $this->getNotifiableNotification($notifiable, $notification);
        if ($nn) {
            $nn->setSeen(true);
            $event = new NotificationEvent($notification, $notifiable);
            $this->dispatcher->dispatch(MgiletNotificationEvents::SEEN, $event);
            $this->flush($flush);
        } else {
            throw new EntityNotFoundException('The link between the notifiable and the notification has not been found');
        }
    }

    /**
     * @param NotifiableInterface   $notifiable
     * @param NotificationInterface $notification
     * @param bool                  $flush
     *
     * @throws EntityNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function markAsUnseen(NotifiableInterface $notifiable, NotificationInterface $notification, $flush = false)
    {
        $nn = $this->getNotifiableNotification($notifiable, $notification);
        if ($nn) {
            $nn->setSeen(false);
            $event = new NotificationEvent($notification, $notifiable);
            $this->dispatcher->dispatch(MgiletNotificationEvents::UNSEEN, $event);
            $this->flush($flush);
        } else {
            throw new EntityNotFoundException('The link between the notifiable and the notification has not been found');
        }
    }

    /**
     * @param NotifiableInterface $notifiable
     * @param bool                $flush
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function markAllAsSeen(NotifiableInterface $notifiable, $flush = false)
    {
        $nns = $this->notifiableNotificationRepository->findAllForNotifiable(
            $this->generateIdentifier($notifiable),
            ClassUtils::getRealClass(get_class($notifiable))
        );
        foreach ($nns as $nn) {
            $nn->setSeen(true);
            $event = new NotificationEvent($nn->getNotification(), $notifiable);
            $this->dispatcher->dispatch(MgiletNotificationEvents::SEEN, $event);
        }
        $this->flush($flush);
    }

    /**
     * @param NotifiableInterface   $notifiable
     * @param NotificationInterface $notification
     *
     * @return bool
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws EntityNotFoundException
     */
    public function isSeen(NotifiableInterface $notifiable, NotificationInterface $notification)
    {
        $nn = $this->getNotifiableNotification($notifiable, $notification);
        if ($nn) {
            return $nn->isSeen();
        }

        throw new EntityNotFoundException('The link between the notifiable and the notification has not been found');
    }

    /**
     * @param NotifiableInterface $notifiable
     *
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getNotificationCount(NotifiableInterface $notifiable)
    {
        return $this->notifiableNotificationRepository->getNotificationCount(
            $this->generateIdentifier($notifiable),
            ClassUtils::getRealClass(get_class($notifiable))
        );
    }

    /**
     * @param NotifiableInterface $notifiable
     *
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getUnseenNotificationCount(NotifiableInterface $notifiable)
    {
        return $this->notifiableNotificationRepository->getNotificationCount(
            $this->generateIdentifier($notifiable),
            ClassUtils::getRealClass(get_class($notifiable)),
            false
        );
    }

    /**
     * @param NotifiableInterface $notifiable
     *
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getSeenNotificationCount(NotifiableInterface $notifiable)
    {
        return $this->notifiableNotificationRepository->getNotificationCount(
            $this->generateIdentifier($notifiable),
            ClassUtils::getRealClass(get_class($notifiable)),
            true
        );
    }

    /**
     * @param NotificationInterface $notification
     * @param \DateTime             $dateTime
     * @param bool                  $flush
     *
     * @return NotificationInterface
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setDate(NotificationInterface $notification, \DateTime $dateTime, $flush = false)
    {
        $notification->setDate($dateTime);
        $this->flush($flush);

        $event = new NotificationEvent($notification);
        $this->dispatcher->dispatch(MgiletNotificationEvents::MODIFIED, $event);

        return $notification;
    }

    /**
     * @param NotificationInterface $notification
     * @param string       $subject
     * @param bool         $flush
     *
     * @return NotificationInterface
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setSubject(NotificationInterface $notification, $subject, $flush = false)
    {
        $notification->setSubject($subject);
        $this->flush($flush);

        $event = new NotificationEvent($notification);
        $this->dispatcher->dispatch(MgiletNotificationEvents::MODIFIED, $event);

        return $notification;
    }

    /**
     * @param NotificationInterface $notification
     * @param string       $message
     * @param bool         $flush
     *
     * @return NotificationInterface
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setMessage(NotificationInterface $notification, $message, $flush = false)
    {
        $notification->setMessage($message);
        $this->flush($flush);

        $event = new NotificationEvent($notification);
        $this->dispatcher->dispatch(MgiletNotificationEvents::MODIFIED, $event);

        return $notification;
    }

    /**
     * @param NotificationInterface $notification
     * @param string       $link
     * @param bool         $flush
     *
     * @return NotificationInterface
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setLink(NotificationInterface $notification, $link, $flush = false)
    {
        $notification->setLink($link);
        $this->flush($flush);

        $event = new NotificationEvent($notification);
        $this->dispatcher->dispatch(MgiletNotificationEvents::MODIFIED, $event);

        return $notification;
    }

    /**
     * @param NotificationInterface $notification
     *
     * @return NotifiableInterface[]
     */
    public function getNotifiables(NotificationInterface $notification)
    {
        return $this->notifiableRepository->findAllByNotification($notification);
    }

    /**
     * @param NotificationInterface $notification
     *
     * @return NotifiableInterface[]
     */
    public function getUnseenNotifiables(NotificationInterface $notification)
    {
        return $this->notifiableRepository->findAllByNotification($notification, true);
    }

    /**
     * @param NotificationInterface $notification
     *
     * @return NotifiableInterface[]
     */
    public function getSeenNotifiables(NotificationInterface $notification)
    {
        return $this->notifiableRepository->findAllByNotification($notification, false);
    }
}
