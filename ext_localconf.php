<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'HSE.' . $_EXTKEY,
	'Imagedisplay',
	array(
		'Gallery' => 'list, listFolders, showFolder, goBack',
	),
	// non-cacheable actions
	array(
		'Gallery' => 'list, listFolders, showFolder, goBack',
	)
);
