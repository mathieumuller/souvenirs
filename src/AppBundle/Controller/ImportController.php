<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Model\ImportMedias;
use AppBundle\Entity\Album;
use AppBundle\Entity\Tag;
use AppBundle\Form\Type\ImportMediaType;
use AppBundle\Form\Type\AlbumType;
use AppBundle\Form\Type\TagType;
use AppBundle\Services\MediaImporter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ImportController extends Controller
{
    /**
     * @Route("/import", name="import")
     */
    public function importAction(Request $request, MediaImporter $importer)
    {
        $model = new ImportMedias();
        $form = $this->createForm(ImportMediaType::class, $model);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $importer->importMediasFromModel($model);
            }
        }

        return $this->render('import.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/album/{id}", name="edit_album", defaults={"id": null})
     * @ParamConverter("album", class="AppBundle:Album")
     */
    public function editAlbumAction(Request $request, Album $album = null)
    {
        $album = is_null($album) ? new Album() : $album;
        $albumType = $this->createForm(AlbumType::class, $album);

        $albumType->handleRequest($request);
        if ($albumType->isSubmitted()) {
            return $this->handleMicroForm($albumType, $album);
        }

        return $this->render('edit-album.html.twig', [
            'albumType' => $albumType->createView(),
        ]);
    }

    /**
     * @Route("/tag/{id}", name="edit_tag", defaults={"id": null})
     * @ParamConverter("tag", class="AppBundle:Tag")
     */
    public function editTagAction(Request $request, Tag $tag = null)
    {
        $tag = is_null($tag) ? new Tag() : $tag;
        $tagType = $this->createForm(TagType::class, $tag);

        $tagType->handleRequest($request);
        if ($tagType->isSubmitted()) {
            return $this->handleMicroForm($tagType, $tag);
        }

        return $this->render('edit-tag.html.twig', [
            'tagType' => $tagType->createView(),
        ]);
    }

    private function handleMicroForm($form, $entity)
    {
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->addFlash('success', "L'élément ".$entity.' a bien été créé');
        } else {
            foreach ($form->getErrors(true, true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->redirectToRoute('import');
    }
}
