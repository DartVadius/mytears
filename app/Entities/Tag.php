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

/**
 * @ORM\Entity
 * @ORM\Table(name="tags")
 * @ORM\Entity(repositoryClass="App\Repositories\Tag\TagRepository")
 */
class Tag implements EntityInterface
{

    use MetaFields, Sluggable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer", length=10, options={"unsigned":true})
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection|Post[]
     *
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="tags", fetch="EXTRA_LAZY")
     */
    private $posts;

    public function __construct() {
        $this->posts = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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
     * @return Tag
     */
    public function addPost(Post $post): self
    {
        if ($this->posts->contains($post)) {

            return $this;
        }
        $this->posts->add($post);
        $post->addTag($this);

        return $this;
    }

    /**
     * @param Post $post
     * @return Tag
     */
    public function removePost(Post $post): self
    {
        if (!$this->posts->contains($post)) {

            return $this;
        }
        $this->posts->removeElement($post);
        $post->removeTag($this);

        return $this;
    }

    public function toBase()
    {
        return $this;
    }

}