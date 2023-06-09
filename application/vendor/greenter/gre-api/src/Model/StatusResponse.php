<?php
/**
 * StatusResponse
 *
 * PHP version 7.4
 *
 * @category Class
 * @package  Greenter\Sunat\GRE
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * SUNAT GRE API
 *
 * PLATAFORMA NUEVA GRE.
 *
 * The version of the OpenAPI document: 1.0.0
 * Contact: me@giansalex.dev
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 6.3.0-SNAPSHOT
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Greenter\Sunat\GRE\Model;

use \ArrayAccess;
use \Greenter\Sunat\GRE\ObjectSerializer;

/**
 * StatusResponse Class Doc Comment
 *
 * @category Class
 * @package  Greenter\Sunat\GRE
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class StatusResponse implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'StatusResponse';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'cod_respuesta' => 'string',
        'error' => '\Greenter\Sunat\GRE\Model\StatusResponseError',
        'arc_cdr' => 'string',
        'ind_cdr_generado' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'cod_respuesta' => null,
        'error' => null,
        'arc_cdr' => null,
        'ind_cdr_generado' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'cod_respuesta' => false,
		'error' => false,
		'arc_cdr' => false,
		'ind_cdr_generado' => false
    ];

    /**
      * If a nullable field gets set to null, insert it here
      *
      * @var boolean[]
      */
    protected array $openAPINullablesSetToNull = [];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of nullable properties
     *
     * @return array
     */
    protected static function openAPINullables(): array
    {
        return self::$openAPINullables;
    }

    /**
     * Array of nullable field names deliberately set to null
     *
     * @return boolean[]
     */
    private function getOpenAPINullablesSetToNull(): array
    {
        return $this->openAPINullablesSetToNull;
    }

    /**
     * Setter - Array of nullable field names deliberately set to null
     *
     * @param boolean[] $openAPINullablesSetToNull
     */
    private function setOpenAPINullablesSetToNull(array $openAPINullablesSetToNull): void
    {
        $this->openAPINullablesSetToNull = $openAPINullablesSetToNull;
    }

    /**
     * Checks if a property is nullable
     *
     * @param string $property
     * @return bool
     */
    public static function isNullable(string $property): bool
    {
        return self::openAPINullables()[$property] ?? false;
    }

    /**
     * Checks if a nullable property is set to null.
     *
     * @param string $property
     * @return bool
     */
    public function isNullableSetToNull(string $property): bool
    {
        return in_array($property, $this->getOpenAPINullablesSetToNull(), true);
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'cod_respuesta' => 'codRespuesta',
        'error' => 'error',
        'arc_cdr' => 'arcCdr',
        'ind_cdr_generado' => 'indCdrGenerado'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'cod_respuesta' => 'setCodRespuesta',
        'error' => 'setError',
        'arc_cdr' => 'setArcCdr',
        'ind_cdr_generado' => 'setIndCdrGenerado'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'cod_respuesta' => 'getCodRespuesta',
        'error' => 'getError',
        'arc_cdr' => 'getArcCdr',
        'ind_cdr_generado' => 'getIndCdrGenerado'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }


    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->setIfExists('cod_respuesta', $data ?? [], null);
        $this->setIfExists('error', $data ?? [], null);
        $this->setIfExists('arc_cdr', $data ?? [], null);
        $this->setIfExists('ind_cdr_generado', $data ?? [], null);
    }

    /**
    * Sets $this->container[$variableName] to the given data or to the given default Value; if $variableName
    * is nullable and its value is set to null in the $fields array, then mark it as "set to null" in the
    * $this->openAPINullablesSetToNull array
    *
    * @param string $variableName
    * @param array  $fields
    * @param mixed  $defaultValue
    */
    private function setIfExists(string $variableName, array $fields, $defaultValue): void
    {
        if (self::isNullable($variableName) && array_key_exists($variableName, $fields) && is_null($fields[$variableName])) {
            $this->openAPINullablesSetToNull[] = $variableName;
        }

        $this->container[$variableName] = $fields[$variableName] ?? $defaultValue;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets cod_respuesta
     *
     * @return string|null
     */
    public function getCodRespuesta()
    {
        return $this->container['cod_respuesta'];
    }

    /**
     * Sets cod_respuesta
     *
     * @param string|null $cod_respuesta Codigo de respuesta (98: en proceso, 99: envío con error, 0: envío OK).
     *
     * @return self
     */
    public function setCodRespuesta($cod_respuesta)
    {
        if (is_null($cod_respuesta)) {
            throw new \InvalidArgumentException('non-nullable cod_respuesta cannot be null');
        }
        $this->container['cod_respuesta'] = $cod_respuesta;

        return $this;
    }

    /**
     * Gets error
     *
     * @return \Greenter\Sunat\GRE\Model\StatusResponseError|null
     */
    public function getError()
    {
        return $this->container['error'];
    }

    /**
     * Sets error
     *
     * @param \Greenter\Sunat\GRE\Model\StatusResponseError|null $error error
     *
     * @return self
     */
    public function setError($error)
    {
        if (is_null($error)) {
            throw new \InvalidArgumentException('non-nullable error cannot be null');
        }
        $this->container['error'] = $error;

        return $this;
    }

    /**
     * Gets arc_cdr
     *
     * @return string|null
     */
    public function getArcCdr()
    {
        return $this->container['arc_cdr'];
    }

    /**
     * Sets arc_cdr
     *
     * @param string|null $arc_cdr CDR generado (base64)
     *
     * @return self
     */
    public function setArcCdr($arc_cdr)
    {
        if (is_null($arc_cdr)) {
            throw new \InvalidArgumentException('non-nullable arc_cdr cannot be null');
        }
        $this->container['arc_cdr'] = $arc_cdr;

        return $this;
    }

    /**
     * Gets ind_cdr_generado
     *
     * @return string|null
     */
    public function getIndCdrGenerado()
    {
        return $this->container['ind_cdr_generado'];
    }

    /**
     * Sets ind_cdr_generado
     *
     * @param string|null $ind_cdr_generado Indicador de generación de CDR (1: Si genera CDR, 0: No genera CDR).
     *
     * @return self
     */
    public function setIndCdrGenerado($ind_cdr_generado)
    {
        if (is_null($ind_cdr_generado)) {
            throw new \InvalidArgumentException('non-nullable ind_cdr_generado cannot be null');
        }
        $this->container['ind_cdr_generado'] = $ind_cdr_generado;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed|null
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     *
     * @param int|null $offset Offset
     * @param mixed    $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     * @link https://www.php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed Returns data which can be serialized by json_encode(), which is a value
     * of any type other than a resource.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
       return ObjectSerializer::sanitizeForSerialization($this);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            ObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Gets a header-safe presentation of the object
     *
     * @return string
     */
    public function toHeaderValue()
    {
        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}


