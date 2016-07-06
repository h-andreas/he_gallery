<?php

namespace HSE\HeGallery\ViewHelpers;

use HSE\HeGallery\Utility\FileUtility;
use TYPO3\CMS\Core\Resource\File;

class RandImageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
     * @param string $folder
     * @param int $uid
     * @return object
     */
    public function render($folder, $uid){
        $images = $this->findImages($folder, $uid);
        if(!empty($images)){
            $randImage = $images[array_rand($images)]->getIdentifier();
        }
        return $randImage;
    }

    /**
     * @param string $folder
     * @param int $uid
     * @return \TYPO3\CMS\Core\Resource\File[]
     */
    protected function findImages($folder, $uid){
        $images = FileUtility::getFiles($uid . ':' . $folder);
        if(empty($images)){
            $subfolders = FileUtility::getSubfolders($uid . ':' . $folder);
            $subfolderCount = count($subfolders);
            if ($subfolderCount>0) {
                for ($i = 0; $i < $subfolderCount && empty($images); $i++) {
                    /** @var \TYPO3\CMS\Core\Resource\Folder $subfolder */
                    $subfolder = current($subfolders);
                    if(!empty($subfolder)) {
                        $images = $this->findImages($subfolder->getIdentifier(), $uid);
                    }
                    next($subfolders);
                }
            }
        }
        return $images;
    }


    public function test($folder) {
        $image = $folder->getPreviewImage();
        if (empty($image)) {
            $subfolders = FileUtility::getSubfolders($folder);
            $subfolderCount = count($subfolders);
            if ($subfolderCount>0) {
                for ($i=0; $i<$subfolderCount && empty($image); $i++) {
                    $subfolder = next($subfolders);
                    $image = $this->test($subfolder);
                }

            }
         }
        return $image;
    }

}

?>
