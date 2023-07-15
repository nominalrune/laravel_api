<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    protected array $columns;
    abstract protected function getRules(): array;
    abstract protected function storeRules(): array;
    abstract protected function updateRules(): array;

    public function authorize()
    {
        return true;
    }
    public function rules(): array
    {
        switch($this->method()) {
            case 'GET':
                return $this->getRules();
            case 'POST':
                return $this->storeRules();
            case 'PUT':
            case 'PATCH':
                return $this->updateRules();
            default:
                return [];
        }
    }

    protected function nullable(array $keys)
    {
        return $this->append($keys, 'nullable');
    }
    protected function required(array $keys)
    {
        return $this->append($keys, 'required');
    }
    protected function exclude(array $keys)
    {
        return $this->append($keys, 'exclude');
    }
    protected function prohibited(array $keys)
    {
        return $this->append($keys, 'prohibited');
    }
    private function append(array $keys, string $rule)
    {
        if (count($keys) === 0) return [];

        return array_reduce($keys, function ($acc, $curr) use ($rule) {
            if(!isset($this->columns[$curr])) throw new \Exception("Column $curr does not exist. (".__FILE__.", line: ".__LINE__.")");
            return [...$acc, $curr => [...$this->columns[$curr], $rule]];
        }, []);
    }
}
