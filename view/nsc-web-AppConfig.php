<?php

/**
 * 		Configuration file for NII-SECODE Web App.
 *
 * 		Copyright (C) 2010 Duy-Dinh Le.
 * 		All rights reserved.
 * 		Email		: ledduy@gmail.com, ledduy@ieee.org.
 * 		Version		: 1.0.
 * 		Last update	: 08 Jan 2010.
 */
 
require_once "nsc-AppConfig.php";

$gszRootProjectDir = "..";
$gszVideoArchiveName = "trecvidmed11";
$gszFPConceptListMapFN = sprintf("%s/annotation/keyframe-7/%s.lst", $gszRootProjectDir, $gszVideoArchiveName);

$gszRootVideoArchiveDir = "./videolink";
$gszRootKeyFrameDir = sprintf("../keyframe-7");
$gszRootMetadataDir = sprintf("../metadata/keyframe-7");
$gszRootAnnDir = sprintf("%s/annotation", $gszRootProjectDir);
$gszRootTmpDir = sprintf("%s/tmp", $gszRootProjectDir);
$gszExpResultsDir = sprintf('../experiments/trecvidmed11-5/results');

$gszAnnOrg = "NII";
$gszAnnotatorName = "nsc";
?>
