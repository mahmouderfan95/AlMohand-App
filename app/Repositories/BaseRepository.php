<?php

namespace App\Repositories;

use App\Helpers\FileUpload;
use App\Models\Language\Language;
use Exception;
use Prettus\Repository\Eloquent\BaseRepository as PackageBaseRepository;

class BaseRepository extends PackageBaseRepository
{
    use FileUpload;

    /**
     * @return void
     */
    public function model()
    {
        // TODO: Implement model() method.
    }


    /**
     * @param array $data
     * @param null $existingModel
     * @return mixed
     * @throws Exception
     */
    public function saveWithTranslations(array $data, $model = null): mixed
    {
        $main_model = $model ? $model->fill($data) : $this->model->create($data);

        if ($model) {
            $main_model->save();
        }

        $languages = Language::query()->pluck('id', 'code')->toArray();

        if (!property_exists($this->model, 'translatedAttributes')) {
            throw new Exception('Model must define translatableAttributes to use translations.');
        }

        if (!empty($data['translations'])) {
            foreach ($data['translations'] as $locale => $translation_data) {
                $language_id = $languages[$locale] ?? null;

                if (!$language_id) {
                    continue;
                }

                $translation = [];

                foreach ($this->model->translatedAttributes as $attribute) {
                    if (isset($translation_data[$attribute])) {
                        $translation[$attribute] = $translation_data[$attribute];
                    }
                }

                $translation['language_id'] = $language_id;

                $main_model->translations()->updateOrCreate(
                    ['language_id' => $language_id],
                    $translation
                );
            }
        }

        return $main_model;
    }

    public function find($id, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->find($id, $columns);
        $this->resetModel();

        return $this->parserResult($model);
    }

    public function findWithRelations($id, array $relations = [])
    {
        return $this->model->with($relations)->find($id);
    }
}
