<?php

namespace App\Models\Store;

use App\Enums\GeneralStatusEnum;
use App\Models\BaseModel;
use App\Models\Category\CategoryTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $name
 * @property string $owner_name
 * @property string $logo
 * @property int $city_id
 * @property int $region_id
 * @property int $seller_id
 * @property string $post_code
 * @property string $address
 * @property string $location
 * @property double $balance
 * @property boolean $is_active
*/
class Store extends BaseModel
{

    /**
     * @var string
     */
    protected $table = 'store';

    /**
     * @var string[]
     */
    protected $guarded = ['id'];
}
