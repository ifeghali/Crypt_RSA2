<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Example of usage with pre-defined key pair/modulus
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

require_once 'RSA2.php';

$keys = array(
    'private' => '1577597842145141215827386235859308400425266316360320390046723701921007764072494952495805328844759452264690691144783883056266168408068425101194683691823699',
    'public'  => '16868564594156022989725591704399456821824428281405316464169548387533268815999',
    'modulus' => '1853526524131188981158040553308853671808448509043519635769310316020112131248265030543220015716743714039722754383561986488762816536214342882258952530824273'
);

$rsa = new Crypt_RSA2($keys);

$encrypted = $rsa->encrypt('top secret message');
var_dump($encrypted);

$decrypted = $rsa->decrypt($encrypted);
var_dump($decrypted);
