<?php
namespace mhndev\sapClient;

/**
 * Class BusinessPartnerEntity
 * @package mhndev\sapClient
 */
class BusinessPartnerEntity
{

    /**
     * @var string
     */
    protected $sap_id;

    /**
     * @var string
     */
    protected $full_name;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $mobile;

    /**
     * @var string
     */
    protected $national_code;

    /**
     * @var string
     */
    protected $country;

    /**
     * @return string
     */
    public function getSapId(): string
    {
        return $this->sap_id;
    }

    /**
     * @param string $sap_id
     * @return BusinessPartnerEntity
     */
    public function setSapId(string $sap_id): BusinessPartnerEntity
    {
        $this->sap_id = $sap_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->full_name;
    }

    /**
     * @param string $full_name
     * @return BusinessPartnerEntity
     */
    public function setFullName(string $full_name): BusinessPartnerEntity
    {
        $this->full_name = $full_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     * @return BusinessPartnerEntity
     */
    public function setMobile(string $mobile): BusinessPartnerEntity
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * @return string
     */
    public function getNationalCode(): string
    {
        return $this->national_code;
    }

    /**
     * @param string $national_code
     * @return BusinessPartnerEntity
     */
    public function setNationalCode(string $national_code): BusinessPartnerEntity
    {
        $this->national_code = $national_code;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return BusinessPartnerEntity
     */
    public function setCountry(string $country): BusinessPartnerEntity
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }


    /**
     * @param string $email
     * @return BusinessPartnerEntity
     */
    public function setEmail(string $email): BusinessPartnerEntity
    {
        $this->email = $email;
        return $this;
    }


}
