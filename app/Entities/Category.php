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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="App\Repositories\Category\CategoryRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Category implements EntityInterface
{

    use Timestamps, MetaFields, Sluggable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", length=10, options={"unsigned":true})
     */
    private $id;

    /**
     * One-To-Many, Self-referencing
     * Many Categories have One Parent Category.
     * @var Category
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, unique=false)
     */
    private $parent;

    /**
     * One-To-Many, Self-referencing
     * One Category has Many Categories.
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent", fetch="EXTRA_LAZY")
     */
    private $children   ;

    /**
     * @var DateTime
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * One category has many posts. This is the inverse side.
     * @ORM\OneToMany(targetEntity="Post", mappedBy="category", fetch="EXTRA_LAZY")
     */
    private $posts;

    public function __construct() {
        $this->posts = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;

        return $this;
    }

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($value)
    {
        $this->deletedAt = $value;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * @param Post $post
     * @return Category
     */
    public function addPost(Post $post)
    {
        if ($this->posts->contains($post)) {

            return $this;
        }
        $this->posts->add($post);
        $post->setCategory($this);

        return $this;
    }

    /**
     * @param Post $post
     * @return $this
     */
    public function removePost($post)
    {
        if (!$this->posts->contains($post)) {

            return $this;
        }
        $this->posts->removeElement($post);
        $post->removeCategory();

        return $this;
    }

    public function addChildren(Category $category)
    {
        if ($this->children->contains($category)) {

            return $this;
        }
        $this->children->add($category);
        $category->setParent($this);

        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @param $children Category
     */
    public function removeChild(Category $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function setParent(Category $category)
    {
        $this->parent = $category;

        return $this;
    }

    /**
     * @return Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function removeParent()
    {
        $this->parent = null;

        return $this;
    }

    public function toBase()
    {
        return $this;
    }

    public function all()
    {
        $response = [];
        $response['id'] = $this->getId();
        $response['title'] = $this->getTitle();
        $response['slug'] = $this->getSlug();
        $response['metaTitle'] = $this->getMetaTitle();
        $response['metaKeywords'] = $this->getMetaKeywords();
        $response['metaDescription'] = $this->getMetaDescription();
        $response['createdAt'] = $this->getCreatedAt();
        $response['updatedAt'] = $this->getUpdatedAt();
        $response['deletedAt'] = $this->getDeletedAt();

        $response['parent_id'] = null;
        if ($parent = $this->getParent()) {
            $response['parent_id'] = $parent->getId();
        }
        return $response;
    }

    /**
     * @return string
     */
    public function toJson() {
        return \GuzzleHttp\json_encode($this->all());
    }

}