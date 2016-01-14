<?php

namespace IVT\Assert\_Internal\PHPType;

abstract class Type {
    /**
     * @param mixed $value
     * @return self
     * @throws \Exception
     */
    static function fromValue($value) {
        if (is_object($value)) {
            return new ObjectType(get_class($value));
        } else if (is_array($value)) {
            return ArrayType::fromArray($value);
        } else {
            return SimpleType::fromValue($value);
        }
    }

    /**
     * @param Type $that
     * @return bool
     */
    abstract function contains(Type $that);

    /**
     * @return string
     */
    abstract function toString();
}

class SimpleType extends Type {
    const STRING   = 'string';
    const INT      = 'int';
    const OBJECT   = 'object';
    const RESOURCE = 'resource';
    const BOOL     = 'bool';
    const FLOAT    = 'float';
    const NULL     = 'null';
    const ARRAY_   = 'array';

    /**
     * @param mixed $value
     * @return SimpleType
     * @throws \Exception
     */
    static function fromValue($value) {
        if (is_string($value)) $type = self::STRING;
        else if (is_int($value)) $type = self::INT;
        else if (is_object($value)) $type = self::OBJECT;
        else if (is_resource($value)) $type = self::RESOURCE;
        else if (is_bool($value)) $type = self::BOOL;
        else if (is_float($value)) $type = self::FLOAT;
        else if (is_null($value)) $type = self::NULL;
        else if (is_array($value)) $type = self::ARRAY_;
        else throw new \Exception('Unknown type: ' . gettype($value));
        return new self($type);
    }

    /** @var string */
    private $type;

    /**
     * @param string $type
     */
    function __construct($type) {
        $this->type = $type;
    }

    function contains(Type $that) {
        if ($that instanceof self) {
            return $that->type === $this->type;
        } else if ($that instanceof ArrayType && $this->type === self::ARRAY_) {
            return true;
        } else if ($that instanceof ObjectType && $this->type === self::OBJECT) {
            return true;
        } else {
            return false;
        }
    }

    function toString() {
        return $this->type;
    }
}

class ArrayType extends Type {
    static function fromArray(array $value) {
        $self = new self;
        foreach ($value as $v) {
            $self->add(Type::fromValue($v));
        }
        return $self;
    }

    /** @var Type[] */
    private $types = array();

    function add(Type $type) {
        if ($this->has($type))
            return;
        $this->remove($type);
        $this->types[] = $type;
    }

    function has(Type $type) {
        foreach ($this->types as $type_) {
            if ($type_->contains($type)) {
                return true;
            }
        }
        return false;
    }

    function remove(Type $type) {
        foreach ($this->types as $k => $type_) {
            if ($type->contains($type_)) {
                unset($this->types[$k]);
            }
        }
    }

    function contains(Type $that) {
        if ($that instanceof self) {
            foreach ($that->types as $type) {
                if (!$this->has($type)) {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    function toString() {
        $types = array();
        foreach ($this->types as $type) {
            $types[] = $type->toString();
        }
        sort($types, SORT_STRING);
        switch (count($types)) {
            case 0:
                return 'void[]';
            case 1:
                return $types[0] . '[]';
            default:
                return '(' . join('|', $types) . ')[]';
        }
    }
}

class ObjectType extends Type {
    /** @var string */
    private $class;

    function __construct($class) {
        $this->class = $class;
    }

    function contains(Type $that) {
        if ($that instanceof self && $that->class === $this->class) {
            return true;
        } else {
            return false;
        }
    }

    function toString() {
        return $this->class;
    }
}

