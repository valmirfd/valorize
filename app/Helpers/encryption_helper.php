<?php

function Encrypt($value)
{
    if (empty($value)) {
        return null;
    }

    try {
        $encryption = \Config\Services::encrypter();
        return bin2hex($encryption->encrypt($value));
    } catch (\Exception $e) {
        return null;
    }
}

function Decrypt($value)
{
    if (empty($value)) {
        return null;
    }

    try {
        $encryption = \Config\Services::encrypter();
        return $encryption->decrypt(hex2bin($value));
    } catch (\Exception $e) {
        return null;
    }
}
