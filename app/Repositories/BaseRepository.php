<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use App\Interfaces\EntityInterface;
use LaravelDoctrine\ORM\Pagination\PaginatesFromRequest;


class BaseRepository extends \Doctrine\ORM\EntityRepository implements BaseRepositoryInterface
{

    use PaginatesFromRequest;

    /**
     * @param object $entity
     * @throws \Doctrine\ORM\ORMException
     */
    public function persist($entity) :void
    {
        $this->_em->persist($entity);
    }

    /**
     * @param object $entity
     * @throws \Doctrine\ORM\ORMException
     */
    public function merge($entity) :void
    {
        $this->_em->merge($entity);
    }

    /**
     * @param object $entity
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove($entity) :void
    {
        $this->_em->remove($entity);
    }

    /**
     * @param null|object|array $entity
     * @throws \Doctrine\ORM\ORMException
     */
    public function flush($entity = null) :void
    {
        $this->_em->flush($entity);
    }

    /**
     * @param string|null $entityName if given, only entities of this type will get detached
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     */
    public function clear($entityName = null) :void
    {
        $this->_em->clear($entityName);
    }

    /**
     * @param $entity
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\ORMException
     */
    public function save(EntityInterface $entity) {
        if ($entity->getId()) {
            $this->merge($entity);
        } else {
            $this->persist($entity);
        }
        $this->flush();
        $this->clear();
    }

    /**
     * @param $entity
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\ORMException
     */
    public function delete(EntityInterface $entity) {
        $this->remove($entity);
        $this->flush();
        $this->clear();
    }

    /**
     * @param $id
     * @return object|null
     */
    public function findById($id) {
        return $this->findOneBy(['id' => $id]);
    }

    public function getEntityManager()
    {
        return parent::getEntityManager();
    }
}
