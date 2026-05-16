<?php

namespace PHPAccounting\Quickbooks\Traits;

trait GetRequestTrait
{
    /**
     * Set AccountingID from Parameter Bag (AccountID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @param $value
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Set Page Value for Pagination from Parameter Bag
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @param $value
     */
    public function setPage($value) {
        return $this->setParameter('page', $value);
    }

    /**
     * Accounting ID (AccountID)
     * @return mixed comma-delimited-string
     */
    public function getAccountingID() {
        if ($this->getParameter('accounting_id')) {
            return $this->getParameter('accounting_id');
        }
        return null;
    }

    /**
     * Return Page Value for Pagination
     * @return integer
     */
    public function getPage() {
        return $this->getParameter('page');
    }

    /**
     * Set SearchParams from Parameter Bag (interface for query-based searching)
     * @see https://www.odata.org/documentation/odata-version-3-0/odata-version-3-0-core-protocol/
     * @param $value
     */
    public function setSearchParams($value) {
        return $this->setParameter('search_params', $value);
    }
    /**
     * Return Search Parameters for query-based searching
     * @return array
     */
    public function getSearchParams() {
        return $this->getParameter('search_params');
    }

    /**
     * Set boolean to determine partial or exact query based searches
     * @param $value
     */
    public function setExactSearchValue($value) {
        return $this->setParameter('exact_search_value', $value);
    }

    /**
     * Get boolean to determine partial or exact query based searches
     * @return mixed
     */
    public function getExactSearchValue() {
        return $this->getParameter('exact_search_value');
    }

    /**
     * Set SearchFilters from Parameter Bag (interface for query-based searching)
     * @see https://developer.xero.com/documentation/api/requests-and-responses#get-modified
     * @param $value
     */
    public function setSearchFilters($value) {
        return $this->setParameter('search_filters', $value);
    }

    /**
     * Return Search Filters for query-based searching
     * @return array
     */
    public function getSearchFilters() {
        return $this->getParameter('search_filters');
    }

    /**
     * Set boolean to determine whether all filters need to be matched
     * @param $value
     */
    public function setMatchAllFilters($value) {
        return $this->setParameter('match_all_filters', $value);
    }

    /**
     * Get boolean to determine whether all filters need to be matched
     * @return mixed
     */
    public function getMatchAllFilters() {
        return $this->getParameter('match_all_filters');
    }

    /**
     * Set the delta-sync cutoff. QB's Accounting API accepts SQL-style
     * `WHERE Metadata.LastUpdatedTime >= '<datetime>'` in Query() requests,
     * which is the per-entity equivalent of the /cdc batch endpoint.
     *
     * Accepts a DateTimeInterface, a string parsable by DateTime, or null.
     *
     * @param \DateTimeInterface|string|null $value
     */
    public function setLastModifiedSince($value) {
        return $this->setParameter('last_modified_since', $value);
    }

    /**
     * Return the cutoff formatted as QB expects in a Query() WHERE clause:
     * ISO-8601 with no timezone, e.g. `2026-05-15T12:00:00`. Returns null
     * when not set.
     */
    public function getLastModifiedSince(): ?string {
        $value = $this->getParameter('last_modified_since');
        if ($value === null || $value === '') {
            return null;
        }
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d\TH:i:s');
        }
        try {
            return (new \DateTime((string) $value))->format('Y-m-d\TH:i:s');
        } catch (\Throwable $e) {
            return null;
        }
    }
}