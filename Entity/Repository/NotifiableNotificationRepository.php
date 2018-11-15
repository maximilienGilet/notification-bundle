<?php
/**
 * Created by PhpStorm.
 * User: maximilien
 * Date: 12/10/17
 * Time: 09:24
 */

namespace Mgilet\NotificationBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Mgilet\NotificationBundle\Entity\NotifiableNotification;

class NotifiableNotificationRepository extends EntityRepository
{

    /**
     * @param $notification_id
     * @param $notifiable_id
     *
     * @return NotifiableNotification|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOne($notification_id, $notifiable_id)
    {
        return $this->createQueryBuilder('nn')
            ->join('nn.notification', 'n')
            ->join('nn.notifiableEntity', 'ne')
            ->where('n.id = :notification_id')
            ->andWhere('ne.id = :notifiable_id')
            ->setParameter('notification_id', $notification_id)
            ->setParameter('notifiable_id', $notifiable_id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Get all NotifiableNotifications for a notifiable
     *
     * @param        $notifiable_identifier
     * @param        $notifiable_class
     * @param string $order
     *
     * @return NotifiableNotification[]
     */
    public function findAllForNotifiable($notifiable_identifier, $notifiable_class, $order = 'DESC')
    {
        return $this->createQueryBuilder('nn')
            ->join('nn.notifiableEntity', 'ne')
            ->join('nn.notification', 'no')
            ->where('ne.identifier = :identifier')
            ->andWhere('ne.class = :class')
            ->setParameter('identifier', $notifiable_identifier)
            ->setParameter('class', $notifiable_class)
            ->orderBy('no.date', $order)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param           $identifier
     * @param           $class
     * @param bool|null $seen
     * @param string    $order
     * @param null|int  $limit
     * @param null|int  $offset
     *
     * @return array
     */
    public function findAllByNotifiable($identifier, $class, $seen = null, $order = 'DESC', $limit = null,
                                        $offset = null)
    {
        $qb = $this->findAllByNotifiableQb($identifier, $class, $order);

        if ($seen !== null) {
            $whereSeen = $seen ? 1 : 0;
            $qb
                ->andWhere('nn.seen = :seen')
                ->setParameter('seen', $whereSeen)
            ;
        }

        if (null !== $limit) {
            $qb->setMaxResults($limit);
        }
        if (null !== $offset) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param        $identifier
     * @param        $class
     * @param string $order
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllByNotifiableQb($identifier, $class, $order = 'DESC')
    {
        return $this->createQueryBuilder('nn')
            ->addSelect('n')
            ->join('nn.notification', 'n')
            ->join('nn.notifiableEntity', 'ne')
            ->where('ne.identifier = :identifier')
            ->andWhere('ne.class = :class')
            ->orderBy('n.date', $order)
            ->setParameter('identifier', $identifier)
            ->setParameter('class', $class)
            ;
    }

    /**
     * @param        $id
     * @param string $order
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllForNotifiableIdQb($id, $order = 'DESC')
    {
        return $this->createQueryBuilder('nn')
            ->addSelect('n')
            ->join('nn.notification', 'n')
            ->join('nn.notifiableEntity', 'ne')
            ->where('ne.id = :id')
            ->orderBy('n.date', $order)
            ->setParameter('id', $id)
        ;
    }

    /**
     * Get the NotifiableNotifications for a NotifiableEntity id
     *
     * @param        $id
     * @param string $order
     *
     * @return NotifiableNotification[]
     */
    public function findAllForNotifiableId($id, $order = 'DESC')
    {
        return $this->findAllForNotifiableIdQb($id, $order)->getQuery()->getResult();
    }

    /**
     * @param $notifiable_identifier
     * @param $notifiable_class
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getNotificationCountQb($notifiable_identifier, $notifiable_class)
    {
        return $this->createQueryBuilder('nn')
            ->select('COUNT(nn.id)')
            ->join('nn.notifiableEntity', 'ne')
            ->where('ne.identifier = :notifiable_identifier')
            ->andWhere('ne.class = :notifiable_class')
            ->setParameter('notifiable_identifier', $notifiable_identifier)
            ->setParameter('notifiable_class', $notifiable_class)
        ;
    }

    /**
     * Get the count of Notifications for a Notifiable entity.
     *
     * seen option results :
     *      null : get all notifications
     *      true : get seen notifications
     *      false : get unseen notifications
     *
     * @param string    $notifiable_identifier
     * @param string    $notifiable_class
     * @param bool|null $seen
     *
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getNotificationCount($notifiable_identifier, $notifiable_class, $seen = null)
    {
        $qb = $this->getNotificationCountQb($notifiable_identifier, $notifiable_class);

        if ($seen !== null) {
            $whereSeen = $seen ? 1 : 0;
            $qb
                ->andWhere('nn.seen = :seen')
                ->setParameter('seen', $whereSeen);
        }
        return $qb->getQuery()->getSingleScalarResult();
    }
}
