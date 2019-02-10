<?php
/**
 * Created by PhpStorm.
 * User: dartvadius
 * Date: 01.02.19
 * Time: 21:45
 */

namespace App\Services\Traits;


trait Serializer
{

    /**
     * @return array
     */
    public function toArray()
    {
        $entity = [];
        $serializable = $this->getSerializable();
        foreach ($serializable as $value) {
            $entity[$value] = $this->$value;
        }
        return $entity;
    }

    /**
     * @return string
     */
    public function toJson() {
        return \GuzzleHttp\json_encode($this->toArray());
    }

    /**
     * @return array
     */
    private function getSerializable() {
        if (isset($this->serializable)) {
            return $this->serializable;
        }
        return [];
    }

}