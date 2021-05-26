<?php

namespace App\Utils;

use Opis\JsonSchema\JsonPointer;


class SchemaLoader
{
    public static function loadSchema($uri, $resolveLocalRefs = false)
    {
        if (!file_exists($uri)) {
            return null;
        }
        $schema = json_decode(file_get_contents($uri));

        if ($resolveLocalRefs) {
            // довольно наивная имплементация сборки нескольких схем в одну
            // в частности, можно из одной схемы сослаться на другую через $ref
            // код ниже рекурсивно проходит по схеме и, найдя, ссылку на внешнюю схему,
            // попытается её вчитать прямо вместо $ref
            function refSearch(&$schema, $dir) {
                foreach ($schema as $property => &$value) {
                    if (is_array($value) || is_object($value)) {
                        $replace = refSearch($value, $dir);
                        if ($replace) {
                            // замещаем весь объект {$ref: path} на содержисое path
                            $value = $replace;
                        }
                    } else if ($property === '$ref') {
                        list($base_ref, $fragment) = explode('#', $value, 2);
                        // Если $base_ref не пустой, это значит что мы запрашиваем другой файл
                        if ($base_ref !== '') {
                            $schemaPath = realpath($dir . DIRECTORY_SEPARATOR . $base_ref);
                            if (file_exists($schemaPath)) {
                                $result = json_decode(file_get_contents($schemaPath));
                                // если фрагмент пустой, значит возвращаем содержание файла, иначе возвращаем
                                // определенную часть содержимого из файла
                                if ($fragment !== '') {
                                    $result = JsonPointer::getDataByPointer($result, $fragment);
                                }
                                refSearch($result, $dir);
                                return $result;
                            }
                        }
                    }
                }
                return null;
            }

            refSearch($schema, dirname($uri));
        }

        return $schema;
    }
}
