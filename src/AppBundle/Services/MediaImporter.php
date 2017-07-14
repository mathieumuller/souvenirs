<?php

namespace AppBundle\Services;

use AppBundle\Entity\Album;
use AppBundle\Entity\Picture;
use AppBundle\Entity\Video;
use AppBundle\Model\ImportMedias;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class MediaImporter
{
    private $em;
    private $mediaPath;

    public function __construct(EntityManager $em, $mediaPath)
    {
        $this->em = $em;
        $this->mediaPath = $mediaPath;
    }

    public function importMediasFromModel(ImportMedias $model)
    {
        $album = $model->getAlbum();
        $albumPath = $this->getAlbumFolder($album);

        $tags = $model->getTags();

        foreach ($model->getMedias() as $file) {
            switch (explode('/', $file->getMimeType())[0]) {
                case 'image':
                    $media = new Picture();
                    break;
                case 'video':
                    $media = new Video();
                    break;
                default:
                    throw new FileException('Seules les images et les vidéos sont acceptées');
                    break;
            }

            $media->setMediaFile($file);

            $this->em->persist($media);
            dump($media);
        }
        die;
    }

    private function getAlbumFolder(Album $album)
    {
        $fs = new FileSystem();
        $folder = $this->mediaPath.'/'.$album;
        if (!$fs->exists($folder)) {
            $fs->mkdir($folder);
        }

        return $folder;
    }
}
