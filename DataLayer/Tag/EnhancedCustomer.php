<?php declare(strict_types=1);

namespace AdPage\GTM\DataLayer\Tag;

use AdPage\GTM\Api\Data\MergeTagInterface;

class EnhancedCustomer implements MergeTagInterface
{
    public function __construct(
    ) {
    }

    public function merge(): array
    {
        return [
            'working' => true,
        ];
    }
}
