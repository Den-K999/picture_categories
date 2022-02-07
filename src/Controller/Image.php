<?php

namespace Controller;

class Image
{

    /**
     * @var array configuration placeholder
     */
    protected $file = array();
    protected $shop = '';
    protected $rootDir = 'images/';
    protected $types = array(
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/svg+xml' => 'svg',
        'image/webp' => 'webp'
    );

    public function __construct($file)
    {
        $this->file = $file;
        if ($_GET['shop']) {
            $this->shop = $_GET['shop'];
        } elseif ($_POST['shop']) {
            $this->shop = $_POST['shop'];
        }
    }

    private function checkDirectory()
    {
        return is_dir($this->rootDir . $this->shop);
    }

    private function createDirectory()
    {
        mkdir($this->rootDir . $this->shop);
    }

    private function checkFile($id)
    {
        $result = glob($this->rootDir . $this->shop . "/" . $id . ".*");

        return $result;
    }

    public function saveFile($id)
    {
        if (!$this->checkDirectory()) {
            $this->createDirectory();
        }

        $isFiles = $this->checkFile($id);

        if (!empty($isFiles)) {
            unlink($isFiles[0]);
        }

        $image = file_get_contents($this->file['tmp_name']);
        file_put_contents($this->rootDir . $this->shop . "/" . $id . "." . $this->types[$this->file['type']], $image);

        return $id . "." . $this->types[$this->file['type']];
    }
}
