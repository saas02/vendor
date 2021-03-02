<?php

namespace BeGateway;

class GatewayTransport {

    public static function submit($shop_id, $shop_key, $host, $t_request, $headers = array(), $curl_timeout = 30, $curl_connect_timeout = 10, $token) {
        
        $headers = array(
            "Authorization: ".$token,
            'Content-Type: application/json'
        );
        
        if(isset($t_request['headers'])){
            foreach($t_request['headers'] as $header){
                $headers[] = $header;
            }
            unset($t_request['headers']);
        }
                
        $process = curl_init($host);
        $json = json_encode($t_request);
        
        Logger::getInstance()->write("Request to $host", Logger::DEBUG, get_class());
        Logger::getInstance()->write("with Shop Id " . Settings::$shopId . " & Shop key " . Settings::$shopKey, Logger::DEBUG, get_class());
        if (!empty($json))
            Logger::getInstance()->write("with message " . $json, Logger::DEBUG, get_class());


        curl_setopt_array($process, array(
            CURLOPT_URL => $host,
            CURLOPT_CONNECTTIMEOUT => $curl_connect_timeout,
            CURLOPT_TIMEOUT => $curl_timeout,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => isset($t_request['metodo']) && $t_request['metodo'] == 'GET' ? 'GET' : "POST",
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false
        ));

        $response = curl_exec($process);
        
        $error = curl_error($process);
        curl_close($process);

        if ($response === false) {
            throw new \Exception("cURL error " . $error);
        }
        
        Logger::getInstance()->write("Response $response", Logger::DEBUG, get_class());
        return $response;
    }

}

?>
