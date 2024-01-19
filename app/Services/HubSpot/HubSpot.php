<?php

namespace App\Services\HubSpot;

use \HubSpot\Factory;
use \HubSpot\Crm\ObjectType;

class HubSpot
{

    const COMPANIES = ObjectType::COMPANIES;
    const DEALS = ObjectType::DEALS;
    const CONTACTS = ObjectType::CONTACTS;

    public $hubSpot;

    public $token;

    public $model;

    public $limit = 100;

    public $properties;

    public $properties_history = [];

    public $associations = [];

    public function __construct($model) {
        $this->model = $model;
        $this->token = config('app.hubspot_key');
    }

    public function setUpConnection()
    {
        $this->hubSpot = Factory::createWithAccessToken($this->token);
        if (!isset($this->properties)) {
            $this->properties = $this->getAllProperties();
        }
    }

    public function getDatabyObjectId($id) : array
    {
        return [$this->hubSpot->crm()->{$this->model}()->basicApi()->getById($id, implode(",", $this->properties), implode(",", $this->properties_history), implode(",", $this->associations))];
    }

    public function getAllData() : array
    {
        $results = [];
        $offset = null;
        do {
            $response = $this->getDataByPage($offset);
            $results = array_merge($results, $response->getResults());
            $page = $response->getPaging();
            $offset = $page ? $page->getNext()->getAfter() : null;
        } while ($offset);
        return $results;
    }

    private function getDataByPage($offset = 0)
    {
        return $this->hubSpot->crm()->{$this->model}()->basicApi()->getPage($this->limit, $offset, implode(",", $this->properties), implode(",", $this->properties_history), implode(",", $this->associations));
    }

    private function getAllProperties(): array
    {
        $properties = [];
        $response = $this->hubSpot->crm()->properties()->coreApi()->getAll($this->model);
        foreach ($response->getResults() as $item) {
            $properties[] = $item->getName();
        }
        return $properties;
    }

    public function createObject($object)
    {
        return $this->hubSpot->crm()->{$this->model}()->basicApi()->create($object);
    }

}
