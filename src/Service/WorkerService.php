<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Request;

class WorkerService extends ListService
{
    public function __construct(Request $request)
    {
        $this->offset = $request->get('offset') ?? 0;
        $this->limit = $request->get('limit') ?? 0;
        $this->order = $request->get('order') ?? 'asc';
        $this->sort = $request->get('sort') ?? 'name';
        $this->query = $request->get('query') ?? '';
    }
}