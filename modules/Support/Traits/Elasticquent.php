<?php

namespace Modules\Support\Traits;


use Elasticsearch\ClientBuilder;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Support\Elasticquent\DocumentMissingException;

trait Elasticquent
{

    /**
     * Document Score.
     *
     * Hit score when using data
     * from Elasticsearch results.
     *
     * @var null|int
     */
    protected $documentScore = null;

    /**
     * Document Version.
     *
     * Elasticsearch document version.
     *
     * @var null|int
     */
    protected $documentVersion = null;


    /**
     * @return Client
     */
    public function getElasticSearchClient()
    {
        return ClientBuilder::create()->build();
    }


    /**
     * Get Type Name.
     *
     * @return string
     */
    public function getTypeName()
    {
        return $this->getTable();
    }


    /**
     * Get Index Name.
     *
     * @return string
     */
    public function getIndexName()
    {
        return config('elasticsearch.default_index', 'default');
    }


    /**
     * Get Mapping Properties.
     *
     * @return array
     */
    public function getMappingProperties()
    {
        return $this->mappingProperties;
    }


    /**
     * Set Mapping Properties.
     *
     * @param array $mapping
     *
     * @internal param array $mappingProperties
     */
    public function setMappingProperties(array $mapping)
    {
        $this->mappingProperties = $mapping;
    }


    /**
     * Get Document Score.
     *
     * @return null|float
     */
    public function documentScore()
    {
        return $this->documentScore;
    }


    /**
     * Document Version.
     *
     * @return null|int
     */
    public function documentVersion()
    {
        return $this->documentVersion;
    }


    /**
     * Get Index Document Data.
     *
     * Get the data that Elasticsearch will
     * index for this particular document.
     *
     * @return array
     */
    public function getElasticSearchDocumentData()
    {
        return $this->toArray();
    }


    /**
     * Get Basic Elasticsearch Params.
     *
     * @param null $perPage
     * @param int  $offset
     *
     * @return array
     */
    public function getElasticSearchDocumentParams($perPage = null, $offset = 0)
    {
        $params = [
            'index' => $this->getIndexName(),
            'type'  => $this->getTypeName(),
            'id'    => $this->getKey(),
        ];

        if (is_integer($perPage)) {
            $params['body']['size'] = (int) $perPage;
        }

        if (is_integer($offset)) {
            $params['body']['from'] = (int) $offset;
        }

        return $params;
    }


    /**
     * Index Documents
     * Index all documents in an Eloquent model.
     *
     * @return array
     */
    public static function addAllToIndex()
    {
        return static::newQuery()->get()->addToIndex();
    }


    /**
     * Re-Index All Content
     *
     * @return array
     */
    public static function reindex()
    {
        return static::newQuery()->get()->reindex();
    }


    /**
     * Build your own search.
     *
     * @param array $params
     * @param int   $perPage
     * @param int   $offset
     *
     * @return ElasticSearchResultCollection
     */
    public static function searchCustom(array $params = [], $perPage = null, $offset = 0)
    {
        $instance    = new static();
        $basicParams = $instance->getElasticSearchDocumentParams($perPage, $offset);
        unset($basicParams['id']);

        $params      = array_merge($basicParams, $params);
        $result      = $instance->getElasticSearchClient()->search($params);

        return $instance->hitsToItems($result, $perPage);
    }


    /**
     * Search By Query.
     *
     * Search with a query array
     *
     * @param array $query
     * @param int   $perPage
     * @param int   $offset
     *
     * @return ElasticSearchResultCollection
     */
    public static function searchByQuery($query = null, $perPage = null, $offset = 0)
    {
        $instance = new static();
        $params   = $instance->getElasticSearchDocumentParams($perPage, $offset);

        unset($params['id']);

        if ($query) {
            $params['body']['query'] = $query;
        }
        $result = $instance->getElasticSearchClient()->search($params);

        return $instance->hitsToItems($result, $perPage);
    }


    /**
     * Search.
     *
     * Simple search using a match _all query
     *
     * @param string $term
     * @param int    $perPage
     * @param int    $offset
     *
     * @return ElasticSearchResultCollection
     */
    public static function search($term = null, $perPage = null, $offset = 0)
    {
        $instance                                 = new static();
        $params                                   = $instance->getElasticSearchDocumentParams($perPage, $offset);

        unset($params['id']);

        $params['body']['query']['match']['_all'] = $term;
        $result                                   = $instance->getElasticSearchClient()->search($params);

        return $instance->hitsToItems($result, $perPage);
    }


    /**
     * Add to Search Index.
     *
     * @return array
     * @throws DocumentMissingException
     */
    public function addToIndex()
    {
        if ( ! $this->exists) {
            throw new DocumentMissingException('Document does not exist.');
        }
        $params = $this->getElasticSearchDocumentParams();
        // Get our document body data.
        $params['body'] = $this->getElasticSearchDocumentData();
        // The id for the document must always mirror the
        // key for this model, even if it is set to something
        // other than an auto-incrementing value. That way we
        // can do things like remove the document from
        // the index, or get the document from the index.
        $params['id'] = $this->getKey();

        return $this->getElasticSearchClient()->index($params);
    }


    /**
     * Remove From Search Index.
     *
     * @return array
     */
    public function removeFromIndex()
    {
        return $this->getElasticSearchClient()->delete($this->getElasticSearchDocumentParams());
    }


    /**
     * Mapping Exists.
     *
     * @return bool
     */
    public static function mappingExists()
    {
        $instance = new static();
        $mapping  = $instance->getMapping();

        return ( empty( $mapping ) ) ? false : true;
    }


    /**
     * Get Mapping.
     *
     * @return void
     */
    public static function getMapping()
    {
        $instance = new static();
        $params   = $instance->getElasticSearchDocumentParams();

        return $instance->getElasticSearchClient()->indices()->getMapping($params);
    }


    /**
     * Put Mapping.
     *
     * @param  bool $ignoreConflicts
     *
     * @return array
     */
    public static function putMapping($ignoreConflicts = false)
    {
        $instance = new static;
        $mapping  = $instance->getElasticSearchDocumentParams();

        unset( $mapping['id'] );

        $params = [
            '_source'    => ['enabled' => true],
            'properties' => $instance->getMappingProperties(),
        ];

        $mapping['body'][$instance->getTypeName()] = $params;
        $mapping['ignore_conflicts']               = $ignoreConflicts;

        return $instance->getElasticSearchClient()->indices()->putMapping($mapping);
    }


    /**
     * Delete Mapping.
     *
     * @return array
     */
    public static function deleteMapping()
    {
        $instance = new static();
        $params   = $instance->getElasticSearchDocumentParams();

        return $instance->getElasticSearchClient()->indices()->deleteMapping($params);
    }


    /**
     * Rebuild Mapping.
     *
     * This will delete and then re-add
     * the mapping for this model.
     *
     * @return array
     */
    public static function rebuildMapping()
    {
        $instance = new static();
        // If the mapping exists, let's delete it.
        if ($instance->mappingExists()) {
            $instance->deleteMapping();
        }
        // Don't need ignore conflicts because if we
        // just removed the mapping there shouldn't
        // be any conflicts.
        return $instance->putMapping();
    }


    /**
     * Create Index.
     *
     * @param  int $shards
     * @param  int $replicas
     *
     * @return array
     */
    public static function createIndex($shards = null, $replicas = null)
    {
        $instance = new static();
        $index    = ['index' => $instance->getIndexName()];
        if ($shards) {
            $index['body']['settings']['number_of_shards'] = $shards;
        }
        if ($replicas) {
            $index['body']['settings']['number_of_replicas'] = $replicas;
        }

        return $instance->getElasticSearchClient()->indices()->create($index);
    }


    /**
     * Delete Index.
     *
     * @return array
     */
    public static function deleteIndex()
    {
        $instance = new static();
        $index    = ['index' => $instance->getIndexName()];

        return $instance->getElasticSearchClient()->indices()->delete($index);
    }


    /**
     * Index Exists.
     *
     * Does this index exist?
     *
     * @return bool
     */
    public static function indexExists()
    {
        $instance = new static();
        $params   = ['index' => $instance->getIndexName()];

        return $instance->getElasticSearchClient()->indices()->exists($params);
    }


    /**
     * Type Exists.
     *
     * Does this type exist?
     *
     * @return bool
     */
    public static function typeExists()
    {
        $instance = new static();
        $params   = $instance->getElasticSearchDocumentParams();

        return $instance->getElasticSearchClient()->indices()->exists($params);
    }


    /**
     * Optimize the elasticsearch index.
     *
     * @param  array $params
     *
     * @return bool
     */
    public static function optimize(array $params = [])
    {
        $instance    = new static();
        $basicParams = ['index' => $instance->getIndexName()];
        $params      = array_merge($basicParams, $params);

        return $instance->getElasticSearchClient()->indices()->optimize($params);
    }


    /**
     * Hits To Items.
     *
     * @param array $results
     * @param int   $perPage
     *
     * @return array
     */
    protected function hitsToItems(array $results, $perPage = null)
    {
        $ids = [];

        $items = array_get($results, 'hits.hits', []);

        foreach ($items as $hit) {
            $ids[array_get($hit, '_id')] = $hit;
        }

        $page = Paginator::resolveCurrentPage('page');

        $total = array_get($results, 'hits.total', 0);

        $items = $this->getQueryForFoundDocuments($ids)->get();
        $items->each(function($item) use($items) {
            // In addition to setting the attributes
            // from the index, we will set the score as well.
            $item->documentScore = array_get($items, $item->id . '._score');

            // Set our document version
            $item->documentVersion = array_get($items, $item->id . '._version');
        });

        return new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    /**
     * @param array $ids
     *
     * @return Builder
     */
    protected function getQueryForFoundDocuments(array $ids)
    {
        return static::newQuery()->whereIn('id', array_keys($ids));
    }
}