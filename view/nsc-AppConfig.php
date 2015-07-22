<?php

/**
 * 		Configuration file for NII-SECODE App.
 *
 * 		Copyright (C) 2010 Duy-Dinh Le.
 * 		All rights reserved.
 * 		Email		: ledduy@gmail.com, ledduy@ieee.org.
 * 		Version		: 1.0.
 * 		Last update	: 25 Nov 2010.
 * 		Test comment
 */
 
// Update Nov 25
// $gszFeatureFormat = "dvf";  --> used for supporting both dvf and svf in train and test

// Update Oct 01
// Remove some unused parts 
// /net/per900b/raid0/ledduy/kaori-core/php
/*
require_once "../../kaori-core/php/kaori-lib/AppConfig.php";
require_once "../../kaori-core/php/kaori-lib/IOTools.php";
require_once "../../kaori-core/php/kaori-lib/MiscTools.php";
require_once "../../kaori-core/php/kaori-lib/DataProcessingTools.php";
require_once "../../kaori-core/php/kaori-lib/SVMTools.php";
*/
// 25-July lqvu Update Directory
require_once "/net/per900a/raid0/plsang/tools/kaori-core/php/kaori-lib/AppConfig.php";
require_once "/net/per900a/raid0/plsang/tools/kaori-core/php/kaori-lib/IOTools.php";
require_once "/net/per900a/raid0/plsang/tools/kaori-core/php/kaori-lib/MiscTools.php";
require_once "/net/per900a/raid0/plsang/tools/kaori-core/php/kaori-lib/DataProcessingTools.php";
require_once "/net/per900a/raid0/plsang/tools/kaori-core/php/kaori-lib/SVMTools.php";

// 14-Sep plsang turned on error reporting
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

// Global vars

//
$gszDelim = "#$#";

$garLabelList = array("P" => "Pos", "N" => "Neg", "S" => "Skipped");
$garInvLabelList = array("Pos" => "P", "Neg" => "N", "Skipped" => "S");
$garLabelValList = array(1 => "Pos", -1 => "Neg", 0 => "Skipped");
$garInvLabelValList = array("Pos" => 1, "Neg" => -1);
$garLabelMapList = array(1 => "P", -1 => "N", 0 => "S");
$gszPosLabel = "Pos";
$gszNegLabel = "Neg";


// specific for TRECVID
$garPatList = array("devel", "test");
$garFrameRateList = array(
"tv2005" => 30, 
"tv2006" => 29.97, 
"tv2007" => 25, 
"tv2008" => 25, 
"tv2009" => 25, 
"tv2010" => 30);

$gszTopRootDir = "/net/per900b/raid0/ledduy";

$gszBinDir = sprintf("%s/bin", $gszTopRootDir);

// kaori-core
$gszKaoriCoreBinApp = sprintf("%s/kaori-core", $gszBinDir); 
$gszSVMTrainApp = sprintf("%s/libsvm291/svm-train", $gszKaoriCoreBinApp);
$gszSVMPredictScoreApp = sprintf("%s/libsvm291/svm-predict-score", $gszKaoriCoreBinApp);
$gszGridSearchApp = sprintf("%s/libsvm291/grid.py", $gszKaoriCoreBinApp);
$gszSVMSelectSubSetApp = sprintf("%s/libsvm291/subset.py", $gszKaoriCoreBinApp);
$gszSVMScaleApp = sprintf("%s/libsvm291/svm-scale", $gszKaoriCoreBinApp);


$gfPosWeight = 1000;
$gfNegWeight = 1;
$gnMemSize = 1000;
$gnStartC = 0;
$gnEndC = 6;
$gnStepC = 2;
$gnStartG = -20;
$gnEndG = 4;
$gnStepG = 2;

$gszFeatureFormat = "dvf";

/// FOR SQLLite - Oct 23
putenv('TMP=C:/Windows/Temp');



?>
