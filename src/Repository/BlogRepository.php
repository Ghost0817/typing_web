<?php
/**
 * Created by PhpStorm.
 * User: 22cc3355
 * Date: 1/24/2019
 * Time: 9:34 AM
 */

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BlogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    /**
     * @param $slug
     * @return Blog[]
     */
    public function findAllSlugOfTag($slug): array
    {
        $em = $this->getEntityManager();

        $query = null;

        $query = $em->createQuery(
            "
                    SELECT b
                    FROM App:Blog b
                    inner join App:Blogandtag bt WITH b.id = bt.blog
                    inner join App:Tag t WITH bt.tag = t.id
                    WHERE t.slug = :slug AND b.isShow = '1'
                    order by b.sortnum, b.createdAt 
                    "
        )->setParameter('slug', $slug);

        $entity = $query->getResult();
        return $entity;
    }
}