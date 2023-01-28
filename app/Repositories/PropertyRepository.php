<?php

namespace App\Repositories;

use App\Exceptions\CustomException;
use App\Models\Property;
use Illuminate\Support\Facades\Cache;

class PropertyRepository
{
    const DEFAULT_PAGINATION = 20;

    /**
     * Get all properties
     * @return mixed
     */
    public function getAll() {
        $pageLength = (int) request()->page_length;
        $pageLength = $pageLength > 0 ? $pageLength : static::DEFAULT_PAGINATION;

        return Cache::tags([Property::cacheTag()])->remember(Property::cacheKey($pageLength), Property::cacheTTL(),
                function() use ($pageLength) {
                    return Property::paginate($pageLength);
                });
    }

    /**
     * Store properties by file upload
     * @param mixed $requestData
     * @throws CustomException
     * @return void
     */
    public function storePropertiesByFile($requestData) {
        $dataFromFile = csvToArray($requestData['properties']);

        if (count($dataFromFile) < 1) {
            throw new CustomException('File is empty', 400);
        }

        foreach ($dataFromFile as $value) {
            $this->storeProperties(
                $value
            );
        }
    }

    /**
     * Store properties on database
     * @param mixed $requestData
     * @return mixed
     */
    public function storeProperties($requestData) {
        if (isset($requestData['address'])) {
            $requestData = $requestData['address'];
        }
        return Property::firstOrCreate(
            [
                'address_line_1' => $requestData['line_1'],
                'address_line_2' => $requestData['line_2'],
                'postcode' => $requestData['postcode']
            ]
        );
    }
}
