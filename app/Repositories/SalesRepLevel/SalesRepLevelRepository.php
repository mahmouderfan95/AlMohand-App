<?php

namespace App\Repositories\SalesRepLevel;

use App\Enums\GeneralStatusEnum;
use App\Models\SalesRepLevel\SalesRepLevel;
use App\Repositories\Language\LanguageRepository;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Query\JoinClause;
use Prettus\Repository\Eloquent\BaseRepository;

class SalesRepLevelRepository extends BaseRepository
{

    private $salesRepLevelTranslationRepository;
    private $languageRepository;

    public function __construct(Application $app, SalesRepLevelTranslationRepository $salesRepLevelTranslationRepository, LanguageRepository $languageRepository)
    {
        parent::__construct($app);

        $this->salesRepLevelTranslationRepository = $salesRepLevelTranslationRepository;
        $this->languageRepository = $languageRepository;

    }



    public function trash()
    {
        return $this->model->onlyTrashed()->get();
    }

    public function getAllSalesRepLevelsForm($request)
    {
        $isPaginate = $request->input('is_paginate', 1);

        $query = $this->model->with('children.children')->latest();

        if ($isPaginate) {
            return $query->paginate(PAGINATION_COUNT_ADMIN);
        } else {
            return $query->get();
        }
    }



    public function store($data_request)
    {
        $data_request['status'] = GeneralStatusEnum::getStatusActive();
        /*
        $name = $data_request['name'][2];
        $firstLetters = strtoupper(implode('', array_map(function($word) {
            return strtoupper(substr($word, 0, 1));
        }, explode(' ', $name))));

        $lastSalesRepLevel = $this->model->orderBy('id', 'desc')->first();

        if ($lastSalesRepLevel) {
            $lastCode = $lastSalesRepLevel->code;
            $incrementedCode = (int)substr($lastCode, -2) + 1;
            $code = $firstLetters . '-' . str_pad($incrementedCode, 2, '0', STR_PAD_LEFT);
        } else {
            $code = $firstLetters . '-01';
        }

        $data_request['code'] = $code;*/

        $salesRepLevel = $this->model->create($data_request);
        if ($salesRepLevel)
        {
            $this->salesRepLevelTranslationRepository->storeOrUpdate($data_request, $salesRepLevel->id);
        }

        return $salesRepLevel;

    }

    public function update($data_request, $salesRepLevel_id)
    {
        $salesRepLevel = $this->model->find($salesRepLevel_id);
        $salesRepLevel->update($data_request);
        $this->salesRepLevelTranslationRepository->storeOrUpdate($data_request, $salesRepLevel->id);

        return $salesRepLevel;
    }

    public function update_status($status, $salesRepLevel_id)
    {
        $salesRepLevel = $this->model->find($salesRepLevel_id);
        $salesRepLevel->status = $status;
        return $salesRepLevel->save();

    }

    public function show($id)
    {
        return $this->model->where('id', $id)->with([
            'parent'
        ])->first();
    }

    public function destroy($id)
    {
        $salesRepLevel = $this->model->where('id', $id)->first();
        return $salesRepLevel->delete();
    }


    public function destroy_selected($ids)
    {
        foreach ($ids as $id) {
            $this->destroy($id);
        }
        return true;
    }

    public function getMainSalesRepLevels()
    {
        return $this->getModel()::with(['brand','parent'])
            ->withCount('child')
            ->whereNull('parent_id')
            ->active()
            ->get();
    }


    /**
     * SalesRepLevel Model
     *
     * @return string
     */
    public function model(): string
    {
        return SalesRepLevel::class;
    }

    public function getSalesRepLevelDetails($id)
    {
        return $this->model->query()
            ->select(['id', 'parent_id', 'image'])
            ->active()
            ->where('id', $id)
            ->first();
    }
}
