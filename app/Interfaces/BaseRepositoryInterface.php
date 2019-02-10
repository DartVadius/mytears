<?php
/**
 * Created by PhpStorm.
 * User: dartvadius
 * Date: 20.01.19
 * Time: 16:15
 */

namespace App\Interfaces;


interface BaseRepositoryInterface
{
    public function save(EntityInterface $entity);
    public function delete(EntityInterface $entity);
}