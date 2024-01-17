<?php

namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UploadFiles extends AbstractController
{   //cette methode va permettre de générer un nom unique
    private function generateUniqueFileName()
    {
        $name = bin2hex(random_bytes(16)) . '' . uniqid();
        return $name;
    }
    //déplacer le fichier dans le dossier uploads
    public function saveFileUpload($file)
    {
        $fileName = $file->getClientOriginalName();
        $fileName = $this->generateUniqueFileName() . '' . $file->guessExtension();
        //déplacement du fichier dans le dossier public/uploads
        $file->move($this->getParameter('uploads_directory'), $fileName);
        return $fileName;
    }
}
