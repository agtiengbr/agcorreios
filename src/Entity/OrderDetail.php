<?php


namespace AGTI\Correios\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class OrderDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id_order_detail", type="integer")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Orders", cascade={"persist"})
     * @ORM\JoinColumn(name="id_order", referencedColumnName="id_order")
     */
    private $order;

    /**
     * @ORM\Column(name="product_quantity", type="integer")
     */
    private $qty;

    /**
     * @ORM\Column(name="product_price", type="float")
     */
    private $price;

    /**
     * @ORM\Column(name="unit_price_tax_incl", type="float")
     */
    private $unitPriceTaxIncl;

    /**
     * @ORM\Column(type="float")
     */
    private $totalPriceTaxIncl;
    /**
     * @ORM\Column(type="float")
     */
    private $totalPriceTaxExcl;
    /**
     * @ORM\Column(type="float")
     */
    private $unitPriceTaxExcl;

    /**
     * @ORM\Column(type="integer")
     */
    private $idShop;

    /**
     * @ORM\Column(type="integer")
     */
    private $productId;

    /**
     * @ORM\Column(type="string")
     */
    private $productName;

    /**
     * @ORM\Column(type="float")
     */
    private $productWeight;

    /**
     * @ORM\Column(type="string")
     */
    private $taxName;

    /**
     * @ORM\Column(type="string")
     */
    private $productReference;

    /**
     * @ORM\Column(type="string")
     */
    private $productSupplierReference;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id_product")
     */
    private $product;
    
    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of price
     */ 
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */ 
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of order
     */ 
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set the value of order
     *
     * @return  self
     */ 
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get the value of qty
     */ 
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * Set the value of qty
     *
     * @return  self
     */ 
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get the value of productName
     */ 
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * Set the value of productName
     *
     * @return  self
     */ 
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * Get the value of productWeight
     */ 
    public function getProductWeight()
    {
        return $this->productWeight;
    }

    /**
     * Set the value of productWeight
     *
     * @return  self
     */ 
    public function setProductWeight($productWeight)
    {
        $this->productWeight = $productWeight;

        return $this;
    }

    /**
     * Get the value of taxName
     */ 
    public function getTaxName()
    {
        return $this->taxName;
    }

    /**
     * Set the value of taxName
     *
     * @return  self
     */ 
    public function setTaxName($taxName)
    {
        $this->taxName = $taxName;

        return $this;
    }

    /**
     * Get the value of idShop
     */ 
    public function getIdShop()
    {
        return $this->idShop;
    }

    /**
     * Set the value of idShop
     *
     * @return  self
     */ 
    public function setIdShop($idShop)
    {
        $this->idShop = $idShop;

        return $this;
    }

    /**
     * Get the value of productReference
     */ 
    public function getProductReference()
    {
        return $this->productReference;
    }

    /**
     * Set the value of productReference
     *
     * @return  self
     */ 
    public function setProductReference($productReference)
    {
        $this->productReference = $productReference;

        return $this;
    }

    /**
     * Get the value of productSupplierReference
     */ 
    public function getProductSupplierReference()
    {
        return $this->productSupplierReference;
    }

    /**
     * Set the value of productSupplierReference
     *
     * @return  self
     */ 
    public function setProductSupplierReference($productSupplierReference)
    {
        $this->productSupplierReference = $productSupplierReference;

        return $this;
    }

    /**
     * Get the value of unitPriceTaxIncl
     */ 
    public function getUnitPriceTaxIncl()
    {
        return $this->unitPriceTaxIncl;
    }

    /**
     * Set the value of unitPriceTaxIncl
     *
     * @return  self
     */ 
    public function setUnitPriceTaxIncl($unitPriceTaxIncl)
    {
        $this->unitPriceTaxIncl = $unitPriceTaxIncl;

        return $this;
    }

    /**
     * Get the value of totalPriceTaxIncl
     */ 
    public function getTotalPriceTaxIncl()
    {
        return $this->totalPriceTaxIncl;
    }

    /**
     * Set the value of totalPriceTaxIncl
     *
     * @return  self
     */ 
    public function setTotalPriceTaxIncl($totalPriceTaxIncl)
    {
        $this->totalPriceTaxIncl = $totalPriceTaxIncl;

        return $this;
    }

    /**
     * Get the value of totalPriceTaxExcl
     */ 
    public function getTotalPriceTaxExcl()
    {
        return $this->totalPriceTaxExcl;
    }

    /**
     * Set the value of totalPriceTaxExcl
     *
     * @return  self
     */ 
    public function setTotalPriceTaxExcl($totalPriceTaxExcl)
    {
        $this->totalPriceTaxExcl = $totalPriceTaxExcl;

        return $this;
    }

    /**
     * Get the value of unitPriceTaxExcl
     */ 
    public function getUnitPriceTaxExcl()
    {
        return $this->unitPriceTaxExcl;
    }

    /**
     * Set the value of unitPriceTaxExcl
     *
     * @return  self
     */ 
    public function setUnitPriceTaxExcl($unitPriceTaxExcl)
    {
        $this->unitPriceTaxExcl = $unitPriceTaxExcl;

        return $this;
    }

    /**
     * Get the value of productId
     */ 
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set the value of productId
     *
     * @return  self
     */ 
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get the value of product
     */ 
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set the value of product
     *
     * @return  self
     */ 
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }
}