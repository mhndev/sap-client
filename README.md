### SAP (Business One) PHP API Client

you can find usage of this library down here,

```php
use mhndev\sapClient\SapClient;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "vendor/autoload.php";

$wsdl = "http://192.168.100.34:8081/?wsdl";
$username = "Admin";
$password = "Admin";

$sap_client = new SapClient($wsdl, $username, $password);

//var_dump($sap_client->healthCheck());

//var_dump($sap_client->checkBusinessPartner('0014297884', '9124971706'));

//var_dump($sap_client->createBusinessPartner(
//    'Majid Abdolhosseini',
//    '0014297884',
//    '09124971706',
//    'majid8911303@gmail.com'
//));

var_dump($sap_client->updateBusinessPartner(
    'Majid Abdolhosseini',
    '0014297884',
    '09124971706',
    'majid8911303@gmail.com'
));

die();

```