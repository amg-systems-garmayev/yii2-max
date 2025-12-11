<?php

namespace garmayev\max\types\payloads;

use yii\base\Model;

class ContactPayload extends Payload
{
    private ?string $_name;
    private ?int $_contact_id;
    public string $_vcf_info;
    public ?string $_vcf_phone;
    public array $max_info;

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
     * @return string|null
     */
    public function getVcf_info(): ?string
    {
        return $this->_vcf_info;
    }

    /**
     * @param string|null $vcf_info
     * @return void
     */
    public function setVcf_info(?string $vcf_info): void
    {
        $this->_vcf_info = $vcf_info;
    }

    /**
     * @return string|null
     */
    public function getVcf_phone(): ?string
    {
        return $this->_vcf_phone;
    }

    /**
     * @param string|null $vcf_phone
     * @return void
     */
    public function setVcf_phone(?string $vcf_phone): void
    {
        $this->_vcf_phone = $vcf_phone;
    }
}