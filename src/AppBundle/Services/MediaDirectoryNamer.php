<?php

namespace AppBundle\Services;

use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class MediaDirectoryNamer implements DirectoryNamerInterface
{
    public function directoryName($entity, \Vich\UploaderBundle\Mapping\PropertyMapping $mapping)
    {
        $album = $entity->getAlbum();

        return $album ? $album->getName() : 'Misc';
    }
}
