<?php

namespace PHPAccounting\Quickbooks\Helpers;

class SearchQueryBuilder
{
    /**
     * Builds search / filter query based on search parameters and filter.
     * When $modifiedSince is supplied, appends a
     * `Metadata.LastUpdatedTime >= '...'` clause for delta sync — QB's
     * per-entity equivalent of the /cdc batch endpoint. The cutoff is an
     * ISO-8601 datetime in QB's expected `Y-m-d\TH:i:s` format.
     */
    public static function buildSearchQuery($modelClass, $searchParams, $exactSearch, $searchFilters, $exactFilters, ?string $modifiedSince = null) {
        $query = "SELECT * FROM ". $modelClass. " WHERE ";
        $separationFilter = "";
        if ($searchParams) {
            $searchParameters = $searchParams;
            foreach($searchParameters as $key => $value)
            {
                if ($exactSearch)
                {
                    $statement = $separationFilter.$key."='".$value."'";
                } else {
                    $statement = $separationFilter.$key." LIKE '%".$value."%'";
                }

                $separationFilter = " AND ";
                $query .= $statement;
            }
        }
        $queryCounter = 0;
        if ($searchFilters) {
            if ($searchParams)
            {
                $query.=' AND ';
            }
            foreach($searchFilters as $key => $value) {
                $queryString = '';
                $filterKey = $key;
                if ($exactFilters)
                {
                    if (is_array($value)) {
                        foreach($value as $filterValue) {
                            if (is_bool($filterValue)) {
                                $searchQuery = $filterKey."=".($filterValue ? 'true' : 'false');
                            } else {
                                $searchQuery = $filterKey."='".$filterValue."'";
                            }
                            if ($queryCounter == 0) {
                                $queryString = $searchQuery;
                            } else {
                                $queryString.= ' AND '.$searchQuery;
                            }
                            $queryCounter++;
                        }
                    } else {
                        if (is_bool($value)) {
                            $searchQuery = $filterKey."=".($value ? 'true' : 'false');
                        } else {
                            $searchQuery = $filterKey."='".$value."'";
                        }
                        if ($queryCounter == 0) {
                            $queryString = $searchQuery;
                        } else {
                            $queryString.= ' AND '.$searchQuery;
                        }
                        $queryCounter++;
                    }

                } else {
                    $searchQuery = $filterKey." IN (";
                    $count = 1;
                    $queryString = $searchQuery;
                    if (is_array($value)) {
                        foreach($value as $filterValue) {
                            if ($count != count($value))
                            {
                                $queryString.="'".$filterValue."', ";
                            }
                            else {
                                $queryString.="'".$filterValue."')";
                            }
                            $count++;
                        }
                    } else {
                        $queryString.="'".$value."')";
                    }

                }
                $query .= $queryString;
            }
        }

        if ($modifiedSince !== null && $modifiedSince !== '') {
            $hasPriorClauses = (bool) ($searchParams || $searchFilters);
            if ($hasPriorClauses) {
                $query .= " AND ";
            }
            $query .= "Metadata.LastUpdatedTime >= '" . $modifiedSince . "'";
        }

        return $query;
    }
}