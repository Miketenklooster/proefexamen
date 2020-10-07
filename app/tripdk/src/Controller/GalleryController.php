<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Form\GalleryType;
use App\Repository\GalleryRepository;
use ContainerR25E6ut\getImageResizeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Image;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin/gallery")
 */
class GalleryController extends AbstractController
{
    /**
     * @Route("/", name="gallery_index", methods={"GET"})
     * @param GalleryRepository $galleryRepository
     * @return Response
     */
    public function index(GalleryRepository $galleryRepository): Response
    {
        return $this->render('admin/gallery/index.html.twig', [
            'galleries' => $galleryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="gallery_new", methods={"GET","POST"})
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param imageResize $resize
     * @return Response
     */
    public function new(Request $request, SluggerInterface $slugger, imageResize $resize): Response
    {
        $gallery = new Gallery();
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image_name')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile, PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                $image_p = $resize->resizeImage($imageFile);

                // Move the file to the directory where images are stored
                try {
                    imagejpeg($image_p,  $this->getParameter('low_res_gallery').'/'.$newFilename);

                    $imageFile->move(
                        $this->getParameter('image_gallery'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    throw new FileException($e);
                }

                $gallery->setImageName($newFilename);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($gallery);
            $entityManager->flush();

            return $this->redirectToRoute('gallery_index');
        }

        return $this->render('admin/gallery/new.html.twig', [
            'gallery' => $gallery,
        ]);
    }

    /**
     * @Route("/{id}", name="gallery_show", methods={"GET"})
     * @param Gallery $gallery
     * @return Response
     */
    public function show(Gallery $gallery): Response
    {
        return $this->render('admin/gallery/show.html.twig', [
            'gallery' => $gallery,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="gallery_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Gallery $gallery
     * @param SluggerInterface $slugger
     * @param imageResize $resize
     * @return Response
     */
    public function edit(Request $request, Gallery $gallery, SluggerInterface $slugger, imageResize $resize): Response
    {
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form['image_name']->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile, PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

               $image_p = $resize->resizeImage($imageFile);

                $file = $this->getParameter("image_gallery"). '/' . $gallery->getImageName();
                $file2 = $this->getParameter("low_res_gallery"). '/' . $gallery->getImageName();

                // Move the file to the directory where images are stored
                try {
                    imagejpeg($image_p, $this->getParameter('low_res_gallery') . '/' . $newFilename);

                    $imageFile->move(
                        $this->getParameter('image_gallery'),
                        $newFilename
                    );

                    if ($file && $file2) {
                        unlink($file);
                        unlink($file2);
                    }
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    throw new FileException($e);
                }
                $gallery->setImageName($newFilename);
            }

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('gallery_index');
        }

        return $this->render('admin/gallery/edit.html.twig', [
            'gallery' => $gallery,
        ]);
    }

    /**
     * @Route("/{id}", name="gallery_delete", methods={"DELETE"})
     * @param Request $request
     * @param Gallery $gallery
     * @return Response
     */
    public function delete(Request $request, Gallery $gallery): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gallery->getId(), $request->request->get('_token'))) {
            $file = $this->getParameter("image_gallery"). '/' . $gallery->getImageName();
            $file2 = $this->getParameter("low_res_gallery"). '/' . $gallery->getImageName();
            if ($file && $file2) {
                unlink($file);
                unlink($file2);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gallery);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gallery_index');
    }
}
