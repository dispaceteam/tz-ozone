<?php


namespace App\Services\Ozone\Entity;

/**
 * @OA\Schema(required={"name", "description", "category_id", "offer_id", "price", "vat", "images", "height", "depth", "width", "dimension_unit", "weight", "weight_unit"}, @OA\Xml(name="ProductEntity"))
 */
class ProductEntity extends BaseEntity
{

    protected $fields = ["name", "barcode", "description", "category_id", "offer_id", "price", "old_price", "premium_price", "vat", "vendor", "vendor_code", "height", "depth", "width", "dimension_unit", "weight", "weight_unit", "attributes", "images"];
    protected $required = ["name", "description", "category_id", "offer_id", "price", "vat", "images", "height", "depth", "width", "dimension_unit", "weight", "weight_unit"];

    /**
     * @OA\Property(example="Samsung Galaxy S9")
     * @var string
     */
    public $name;

    /**
     * @OA\Property(example="8801643566784")
     * @var string
     */
    public $barcode;

    /**
     * @OA\Property(example="Red Samsung Galaxy S9 with 512GB")
     * @var string
     */
    public $description;

    /**
     * @OA\Property(example="17030819")
     * @var integer
     */
    public $category_id;

    /**
     * @OA\Property(example="REDSGS9-512")
     * @var string
     */
    public $offer_id;

    /**
     * @OA\Property(example="79990")
     * @var string
     */
    public $price;

    /**
     * @OA\Property(example="89990")
     * @var string
     */
    public $old_price;

    /**
     * @OA\Property(example="75555")
     * @var string
     */
    public $premium_price;

    /**
     * @OA\Property(example="0")
     * @var string
     */
    public $vat;

    /**
     * @OA\Property(example="Samsung")
     * @var string
     */
    public $vendor;

    /**
     * @OA\Property(example="SM-G960UZPAXAA")
     * @var string
     */
    public $vendor_code;

    /**
     * @OA\Property(example=77)
     * @var integer
     */
    public $height;

    /**
     * @OA\Property(example=11)
     * @var integer
     */
    public $depth;

    /**
     * @OA\Property(example=120)
     * @var integer
     */
    public $width;

    /**
     * @OA\Property(example="mm")
     * @var string
     */
    public $dimension_unit;

    /**
     * @OA\Property(example=100)
     * @var int
     */
    public $weight;
    /**
     * @OA\Property(example="g")
     * @var string
     */
    public $weight_unit;

    /**
     * @OA\Property(
     *      type="array",
     *          @OA\Items(
     *              type="object",
     *              @OA\Property(
     *                  property="id",
     *                  type="integer"
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string"
     *              ),
     *          )
     * )
     */
    public $attributes;

    /**
     * @OA\Property(
     *      type="array",
     *          @OA\Items(
     *              type="object",
     *              @OA\Property(
     *                  property="file_name",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="default",
     *                  type="boolean"
     *              ),
     *          )
     * )
     */
    public $images;

    /*
     * !!! ПРИ ПОЛНОЦЕННОЙ РЕАЛИЗАЦИИ ЗДЕСЬ БЫЛА БЫ ЛОГИКА КОНВЕРТИРУЮЩАЯ ФОРМАТ ТОВАРА НАШЕГО API В ФОРМАТ ДЛЯ OZONE
     */
    public function fill_fields($request){
        foreach ($this->fields as $field){
            if(isset($request[$field])){
                $this->{$field} = $request[$field];
            }
        }
    }

    /**
     * @return array
     */
    public function getRequired(): array
    {
        return $this->required;
    }
}
