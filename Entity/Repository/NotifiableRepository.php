<?php

namespace Mgilet\NotificationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Mgilet\NotificationBundle\Entity\NotifiableEntity;
use Mgilet\NotificationBundle\NotifiableInterface;

class NotifiableRepository extends EntityRepository
{

    /**
     * @param NotifiableEntity $notifiableEntity
     * @param array            $mapping
     *
     * @return NotifiableInterface|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findNotifiableInterface(NotifiableEntity $notifiableEntity, array $mapping)
    {
        // create the querybuilder from the entity
        $qb = $this->createQueryBuilder('n')->select('e')->from($notifiableEntity->getClass(), 'e');

        // map the identifier(s) to the value(s)
        $identifiers = explode('-', $notifiableEntity->getIdentifier());
        foreach ($mapping as $key => $identifier) {
            $qb->andWhere(sprintf('e.%s = :%s', $identifier, $identifier));
            $qb->setParameter($identifier, $identifiers[$key]);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param           $id
     * @param bool|null $seen
     *
     * @return NotifiableInterface[]
     */
    public function findAllByNotification($id, $seen = null)
    {
        $qb = $this
            ->createQueryBuilder('notifiable')
            ->join('notifiable.notifiableNotifications', 'nn')
            ->join('nn.notification', 'notification')
            ->where('notification.id = :notification_id')
            ->setParameter('notification_id', $id)
        ;

        if ($seen !== null) {
            $whereSeen = $seen ? 1 : 0;
            $qb
                ->andWhere('nn.seen = :seen')
                ->setParameter('seen', $whereSeen)
            ;
        }

        return $qb->getQuery()->getResult();
    }
}
