<?php

namespace App\Application\CampanasLeads\QueryHandlers;

use App\Application\CampanasLeads\Queries\GetLeadsQuery;
use App\Domain\CampanasLeads\Contracts\LeadRepositoryInterface;

class GetLeadsQueryHandler
{
    public function __construct(private readonly LeadRepositoryInterface $repository) {}

    public function handle(GetLeadsQuery $query): array
    {
        return $this->repository->paginate($query->campanaLeadId, $query->pagination);
    }
}
