<?php
namespace easyphp\core\easyorm\interfaces;

interface ItypeData
{
 /* Tipos de columnas numéricas */
public const EASYPHP_INTEGER = 'int';
public const EASYPHP_TINY_INTEGER = 'tinyint';
public const EASYPHP_SMALL_INTEGER = 'smallint';
public const EASYPHP_BIG_INTEGER = 'biginteger';
public const EASYPHP_MEDIUM_INTEGER = 'mediumint';
public const EASYPHP_FLOAT = 'float';
public const EASYPHP_DECIMAL = 'decimal';
public const EASYPHP_NUMERIC = 'numeric';
public const EASYPHP_DOUBLE = 'double';


/* Tipos de columnas de caracteres */
public const EASYPHP_STRING = 'varchar';
public const EASYPHP_CHAR = 'char';
public const EASYPHP_TEXT = 'text';
public const EASYPHP_TENYETEXT = 'tinytext';
public const EASYPHP_MEDIUMTEXT = 'mediumtext';
public const EASYPHP_LONGTEXT = 'longtext';
public const EASYPHP_BLOB = 'blob';
public const EASYPHP_TINYBLOB = 'tinyblob';
public const EASYPHP_MEDIUMBLOB = 'mediumblob';
public const EASYPHP_LONGBLOB = 'longblob';
public const EASYPHP_ENUM = 'enum';
public const EASYPHP_SET = 'set';



/* Otros tipos de columnas */
public const EASYPHP_DATE = 'date';
public const EASYPHP_DATETIME = 'datetime';
public const EASYPHP_TIMESTAMP = 'timestamp';
public const EASYPHP_TIME = 'time';
public const EASYPHP_YEAR = 'year';


    
}