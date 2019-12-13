<?php

namespace Odmin;

class API
{
    private $token;

    public function __construct($token) {
        $this->token = $token;
    }

    private function curl_post($data = [], $pfad = "public.php"){
        $data["api_key"] = ODMIN_API_KEY;
        $data["token"] = $this->token;
        $url = ODMIN_API_URL . $pfad;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"],
            CURLOPT_POSTFIELDS => http_build_query($data)
        ]);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    public function get($type){
        try {
            return (object) json_decode($this->curl_post(["type" => $type]));
        } catch (\Throwable $th) {}
        return false;
    }

}