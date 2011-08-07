<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Main class for Crypt_RSA2
 *
 * PHP version 5
 *
 * LICENSE: 
 *
 * Copyright (c) 2011 Mishu Zvancu, Igor Feghali
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
 * @author    Mishu Zvancu <mishu@directaccess.ro>
 * @author    Igor Feghali <ifeghali@php.net>
 * @copyright 1997-2011 The PHP Group
 * @license   http://opensource.org/licenses/bsd-license.php BSD License
 * @version   SVN: $Id$
 * @link      http://pear.php.net/package/Crypt_RSA2
 * @see       Crypt_RSA
 * @since     File available since Release 0.1.0
 */

require_once 'RSA2/Exception.php';

/**
 * This software is heavily based on the code written by Mishu Zvancu, that can 
 * be found in the following URL:
 *
 *  http://goo.gl/owhBU
 *
 * All the information about RSA was taken from:
 *
 *  http://en.wikipedia.org/wiki/Rsa
 *
 * It aims to be compatible with the jQuery plugin jCryption, by Daniel 
 * Griesseri that can be found in:
 *
 *  http://www.jcryption.org/
 *
 * @category  Encryption
 * @package   Crypt_RSA2
 * @author    Mishu Zvancu <mishu@directaccess.ro>
 * @author    Igor Feghali <ifeghali@php.net>
 * @copyright 1997-2011 The PHP Group
 * @license   http://opensource.org/licenses/bsd-license.php BSD License
 * @version   SVN: $Id$
 * @link      http://pear.php.net/package/Crypt_RSA2
 * @see       Crypt_RSA
 * @since     File available since Release 0.1.0
 */
class Crypt_RSA2
{
    /**
     * Private (or decryption) exponent, as a string of a big int
     *
     * @var string
     */
    private $_privateKey = null;

    /**
     * Public (or encryption) exponent, as a string of a big int
     *
     * @var string
     */
    private $_publicKey = null;

    /**
     * Modulus for both the public and private keys. It is a product of two 
     * prime numbers. String of a big integer.
     *
     * @var string
     */
    private $_modulus = null;

    /**
     * Constructor
     *
     * @param array $keys optional array containing public and private keys, 
     *                    and also the modulus. values must be a string
     *                    representing a decimal number.
     *
     * @return void
     * @throws Crypt_RSA2_Exception
     * @since Method available since Release 0.1.0
     */
    function __construct($keys = null)
    {
        if ($keys) {

            $d = $keys['private'];
            $e = $keys['public'];
            $n = $keys['modulus'];

        } else {

            list($d, $e, $n) = self::generateRandomKeys();

        }

        try {

            $this->setKeys($d, $e, $n);

        } catch (Crypt_RSA2_Exception $e) {

            throw new Crypt_RSA2_Exception(
                'could not get a valid key pair', $e
            );

        }

    }

    /**
     * Ensures that all arguments are GMP resources, and returns them.
     *
     * @access protected
     * @static
     * @return array arguments initialized to GMP resource
     * @throws Crypt_RSA2_Exception
     * @since Method available since Release 0.1.0
     */
    protected static function initBigInt()
    {
        $return = func_get_args();

        foreach ($return as &$v) {
            if (!is_resource($v)
                || (get_resource_type($v) != 'GMP integer')
            ) {
                $v = gmp_init($v);

                if ($v === false) {
                    throw new Crypt_RSA2_Exception(
                        'not a valid integer'
                    );
                }
            }
        }

        return $return;
    }

    /**
     * Sets the current key pair/modulus
     *
     * @param string $d decimal representation of private key
     * @param string $e decimal representation of public key
     * @param string $n decimal representation of modulus
     *
     * @access public
     * @return void
     * @throws Crypt_RSA2_Exception
     * @since Method available since Release 0.1.0
     */
    public function setKeys($d, $e, $n)
    {
        try {
            list($d, $e, $n) = self::initBigInt($d, $e, $n);
        } catch (Crypt_RSA2_Exception $e) {
            throw new Crypt_RSA2_Exception('invalid key', $e);
        }

        $this->_privateKey = $d;
        $this->_publicKey  = $e;
        $this->_modulus    = $n;
    }

    /**
     * Generates a random key pair/modulus
     *
     * @access protected
     * @static
     * @return array GMP resources of key pair/modulus
     * @since Method available since Release 0.1.0
     */
    protected static function generateRandomKeys()
    {
        /**
         * Computing modulus
         */
        $p = self::getRandomPrime();
        $q = self::getRandomPrime();
        $n = gmp_mul($p, $q);

        /**
         * Computing Euler's totient function
         */
        $f = gmp_mul(gmp_sub($p, 1), gmp_sub($q, 1));

        /**
         * Computing the public key exponent
         */
        $e = gmp_random(4); 
        $e = gmp_nextprime($e);
        while (gmp_cmp(gmp_gcd($e, $f), 1) != 0) {
            $e = gmp_add($e, 1);
        }

        /**
         * Computing the private key exponent
         */
        $d = self::multiplicativeInverse($e, $f);

        return array($d, $e, $n);
    }

    /**
     * Public wrapper for generateRandomKeys()
     *
     * @access public
     * @static
     * @return array strings of key pair/modulus in decimal
     * @see generateRandomKeys()
     * @since Method available since Release 0.1.0
     */
    public static function getRandomKeys()
    {
        list($d, $e, $n) = self::generateRandomKeys();

        return array(
            'private' => gmp_strval($d),
            'public'  => gmp_strval($e),
            'modulus' => gmp_strval($n),
        );
    }

    /**
     * Calculates the modular multiplicative inverse as found in:
     *  http://en.wikipedia.org/wiki/Modular_multiplicative_inverse
     *
     * @param resource $e GMP resource number
     * @param resource $f GMP resource number
     *
     * @access protected
     * @static
     * @return resource calculated number as a GMP resource
     * @since Method available since Release 0.1.0
     */
    protected static function multiplicativeInverse($e, $f)
    {
        $M = $e;
        $N = $f;

        $u1 = 1;
        $u2 = 0;
        $u3 = $f;

        $v1 = 0;
        $v2 = 1;
        $v3 = $e;

        while (gmp_cmp($v3, 0) != 0) {
            $qq = gmp_div($u3, $v3);

            $t1 = gmp_sub($u1, gmp_mul($qq, $v1));
            $t2 = gmp_sub($u2, gmp_mul($qq, $v2));
            $t3 = gmp_sub($u3, gmp_mul($qq, $v3));

            $u1 = $v1;
            $u2 = $v2;
            $u3 = $v3;

            $v1 = $t1;
            $v2 = $t2;
            $v3 = $t3;
        }

        if (gmp_cmp($u2, 0) < 0) {

            return gmp_add($u2, $f);

        }

        return $u2;

    }

    /**
     * Returns a random prime number
     *
     * @param integer $limiter gmp_random's limiter
     *
     * @access protected
     * @static
     * @return resource GMP resource
     * @since Method available since Release 0.1.0
     */
    protected static function getRandomPrime($limiter = 4)
    {
        return gmp_nextprime(
            gmp_random($limiter)
        );
    }

    /**
     * This function returns the maximum chunk size our key can encrypt.
     *
     * The message m (as a number) should fit into the rule:
     *
     *      0 < m < modulus
     * 
     * So we have to split it into chunks before the encryption.
     *
     * For compatibility with jCryption, this is calculated by couting how many 
     * times we can break the modulus into slices of two bytes and the multiply 
     * this number by two, since each character in message m is traslated to a 
     * single byte. This ensures that the chunk will never have more bytes than 
     * (number of bytes in modulus, minus one). Note that this algorithm is 
     * sub-optimal, since for most cases it returns (number of bytes in modulus,
     * minus two), as shown below:
     *
     * CS OP MODULUS
     *  0  0 FF          
     *  0  1 FFF
     *  0  1 FFFF
     *  2  2 FFFFF
     *  2  2 FFFFFF
     *  2  3 FFFFFFF
     *  2  3 FFFFFFFF
     *  4  4 FFFFFFFFF
     *  4  4 FFFFFFFFFF
     *  4  5 FFFFFFFFFFF
     *  4  5 FFFFFFFFFFFF
     *  6  6 FFFFFFFFFFFFF
     *
     *  CS = this algorithm chunk size
     *  OP = optimus chunk size
     *
     * @return int the number of characters for a chunk
     * @access public
     * @since Method available since Release 0.1.0
     */
    public function chunkSize()
    {
        $j = 0;

        for (
            $n = $this->_modulus;
            gmp_strval($n = gmp_div($n, '0x10000'));
            $j++;
        );

        return $j*2;
    }

    /**
     * Encrypts a message using the RSA algorithm. If the message is big enough,
     * it is split into chunks that are encrypted one by one and separated by
     * a white space. Aims to be compatible with jquery plugin jCryption.
     *
     * @param string $message secret message
     *
     * @access public
     * @return string encrypted message
     * @throws Crypt_RSA2_Exception
     * @since Method available since Release 0.1.0
     */
    public function encrypt($message)
    {
        /**
         * holds encrypted message
         */
        $secret = array();

        /**
         * array of ascii values in hex
         */
        $message = str_split(bin2hex($message), 2);

        /**
         * calculates checksum of message
         */
        $checksum = $this->arraySumHex($message);

        /**
         * array of ascii values in hex
         */
        $checksum = str_split(bin2hex($checksum), 2);

        /**
         * inserts checksum at the beginning of message
         */ 
        $message = array_merge($checksum, $message);

        /**
         * we can only encrypt a limited number of chars at a time
         */
        $message = array_chunk($message, $this->chunkSize());

        foreach ($message as $chunk) {

            /**
             * reverses message for jCryption compatibility
             */
            $chunk = array_reverse($chunk);

            /**
             * turns message into a big integer
             */
            $chunk = gmp_init('0x'.(implode($chunk)));

            /**
             * do encryption
             */
            $secret[] = gmp_strval(
                gmp_powm($chunk, $this->_publicKey, $this->_modulus), 16
            );

        }

        return implode(' ', $secret);

    }

    /**
     * Decrypts a message using the RSA algorithm. Aims to be compatible with 
     * jquery plugin jCryption.
     *
     * @param string $secret encrypted message
     *
     * @access public
     * @return string decrypted message
     * @throws Crypt_RSA2_Exception
     * @since Method available since Release 0.1.0
     */
    public function decrypt($secret)
    {
        /**
         * holds decrypted message
         */
        $message = '';

        /**
         * splits message into array of chunks
         */
        $secret = explode(' ', $secret);

        foreach ($secret as $chunk) {

            /**
             * turns message into a big integer
             */
            $chunk = gmp_init('0x'.$chunk);

            /**
             * decrypts chunk, extracts string from hex code and reverses 
             * message for jCryption compatibility
             */
            $message .= strrev(
                pack(
                    'H*',
                    gmp_strval(
                        gmp_powm($chunk, $this->_privateKey, $this->_modulus), 16
                    )
                )
            );

        }

        $checksum = substr($message, 0, 2);
        $message  = substr($message, 2);
        $hexsum   = $this->arraySumHex(str_split(bin2hex($message), 2));

        if ($hexsum != $checksum) {
            return false;
        }

        return $message;
    }

    /**
     * Returns the last two hexadecimal digits of the sum of an array o 
     * hexadecimal numbers. This is intended to be used as the checksum of the 
     * message that is being encrypted.
     *
     * @param array $arr strings representing a 2-digit hex number
     *
     * @access protected
     * @return string checksum
     * @since Method available since Release 0.1.0
     */
    protected function arraySumHex($arr)
    {

        $sum = 0;

        foreach ($arr as $v) {

            /**
             * adds current char
             */
            $sum += "0x$v";

            /**
             * keeps the sum with only 2 digits
             */
            $sum %= 0x100;

        }

        /**
         * returns string representaion of number in hex
         */
        return dechex($sum);

    }
}
