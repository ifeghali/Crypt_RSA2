<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Test suite
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

require_once 'Crypt/RSA2.php';
require_once __DIR__.'/LipsumTest.php';
require_once __DIR__.'/ChunkTest.php';

class Crypt_RSA2_AllTests extends PHPUnit_Framework_TestCase
{
    public static function suite() {

        $suite = new PHPUnit_Framework_TestSuite('Crypt_RSA2 Test Suite');
        $suite->addTestSuite('Crypt_RSA2_LipsumTest');
        $suite->addTestSuite('Crypt_RSA2_ChunkTest');
        return $suite;

    }
}
