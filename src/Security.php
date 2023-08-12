<?php

namespace Komodo\Exodus;

/*
|-----------------------------------------------------------------------------
| Komodo Exodus
|-----------------------------------------------------------------------------
|
| Desenvolvido por: Jhonnata Paixão (Líder de Projeto)
| Iniciado em: 15/10/2022
| Arquivo: Security.php
| Data da Criação Sat Jul 22 2023
| Copyright (c) 2023
|
|-----------------------------------------------------------------------------
|*/

class Security
{

    public static function encrypt($data, $key)
    {
        $srd0x144 = $key;
        $fdc0054c = openssl_cipher_iv_length($dfd00xde1 = "AES-128-CBC");
        $sdd0x3 = openssl_random_pseudo_bytes($fdc0054c);
        $dfd00xde1 = openssl_encrypt($data, $dfd00xde1, $srd0x144, OPENSSL_RAW_DATA, $sdd0x3);
        $sed0x14e = hash_hmac('sha256', $dfd00xde1, $srd0x144, true);
        $dfd00xde1text = base64_encode($sdd0x3 . $sed0x14e . $dfd00xde1);
        return $dfd00xde1text;
    }

    public static function decrypt($data, $key)
    {
        $srd0x144 = $key;
        $c = base64_decode($data);
        $fdc0054c = openssl_cipher_iv_length($dfd00xde15 = "AES-128-CBC");
        $sdd0x3 = substr($c, 0, $fdc0054c);
        $sed0x14e = substr($c, $fdc0054c, $sha2len = 32);
        $dfd00xde1 = substr($c, $fdc0054c + $sha2len);
        $xed014 = openssl_decrypt($dfd00xde1, $dfd00xde15, $srd0x144, OPENSSL_RAW_DATA, $sdd0x3);
        $sse0x14e = hash_hmac('sha256', $dfd00xde1, $srd0x144, true);
        if ($sed0x14e && hash_equals($sed0x14e, $sse0x14e)) {
            return $xed014;
        } else {
            return false;
        }
    }

    /**
     * @param string $cert
     * @param mixed $data
     *
     * @return string
     */
    public static function encryptPublic($cert, $data)
    {
        $keyFile = fopen($cert, "r");
        $publicKey = fread($keyFile, 8192);
        fclose($keyFile);

        openssl_get_publickey($publicKey);
        openssl_public_encrypt($data, $cryptText, $publicKey);
        return (base64_encode($cryptText));
    }

    /**
     * @param string $cert
     * @param mixed $data
     *
     * @return string
     */
    public static function decryptPublic($cert, $data)
    {
        $keyFile = fopen($cert, "r");
        $privateKey = fread($keyFile, 8192);
        fclose($keyFile);

        openssl_get_privatekey($privateKey);
        $binText = base64_decode($data);
        openssl_private_decrypt($binText, $clearText, $privateKey);
        return ($clearText);
    }

    public static function validateEncryptData($data)
    {
        if (self::Decrypt($data, self::Decrypt(_EX_SM_KEY, _EX_PM_KEY))) {
            return true;
        }

        return false;
    }

    public static function fileEncrypt($ds00x142sr, $dex014e, $rex014e)
    {
        $dex014e = substr(sha1($dex014e, true), 0, 16);
        $x0214e = openssl_random_pseudo_bytes(16);
        $e44dxsc = false;
        if ($ex144sc = fopen($rex014e, 'w')) { /*fwrite($ex144sc,$x0214e);*/
            $tes = $x0214e;
            if ($e011cdxe = fopen($ds00x142sr, 'rb')) {
                while (!feof($e011cdxe)) {
                    $ce01d14ex = fread($e011cdxe, 16 * _EX_ENCRYPT_BLOCKS);
                    $d01xxd14e = openssl_encrypt($ce01d14ex, 'AES-128-CBC', $dex014e, OPENSSL_RAW_DATA, $x0214e);
                    $x0214e = substr($d01xxd14e, 0, 16); /*fwrite($ex144sc,$d01xxd14e);*/
                    file_put_contents($rex014e, sprintf("%s", $tes . $d01xxd14e)); /*exption for save*/
                }
                fclose($e011cdxe);
            } else {
                $e44dxsc = true;
            }
            fclose($ex144sc);
        } else {
            $e44dxsc = true;
        }
        return $e44dxsc?false: $rex014e;
    }

    public static function fileDecrypt($x452ce, $xdde4ec, $xdsd5414)
    {
        $xdde4ec = substr(sha1($xdde4ec, true), 0, 16);
        $ddc24w = false;
        if ($rr3ex14 = fopen($xdsd5414, 'w')) {
            if ($sdf00x14ed = fopen($x452ce, 'rb')) {
                $se101x5exc = fread($sdf00x14ed, 16);
                while (!feof($sdf00x14ed)) {
                    $se00x14e58 = fread($sdf00x14ed, 16 * (_EX_ENCRYPT_BLOCKS + 1));
                    $sdr000x1458 = openssl_decrypt($se00x14e58, 'AES-128-CBC', $xdde4ec, OPENSSL_RAW_DATA, $se101x5exc);
                    $se101x5exc = substr($se00x14e58, 0, 16); /*fwrite($rr3ex14,$sdr000x1458);*/
                    file_put_contents($xdsd5414, sprintf("%s", $sdr000x1458)); /*exption for save*/
                }
                fclose($sdf00x14ed);
            } else {
                $ddc24w = true;
            }
            fclose($rr3ex14);
        } else {
            $ddc24w = true;
        }
        return $ddc24w?false: $xdsd5414;
    }

    public static function charSyngleValidation($str5x)
    {
        $rstx00 = false;
        $mlx076 = false;
        $scxd00x24 = strval(gethostbyaddr($_SERVER[ 'REMOTE_ADDR' ]));
        $scx001451AE = strval(getenv("REMOTE_ADDR"));
        $xdf0014fE = gethostbyname($scxd00x24);
        if (preg_match('/\W|_/', $str5x)) {
            $rstx00 = true;
        }
        if (strpos($str5x, "\\") !== false) {
            $rstx00 = true;
            $mlx076 = true;
        }
        if (strpos($str5x, "/") !== false) {
            $rstx00 = true;
            $mlx076 = true;
        }
        if (strpos($str5x, ":") !== false) {
            $rstx00 = true;
            $mlx076 = true;
        }
        return [ $rstx00, $mlx076, $scxd00x24, $scx001451AE, $xdf0014fE ];
    }

    public static function charMultValidation($str5x)
    {
        $rstx00 = false;
        $mlx076 = false;
        $scxd00x24 = strval(gethostbyaddr($_SERVER[ 'REMOTE_ADDR' ]));
        $scx001451AE = strval(getenv("REMOTE_ADDR"));
        $xdf0014fE = gethostbyname($scxd00x24);
        $xrs00c41e = '';
        foreach ($str5x as &$sd000x) {
            if (preg_match('/\W|_/', $sd000x)) {
                $rstx00 = true;
                $xrs00c41e = $sd000x;
            }
            if (strpos($sd000x, "\\") !== false) {
                $rstx00 = true;
                $mlx076 = true;
                $xrs00c41e = $sd000x;
            }
            if (strpos($sd000x, "/") !== false) {
                $rstx00 = true;
                $mlx076 = true;
                $xrs00c41e = $sd000x;
            }
            if (strpos($sd000x, ":") !== false) {
                $rstx00 = true;
                $mlx076 = true;
                $xrs00c41e = $sd000x;
            }
        };
        return [ $rstx00, $mlx076, $scxd00x24, $scx001451AE, $xdf0014fE, $xrs00c41e ];
    }
}
