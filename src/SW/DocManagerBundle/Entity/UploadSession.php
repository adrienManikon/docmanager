<?php

namespace SW\DocManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * UploadSession
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class UploadSession
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var array
     *
     * @ORM\Column(name="documents", type="array")
     */
    private $documents;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

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
     * Set documents
     *
     * @param array $documents
     *
     * @return UploadSession
     */
    public function setDocuments($documents)
    {
        $this->documents = $documents;

        return $this;
    }

    /**
     * Get documents
     *
     * @return array
     */
    public function getDocuments()
    {
        return $this->documents;
    }
}

