<?php

namespace App\Repository;

use App\Entity\Like;
use App\Entity\Recipe;
use App\Service\Helper\CriteriaHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Like>
 *
 * @method Like|null find($id, $lockMode = null, $lockVersion = null)
 * @method Like|null findOneBy(array $criteria, array $orderBy = null)
 * @method Like[]    findAll()
 * @method Like[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeRepository extends ServiceEntityRepository implements LikeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }

    public function save(Like $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Like $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function UpdateLike(Recipe $recette, bool $isLiked)
    {
        $qb = $this->createQueryBuilder('like');
        $qb->update()
            ->set('like.isLike', ':isLiked')
            ->where('like.isLike = :isLiked')
            ->setParameter('isLiked', $isLiked)
            ->setParameter('recette', $recette);

        return $qb->getQuery()->execute();
    }


}
