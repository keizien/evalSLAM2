<?php
declare (strict_types = 1);
namespace MyApp\Entity;

class Upload
{
    private array $extensions;
    private string $path;
    private int $size;
    public function __construct(array $extensions, string $path, int $size)
    {
        $this->extensions = $extensions;
        $this->path = $path;
        $this->size = $size;
    }

    public function save(string $data): array
    {
        $file = ['name' => null, 'error' => null];

        if (!isset($_FILES[$data]) || empty($_FILES[$data]['name'])){
            return $file;
        }

        $fileExtension = strtolower(pathinfo($_FILES[$data]['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $this->extensions)) {
            $file['error'] = 'Type de fichier non autorisé';
            return $file;
        }

        if ($_FILES[$data]['size'] > $this->size) {
            $file['error'] = 'Fichier trop volumineux';
            return $file;
        }

        $uniqueName = uniqid().'.'.$fileExtension;
        $destination = $this->path.'/'.$uniqueName;

        if (!move_uploaded_file($_FILES[$data]['tmp_name'], $destination)) {
            $file['error'] = 'Erreur lors de l\'upload';
            return $file;
        }

        $file['name'] = $uniqueName;
        return $file;
    }
}
