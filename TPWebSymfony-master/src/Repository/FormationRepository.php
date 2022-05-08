<?php

namespace App\Repository;

use App\Entity\Formation;
use App\Entity\Niveau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Formation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    /**
     * Retourne toutes les formations triées sur un champ
     * @param type $champ
     * @param type $ordre
     * @return Formation[]
     */
    public function findAllOrderBy($champ, $ordre): array{
            return $this->createQueryBuilder('f')
                    ->orderBy('f.'.$champ, $ordre)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @return Formation[]
     */
    public function findByContainValue($champ, $valeur): array{
        if($valeur==""){
            return $this->createQueryBuilder('f')
                    ->orderBy('f.'.$champ, 'ASC')
                    ->getQuery()
                    ->getResult();
        }else{
            return $this->createQueryBuilder('f')
                    ->where('f.'.$champ.' LIKE :valeur')
                    ->orderBy('f.publishedAt', 'DESC')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->getQuery()
                    ->getResult();            
        }
    }
       
    /**
     * Enregistrements dont un champ contient le nom du niveau
     * ou tous les enregistrements si la valeur envoyée est vide
     * @param type $champ
     * @param type $valeur
     * @return array
     */
    public function findByNiveau($champ, $valeur): array{
        if($valeur==""){
            return $this->createQueryBuilder('f')
                    ->orderBy('f.'.$champ, 'ASC')
                    ->getQuery()
                    ->getResult();
        }else{
            return $this->createQueryBuilder('f')
                    ->leftJoin(Niveau::class, "n", Expr\Join::WITH, "f.".$champ." = n.id")
                    ->where('n.nom LIKE :valeur')
                    ->orderBy('f.publishedAt', 'DESC')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->getQuery()
                    ->getResult();         
        }
    }
       
    /**
     * Retourne les n formations les plus récentes
     * @param type $nb
     * @return Formation[]
     */
    public function findAllLasted($nb) : array {
        return $this->createQueryBuilder('f')
           ->orderBy('f.publishedAt', 'DESC')
           ->setMaxResults($nb)     
           ->getQuery()
           ->getResult();
    }
}
