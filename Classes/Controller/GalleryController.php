<?php
namespace HSE\HeGallery\Controller;

use HSE\HeGallery\Utility\FileUtility;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
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
   *  initializeAction
   *
   *  add css and js file concerning to TypoScript settings
   *
   * @return void
   */
  public function initializeAction() {

    $extPath =  ExtensionManagementUtility::siteRelPath('he_gallery');
    /** @var \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer */
    $pageRenderer = $this->objectManager->get(PageRenderer::class);

    if (intval($this->settings['addVenoboxCss'])==1) {
      $pageRenderer->addCssFile($extPath . 'Resources/Public/venobox/venobox.css');
    }

    if (intval($this->settings['addVenoboxJs'])==1) {
      $pageRenderer->addJsFooterFile($extPath . 'Resources/Public/venobox/venobox.js');
    }
    if (intval($this->settings['addMasonryJs'])==1) {
      $pageRenderer->addJsFooterFile($extPath . 'Resources/Public/masonry/masonry.pkgd.js');
      $pageRenderer->addJsFooterFile($extPath . 'Resources/Public/js/he_gallery_masonry.js');
      $pageRenderer->addCssFile($extPath . 'Resources/Public/css/he_gallery_masonry.css');
    } else {
      $pageRenderer->addJsFooterFile($extPath . 'Resources/Public/js/he_gallery.js');
      $pageRenderer->addCssFile($extPath . 'Resources/Public/css/he_gallery.css');
    }

  }


  /**
   * action list
   *
   * @return void
   */
  public function listAction(){

    $cssClass = $this->settings['layout'];
    $storageFolder = $this->settings['imageFolder'];
    $storageArray = explode(':', $storageFolder);

    $currentFolder = $storageArray[1];
    $masonry = $this->settings['addMasonryJs'];


    $images = FileUtility::getFiles($storageArray[0], $storageArray[1]);

    $this->view->assign('images', $images);
    $this->view->assign('cssClass', $cssClass);
    $this->view->assign('currentFolder', $currentFolder);
    $this->view->assign('masonry', $masonry);
  }

  /**
   * action listFolders
   *
   * @return void
   */
  public function listFoldersAction(){

    $cssClass = $this->settings['layout'];
    $storageFolder = $this->settings['imageFolder'];
    $masonry = $this->settings['addMasonryJs'];

    $storageArray = explode(':', $storageFolder);
    $subfolders = FileUtility::getSubfolders($storageArray[0], $storageArray[1]);
    $uid = $storageArray[0];
    $initialFolder = $storageArray[1];
    $currentFolder = $initialFolder;

    if(!empty($subfolders)) {
      $this->view->assign('subfolders', $subfolders);
    } else {
      $images = FileUtility::getFiles($storageArray[0], $storageArray[1]);
      $this->view->assign('images', $images);
    }

    $this->view->assign('uid', $uid);
    $this->view->assign('cssClass', $cssClass);
    $this->view->assign('initialFolder', $initialFolder);
    $this->view->assign('currentFolder', $currentFolder);
    $this->view->assign('masonry', $masonry);

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
    $masonry = $this->settings['addMasonryJs'];
    $temp = substr($subfolder,0,strrpos($subfolder,"/"));
    $parentFolder = substr($temp,0,strrpos($temp,"/")) . '/';
    $subsubfolders = FileUtility::getSubfolders($uid, $subfolder);
    $currentFolder = $subfolder;

    if(empty($subsubfolders)) {
      $images = FileUtility::getFiles($uid, $subfolder);
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
    $this->view->assign('masonry', $masonry);
  }
}


