<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyRequest;
use App\Repositories\PropertyRepository;
use App\Traits\SendApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    use SendApiResponse;

    private $propertyRepository;

    public function __construct(PropertyRepository $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    /**
     * Get properties endpoint
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        $data = $this->propertyRepository->getAll();
        return $this->sendApiResponse('Properties fetched successfully', Response::HTTP_OK, $data);
    }

    /**
     * Store propertties endpoint
     * @param StorePropertyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePropertyRequest $request) {
        $data = null;

        if ($request->hasFile('properties')) {
            $this->propertyRepository->storePropertiesByFile($request->validated());

            return $this->sendApiResponse('Properties uploaded successfully', Response::HTTP_CREATED);
        }

        $data = $this->propertyRepository->storeProperties($request->validated());

        return $this->sendApiResponse('Property stored successfully', Response::HTTP_CREATED, $data);
    }

    /**
     * Download sample properties
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadSampleCsv() {
        return response()->download(public_path('properties.csv'));
    }
}
