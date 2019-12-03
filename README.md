### SAP (Business One) PHP API Client

you can find usage of this library down here,

```php
use mhndev\sapClient\SapClient;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

require "vendor/autoload.php";

$wsdl_internal = "http://192.168.100.34:8081/CheckPartnerService.asmx?wsdl";
$wsdl_external = "http://81.91.156.134:2275?wsdl";

$username = "Admin";
$password = "Admin";

$sap_client = new SapClient($wsdl_internal, $username, $password);

var_dump($sap_client->createBusinessPartner(
    'Mohammad Ghaderi',
    '0012497797',
    '09128049107',
    'm.ghaderi.d90@gmail.com'
));
die();


//var_dump($sap_client->checkBusinessPartner('3732981134', '09364517379'));
//die();


var_dump($sap_client->getBusinessPartnerByMobile('09123169242'));

die();

//var_dump($sap_client->getBusinessPartnerByBPSapIdentifier('C0010702'));
//var_dump($sap_client->getBusinessPartnerByNationalCode('3732981134'));


//var_dump($sap_client->healthCheck());

//var_dump($sap_client->createBusinessPartner(
//    'Majid Abdolhosseini',
//    '0014297884',
//    '09124971706',
//    'majid8911303@gmail.com'
//));
//
//var_dump($sap_client->checkBusinessPartner('0014297884', '09124971706'));
//die();


//
//var_dump($sap_client->updateBusinessPartner(
//    'Majid Abdolhosseini',
//    '0014297884',
//    '09124971706',
//    'majid8911303@gmail.com'
//));

die();

```
