<?php
namespace HSE\HeGallery\Controller;

use HSE\HeGallery\Utility\FileUtility;
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
class GalleryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{


    /**
     * action list
     *
     * @return void
     */
    public function listAction(){

        $cssClass = $this->settings['layout'];
        $imageFolder = $this->settings['imageFolder'];
        $currentFolder = substr($imageFolder, 2, strlen($imageFolder));

        $images = FileUtility::getFiles($imageFolder);

/*
        foreach ($images as $imageItems) {
            $imageidentifiers[] = $imageItems->getIdentifier();
        }
        foreach ($imageidentifiers as $imageidentifierItems) {
            $imagePaths[] = '/fileadmin' . $imageidentifierItems;
        }
        //$imagePaths[] = '/fileadmin' . $imageidentifiers;

        // Hier eine Methode, um die Bildmasse abzufragen:
        // $imageDimension = getimagesize($absImgPath);
*/
        $this->view->assign('images', $images);
        $this->view->assign('cssClass', $cssClass);
        $this->view->assign('currentFolder', $currentFolder);
    }

    /**
     * action listFolders
     *
     * @return void
     */
    public function listFoldersAction(){

        $cssClass = $this->settings['layout'];
        $imageFolder = $this->settings['imageFolder'];

        $subfolders = FileUtility::getSubfolders($imageFolder);
        $uid = substr($imageFolder, 0, strpos($imageFolder, ':'));
        $initialFolder = substr($imageFolder, strpos($imageFolder, ':') + 1);

       if(!empty($subfolders)) {
           $this->view->assign('subfolders', $subfolders);
       } else {
           $images = FileUtility::getFiles($imageFolder);
           $this->view->assign('images', $images);
       }

        $this->view->assign('uid', $uid);
        $this->view->assign('cssClass', $cssClass);
        $this->view->assign('initialFolder', $initialFolder);
    }

    /**
     * action showFolder
     *
     * @param string $subfolder
     * @param int $uid
     * @param string $initialFolder
     * @return void
     */
    public function showFolderAction($subfolder, $uid, $initialFolder){

        $cssClass = $this->settings['layout'];
        $temp = substr($subfolder,0,strrpos($subfolder,"/"));
        $parentFolder = substr($temp,0,strrpos($temp,"/")) . '/';
        $subsubfolders = FileUtility::getSubfolders($uid . ':' . $subfolder);
        $currentFolder = $subfolder;

        if(empty($subsubfolders)) {
            $images = FileUtility::getFiles($uid . ':' . $subfolder);
            $this->view->assign('images', $images);
        } else {
            $this->view->assign('subfolders', $subsubfolders);
        }

        $this->view->assign('subfolder', $subfolder);
        $this->view->assign('uid', $uid);
        $this->view->assign('cssClass', $cssClass);
        $this->view->assign('parentFolder', $parentFolder);
        $this->view->assign('initialFolder', $initialFolder);
        $this->view->assign('currentFolder', $currentFolder);
    }

    // hier die Methode, um aus einem Verzeichnis die Dateien auszulesen:
    /**
     * get all Files from given folder
     *
     * @param String $imageFolder
     * @return \TYPO3\CMS\Core\Resource\File[]
     */
    protected function getFiles($imageFolder) {
      /** @var \TYPO3\CMS\Core\Resource\Folder $folderObject */
      $folderObject = $this->getFolderObject($imageFolder);

      /**@var \TYPO3\CMS\Core\Resource\File[] $files */
      $files = $folderObject->getFiles(0, 999);

      return $files;
    }

  /**
   * get all Subfolders from given folder
   *
   * @param String $imageFolder
   * @return \TYPO3\CMS\Core\Resource\Folder[]
   */
  protected function getSubfolders($imageFolder) {
    /** @var \TYPO3\CMS\Core\Resource\Folder $folderObject */
    $folderObject = $this->getFolderObject($imageFolder);

    /**@var \TYPO3\CMS\Core\Resource\Folder[] $files */
    $folderList = $folderObject->getSubfolders(0, 999);

    return $folderList;
  }


  /**
   * get folder object
   *
   * @param String $imageFolder
   * @return \TYPO3\CMS\Core\Resource\Folder
   */
  protected function getFolderObject($imageFolder) {
    /** @var $resourceFactory \TYPO3\CMS\Core\Resource\ResourceFactory */
    $resourceFactory = $this->objectManager->get(\TYPO3\CMS\Core\Resource\ResourceFactory::class);
    $storage = $resourceFactory->getStorageObjectFromCombinedIdentifier($imageFolder);
    $identifier = substr($imageFolder, strpos($imageFolder, ':') + 1);
    if (!$storage->hasFolder($identifier)) {
      $identifier = $storage->getFolderIdentifierFromFileIdentifier($identifier);
    }
    /** @var \TYPO3\CMS\Core\Resource\Folder $folderObject */
    $folderObject = $resourceFactory->getFolderObjectFromCombinedIdentifier($storage->getUid() . ':' . $identifier);
    return $folderObject;
  }


}


