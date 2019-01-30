<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
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
    public function save($entity) {
        $this->persist($entity);
        $this->flush();
        $this->clear();
    }

    /**
     * @param $entity
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\ORMException
     */
    public function delete($entity) {
        $this->remove($entity);
        $this->flush();
        $this->clear();
    }
}
