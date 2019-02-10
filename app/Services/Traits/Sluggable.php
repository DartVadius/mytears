<?php
/**
 * Created by PhpStorm.
 * User: dartvadius
 * Date: 07.02.19
 * Time: 21:01
 */

namespace App\Services\Traits;

use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait Sluggable
{

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false, unique=true, length=255)
     */
    private $title;

    /**
     * @var string
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(type="string", nullable=false, unique=true, length=255)
     */
    protected $slug;

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param $slug string
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @param $title string
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

}