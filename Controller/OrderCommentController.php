<?php
declare(strict_types=1);

namespace AdPage\GTM\Controller;

class OrderCommentController extends \Magento\Framework\App\Action\Action {
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();

        return [
            'id' => 'test'
        ];
    }
}