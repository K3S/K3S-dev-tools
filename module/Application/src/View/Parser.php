<?php

namespace Application\View;

final class Parser
{
    public const DATA_TYPE_CHARACTER = 'CHAR';
    public const DATA_TYPE_PACKED = 'PACKED';
    public const DATA_TYPE_DATE = 'DATE';

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
        $length = $this->translateLength($definition);
        preg_match('/\\s[0-9]+/i', trim($definition), $matches);
        $precision = '';
        if (str_contains($dataType, self::DATA_TYPE_PACKED)) {
            $precision = trim($matches[0]);
        }

        $rpgDefinition = ucfirst($dataType);
        $rpgDefinition .= '(' . $length;
        if ($dataType === self::DATA_TYPE_PACKED) {
            $rpgDefinition .= ':' . $precision;
        }
        $rpgDefinition .= ')';

        $clDefinition = '';
        $clDefinition = $this->buildCLDefinition($parameterName, $dataType, $length, $precision);

        return [
            'parameterName' => $parameterName,
            'description' => $description,
            'dataType' => $dataType,
            'length' => $length,
            'precision' => $precision,
            'rpgDefinition' => $rpgDefinition,
            'clDefinition' => $clDefinition
        ];
    }

    private function buildCLDefinition(string $parameterName, string $dataType, string $length, string $precision): string
    {
        $format = 'VAR(&%s) TYPE(%s) LEN(%s)';
        // VAR(&COMP) TYPE(*DEC) LEN(1 )
        $clDataType = '';
        $clLength = '';
        if ($dataType === self::DATA_TYPE_CHARACTER) {
           $clDataType = '*CHAR';
           $clLength = $length;
        } elseif ($dataType === self::DATA_TYPE_PACKED) {
            $clDataType = '*DEC';
            $clLength = $length . ' ' . $precision;
        }

        return sprintf($format, strtoupper($parameterName), $clDataType, $clLength);
    }

    public function translateLength(string $definition): string
    {
        if (strpos($definition, 'A')) {
            return trim(str_replace('A', '', $definition));
        }

        if (strpos($definition, 'L')) {
            return '10';
        }

        preg_match('/[0-9]+\\s/i', $definition, $matches);

        return trim($matches[0]);
    }

    public function translateDataType(string $dataType): string
    {
        $dataType = trim(strtoupper($dataType));
        $stringLength = strlen($dataType);
        if (substr($dataType, $stringLength - 1, 1) === 'A') {
            $parameterLength = substr($dataType, 0, ($stringLength - 1));
            return self::DATA_TYPE_CHARACTER;
        } elseif (substr($dataType, $stringLength - 1, 1) === 'L') {
            return self::DATA_TYPE_DATE;
        } else {
            $digits = substr($dataType, 0, 1);
            $precision = trim(substr($dataType, 1, 5));
            $precision = trim($precision);
            return self::DATA_TYPE_PACKED;
            return 'Packed(' . $digits . ':' . $precision . ');';
        }


    }
}