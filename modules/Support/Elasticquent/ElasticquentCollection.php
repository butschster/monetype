<?php

namespace Modules\Support\Elasticquent;

use Elasticsearch\ClientBuilder;
use Illuminate\Database\Eloquent\Collection;

class ElasticquentCollection extends Collection
{

    /**
     * Add To Index.
     *
     * Add all documents in this collection to
     * to the Elasticsearch document index.
     *
     * @return mixed
     */
    public function addToIndex()
    {
        if ($this->isEmpty()) {
            return;
        }

        $params = [];

        foreach ($this->all() as $item) {
            $params['body'][] = [
                'index' => [
                    '_id'    => $item->getKey(),
                    '_type'  => $item->getTypeName(),
                    '_index' => $item->getIndexName(),
                ],
            ];

            $params['body'][] = $item->getIndexDocumentData();
        }

        return $this->getElasticSearchClient()->index($params);
    }


    /**
     * Delete From Index.
     *
     * @return array
     */
    public function deleteFromIndex()
    {
        $all = $this->all();

        $params = [];

        foreach ($all as $item) {
            $params['body'][] = [
                'delete' => [
                    '_id'    => $item->getKey(),
                    '_type'  => $item->getTypeName(),
                    '_index' => $item->getIndexName(),
                ],
            ];
        }

        return $this->getElasticSearchClient()->delete($params);
    }


    /**
     * Reindex.
     *
     * Delete the items and then re-index them.
     *
     * @return array
     */
    public function reindex()
    {
        $this->deleteFromIndex();

        return $this->addToIndex();
    }


    /**
     * @return Client
     */
    public function getElasticSearchClient()
    {
        return ClientBuilder::create()->build();
    }
}