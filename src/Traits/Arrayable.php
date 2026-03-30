<?php

namespace Fireblocks\Sdk\Traits;

trait Arrayable
{
    /**
     * Convert the object to an array.
     */
    public function toArray(): array
    {
        $data = [];
        foreach (get_object_vars($this) as $key => $value) {
            if ($value !== null) {
                $snakeKey = $this->camelToSnake($key);
                if (is_object($value) && method_exists($value, 'toArray')) {
                    $data[$snakeKey] = $value->toArray();
                } elseif (is_array($value)) {
                    $data[$snakeKey] = array_map(function ($item) {
                        return is_object($item) && method_exists($item, 'toArray') ? $item->toArray() : $item;
                    }, $value);
                } else {
                    $data[$snakeKey] = $value;
                }
            }
        }
        return $data;
    }

    /**
     * Convert camelCase to snake_case.
     */
    private function camelToSnake(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    /**
     * Fill model from array.
     */
    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            $camelKey = $this->snakeToCamel($key);
            if (property_exists($this, $camelKey)) {
                $this->{$camelKey} = $value;
            }
        }
    }

    /**
     * Convert snake_case to camelCase.
     */
    private function snakeToCamel(string $input): string
    {
        return lcfirst(str_replace('_', '', ucwords($input, '_')));
    }
}
