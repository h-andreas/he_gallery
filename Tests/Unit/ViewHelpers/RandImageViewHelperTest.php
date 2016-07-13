<?php


class RandImageViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    public function testIsEmptyIfNoFolderGivenToRender(){

        $randImageVH = new HSE\HeGallery\ViewHelpers\RandImageViewHelper();

        $temp = $randImageVH->render('',1);

        $this->assertNotEmpty($temp);

    }

    public function testIsEmptyIfNoFolderAndWrongUidGivenToRender(){

        $randImageVH = new HSE\HeGallery\ViewHelpers\RandImageViewHelper();

        $temp = $randImageVH->render('',2);

        $this->assertNotEmpty($temp);

    }

}


