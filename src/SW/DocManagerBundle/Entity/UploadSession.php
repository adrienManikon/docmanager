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
    * @ORM\OneToOne(targetEntity="SW\DocManagerBundle\Entity\Document", cascade={"persist"})
     *@ORM\JoinColumn(nullable=true)
    */
    private $documentRef;
    
    /**
     * @ORM\OneToMany(targetEntity="SW\DocManagerBundle\Entity\Document", mappedBy="uploadSession")
     * @ORM\JoinColumn(nullable=true)
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
    
    public function hasExistedNames() {
        return $this->existedNames;
    }

    public function setExistedNames($existedNames) {
        $this->existedNames = $existedNames;
    }


    /**
     * Set documentRef
     *
     * @param \SW\DocManagerBundle\Entity\Document $documentRef
     *
     * @return UploadSession
     */
    public function setDocumentRef(\SW\DocManagerBundle\Entity\Document $documentRef = null)
    {
        $this->documentRef = $documentRef;

        return $this;
    }

    /**
     * Get documentRef
     *
     * @return \SW\DocManagerBundle\Entity\Document
     */
    public function getDocumentRef()
    {
        return $this->documentRef;
    }


    /**
     * Add document
     *
     * @param \SW\DocManagerBundle\Entity\Document $document
     *
     * @return UploadSession
     */
    public function addDocument(\SW\DocManagerBundle\Entity\Document $document)
    {
        $this->documents[] = $document;
        $document->setUploadSession($this);

        return $this;
    }

    /**
     * Remove document
     *
     * @param \SW\DocManagerBundle\Entity\Document $document
     */
    public function removeDocument(\SW\DocManagerBundle\Entity\Document $document)
    {
        $this->documents->removeElement($document);
        $document->setUploadSession(null);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }
    
    /**
     * Set documents
     *
     * @return UploadSession
     */
    public function setDocuments(\Doctrine\Common\Collections\Collection $documents)
    {
        $this->documents = $documents;
        return $this;
    }    
    
    public function updateDocuments()
    {
        foreach ($this->documents as $document) {
            $document->setUploadSession($this);
        }
        return $this;
    }
}
