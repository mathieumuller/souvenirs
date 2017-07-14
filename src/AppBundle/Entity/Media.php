<?php

namespace AppBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Media.
 *
 * @Vich\Uploadable
 */
abstract class Media
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var Vich\UploaderBundle\Entity\File
     */
    private $file;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var Album
     */
    private $album;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(
     *     mapping="media",
     *     fileNameProperty="file.name",
     *     size="file.size",
     *     mimeType="file.mimeType",
     *     originalName="file.originalName"
     * )
     *
     * @var File
     */
    private $mediaFile;

    public function __construct()
    {
        $this->file = new EmbeddedFile();
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile $file
     */
    public function setMediaFile(File $mediaFile = null)
    {
        $this->mediaFile = $mediaFile;

        if ($mediaFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the mediaFile is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return File|null
     */
    public function getMediaFile()
    {
        return $this->mediaFile;
    }

    /**
     * @param EmbeddedFile $file
     */
    public function setFile(EmbeddedFile $file)
    {
        $this->file = $file;
    }

    /**
     * @return EmbeddedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Album
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @param Album $album
     *
     * @return self
     */
    public function setAlbum(Album $album)
    {
        $this->album = $album;

        return $this;
    }
}
