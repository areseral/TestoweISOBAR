<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\FBpostsRepository")
 * @ORM\Table(name="fbposts")
 */
class FBposts
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $idPost;
    /**
     * @ORM\Column(type="text")
     */
    private $message;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $fromId;
    /**
     * @ORM\Column(type="string", length=400)
     */
    private $fromName;
    
    /** 
     * @ORM\Column(type="datetime") 
     */
    private $created;
    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idPost
     *
     * @param string $idPost
     *
     * @return FBposts
     */
    public function setIdPost($idPost)
    {
        $this->idPost = $idPost;

        return $this;
    }

    /**
     * Get idPost
     *
     * @return string
     */
    public function getIdPost()
    {
        return $this->idPost;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return FBposts
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set fromId
     *
     * @param string $fromId
     *
     * @return FBposts
     */
    public function setFromId($fromId)
    {
        $this->fromId = $fromId;

        return $this;
    }

    /**
     * Get fromId
     *
     * @return string
     */
    public function getFromId()
    {
        return $this->fromId;
    }

    /**
     * Set fromName
     *
     * @param string $fromName
     *
     * @return FBposts
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;

        return $this;
    }

    /**
     * Get fromName
     *
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return FBposts
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
}

class FBpostsRepository extends EntityRepository
{
    public function getNewestPostsFB()
    {
        return $this->_em->createQuery('SELECT u FROM AppBundle:FBposts u ORDER BY u.created DESC')
                        ->setMaxResults(50)    
                         ->getResult();
    }
    
    public function checkIsExistPostsFB($post_id)
    {
        $post = $this->_em->createQuery('SELECT u FROM AppBundle:FBposts u WHERE u.idPost LIKE ' . "'$post_id'")
                         ->getResult();
        
        if( is_array($post) && empty($post) ) return false;
        
        return true;
    }
}
