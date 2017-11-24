<?php

namespace Mgilet\NotificationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository
{
    /**
     * @param           $identifier
     * @param           $class
     * @param bool|null $seen
     *
     * @return array
     */
    public function findAllByNotifiable($identifier, $class, $seen = null)
    {
        $qb = $this->findAllByNotifiableQb($identifier, $class);

        if ($seen !== null) {
            $whereSeen = $seen ? 1 : 0;
            $qb
                ->andWhere('notifiable_notifications.seen = :seen')
                ->setParameter('seen', $whereSeen)
            ;
        }
        return $qb->getQuery()->getResult();
    }

    /**
     * @param $identifier
     * @param $class
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllByNotifiableQb($identifier, $class)
    {
        return $this->createQueryBuilder('notification')
            ->addSelect('notifiable_notifications.seen')
            ->join('notification.notifiableNotifications', 'notifiable_notifications')
            ->join('notifiable_notifications.notifiableEntity', 'notifiable_entity')
            ->where('notifiable_entity.identifier = :identifier')
            ->andWhere('notifiable_entity.class = :class')
            ->setParameter('identifier', $identifier)
            ->setParameter('class', $class)
        ;
    }
}
