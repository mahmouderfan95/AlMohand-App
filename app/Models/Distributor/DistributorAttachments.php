<?php

namespace App\Models\Distributor;

use App\Models\BaseModel;
use App\Traits\UuidDefaultValue;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $distributor_id
 * @property string $file_url
 * @property string $type
 * @property string $extension
 * @property string $size
 **/
class DistributorAttachments extends BaseModel
{
    use SoftDeletes, UuidDefaultValue;

    /**
     * @var string
     */
    protected $table = 'distributor_attachments';
    /**
     * @var bool
     */
    public $incrementing = false;
    /**
     * @var string
     */
    protected $keyType = 'string';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'distributor_id',
        'file_url',
        'type',
        'extension',
        'size'
    ];

    public function getFileUrlAttribute($value): string
    {
        if (isset($value))
            return asset('storage/uploads/distributors' . '/' . $value);

        return url("images/no-image.png");
    }

}
