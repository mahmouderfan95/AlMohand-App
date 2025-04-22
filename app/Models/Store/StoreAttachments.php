<?php

namespace App\Models\Store;

use App\Enums\GeneralStatusEnum;
use App\Models\BaseModel;
use App\Models\Category\CategoryTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $store_id
 * @property string $file_url
 * @property string $type
 * @property int $extension
 * @property int $size
*/
class StoreAttachments extends BaseModel
{

    /**
     * @var string
     */
    protected $table = 'store_attachments';

    /**
     * @var string[]
     */
    protected $guarded = ['id'];
}
