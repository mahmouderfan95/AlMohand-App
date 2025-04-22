<?php

namespace App\Repositories\Admin;

use App\Helpers\FileUpload;
use App\Models\Language\Language;
use App\Models\Setting;
use App\Repositories\Currency\CurrencyRepository;
use App\Repositories\GeoLocation\CityRepository;
use App\Repositories\GeoLocation\CountryRepository;
use App\Repositories\GeoLocation\RegionRepository;
use App\Repositories\Language\LanguageRepository;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;

class SettingRepository extends BaseRepository
{
    use FileUpload;

    public function __construct(
        Application $app,
        private SettingTranslationRepository $settingTranslationRepository,
        private CountryRepository $countryRepository,
        private RegionRepository $regionRepository,
        private CityRepository $cityRepository,
        private CurrencyRepository $currencyRepository,
        private LanguageRepository $languageRepository,
    )
    {
        parent::__construct($app);
    }

    public function getSettingByKeyword($key)
    {
        return $this->model->where('key', $key)->value('plain_value');
    }

    public function mainSettings()
    {
        $settings = $this->model
            ->whereIn('key',[
                'store_name',
                'manager_name',
                'phone',
                'email',
                'address_line',
                'main_image',
                'maintenance_mode',
                // 'dashboard_default_language',
                // 'website_default_language',
                // 'country_id',
                // 'region_id',
                // 'city_id',
                // 'postal_code',
                // 'meta_title',
                // 'meta_description',
                // 'meta_keywords',
            ])
            ->get();

        $local = app()->getLocale() ??'ar';
        $langId = Language::where('code',$local)->first()->id;
        foreach ($settings as $setting) {
            if ($setting->is_translatable == 1) {
                $setting->translations = $this->settingTranslationRepository->getSettingTranslations($setting->id);
            }
            switch ($setting->key) {
                case 'main_image':
                    if (isset($setting->plain_value))
                        $setting->plain_value = asset('/storage/uploads/settings'). '/'.$setting->plain_value;
                    break;
                default:
                    $setting->model = null;
                    break;
            }
        }

        return $settings;
    }
    public function updateMainSettings($requestData)
    {
        $settings = $this->model->all();
        foreach ($settings as $setting) {
            $inputName = $setting->key;
            if(! $requestData->has($setting->key)){
                continue;
            }
            if($setting->is_translatable){
                $this->settingTranslationRepository->store($setting, $requestData);
            }else{
                switch ($setting->key) {
                    case 'main_image':
                        if (isset($requestData->main_image))
                            $setting->plain_value = $this->save_file($requestData->main_image, 'settings');
                        break;
                    default:
                        $setting->plain_value = $requestData->{$inputName};
                        break;
                }
            }
            $setting->save();

        }

        return true;
    }

    public function updatePricesDisplay($requestData)
    {
        $setting = $this->model->where('key', 'prices_include_tax')->first();
        if (! $setting)
            return false;
        $setting->plain_value = $requestData->prices_include_tax;
        $setting->save();
        return $setting;
    }

    public function updateTaxNumber($requestData)
    {
        $settings = $this->model
            ->whereIn('key',[
                'tax_number',
                'show_tax_number',
                'tax_files'
            ])
            ->get();

        foreach ($settings as $setting) {
            switch ($setting->key) {
                case 'tax_number':
                    $setting->plain_value = $requestData->tax_number;
                    break;
                case 'show_tax_number':
                    $setting->plain_value = $requestData->show_tax_number;
                    break;
                case 'tax_files':
                    $filesPath = [];
                    if (isset($requestData->tax_files) && $requestData->hasFile('tax_files')) {
                        foreach ($requestData->file('tax_files') as $taxFile)
                            $filesPath[] = $this->save_file($taxFile, 'settings');
                    }
                    $setting->plain_value = json_encode($filesPath);
                    break;
                default:
                    break;
            }
            $setting->save();
        }
        return $settings;
    }

    public function getTaxesKeys()
    {
        $settings = $this->model
            ->whereIn('key',[
                'tax_number',
                'prices_include_tax',
                'show_tax_number',
                'tax_files'
            ])
            ->get();

        $data = [];
        foreach ($settings as $setting) {
            if ($setting->key != 'tax_files')
                $data[$setting->key] = $setting->plain_value;
            elseif (isset($setting->plain_value) && is_array(json_decode($setting->plain_value)) && count(json_decode($setting->plain_value)) > 0) {
                foreach (json_decode($setting->plain_value) as $file){
                    $data['tax_files'][] = asset('/storage/uploads/settings') . '/' . $file;
                }
            }else{
                $data['tax_files'] = [];
            }
        }
        return $data;
    }


    public function model(): string
    {
        return Setting::class;
    }
}
