<?php

/**
 * Created by PhpStorm.
 * User: tuyennn
 * Date: 6/23/2016
 * Time: 6:45 PM
 */
class Evince_Testimony_Helper_Files extends Mage_Core_Helper_Abstract
{
    protected function _commonHelper()
    {
        return Mage::helper('testimony');
    }

    protected function _getPathInfo($fileName, $key)
    {
        $pathParts = pathinfo($fileName);
        return isset($pathParts[$key]) ? $pathParts[$key] : false;
    }

    public function getBaseName($fileName)
    {
        return $this->_getBaseName($fileName);
    }

    public function getFileName($fileName)
    {
        return $this->_getFileName($fileName);
    }

    public function getDirName($fileName)
    {
        return $this->_getDirName($fileName);
    }

    public function getExtension($fileName)
    {
        return $this->_getExtension($fileName);
    }

    protected function _getDirName($fileName)
    {
        return $this->_getPathInfo($fileName, 'dirname');
    }

    protected function _getFileName($fileName)
    {
        return $this->_getPathInfo($fileName, 'filename');
    }

    protected function _getBaseName($fileName)
    {
        return $this->_getPathInfo($fileName, 'basename');
    }

    protected function _getExtension($fileName)
    {
        return $this->_getPathInfo($fileName, 'extension');
    }

    public function saveContentToFile($fileName, $data, $overwrite = true, &$fileObject = null)
    {
        if ($fileName) {

            if (!file_exists($fileName)) {

                $dirName = $this->_getDirName($fileName);
                if (!file_exists($dirName)) {
                    mkdir($dirName, 0755, true);
                }

                file_put_contents($fileName, $data);
            } else {
                ///TODO
                file_put_contents($fileName, $data);
            }
        }

        return $this;
    }

    public function getContentFromFile($fileName)
    {
        return file_get_contents($fileName);
    }
}