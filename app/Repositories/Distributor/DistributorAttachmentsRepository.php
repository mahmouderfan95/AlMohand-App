<?php

namespace App\Repositories\Distributor;

use App\Enums\Distributor\DistributorAttachmentTypeEnum;
use App\Enums\SellerAttachmentType;
use App\Models\Distributor\DistributorAttachments;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Container\Container as Application;


class DistributorAttachmentsRepository extends BaseRepository
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Currency Model
     *
     * @return string
     */
    public function model(): string
    {
        return DistributorAttachments::class;
    }

    public function store($requestData, $distributor_id)
    {
        $data = [];
        // make loop in all types of files
        foreach (DistributorAttachmentTypeEnum::getList() as $type){
            $temp = [];
            // check files to handling save it
            if ($requestData->hasFile($type)){
                // make loop in multiple files in one type
                foreach($requestData->file($type) as $key => $file){
                    $temp['distributor_id'] = $distributor_id;
                    $temp['file_url'] = $this->save_file($file, 'distributors');
                    $temp['type'] = $type;
                    $temp['created_at'] = Carbon::now();
                    $temp['updated_at'] = Carbon::now();
                    // get extension and size of file
                    $size = $this->convertToHumanReadableSize($file->getSize());
                    $fileExtension = $file->getClientOriginalExtension();
                    $temp['extension'] = $fileExtension;
                    $temp['size'] = $size;
                    $data[] = $temp;
                }
            }
        }
        foreach ($data as $item) {
            $this->model->query()->create($item);
        }
        return true;
    }

    private function convertToHumanReadableSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $unitIndex = 0;
        while ($size > 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }
        return round($size, 2) . ' ' . $units[$unitIndex];
    }
}
