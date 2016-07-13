<?php
namespace HSE\HeGallery\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * GalleryController
 */
class FileUtility
{


   // hier die Methode, um aus einem Verzeichnis die Dateien auszulesen:
    /**
     * get all Files from given folder
     *
     * @param integer $storageId
     * @param String $imageFolder
     * @return \TYPO3\CMS\Core\Resource\File[]
     */


    public static  function getFiles($storageId, $imageFolder) {
      $files = array();

      if (!empty($imageFolder)) {
        /** @var \TYPO3\CMS\Core\Resource\Folder $folderObject */
        $folderObject = self::getFolderObject($storageId, $imageFolder);

        if (!empty($folderObject)) {
          /**@var \TYPO3\CMS\Core\Resource\File[] $files */
          $files = $folderObject->getFiles(0, 999);
        }

      }

      return $files;
    }

  /**
   * get all Subfolders from given folder
   *
   * @param integer $storageId
   * @param String $imageFolder
   * @return \TYPO3\CMS\Core\Resource\Folder[]
   */
    public static function getSubfolders($storageId, $imageFolder) {

    /** @var \TYPO3\CMS\Core\Resource\Folder $folderObject */
    $folderObject = self::getFolderObject($storageId, $imageFolder);

    /**@var \TYPO3\CMS\Core\Resource\Folder[] $files */
    $folderList = $folderObject->getSubfolders(0, 999);

    return $folderList;
  }


  /**
   * get folder object
   *
   * @param integer $storageId
   * @param String $imageFolder
   *
   * @throws \InvalidArgumentException
   * @return \TYPO3\CMS\Core\Resource\Folder
   */
  public static function getFolderObject($storageId, $imageFolder) {
    if (!is_numeric($storageId)) {
      throw new \InvalidArgumentException('storageId has to be numeric.');
    }
    /** @var $resourceFactory \TYPO3\CMS\Core\Resource\ResourceFactory */
    $resourceFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeinstance(\TYPO3\CMS\Core\Resource\ResourceFactory::class);
    $storage = $resourceFactory->getStorageObject($storageId);
    if (!$storage->hasFolder($imageFolder)) {
      $imageFolder = $storage->getFolderIdentifierFromFileIdentifier($imageFolder);
    }
    /** @var \TYPO3\CMS\Core\Resource\Folder $folderObject */
    $folderObject = $storage->getFolder($imageFolder);
    return $folderObject;
  }


}


