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
$szRootAnnDir = '/net/per920a/export/das14a/satoh-lab/plsang/coco2014/annotations';
$szRootKfDir = '../images';
$szCVPR2014Root = './im2cap-cvpr2015';

$szSet = $_REQUEST['vSet'];
$szMode = $_REQUEST['vMode'];
$szConceptName = $_REQUEST['vConceptName'];

if(isset($_REQUEST['vPage']))
    $nPageID = intval($_REQUEST['vPage']);
else 
	$nPageID = 0;

//$szFilter = $_REQUEST['vFilter'];
//$szPat = $_REQUEST['vPat'];

$szPageURL = sprintf("%s?vConceptName=%s&vSet=%s", $_SERVER['PHP_SELF'],
$szConceptName, $szSet);

$szPageParam = sprintf("vConceptName=%s&vSet=%s&vMode=%s", $szConceptName, $szSet, $szMode);

$szFPConceptVideosFN = sprintf("%s/%s/%s.txt", $szRootAnnDir, $szSet, $szConceptName);

if(!file_exists($szFPConceptVideosFN))
{
	printf("<P><H3>File [%s] does not exist!\n", $szFPConceptVideosFN);
	exit();
}

//$arNSCAnnList[$szConceptName][$szVideoID][$szShotID][$szKeyFrameID] = $szLabel;
loadListFile($arConceptVideos, $szFPConceptVideosFN);
//print_r($arNSCAnnList);

// loading look up table

//print_r($lookup);

$nNumVideos = sizeof($arConceptVideos);



printf("<P><H1> Images in [Set: %s] - [Category: %s ] </H1></P>\n", $szSet, $szConceptName );
//printf("<P><H2> Total samples [%s]: %d</H2></P>\n", $szFilter, $nNumKeyFrames);

//$nMaxItemsPerPage = max(50, $_REQUEST['vMaxItemsPerPage']);
$nMaxItemsPerPage = 50;

$nStartID = $nPageID*$nMaxItemsPerPage;
$nEndID = min(($nPageID+1)*$nMaxItemsPerPage, $nNumVideos);

$nNumPages = intval(($nNumVideos+$nMaxItemsPerPage-1)/$nMaxItemsPerPage);


printf("<P><H3>Page: ");
for($i=0; $i<$nNumPages; $i++)
{
    if($i!=$nPageID)
    {
        printf("<A HREF='%s?vPage=%s&%s'>%02d</A> ", $_SERVER['PHP_SELF'], $i, $szPageParam, $i+1);
    }
    else
    {
        printf("%02d ", $i+1);
    }
}
printf("</H3>\n");
//print_r($arData);

// load list keyframes of videoID
$szKFVideosDir = sprintf("%s/%s", $szRootKfDir, $szSet);

if(!file_exists($szKFVideosDir))
{
	printf("<P><H3>File [%s] does not exist!\n", $szKFVideosDir);
	exit();
}

printf("<table border=\"5\" cellpadding=\"5\">");
printf("<tr>");
$nCols = 5;
$nCountItem = 0;

for ($ii=$nStartID; $ii<$nEndID; $ii++) 
{   
	$nCountItem++;
	printf("<td>");
	
	$szKeyFrameID = $arConceptVideos[$ii];
	if (substr($szKeyFrameID, -strlen(".jpg")) === ".jpg"){
		$szImgURL = sprintf("%s/%s", $szKFVideosDir, $szKeyFrameID);
		$szDetectParams = sprintf("nsc-web-detect-object.php?vSet=%s&vConceptName=%s&vKeyFrameID=%s&vMode=%s", $szSet, $szConceptName, $szKeyFrameID, $szMode);
		printf("<A HREF='%s'>[Detect object] </A> <IMG ALT='%s' SRC='%s' BORDER='0' WIDTH='300' />\n", $szDetectParams, $szKeyFrameID, $szImgURL);
        
        //
        $cvpr2015_detected_file = sprintf('%s/%s/%s.txt', $szCVPR2014Root, $szSet, basename($szKeyFrameID, '.jpg'));
        if(file_exists($cvpr2015_detected_file)){
            loadListFile($arCVPR2015DetectedConcepts, $cvpr2015_detected_file);
            printf("<br>%s", $arCVPR2015DetectedConcepts[0]);
        }
	}
	
	printf("</td>");
	
	if($nCountItem % $nCols == 0)
	{
        printf("</tr>");
		printf("<tr>");
	}
}

printf("</table>");

?>
