<?php
declare(strict_types=1);

namespace AdPage\GTM\Controller;

use Magento\Framework\Webapi\Rest\Request;

class OrderCommentController {
    protected $request;

    public function __construct(
        Request $request
    )
    {
        $this->request = $request;
    }

    public function execute()
    {
        $body = $this->request->getBodyParams();
        var_dump($body); exit;

        return [
            'id' => 'test'
        ];
    }
}