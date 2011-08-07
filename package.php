<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Generates package.xml
 *
 * PHP version 5
 *
 * LICENSE: 
 *
 * Copyright (c) 2011 Igor Feghali
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without 
 * modification, are permitted provided that the following conditions are met:
 *
 *  Redistributions of source code must retain the above copyright notice, this 
 *  list of conditions and the following disclaimer.
 *
 *  Redistributions in binary form must reproduce the above copyright notice, 
 *  this list of conditions and the following disclaimer in the documentation 
 *  and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE 
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE 
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE 
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR 
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF 
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS 
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN 
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) 
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE 
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Encryption
 * @package   Crypt_RSA2
 * @author    Igor Feghali <ifeghali@php.net>
 * @copyright 1997-2011 The PHP Group
 * @license   http://opensource.org/licenses/bsd-license.php BSD License
 * @version   SVN: $Id$
 * @link      http://pear.php.net/package/Crypt_RSA2
 * @see       Crypt_RSA
 * @since     File available since Release 0.1.0
 */

require_once 'PEAR/PackageFileManager2.php';

$package = new PEAR_PackageFileManager2();

$res = $package->setOptions(
    array(
        'baseinstalldir' => 'Crypt',
        'filelistgenerator' => 'file',
        'packagedirectory' => dirname(__FILE__),
        'simpleoutput' => true,
        'cleardependencies' => true,
        'clearcontents' => true,
        'packagefile' => 'package.xml',
        'ignore' => array(
            'package.php',
            '*.tgz',
        ),
    )
);

if (PEAR::isError($res)) {
    printf("%s\n", $res->getMessage());
    exit(1);
}

$package->setPackageType('php');
$package->setPackage('Crypt_RSA2');
$package->setChannel('pear.php.net');

$package->addMaintainer('lead', 'ifeghali', 'Igor Feghali', 'ifeghali@php.net');
$package->setLicense('PHP License', 'http://www.php.net/license');

$package->addRelease();
$package->setReleaseVersion('0.1.0');
$package->setAPIVersion('0.1.0');
$package->setReleaseStability('alpha');
$package->setAPIStability('alpha');
$package->setNotes('Initial release');

$package->generateContents();

$package->setPhpDep('5.2.0');
$package->setPearInstallerDep('1.7.0');
$package->addExtensionDep('required', 'gmp');
$package->detectDependencies();

$package->setSummary('Provides RSA-like key generation, encryption and 
    decryption');
$package->setDescription('This is Crypt_RSA rewritten from scratch for PHP 5+.  
    It relies only in the GMP extension, so the source code is clearer and 
    lighter. Also, RSA_Crypt2 aims to be fully compatible with jCryption, a 
    jQuery plugin which sums up as a great javascript frontend.');

if (isset($_GET['make'])
    || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')
) {

    $res = $package->writePackageFile();

    if (PEAR::isError($res)) {
        printf("%s\n", $res->getMessage());
        exit(1);
    }

} else {

    $res = $package->debugPackageFile();

    if (PEAR::isError($res)) {
        printf("%s\n", $res->getMessage());
        exit(1);
    }
} 
