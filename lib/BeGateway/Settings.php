<?php

namespace BeGateway;

class Settings {
  public static $shopId;
  public static $shopKey;
  public static $shopPubKey;
  public static $registerBase  = 'http://www.inter_services.com:8081/v1/api/credibanco/register_order';//$gatewayBase
  public static $getOrderStatus  = 'http://www.inter_services.com:8081/v1/api/credibanco/get_order_status';
  public static $crearFacturacion  = 'http://www.inter_services.com:8081/v1/api/inter/servicios/crear/facturacion';
  public static $loginBase = 'http://www.inter_services.com:8081/v1/auth/usuario/login';//$checkoutBase
  public static $domainReturn = 'http://www.localhost:8081/ecommerce/credibanco/checkout/redirect/action';
  public static $apiBase      = 'https://api.begateway.com';  
  public static $encryptionKey = 'GeeksforGeeks';
  public static $cryptionV = '1234567891011121';
  public static $ciphering = "AES-128-CTR";
  public static $idCaja = -1;
  public static $idCentroServicio = 1287;
  public static $headersFacturacion = [
        "usuario" => "admin",
        "IdCentroServicio" => 1287,
        "NombreCentroServicio" => "paquito",
        "IdAplicativoOrigen" => 1,
  ];
  public static $prefijo = 191;
  public static $interFacturation = true;
  public static $idClienteCotizador = "1090";
  public static $cotizadorUrl = 'http://www.inter_services.com:8081/v1/api/inter/servicios/cotizador';
}
?>
