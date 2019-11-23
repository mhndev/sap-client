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

        if($result == 0) {
            return false;
        }
        elseif ($result == 1) {
            return true;
        }
        elseif($result == 'IS Not Valid User') {
            throw new UnAuthenticatedException;
        }

        return $result;
    }

    /**
     * @param string $full_name
     * @param string $national_code
     * @param string $mobile
     * @param string $email
     * @param int $is_lid
     * @return mixed
     * @throws APIResponseConnectException
     * @throws APIResponseException
     * @throws UnAuthenticatedException
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
            'Email' => $email,
            'isLid' => $is_lid,
        ];

        $result = $this->getSoapClient()->CreateBusinessPartner($params)->CreateBusinessPartnerResult;

        ### failed creation of business partner
        if($result == 'BP did not add') {
            return false;
        }

        ## invalid username and password
        elseif($result == 'IS Not Valid User') {
            throw new UnAuthenticatedException;
        }

        ### success business partner creation
        else {
            return true;
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
        $params = [
            'username' => $this->username,
            'password' => $this->password
        ];

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
                    sprintf("Exception Class : %s , Exception Message is : %s", get_class($e), $e->getMessage())
                );
            }


            else {
                throw new APIResponseException(
                    sprintf("Exception Class : %s , Exception Message is : %s", get_class($e), $e->getMessage())
                );
            }
        }

    }

}
