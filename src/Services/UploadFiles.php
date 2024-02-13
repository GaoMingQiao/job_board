<?php

namespace App\Services;

use Throwable;
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
        $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
        //déplacement du fichier dans le dossier public/uploads
        $file->move($this->getParameter('uploads_directory'), $fileName);
        return $fileName;
    }

    public function updateFileUpload($file, $oldFile)
    {
        $fileName = $this->saveFileUpload($file);
        // if ($oldFile != 'default.png') {
        //     unlink($this->getParameter('uploads_directory') . '/' . $oldFile);
        // }
        $this->deleteFileUpload($oldFile);
        return $fileName;
    }
    public function deleteFileUpload($file)
    {
        try {
            if ($file !== 'default.png') {
                unlink($this->getParameter('uploads_directory' . '/' . $file));
            }
        } catch (Throwable $th) {
        }
    }
}
