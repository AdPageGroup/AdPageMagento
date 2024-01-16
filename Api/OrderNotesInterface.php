<?php declare(strict_types=1);

namespace AdPage\GTM\Api;


interface OrderNotesInterface
{
    /**
     * Save custom data
     *
     * @param array $jsonData
     * @return string
     */
    public function saveData($jsonData);
}