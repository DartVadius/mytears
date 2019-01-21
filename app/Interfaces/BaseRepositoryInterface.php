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
    public function persist($entity);
    public function flush($entity);
    public function clear();
}