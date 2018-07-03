<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\OrderPdf\Service;

$includePath = get_include_path().';'.__DIR__.'/../vendor/tcpdf-lib';
$includePath = $includePath.';'.__DIR__.'/../vendor/FPDI-lib';
set_include_path($includePath);

require_once __DIR__.'/../vendor/tcpdf-lib/tcpdf.php';
require_once __DIR__.'/../vendor/FPDI-lib/fpdi.php';

/**
 * FPDIのラッパークラス.
 */
abstract class AbstractFPDIService extends \FPDI
{
}
