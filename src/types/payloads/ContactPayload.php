<?php

namespace garmayev\max\types\payloads;

use yii\base\Model;

class ContactPayload extends Model
{
    private ?string $_name;
    private ?int $_contact_id;
    private ?string $_vcf_info;
    private ?string $_vcf_phone;

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
    public function getContactId(): ?int
    {
        return $this->_contact_id;
    }

    /**
     * @param int|null $contact_id
     * @return void
     */
    public function setContactId(?int $contact_id): void
    {
        $this->_contact_id = $contact_id;
    }

    /**
     * @return string|null
     */
    public function getVcfInfo(): ?string
    {
        return $this->_vcf_info;
    }

    /**
     * @param string|null $vcf_info
     * @return void
     */
    public function setVcfInfo(?string $vcf_info): void
    {
        $this->_vcf_info = $vcf_info;
    }

    /**
     * @return string|null
     */
    public function getVcfPhone(): ?string
    {
        return $this->_vcf_phone;
    }

    /**
     * @param string|null $vcf_phone
     * @return void
     */
    public function setVcfPhone(?string $vcf_phone): void
    {
        $this->_vcf_phone = $vcf_phone;
    }
}