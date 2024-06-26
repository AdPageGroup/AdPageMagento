<?php declare(strict_types=1);

namespace AdPage\GTM\Model\Config\Backend;

use Magento\Framework\App\Config\Value;
use AdPage\GTM\Exception\InvalidConfig;

class ContainerConfig extends Value
{
    public function beforeSave()
    {
        if (false === $this->validate()) {
            throw new InvalidConfig('Invalid container ID "' . $this->getValue() . '". It should start with "GTM-"');
        }

        return parent::beforeSave();
    }

    private function validate(): bool
    {
        if (!empty($this->getValue())) {
            return true;
        }

        return false;
    }
}
