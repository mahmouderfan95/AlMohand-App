<?php

namespace App\DTO;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionProperty;

class BaseDTO
{

    protected Request $request;
    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->autoMapProperties($request);
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function autoMapProperties(Request $request): void
    {
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PRIVATE | ReflectionProperty::IS_PROTECTED);

        foreach ($properties as $property) {
            $name = $property->getName();
            $camel_case_name = Str::camel($name);
            $setter_method = 'set' . ucfirst($camel_case_name);

            if ($name === 'translations' && $request->has($name)) {
                $this->processTranslations($request->input($name));
            } elseif ($request->has($name)) {
                $value = $request->input($name);

                if (method_exists($this, $setter_method)) {
                    $this->$setter_method($value);
                } else {
                    $this->$name = $value;
                }
            }
        }
    }

    /**
     * Process translations dynamically.
     *
     * @param array $translations
     * @return void
     */
    protected function processTranslations(array $translations): void
    {
        $normalizedTranslations = [];

        foreach ($translations as $locale => $fields) {
            foreach ($fields as $key => $value) {
                $normalizedTranslations[$locale][$key] = $value;
            }
        }

        $this->translations = $normalizedTranslations;
    }


    public function getRequestData() :array
    {
        return $this->request->all();
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * This Method get all data that set to properties
    */
    public function toArray(array $ignored = []): array
    {
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PRIVATE | ReflectionProperty::IS_PROTECTED);

        $data = [];
        foreach ($properties as $property) {
            $name = $property->getName();

            if (! in_array($name, $ignored) and $name != 'request') {
                $data[$name] = $this->$name;
            }
        }

        return $data;
    }
}
