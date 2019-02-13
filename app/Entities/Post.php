<?php
/**
 * Created by PhpStorm.
 * User: dartvadius
 * Date: 06.02.19
 * Time: 20:09
 */

namespace App\Entities;

use App\Interfaces\EntityInterface;
use App\Services\Traits\MetaFields;
use App\Services\Traits\Sluggable;
use DateTime;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @ORM\Entity
 * @ORM\Table(name="posts")
 * @ORM\Entity(repositoryClass="App\Repositories\Post\PostRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Post implements EntityInterface
{

    use Timestamps, MetaFields, Sluggable;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     */
    private $category;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", length=10, options={"unsigned":true})
     */
    private $id;

    /**
     * Many posts have one category. This is the owning side.
     * @var integer
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="posts")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true, unique=false)
     */
    private $categoryId;

    /**
     * @var DateTime
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var string
     * @ORM\Column(name="short_text", type="text", nullable=true)
     */
    public $shortText;

    /**
     * @var string
     * @ORM\Column(name="full_text", type="text", nullable=false)
     */
    public $fullText;

    /**
     * @var string
     * @ORM\Column(type="smallint", nullable=false, length=1, options={"unsigned":true, "default":0})
     */
    public $publish;

    /**
     * @var integer
     * @ORM\Column(type="smallint", nullable=true, length=10)
     */
    public $order;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function toBase()
    {
        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return Post
     */
    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

}