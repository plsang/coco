<?php

/**
 * 		View concept annotation form - Web app.
 *
 * 		Copyright (C) 2010 Duy-Dinh Le
 * 		All rights reserved.
 * 		Email		: ledduy@gmail.com, ledduy@ieee.org.
 * 		Version		: 1.0.
 * 		Last update	: 13 Jan 2010.
 */

require_once "nsc-web-AppConfig.php";
require_once "nsc-TRECVIDTools.php";

// show form
$szRootProjectDir = $gszRootProjectDir;
$szVideoArchiveName = $gszVideoArchiveName;
$szFPConceptListMapFN = '/net/per920a/export/das14a/satoh-lab/plsang/coco2014/annotations/val2014.txt';

loadListFile($arConceptNameList, $szFPConceptListMapFN);
$nNumConcepts = sizeof($arConceptNameList);

printf("<P><H1>Object Detection Demo (on MS CoCo dataset)</H1></P>\n");

printf("<P><H2>-- The object detection models were trained on the PASCAL VOC 2007 dataset which contains 20 categories: </H2></P>\n");
         
printf("<P><H3> aeroplane, bicycle, bird, boat, bottle, bus, car, cat, chair, cow, diningtable, dog, horse, motorbike, person, pottedplant, sheep, sofa, train, tvmonitor. </H3></P>\n");
		   
printf("<FORM ACTION='nsc-web-ViewExtractedKeyframe.php' TARGET='_blank'>\n");

printf("<P><H3>Select Set: <BR>\n");
printf("<SELECT NAME='vSet'>\n");

printf("<OPTION VALUE='train2014'>train2014</OPTION>\n");
printf("<OPTION VALUE='val2014' SELECTED>val2014</OPTION>\n");
//printf("<OPTION VALUE='test2014'>test2014</OPTION>\n");

printf("</SELECT>\n");

printf("<P><H3>Select MSCoCo Categories:\n");
printf("<P><H4> (-- Select a category that also appears in the VOC dataset to get more reasonable results.) <BR>\n");

printf("<SELECT NAME='vConceptName'>\n");

for($i=0; $i<$nNumConcepts; $i++)
{
	$szConceptName = $arConceptNameList[$i];
	
	$splits = explode(' >.< ', $szConceptName);
	
	printf("<OPTION VALUE='%s'>%s</OPTION>\n", $szConceptName, $szConceptName);
}
printf("</SELECT>\n");

printf("<P><H3>Selective Search Mode: <BR>\n");
printf("<SELECT NAME='vMode'>\n");
printf("<OPTION VALUE='fast'>Fast</OPTION>\n");
printf("<OPTION VALUE='full'>Full</OPTION>\n");
printf("</SELECT>\n");

printf("<P><INPUT TYPE='SUBMIT' value='Submit' name='vSubmit'>\n");
printf("</FORM>\n");

?>