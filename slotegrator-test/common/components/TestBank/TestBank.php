<?php

namespace common\components\TestBank;

/**
 * Class TestBank
 * @package common\components\TestBank
 */
class TestBank
{
    protected $publicKey = 'F0BMCt0aGdYfnOvethZTfN46LiVbqm*EJ';
    protected $privatKey = 'LcloGLdTlbY2l2CaAMLy9SsCwrC722chUAV0itvEVZdYTB4RM7lO6LDwfEh7EDlsB7vneELqGtvAYAeHebAmh2FAP0M3yKDKUZ0rkisSc43kDoz5SXnjAfOEuL3SHMrd';
    protected $url = 'https://b2b.testbank/api/statment/';

    protected function setCurl($data)
    {
        $curlOpt = [
            CURLOPT_URL => $this->url,
            CURLOPT_POST => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['charset=utf-8'],
        ];
        $cUrl = curl_init();
        curl_setopt_array($cUrl, $curlOpt);

        $receivData = curl_exec($cUrl);
        curl_close($cUrl);

            return $receivData;

    }

    /**
     * @param $cardData
     * @param $amount
     * @return mixed
     */
    public function sendMany($cardData, $amount)
    {
        $time = time();
        $data = $this->publicKey.';'.$cardData.';'.$time.';'. $amount .';';
        $signature = hash_hmac("sha256",$data,$this->privatKey);
        $postDataString = 'signature='.$signature.'&time='.$time.'&public_key='.$this->publicKey.'&amount='.$amount;

        return $this->setCurl($postDataString);

    }


}