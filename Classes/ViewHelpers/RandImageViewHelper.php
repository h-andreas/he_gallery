<?php

namespace HSE\HeGallery\ViewHelpers;

use HSE\HeGallery\Utility\FileUtility;
use TYPO3\CMS\Core\Resource\File;

class RandImageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
     * @param string $key
     * @return string
     */
    public function render($key){
        $images = FileUtility::getFiles($key);
        $randImage = array_rand($images);
        return $randImage;

    }

}

?>
