<?php 
declare(strict_types=1);

namespace AdPage\GTM\Api;

use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\App\ResponseInterface;

interface OrderCommentControllerInterface
{
    /**
     * @return ResultInterface|ResponseInterface 
     */
    public function execute();
}