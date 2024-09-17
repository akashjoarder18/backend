<?php
class FontFamily
{
    private $uploadDir = __DIR__ . '/../uploads/';
    private $allowedExtensions = ['ttf'];

    public function upload($file)
    {
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array($fileExtension, $this->allowedExtensions)) {
            return ['error' => 'Only TTF files are allowed'];
        }

        $fileName = uniqid() . '.' . $fileExtension;
        $filePath = $this->uploadDir . $fileName;


        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return ['success' => true, 'fileName' => $fileName];
        }

        return ['error' => 'File upload failed'];
    }

    public function deleteUploadFile($fontFileName)
    {
        // Delete the actual font file from the server if it exists
        $filePath = $this->uploadDir . $fontFileName;
        if (file_exists($filePath)) {
            unlink($filePath); // Delete the file
        }

        return ['success' => true, 'message' => 'Font file deleted successfully'];
    }

    public function getAllFonts()
    {
        $files = scandir($this->uploadDir);
        return array_filter($files, function ($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'ttf';
        });
    }
}
