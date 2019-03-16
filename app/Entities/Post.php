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

    const PUBLISHED_TRUE = 1;
    const PUBLISHED_FALSE = 0;

    const PAGE = 1;
    const LIMIT = 25;

    use Timestamps, MetaFields, Sluggable;

    /**
     * Many Posts have One Parent Category.
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="posts")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true, unique=false, onDelete="set null")
     */
    private $category;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", length=10, options={"unsigned":true})
     */
    private $id;

    /**
     * @var DateTime
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var string
     * @ORM\Column(name="short_text", type="text", nullable=true)
     */
    private $shortText;

    /**
     * @var string
     * @ORM\Column(name="full_text", type="text", nullable=false)
     */
    private $fullText;

    /**
     * @var string
     * @ORM\Column(name="publish", type="smallint", nullable=false, length=1, options={"unsigned":true, "default":0})
     */
    private $publish;

    /**
     * @var integer
     * @ORM\Column(name="post_order", type="smallint", nullable=true, length=10)
     */
    private $order;

    /**
     * @var \Doctrine\Common\Collections\Collection|Tag[]
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="posts", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(
     *  name="posts_tags",
     *  joinColumns={
     *      @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     *  }
     * )
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

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

    public function isPublish()
    {
        return $this->publish === self::PUBLISHED_TRUE;
    }

    public function getShortText()
    {
        return $this->shortText;
    }

    public function setShortText($text)
    {
        $this->shortText = $text;

        return $this;
    }

    public function getFullText()
    {
        return $this->fullText;
    }

    public function setFullText($text)
    {
        $this->fullText = $text;

        return $this;
    }

    public function getPublish()
    {
        return $this->publish;
    }

    public function setPublish($publish)
    {
        $this->publish = $publish;

        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return Post
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
        $category->addPost($this);

        return $this;
    }

    public function removeCategory()
    {
        $this->category = null;

        return $this;
    }

    /**
     * @return Tag[]|ArrayCollection|\Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag $tag
     * @return $this
     */
    public function addTag(Tag $tag)
    {
        if ($this->tags->contains($tag)) {

            return $this;
        }
        $this->tags->add($tag);
        $tag->addPost($this);

        return $this;
    }

    /**
     * @param Tag $tag
     * @return $this
     */
    public function removeTag(Tag $tag)
    {
        if (!$this->tags->contains($tag)) {

            return $this;
        }
        $this->tags->removeElement($tag);
        $tag->removePost($this);

        return $this;
    }

    public function removeAllTags()
    {
        $this->tags->clear();

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

    public function forCollection()
    {
        $response = [];
        $response['id'] = $this->getId();
        $response['title'] = $this->getTitle();
        $response['slug'] = $this->getSlug();
        $response['short_text'] = $this->getShortText();
        $response['updatedAt'] = $this->getUpdatedAt();
        $response['publish'] = $this->getPublish();
        $response['order'] = $this->getOrder();
        $response['tags'] = [];
        foreach ($this->tags as $tag) {
            $response['tags'][] = $tag->getId();
        }
        $response['category_id'] = null;
        if ($parent = $this->getCategory()) {
            $response['category_id'] = $parent->getId();
        }
        return $response;
    }

    public function all()
    {
        $response = $this->forCollection();
        $response['full_text'] = $this->getFullText();
        $response['metaTitle'] = $this->getMetaTitle();
        $response['metaKeywords'] = $this->getMetaKeywords();
        $response['metaDescription'] = $this->getMetaDescription();
        $response['createdAt'] = $this->getCreatedAt();
        $response['deletedAt'] = $this->getDeletedAt();
        return $response;
    }

    /**
     * @return string
     */
    public function toJson() {
        return \GuzzleHttp\json_encode($this->all());
    }

}