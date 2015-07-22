<?php

/**
 * 		Do concept annotation - Web app.
 *
 * 		Copyright (C) 2010 Duy-Dinh Le
 * 		All rights reserved.
 * 		Email		: ledduy@gmail.com, ledduy@ieee.org.
 * 		Version		: 1.0.
 * 		Last update	: 13 Jan 2010.
 */

require_once "nsc-web-AppConfig.php";
require_once "nsc-TRECVIDTools.php";

set_time_limit ( 60 );

/*
$szRootProjectDir = "/net/per900b/raid0/ledduy/nii-secode2";
$szRootVideoArchiveDir = "/net/per900b/raid0/ledduy/video.archive";
$szVideoArchiveName = "trecvid";
$szRootKeyFrameDir = sprintf("../../../../video.archive/keyframe/%s", $szVideoArchiveName);
$szRootAnnDir = sprintf("%s/metadata/annotation/%s", $szRootProjectDir, $szVideoArchiveName);
*/
$szRootProjectDir = $gszRootProjectDir;
$szRootVideoArchiveDir = $gszRootVideoArchiveDir;
$szVideoArchiveName = $gszVideoArchiveName;
$szRootKeyFrameDir = $gszRootKeyFrameDir;

$szRootAnnDir = '../annotations';
$szRootKfDir = '../images';
$szTmpDir = '/net/per610a/export/das11f/plsang/coco2014/selective_search';

$szSet = $_REQUEST['vSet'];
$szKeyFrameID = $_REQUEST['vKeyFrameID'];
$szMode = $_REQUEST['vMode'];

if($szMode == 'fast'){
    $szRootRCNNOutput='../fast_rcnn_draw';
    $szRootSelectiveBox='/net/per610a/export/das11f/plsang/coco2014/fast_rcnn_boxes';
} else{
    $szRootRCNNOutput='../fast_rcnn_draw_full';
    $szRootSelectiveBox='/net/per610a/export/das11f/plsang/coco2014/fast_rcnn_boxes_full';
}


$szRootKfDir = '../images';
$szKFVideosDir = sprintf("%s/%s", $szRootKfDir, $szSet);
$szImgURL = sprintf("%s/%s", $szKFVideosDir, $szKeyFrameID);

$szRCNNImgURL = sprintf("%s/%s/%s", $szRootRCNNOutput, $szSet, $szKeyFrameID);
//printf('%s - %s - %s', $szSet, $szConceptName, $szKeyFrameID);

if (!file_exists($szRCNNImgURL)){
	
	$arCmdLine[] = sprintf('cd %s', $szTmpDir);
	$arCmdLine[] = sprintf('source bash.sh');
	$arCmdLine[] = sprintf('export HOME=/net/per610a/export/das11f/plsang/coco2014/selective_search');

	//$szSelectiveBoxFile = sprintf("%s/%s/%s.mat", $szRootSelectiveBox, $szSet, basename($szKeyFrameID, '.jpg'));
    
	//if (!file_exists($szSelectiveBoxFile)){
	//	$szCmdLine = sprintf("/usr/local/bin/matlab -nodisplay -r \"run_selective_search('%s', '%s')\"", $szImgURL, $szSet);
	//	$arCmdLine[] = $szCmdLine;
	//}
	
	//$szCmdLine = sprintf('/net/per900a/raid0/plsang/usr.local/bin/python /net/per610a/export/das11f/plsang/coco2014/fast-rcnn/demo.py --cpu --img %s --set %s', basename($szKeyFrameID, '.jpg'), $szSet);
    $szCmdLine = sprintf('/net/per900a/raid0/plsang/usr.local/bin/python /net/per610a/export/das11f/plsang/codes/coco/fast-rcnn/fastrcnn_draw_object.py --cpu --img %s --set %s --ssmode %s', basename($szKeyFrameID, '.jpg'), $szSet, $szMode);    

	$arCmdLine[] = $szCmdLine;

	$szFPCmdFN = sprintf('%s/%s.sh', $szTmpDir, $szKeyFrameID);
	saveDataFromMem2File($arCmdLine, $szFPCmdFN);

	$szCmdLine = sprintf("chmod +x %s", $szFPCmdFN);
	system($szCmdLine);

	system($szFPCmdFN);
	deleteFile($szFPCmdFN);
}

if ($szMode == 'fast'){
    $szNewMode = 'full';
}else{
    $szNewMode = 'fast';
}

$szPageParams = sprintf("vSet=%s&vKeyFrameID=%s&vMode=%s", $szSet, $szKeyFrameID, $szNewMode);
$szPageUrl = sprintf('%s?%s', $_SERVER['PHP_SELF'], $szPageParams);
$szNotice = sprintf('Detect object using [%s] mode', $szNewMode);

$szRCNNImgURL = sprintf("%s/%s/%s", $szRootRCNNOutput, $szSet, $szKeyFrameID);
printf("<div align='center'><A HREF='%s' TARGET='_blank'><h3>[%s]</h3> </A> <br> <IMG ALT='%s' SRC='%s' BORDER='5'/></div>\n", $szPageUrl, $szNotice, $szKeyFrameID, $szRCNNImgURL);

//deleteFile($szFPCmdFN);

?>
