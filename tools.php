<?php

// DATABASE TOOLS

function getDataType($column) {
    switch ($column) {
        case is_null($column):
            return PDO::PARAM_NULL;
        case is_int($column):
            return PDO::PARAM_INT;
        case is_bool($column):
            return PDO::PARAM_BOOL;
        default:
            return PDO::PARAM_STR;
    }
}

function createPlaceholders($params, $placeholder) {
    return implode(',', array_fill(0, count($params), $placeholder));
}