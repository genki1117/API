<?php
declare(strict_types=1);
namespace App\Foundations\Csv\Collection;

class HeaderCollection
{
    /** @var array */
    private array $properties;

    /**
     * HeaderCollection constructor.
     * @param array $properties
     */
    public function __construct(array $properties = [])
    {
        $this->properties = $properties;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $entities = [];
        foreach ($this->properties as $property) {
            $entities[] = $property;
        }
        return $entities;
    }
}
