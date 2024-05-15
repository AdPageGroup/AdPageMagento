<?php declare(strict_types=1);

namespace AdPage\GTM\DataLayer\Event;

use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use AdPage\GTM\Api\Data\EventInterface;
use AdPage\GTM\DataLayer\Mapper\ProductDataMapper;
use AdPage\GTM\DataLayer\Tag\CurrencyCode;
use AdPage\GTM\Util\PriceFormatter;
use Magento\Catalog\Api\ProductRepositoryInterface;

class AddToCart implements EventInterface
{
    private ProductDataMapper $productDataMapper;
    private CurrencyCode $currencyCode;
    private PriceFormatter $priceFormatter;
    private Product $product;
    private ProductRepositoryInterface $productRepository;
    private int $qty = 1;

    /**
     * @param ProductDataMapper $productDataMapper
     * @param CurrencyCode $currencyCode
     */
    public function __construct(
        ProductDataMapper $productDataMapper,
        CurrencyCode $currencyCode,
        PriceFormatter $priceFormatter,
        ProductRepositoryInterface $productRepository
    ) {
        $this->productDataMapper = $productDataMapper;
        $this->currencyCode = $currencyCode;
        $this->priceFormatter = $priceFormatter;
        $this->productRepository = $productRepository;
    }

    /**
     * @return string[]
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function get(): array
    {
        $qty = ($this->qty > 0) ? $this->qty : 1;

        $product = $this->productRepository->get($this->product->getSku());
        $itemData = $this->productDataMapper->mapByProduct($product);
        $itemData['quantity'] = $qty;
        $value = $itemData['price'] * $qty;

        return [
            'event' => 'trytagging_add_to_cart',
            'ecommerce' => [
                'currency' => $this->currencyCode->get(),
                'value' => $this->priceFormatter->format((float)$value),
                'items' => [$itemData]
            ]
        ];
    }

    /**
     * @param Product $product
     * @return AddToCart
     */
    public function setProduct(Product $product): AddToCart
    {
        $this->product = $product;
        return $this;
    }

    public function setQty(int $qty): AddToCart
    {
        $this->qty = $qty;
        return $this;
    }
}
