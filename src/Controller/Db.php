<?php

namespace Controller;

class Db
{
    /**
     * @var array configuration placeholder
     */
    protected $config = array();
    protected $shop = '';

    public function __construct($config)
    {
        $this->config = $config;

        if($_GET['shop']){
            $this->shop = $_GET['shop'];
        }
        elseif($_POST['shop']){
            $this->shop = $_POST['shop'];
        }
    }

    private function db()
    {
        static $handle = null;
        if (!$handle) {
            $handle = new \PDO(
                $this->config['db']['connection'],
                $this->config['db']['user'],
                $this->config['db']['pass']
            );
        }

        return $handle;
    }

    public function checkAvailability()
    {
        $db = $this->db();
        $check = $db->prepare('DESCRIBE '.$this->shop);

        if(!$check->execute()){
            $sql = $db->prepare("CREATE TABLE ".$this->shop." (
                    id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    category_id INT(10),
                    custom_image ENUM('1','0') NOT NULL DEFAULT '0',
                    image VARCHAR(50)
                    )");

            $sql->execute();

        }
    }

    public function getConfigItem($id)
    {

        $db = $this->db();
        $configItems = $db->prepare('SELECT * FROM '.$this->shop.' WHERE category_id = ?');
        $configItems->execute(array($id));

        return $configItems->fetch();
    }

    public function getSetting()
    {

        $db = $this->db();
        $configItems = $db->prepare('SELECT * FROM settings WHERE shop = ?');
        $configItems->execute(array($this->shop));

        return $configItems->fetch();
    }

    public function updateSetting($arguments)
    {

        $db = $this->db();

        $config = $db->prepare('SELECT * FROM settings WHERE shop = ?');
        $config->execute(array($this->shop));

        if($config->rowCount()){
            $configUpdate = $db->prepare('UPDATE settings SET mobile_width = ?, tablet_width = ?, desktop_width = ?, activity = ?, color = ?, position=? WHERE shop = ?');
            $data = array(
                $arguments['mobile_width'],
                $arguments['tablet_width'],
                $arguments['desktop_width'],
                $arguments['activity'],
                $arguments['color'],
                $arguments['position'],
                $this->shop
            );
        }
        else{
            $configUpdate = $db->prepare('INSERT INTO settings SET mobile_width = ?, tablet_width = ?, desktop_width = ?, activity = ?, color = ?, position=?, shop = ?');
            $data = array(
                $arguments['mobile_width'],
                $arguments['tablet_width'],
                $arguments['desktop_width'],
                $arguments['activity'],
                $arguments['color'],
                $arguments['position'],
                $this->shop
            );
        }

        return $configUpdate->execute($data);
    }

    public function getConfigItems()
    {
        $db = $this->db();
        $configItems = $db->prepare('SELECT category_id, custom_image, image FROM '.$this->shop);
        $configItems->execute();

        $result = $configItems->fetchAll(\PDO::FETCH_GROUP|\PDO::FETCH_ASSOC);
        $result = array_map('reset', $result);

        return array('configCategories'=>$result, 'shop'=>$this->shop);
    }


    public function updateConfig($arguments)
    {
        $db = $this->db();

        $config = $db->prepare('SELECT * FROM '.$this->shop.' WHERE category_id = ?');
        $config->execute(array($arguments['category_id']));

        if ($_FILES['image']['error'] === 0) {
            $image = new Image($_FILES['image']);
            $imageName = $image->saveFile($arguments['category_id']);

            if($config->rowCount()){
                $configUpdate = $db->prepare('UPDATE '.$this->shop.' SET custom_image = ?, image = ? WHERE category_id = ?');
                $data = array(
                    $arguments['custom_image'] == 'true'?'1':'0',
                    $imageName,
                    $arguments['category_id']
                );
            }
            else{
                $configUpdate = $db->prepare('INSERT INTO '.$this->shop.' SET category_id = ?, custom_image = ?, image = ?');
                $data = array(
                    $arguments['category_id'],
                    $arguments['custom_image'] == 'true'?'1':'0',
                    $imageName
                );
            }
        }
        else{
            if($config->rowCount()){
                $configUpdate = $db->prepare('UPDATE '.$this->shop.' SET custom_image = ? WHERE category_id = ?');
                $data = array(
                    $arguments['custom_image'] == 'true'?'1':'0',
                    $arguments['category_id']
                );
            }
            else{
                $configUpdate = $db->prepare('INSERT INTO '.$this->shop.' SET category_id = ?, custom_image = ?');
                $data = array(
                    $arguments['category_id'],
                    $arguments['custom_image'] == 'true'?'1':'0'
                );
            }
        }

        return $configUpdate->execute($data);
    }
}