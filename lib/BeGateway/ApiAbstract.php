<?php

namespace BeGateway;

abstract class ApiAbstract {

    protected abstract function _buildRequestMessage();

    protected $_language;
    protected $_timeout_connect = 10;
    protected $_timeout_read = 30;
    protected $_headers = array();

    public function submit($request = null) {
        try {
            $response = $this->_remoteRequest($request);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $response = '{ "errors":"' . $msg . '", "message":"' . $msg . '" }';
        }
        return new Response($response);
    }

    protected function _remoteRequest($request = null) {
        
        //$this->_buildRequestMessage()
        /* cambiar la variable $this->_endpoint() para que venga del admin */
        $methods = [
            "getOrderStatus" => Settings::$getOrderStatus , 
            "crearFacturacion" => Settings::$crearFacturacion,
            "cotizador" => Settings::$cotizadorUrl,
        ];
        
        $endPoint = (isset($request["url"])) ? (array_key_exists($request["url"], $methods) ? $methods[$request["url"]] : $this->_endpoint() ) : $this->_endpoint();
        
        return GatewayTransport::submit(Settings::$shopId, Settings::$shopKey,
                        $endPoint, $request,
                        $this->_headers,
                        $this->_timeout_read, $this->_timeout_connect, $this->obtenerToken());
    }

    public function obtenerToken() {
        $LoginData = [
            "email" => "registraduria@registraduria.com",
            "password" => "r3g1str4d8r14"
        ];
        
        $url = Settings::$loginBase;        
        $response = json_decode($this->crearCurl($url, $LoginData), true);
        
        if (isset($response['status']) && $response['status'] != "error") {
            return $response['message'];
        } else {
            return "error: no se pudo crear el token";
        }
    }

    public function crearCurl($url, $data, $token = null, $tipo = null) {
        $curl = curl_init();
        $autorizacion_bearer = "Authorization: ";
        if ($token != null) {
            $autorizacion_bearer .= $token;
        }

        $headers = array(
            $autorizacion_bearer,
            'Content-Type: application/json'
        );

        if (isset($data['headers'])) {
            foreach ($data['headers'] as $header) {
                $headers[] = $header;
            }
            unset($data['headers']);
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => ($tipo != null) ? $tipo : "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;
    }

    protected function _endpoint($url = false) {
        return ($url == false) ? Settings::$registerBase: $url;
    }

    protected function _getTransactionType() {
        list($module, $klass) = explode('\\', get_class($this));
        $klass = str_replace('Operation', '', $klass);
        $klass = strtolower($klass) . 's';
        return $klass;
    }

    public function setLanguage($language_code) {
        if (in_array($language_code, Language::getSupportedLanguages())) {
            $this->_language = $language_code;
        } else {
            $this->_language = Language::getDefaultLanguage();
        }
    }

    public function getLanguage() {
        return $this->_language;
    }

    public function setConnectTimeout($timeout) {
        $this->_timeout_connect = $timeout;
    }

    public function setTimeout($timeout) {
        $this->_timeout_read = $timeout;
    }

    public function setRequestHeaders($headers) {
        $this->_headers = $headers;
    }

}

?>
