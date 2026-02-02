<?php

namespace garmayev\max\types\payloads;

use app\components\VCardParser;

/**
 * @property string|null $name
 * @property int|null $contact_id
 * @property string $vcf_info
 * @property string|null $vcf_phone
 * @property array $max_info
 */
class ContactPayload extends Payload
{
    private ?string $_name;
    private ?int $_contact_id;
    public array $_vcf_info;
    public ?string $_vcf_phone;
    public array $_max_info;

    public function __construct($config = [])
    {
        $this->setName($config['name'] ?? null);
        $this->setContact_id($config['contact_id'] ?? null);
        $this->setVcf_info($config['vcf_info'] ?? null);
        $this->setVcf_phone($config['vcf_phone'] ?? null);
        $this->setMax_info($config['max_info'] ?? null);
        parent::__construct($config);
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->_name;
    }

    /**
     * @param string|null $name
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->_name = $name;
    }

    /**
     * @return int|null
     */
    public function getContact_id(): ?int
    {
        return $this->_contact_id;
    }

    /**
     * @param int|null $contact_id
     * @return void
     */
    public function setContact_id(?int $contact_id): void
    {
        $this->_contact_id = $contact_id;
    }

    /**
     * @return array|null
     */
    public function getVcf_info(): ?array
    {
        return $this->_vcf_info;
    }

    /**
     * @param string|null $vcf_info
     * @return void
     */
    public function setVcf_info(?string $vcf_info): void
    {
        $this->_vcf_info = VCardParser::parseQuick($vcf_info);
    }

    /**
     * @return string|null
     */
    public function getVcf_phone(): ?string
    {
        return $this->_vcf_phone ?? "";
    }

    /**
     * @param string|null $vcf_phone
     * @return void
     */
    public function setVcf_phone(?string $vcf_phone): void
    {
        $this->_vcf_phone = $vcf_phone;
    }

    public function getMax_info(): ?array
    {
        return $this->_max_info ?? [];
    }

    public function setMax_info($max_info)
    {
        $this->_max_info = $max_info;
    }
}