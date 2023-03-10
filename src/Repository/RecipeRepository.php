<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Entity\User;
use App\Service\Helper\CriteriaHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository implements RepositoryInterface, RecipeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function save(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Recipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findThreeLastRecette() :  ?array
    {
        return $this->createQueryBuilder('recette')
                    ->orderBy('recette.id', 'DESC')
                    ->setMaxResults(3)
                    ->getQuery()
                    ->getResult();
    }

    public function findFavoriesByUser() : ?array
    {
        return $this->createQueryBuilder('recette')
                    ->getQuery()
                    ->getResult();
    }

    public function filterByRecette(string $name) : ?array
    {
        return $this->createQueryBuilder('recette')
                    ->addCriteria(CriteriaHelper::createFilterByRecetteName($name))
                    ->getQuery()
                    ->getResult();
    }

    public function findTenLastObject(int $recette = 10) : ? array
    {
        return $this->createQueryBuilder('recette')
                    ->setMaxResults($recette)
                    ->orderBy('recette.id','DESC')
                    ->getQuery()
                    ->getResult();
    }

    public function findTopThreeBestLikedRecipe() : ?array
    {
        return $this->createQueryBuilder('recette')
                ->select('recette, COUNT(l.isLike)')
                ->innerJoin('recette.likes', 'l')
                ->andWhere('l.isLike = true')
                ->groupBy('recette.id')
                ->orderBy('COUNT(l.isLike)', 'DESC')
                ->setMaxResults(3)
                ->getQuery()
                ->getResult();
    }

    public function findRecipeByOrder() : ?array
    {
        return $this->createQueryBuilder('r')
                ->orderBy('r.id', 'DESC')
                ->getQuery()
                ->getResult();
    }

    public function findRecipeByUser(User $user) : ?array
    {
        return $this->createQueryBuilder('r')
                    ->addCriteria(self::createRecipeByUser($user))
                    ->orderBy('r.id', 'DESC')
                    ->getQuery()
                    ->getResult();
    }

    public static function createRecipeByUser(User $user) : Criteria
    {
        return Criteria::create()->andWhere(
            Criteria::expr()->eq('r.users', $user)
        );
    }

    public function findLikesByUser(User $user) : ?array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.likes', 'l')
            ->addCriteria(self::createLikeByUser($user))
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public static function createLikeByUser(User $user) : Criteria
    {
        return Criteria::create()->andWhere(
            Criteria::expr()->andX(
                Criteria::expr()->eq('l.user', $user->getId()),
                Criteria::expr()->eq('l.isLike', true)
            )
        );
    }
}
