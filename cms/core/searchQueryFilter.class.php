<?php

/**
 *  This class describes the general functionality of all typical filters used in Search.
 *  Each filter basically makes the only one procedure:
 *  1. Search for arguments in title first
 *  2. Search for arguments in content (and/or introduction or any other text fields) last
 *
 *  As a result all found elements with query word in title go first, others go after them.
 *
 *  The only difference between filters are database column names and database table name,
 *  so the inheriting filter should only implement 3 getters to function,
 *  the rest is implemented by this abstract class
 */
abstract class searchQueryFilter extends queryFilter
{
    /**
     * All search filters implement this method in a same way, so we can use type name for this purpose
     * @return string
     */
    public function getRequiredType()
    {
        return $this->getTypeName();
    }

    /**
     * All ajax search filters use the same algorithm for searching the elements,
     * so they should have method a common implementation of this method
     *
     * @param mixed $argument
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function getFilteredIdList($argument, $query)
    {
        if (!is_array($argument)) {
            $argument = (array)$argument;
        }
        $titleFields = $this->getTitleFieldNames();
        $contentFields = $this->getContentFieldNames();

        $query->where(function ($finalQuery) use ($argument, $query, $titleFields, $contentFields) {
            /**
             * @var \Illuminate\Database\Query\Builder $finalQuery
             */
            // find matches from title
            if ($titleFields) {
                foreach ($titleFields as $field) {
                    foreach ($argument as $argumentWord) {
                        $finalQuery->orWhere($field, 'like', '%' . $argumentWord . '%');
                        $query->orderByRaw($query->raw('INSTR(`' . $field . '`, "' . $argumentWord . '")'));
                    }
                }
                if ($contentFields) {
                    foreach ($contentFields as $field) {
                        foreach ($argument as $argumentWord) {
                            $finalQuery->orWhere($field, 'like', '%' . $argumentWord . '%');
                            $query->orderByRaw($query->raw('INSTR(`' . $field . '`, "' . $argumentWord . '")'));
                        }
                    }
                }
            } elseif ($contentFields) {
                foreach ($contentFields as $field) {
                    foreach ($argument as $argumentWord) {
                        $finalQuery->orWhere($field, 'like', '%' . $argumentWord . '%');
                    }
                }
            }
        });
        return $query;
    }

    /**
     * A small helper to divide each possible item from argument array into separated words for usage in SQL "LIKE" query
     *
     * @param $argument
     * @return array
     * @deprecated - old logic only, don't use it here
     */
    protected function generateQueryStrings($argument)
    {
        if (!is_array($argument)) {
            $argument = [$argument];
        }
        $queryStrings = [];
        foreach ($argument as &$query) {
            $words = explode(" ", $query);
            foreach ($words as &$word) {
                if (mb_strlen(trim($word)) > 2) {
                    $queryStrings[] = '%%' . $word . '%%';
                }
            }
        }
        return $queryStrings;
    }

    protected function getResourceName()
    {
        return $this->getTypeName();
    }

    /**
     * Type name is used as a query filter's required type and database table name
     * For example, type name of productSearchQueryFilter is "product"
     *
     * @abstract
     * @return string
     */
    abstract protected function getTypeName();

    /**
     * Returns an array of database table column names used as "title" columns
     *
     * @abstract
     * @return string[]
     */
    abstract protected function getTitleFieldNames();

    /**
     * Returns an array of database table column names used as "content" columns
     *
     * @abstract
     * @return string[]
     */
    abstract protected function getContentFieldNames();
}