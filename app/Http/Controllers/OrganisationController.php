<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Organisation;
use App\Services\OrganisationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class OrganisationController
 * @package App\Http\Controllers
 */
class OrganisationController extends ApiController
{
    /**
     * @var OrganisationService
     */
    protected $service;

    /**
     * OrganisationController constructor.
     * @param Request $request
     * @param OrganisationService $service
     */
    public function __construct(Request $request, OrganisationService $service)
    {
        parent::__construct($request);

        $this->service = $service;
    }

    /**
     * Store Organisation
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $organisation = $this->service->createOrganisation($request->all());
        return $this->transformItem('organisation', $organisation, ['user'])->respond();
    }

    /**
     * @param Request $request
     * @return Organisation
     */
    public function listAll(Request $request): Organisation
    {
        $filter = $request['filter'] ?: false;
        //TODO: add pagination
        return Organisation::filter($filter)->get();
    }
}
