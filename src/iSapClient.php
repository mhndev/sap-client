<?php
namespace mhndev\sapClient;

/**
 * Interface iSapClient
 * @package mhndev\sapClient
 */
interface iSapClient
{

    /**
     * @param string $national_code
     * @param string $phone_number
     * @return mixed
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
