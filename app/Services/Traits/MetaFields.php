<?php
/**
 * Created by PhpStorm.
 * User: dartvadius
 * Date: 07.02.19
 * Time: 20:44
 */

namespace App\Services\Traits;

use Doctrine\ORM\Mapping AS ORM;

trait MetaFields
{
    /**
     * @var string
     * @ORM\Column(name="meta_title", nullable=true, unique=false, length=255)
     */
    protected $metaTitle;

    /**
     * @var string
     * @ORM\Column(name="meta_keywords", nullable=true, unique=false, length=255)
     */
    protected $metaKeywords;

    /**
     * @var string
     * @ORM\Column(name="meta_description", nullable=true, unique=false, length=255)
     */
    protected $metaDescription;

    /**
     * @param $value string
     * @return MetaFields
     */
    public function setMetaTitle ($value): self
    {
        $this->metaTitle = $value;

        return $this;
    }

    /**
     * @param $value string
     * @return MetaFields
     */
    public function setMetaKeywords ($value): self
    {
        $this->metaKeywords = $value;

        return $this;
    }

    /**
     * @param $value string
     * @return MetaFields
     */
    public function setMetaDescription ($value): self
    {
        $this->metaDescription = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getMetaTitle ()
    {
        return $this->metaTitle;
    }

    /**
     * @return string
     */
    public function getMetaKeywords ()
    {
        return $this->metaKeywords;
    }

    /**
     * @return string
     */
    public function getMetaDescription ()
    {
        return $this->metaDescription;
    }
}