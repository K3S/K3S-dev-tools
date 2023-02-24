<?php

namespace Application\View;

final class Parser
{
    public function parseParameters(array $parameters): array
    {
        if ($parameters[0] === '') {
            return [];
        }
        foreach ($parameters as $parameter) {
            $result[] = $this->parseParameter($parameter);
        }

        return $result;
    }
    public function parseParameter(string $definition): array
    {
        // Remove surrounding whitespace and "BOTH" specification
        $definition = trim(substr(trim($definition), 4));

        // Parse out parameter name
        $parameterName = strtolower(trim(substr($definition, 0, 10)));

        // Remove parameter name from definition
        $definition = trim(substr($definition, strlen($parameterName)));

        // Parse out description
        $descriptionStart = strpos($definition, "'");
        $descriptionSize = strpos($definition, "'", 1) + 1;
        $description = substr($definition, $descriptionStart, $descriptionSize);
        $definition = substr($definition, $descriptionSize);
        $description = str_replace("'", "", $description);

        // Parse out and translate data type
        $dataType = $this->translateDataType(trim($definition));
        $length = '1';
        $precision = '0';

        return [
            'parameterName' => $parameterName,
            'description' => $description,
            'dataType' => $dataType,
            'length' => $length,
            'precision' => $precision,
        ];
    }

    public function translateDataType(string $dataType): string
    {
        $dataType = trim(strtoupper($dataType));
        $stringLength = strlen($dataType);
        if (substr($dataType, $stringLength - 1, 1) === 'A') {
            $parameterLength = substr($dataType, 0, ($stringLength - 1));
            return 'Char(' . $parameterLength . ');';
        } else {
            $digits = substr($dataType, 0, 1);
            $precision = trim(substr($dataType, 1, 5));
            $precision = trim($precision);
            return 'Packed(' . $digits . ':' . $precision . ');';
        }


    }
}