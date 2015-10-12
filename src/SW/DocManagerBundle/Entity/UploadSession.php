<?php

namespace SW\DocManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * UploadSession
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SW\DocManagerBundle\Entity\UploadSessionRepository")
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
    * @ORM\ManyToOne(targetEntity="SW\DocManagerBundle\Entity\Document")
    * @ORM\JoinColumn(nullable=true)
    */
    private $documentRef;
    
  /**
   * @ORM\ManyToMany(targetEntity="SW\DocManagerBundle\Entity\Document", cascade={"persist"})
   */
    private $documents;
    
    private $existedNames;
            
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
    
    public function getDocumentRef() {
        return $this->documentRef;
    }

    public function setDocumentRef(Document $documentRef) {
        $this->documentRef = $documentRef;
    }
    
    public function hasExistedNames() {
        return $this->existedNames;
    }

    public function setExistedNames($existedNames) {
        $this->existedNames = $existedNames;
    }


}

