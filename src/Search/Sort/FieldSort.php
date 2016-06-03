<?php namespace Nord\Lumen\Elasticsearch\Search\Sort;

class FieldSort extends AbstractSort
{
    const MISSING_FIRST = '_first';
    const MISSING_LAST  = '_last';

    /**
     * @var string the field name to sort on.
     */
    private $field;

    /**
     * @var string The missing parameter specifies how docs which are missing the field should be treated. The missing
     * value can be set to _last, _first, or a custom value (that will be used for missing docs as the sort value).
     */
    private $missing;

    /**
     * @var string By default, the search request will fail if there is no mapping associated with a field. The
     * unmapped_type option allows to ignore fields that have no mapping and not sort by them. The value of this
     * parameter is used to determine what sort values to emit.
     */
    private $unmappedType;


    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $options = $this->applyOptions([]);

        $missing = $this->getMissing();
        if (!is_null($missing)) {
            $options['missing'] = $missing;
        }

        $unmappedType = $this->getUnmappedType();
        if (!is_null($unmappedType)) {
            $options['unmapped_type'] = $unmappedType;
        }

        if (empty($options)) {
            return $this->getField();
        } else {
            return [$this->getField() => $options];
        }
    }


    /**
     * @param string $field
     * @return FieldSort
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }


    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }


    /**
     * @param string $missing
     * @return FieldSort
     */
    public function setMissing($missing)
    {
        $this->missing = $missing;
        return $this;
    }


    /**
     * @return string
     */
    public function getMissing()
    {
        return $this->missing;
    }


    /**
     * @param string $unmappedType
     * @return FieldSort
     */
    public function setUnmappedType($unmappedType)
    {
        $this->unmappedType = $unmappedType;
        return $this;
    }


    /**
     * @return string
     */
    public function getUnmappedType()
    {
        return $this->unmappedType;
    }
}
