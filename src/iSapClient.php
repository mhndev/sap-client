<?php
namespace mhndev\sapClient;

/**
 * Interface iSapClient
 * @package mhndev\sapClient
 */
interface iSapClient
{


    /**
     * @param string $mobile
     * @return BusinessPartnerEntity
     */
    function getBusinessPartnerByMobile(string $mobile);

    /**
     * @param string $national_code
     * @return BusinessPartnerEntity
     */
    function getBusinessPartnerByNationalCode(string $national_code);

    /**
     * @param string $sap_identifier
     * @return BusinessPartnerEntity
     */
    function getBusinessPartnerByBPSapIdentifier(string $sap_identifier);

    /**
     * @param string $national_code
     * @param string $phone_number
     * @return bool
     */
    function checkBusinessPartner(string $national_code, string $phone_number);

    /**
     * @param string $full_name
     * @param string $national_code
     * @param string $mobile
     * @param string $email
     * @param int $is_lid
     * @return mixed
     */
    function createBusinessPartner(
        string $full_name,
        string $national_code,
        string $mobile,
        string $email,
        int $is_lid = 0
    );

    /**
     * @param string $full_name
     * @param string $national_code
     * @param string $mobile
     * @param string $email
     * @param int $is_lid
     * @param int $have_access
     * @return mixed
     */
    function updateBusinessPartner(
        string $full_name,
        string $national_code,
        string $mobile,
        string $email,
        int $is_lid = 0,
        int $have_access = 0
    );

    /**
     * @return bool
     */
    function healthCheck();

}
