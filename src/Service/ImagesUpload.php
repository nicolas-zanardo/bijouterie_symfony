<?php


namespace App\Service;


use ErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ImagesUpload extends AbstractController
{
    private $imageFile;
    private $nomImage;

    public function insertImage($class, $form): void
    {
        $this->initImage($form, 'image');

        if($this->imageFile) {
            $this->createImageName();
            $this->moveImage();
            $class->setImage($this->nomImage);
        } else {
            throw new ErrorException("No Image found in $form $class");
        }
    }

    public function editImage($class, $form): void
    {
        $this->initImage($form, 'imageFile');
        if ($this->imageFile) {
            $this->editImageName();
            $this->moveImage();
            if ($class->getImage()) {
                unlink($this->getParameter('image_produit') . '/' . $class->getImage());
            }
            $class->setImage($this->nomImage);
        }
    }

    public function deleteImage(Request $request, $class): void {
        if($request->request->get('imageQuestion') === "oui") {
            unlink($this->getParameter('image_produit') . '/' . $class->getImage());
            $class->setImage(NULL);
        }
    }


    private function initImage($form, $img): void
    {

        $this->imageFile = $form->get($img)->getData();

    }

    private function createImageName(): void
    {
        $nomReelImage = str_replace(" ", "-", $this->imageFile->getClientOriginalName());
        $this->nomImage = date("YmdHis") . "-" . uniqid('img_', true) . "-" . $nomReelImage;
    }

    private function editImageName(): void
    {
        $this->nomImage = date('YmdHis') . "-" . uniqid('img_', false) . "-" . $this->imageFile->getClientOriginalName();
    }

    private function moveImage(): void
    {
        $this->imageFile->move(
            $this->getParameter("image_produit"),
            $this->nomImage
        );
    }
}