<?php

namespace AmoCRM\Models;

use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\Interfaces\CanBeLinkedInterface;
use AmoCRM\Models\Interfaces\HasIdInterface;
use AmoCRM\Models\Interfaces\TypeAwareInterface;
use AmoCRM\Models\Traits\GetLinkTrait;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\Traits\RequestIdTrait;

class CatalogElementModel extends BaseApiModel implements TypeAwareInterface, CanBeLinkedInterface, HasIdInterface
{
    use RequestIdTrait;
    use GetLinkTrait;

    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var null|string
     */
    protected $name;

    /**
     * @var int
     */
    protected $catalogId;

    /**
     * @var null|int
     */
    protected $createdBy;

    /**
     * @var null|int
     */
    protected $updatedBy;

    /**
     * @var null|int
     */
    protected $createdAt;

    /**
     * @var null|int
     */
    protected $updatedAt;

    /**
     * @var CustomFieldsValuesCollection|null
     */
    protected $customFieldsValues;

    /**
     * @var bool|null
     */
    protected $isDeleted;

    /**
     * @var int|null
     */
    protected $quantity;

    /**
     * @var int|null
     */
    protected $accountId;

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getCatalogId(): ?int
    {
        return $this->catalogId;
    }

    /**
     * @param null|int $id
     *
     * @return self
     */
    public function setCatalogId(?int $id): self
    {
        $this->catalogId = $id;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getCreatedBy(): ?int
    {
        return $this->createdBy;
    }

    /**
     * @param null|int $userId
     *
     * @return self
     */
    public function setCreatedBy(?int $userId): self
    {
        $this->createdBy = $userId;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getUpdatedBy(): ?int
    {
        return $this->updatedBy;
    }

    /**
     * @param null|int $userId
     *
     * @return self
     */
    public function setUpdatedBy(?int $userId): self
    {
        $this->updatedBy = $userId;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    /**
     * @param null|int $timestamp
     *
     * @return self
     */
    public function setCreatedAt(?int $timestamp): self
    {
        $this->createdAt = $timestamp;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getUpdatedAt(): ?int
    {
        return $this->updatedAt;
    }

    /**
     * @param null|int $timestamp
     *
     * @return self
     */
    public function setUpdatedAt(?int $timestamp): self
    {
        $this->updatedAt = $timestamp;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    /**
     * @param null|bool $flag
     *
     * @return self
     */
    public function setIsDeleted(?bool $flag): self
    {
        $this->isDeleted = $flag;

        return $this;
    }

    /**
     * @return CustomFieldsValuesCollection|null
     */
    public function getCustomFieldsValues(): ?CustomFieldsValuesCollection
    {
        return $this->customFieldsValues;
    }

    /**
     * @param CustomFieldsValuesCollection|null $values
     *
     * @return self
     */
    public function setCustomFieldsValues(?CustomFieldsValuesCollection $values): self
    {
        $this->customFieldsValues = $values;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    /**
     * @param int|null $accountId
     *
     * @return CatalogElementModel
     */
    public function setAccountId(?int $accountId): CatalogElementModel
    {
        $this->accountId = $accountId;

        return $this;
    }

    public function getType(): string
    {
        return EntityTypesInterface::CATALOG_ELEMENTS_FULL;
    }

    /**
     * @param array $catalogElement
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromArray(array $catalogElement): self
    {
        if (empty($catalogElement['id'])) {
            throw new InvalidArgumentException('Catalog id is empty in ' . json_encode($catalogElement));
        }

        $catalogElementModel = new self();

        $catalogElementModel->setId((int)$catalogElement['id']);

        if (!empty($catalogElement['name'])) {
            $catalogElementModel->setName($catalogElement['name']);
        }
        if (array_key_exists('created_by', $catalogElement) && !is_null($catalogElement['created_by'])) {
            $catalogElementModel->setCreatedBy((int)$catalogElement['created_by']);
        }
        if (array_key_exists('updated_by', $catalogElement) && !is_null($catalogElement['updated_by'])) {
            $catalogElementModel->setUpdatedBy((int)$catalogElement['updated_by']);
        }
        if (!empty($catalogElement['created_at'])) {
            $catalogElementModel->setCreatedAt($catalogElement['created_at']);
        }
        if (!empty($catalogElement['updated_at'])) {
            $catalogElementModel->setUpdatedAt($catalogElement['updated_at']);
        }
        if (!empty($catalogElement['catalog_id'])) {
            $catalogElementModel->setCatalogId($catalogElement['catalog_id']);
        }
        if (array_key_exists('is_deleted', $catalogElement) && !is_null($catalogElement['is_deleted'])) {
            $catalogElementModel->setIsDeleted($catalogElement['is_deleted']);
        }
        if (!empty($catalogElement['custom_fields_values'])) {
            $valuesCollection = new CustomFieldsValuesCollection();
            $customFieldsValues = $valuesCollection->fromArray($catalogElement['custom_fields_values']);
            $catalogElementModel->setCustomFieldsValues($customFieldsValues);
        }

        //Костылик для связей
        if (isset($catalogElement['to_element_id'])) {
            $catalogElementModel->setId($catalogElement['to_element_id']);
        }
        if (isset($catalogElement['metadata']['quantity'])) {
            $catalogElementModel->setQuantity($catalogElement['metadata']['quantity']);
        }
        if (isset($catalogElement['metadata']['catalog_id'])) {
            $catalogElementModel->setCatalogId($catalogElement['metadata']['catalog_id']);
        }

        if (!empty($catalog['account_id'])) {
            $catalogElementModel->setAccountId((int)$catalog['account_id']);
        }

        return $catalogElementModel;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'created_by' => $this->getCreatedBy(),
            'updated_by' => $this->getUpdatedBy(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'catalog_id' => $this->getCatalogId(),
            'is_deleted' => $this->getIsDeleted(),
            'custom_fields_values' => $this->getCustomFieldsValues()
                ? $this->getCustomFieldsValues()->toArray()
                : null,
            'account_id' => $this->getAccountId(),
        ];
    }

    public function toApi(?string $requestId = "0"): array
    {
        $result = [];

        if (!is_null($this->getId())) {
            $result['id'] = $this->getId();
        }

        if (!is_null($this->getName())) {
            $result['name'] = $this->getName();
        }

        if (!is_null($this->getCreatedBy())) {
            $result['created_by'] = $this->getCreatedBy();
        }

        if (!is_null($this->getUpdatedBy())) {
            $result['updated_by'] = $this->getUpdatedBy();
        }

        if (!is_null($this->getCreatedAt())) {
            $result['created_at'] = $this->getCreatedAt();
        }

        if (!is_null($this->getCustomFieldsValues())) {
            $result['custom_fields_values'] = $this->getCustomFieldsValues()->toApi();
        }

        if (is_null($this->getRequestId()) && !is_null($requestId)) {
            $this->setRequestId($requestId);
        }

        $result['request_id'] = $this->getRequestId();

        return $result;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return CatalogElementModel
     */
    public function setQuantity(int $quantity): CatalogElementModel
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return array|null
     */
    protected function getMetadataForLink(): ?array
    {
        $result = null;

        if (!is_null($this->getUpdatedBy())) {
            $result['updated_by'] = $this->getUpdatedBy();
        }

        if (!is_null($this->getQuantity())) {
            $result['quantity'] = $this->getQuantity();
        }

        if (!is_null($this->getCatalogId())) {
            $result['catalog_id'] = $this->getCatalogId();
        }

        return $result;
    }
}