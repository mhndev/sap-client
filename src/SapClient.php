<?php
namespace mhndev\sapClient;

use mhndev\sapClient\Exception\APIResponseConnectException;
use mhndev\sapClient\Exception\APIResponseException;
use mhndev\sapClient\Exception\UnAuthenticatedException;
use SoapClient;
use SoapFault;

/**
 * Class SapClient
 * @package mhndev\sapClient
 */
class SapClient implements iSapClient
{

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var SoapClient
     */
    private $soap_client;

    /**
     * @var string
     */
    private $wsdl_url;


    /**
     * GuzzleSapClient constructor.
     * @param string $wsdl_url
     * @param string $username
     * @param string $password
     */
    public function __construct(string $wsdl_url, string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->wsdl_url = $wsdl_url;
    }

    /**
     * This function check whether the combination of $national_code, $phone_number exist in business one or not
     *
     * it returns true if user exists
     * and returns false if user doesnt exist in business one
     *
     * @param string $national_code
     * @param string $phone_number
     * @return mixed
     * @throws APIResponseConnectException
     * @throws APIResponseException
     */
    function checkBusinessPartner(string $national_code, string $phone_number)
    {
        $params = [
            'username' => $this->username,
            'password' => $this->password,
            'nationalCode' => $national_code,
            'phoneNumber' => $phone_number
        ];

        $result = $this->getSoapClient()->CheckBusinessPartner($params)->CheckBusinessPartnerResult;

        if ($result == 'Businees Partner Found.') {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @param string $full_name
     * @param string $national_code
     * @param string $mobile
     * @param string $email
     * @param int $is_lid
     * @return string business partner unique identifier on sap on success
     * @throws APIResponseConnectException
     * @throws APIResponseException
     */
    function createBusinessPartner(
        string $full_name,
        string $national_code,
        string $mobile,
        string $email,
        int $is_lid = 0
    )
    {
        $params = [
            'username' => $this->username,
            'password' => $this->password,
            'nationalCode' => $national_code,
            'mobile' => $mobile,
            'fullName' => $full_name,
            'email' => $email,
            'isLid' => $is_lid,
        ];

        try {
            $result = $this->getSoapClient()->CreateBusinessPartner($params)->CreateBusinessPartnerResult;

            if(strpos($result, "New Business Partner CardCode") !== false ) {
                return $sap_id = explode(':', $result)[1];
            }



        }
        catch (\Exception $e) {
            if(get_class($e) == 'SoapFault' && $e->getMessage() == 'Could not connect to host' ) {
                throw new APIResponseConnectException(
                    sprintf('Exception Class : %s, Exception Message : %s', get_class($e), $e->getMessage())
                );
            }
            throw $e;
        }


    }

    /**
     * @param string $full_name
     * @param string $national_code
     * @param string $mobile
     * @param string $email
     * @param int $is_lid
     * @param int $have_access
     * @return mixed
     * @throws APIResponseConnectException
     * @throws APIResponseException
     * @throws UnAuthenticatedException
     */
    function updateBusinessPartner(
        string $full_name,
        string $national_code,
        string $mobile,
        string $email,
        int $is_lid = 0,
        int $have_access = 0
    )
    {
        $params = [
            'username' => $this->username,
            'password' => $this->password,
            'nationalCode' => $national_code,
            'mobile' => $mobile,
            'fullName' => $full_name,
            'Email' => $email,
            'isLid' => $is_lid,
            'haveAccess' => $have_access
        ];

        $result = $this->getSoapClient()->UpdateBusinessPartner($params)->UpdateBusinessPartnerResult;

        if($result == 1) {
            ## success update
            return true;
        }

        ### unauthenticated user
        elseif($result == 'IS Not Valid User') {
            throw new UnAuthenticatedException;
        }

        else {
            ## not success
            return false;
        }

    }

    /**
     * @return bool
     * @throws APIResponseConnectException
     * @throws APIResponseException
     */
    function healthCheck()
    {
        $params = ['username' => $this->username, 'password' => $this->password];

        $result = $this->getSoapClient()->HealthCheck($params)->HealthCheckResult;

        if($result == 'IS Not Valid User') {
            throw new UnAuthenticatedException;
        }

        return ($result == -1) ? true: false;
    }


    /**
     * @return SoapClient
     * @throws APIResponseException
     * @throws ApiResponseConnectException
     */
    private function getSoapClient()
    {
        if(! is_null($this->soap_client)) {
            return $this->soap_client;
        }

        try{
            $this->soap_client = new SoapClient(
                $this->wsdl_url,
                ['exception' => true, 'trace' => 1]
            );

            return $this->soap_client;
        }

        catch (\Exception $e) {

            if (
                get_class($e) == SoapFault::class &&
                strpos($e->getMessage(), "SOAP-ERROR: Parsing WSDL: Couldn't load from") !== false
            ) {
                throw new ApiResponseConnectException(
                    sprintf("Invalid Soap Server
                    Exception Class : %s , 
                    Exception Message is : %s", get_class($e), $e->getMessage())
                );
            }


            else {
                throw new APIResponseException(
                    sprintf("Exception Class : %s , Exception Message is : %s", get_class($e), $e->getMessage())
                );
            }
        }

    }

    /**
     * @param string $mobile
     * @return BusinessPartnerEntity
     * @throws APIResponseConnectException
     * @throws APIResponseException
     */
    function getBusinessPartnerByMobile(string $mobile)
    {
        $params = ['username' => $this->username, 'password' => $this->password, 'Cellular' => $mobile];

        $result = $this->getSoapClient()->GetBPByCellular($params)->GetBPByCellularResult;
        return $this->extractBPFromXml($result);
    }

    /**
     * @param string $national_code
     * @return BusinessPartnerEntity
     * @throws APIResponseConnectException
     * @throws APIResponseException
     */
    function getBusinessPartnerByNationalCode(string $national_code)
    {
        $params = ['username' => $this->username, 'password' => $this->password, 'NationalId' => $national_code];

        $result = $this->getSoapClient()->GetBPByNationalCode($params)->GetBPByNationalCodeResult;
        return $this->extractBPFromXml($result);
    }

    /**
     * @param string $sap_identifier
     * @return BusinessPartnerEntity
     * @throws APIResponseConnectException
     * @throws APIResponseException
     */
    function getBusinessPartnerByBPSapIdentifier(string $sap_identifier)
    {
        $params = ['username' => $this->username, 'password' => $this->password, 'CardCode' => $sap_identifier];

        $result = $this->getSoapClient()->GetBPByCardCode($params)->GetBPByCardCodeResult;
        return $this->extractBPFromXml($result);    }


    /**
     * @param string $xml
     * @return BusinessPartnerEntity
     */
    private function extractBPFromXml(string $xml)
    {
        $full_name = get_string_between($xml, '<CardName>', '</CardName>');
        $sap_id = get_string_between($xml, '<CardCode>', '</CardCode>');
        $mobile = get_string_between($xml, '<Cellular>', '</Cellular>');
        $email = get_string_between($xml, '<E_Mail>', '</E_Mail>');
        $country = get_string_between($xml, '<Country>', '</Country>');
        $national_code = get_string_between($xml, '<AddID>', '</AddID>');

        return (new BusinessPartnerEntity())
            ->setSapId($sap_id)
            ->setFullName($full_name)
            ->setNationalCode($national_code)
            ->setMobile($mobile)
            ->setEmail($email)
            ->setCountry($country);
    }

}
