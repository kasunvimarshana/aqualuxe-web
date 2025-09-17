<?php
/**
 * Clean Architecture - Abstract Base Classes
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Core\Abstracts;

use AquaLuxe\Domain\Interfaces\Entity_Interface;
use AquaLuxe\Domain\Interfaces\Value_Object_Interface;
use AquaLuxe\Domain\Interfaces\Repository_Interface;
use AquaLuxe\Domain\Interfaces\Service_Interface;
use AquaLuxe\Domain\Interfaces\Specification_Interface;

defined('ABSPATH') || exit;

/**
 * Abstract Entity
 */
abstract class Abstract_Entity implements Entity_Interface {
    /**
     * Entity ID
     *
     * @var mixed
     */
    protected $id;

    /**
     * Entity data
     *
     * @var array
     */
    protected $data = array();

    /**
     * Get entity ID
     *
     * @return mixed
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Set entity ID
     *
     * @param mixed $id Entity ID
     */
    public function set_id($id) {
        $this->id = $id;
    }

    /**
     * Get property
     *
     * @param string $property Property name
     * @return mixed
     */
    public function get($property) {
        return isset($this->data[$property]) ? $this->data[$property] : null;
    }

    /**
     * Set property
     *
     * @param string $property Property name
     * @param mixed  $value Property value
     */
    public function set($property, $value) {
        $this->data[$property] = $value;
    }

    /**
     * Convert to array
     *
     * @return array
     */
    public function to_array() {
        return array_merge(array('id' => $this->id), $this->data);
    }

    /**
     * Validate entity
     *
     * @return bool
     */
    public function is_valid() {
        return !empty($this->id);
    }
}

/**
 * Abstract Value Object
 */
abstract class Abstract_Value_Object implements Value_Object_Interface {
    /**
     * Value object data
     *
     * @var array
     */
    protected $data = array();

    /**
     * Constructor
     *
     * @param array $data Value object data
     */
    public function __construct($data = array()) {
        $this->data = $data;
        $this->validate();
    }

    /**
     * Check equality with another value object
     *
     * @param Value_Object_Interface $other Other value object
     * @return bool
     */
    public function equals(Value_Object_Interface $other) {
        return get_class($this) === get_class($other) && 
               $this->data === $other->get_data();
    }

    /**
     * Get data
     *
     * @return array
     */
    public function get_data() {
        return $this->data;
    }

    /**
     * Convert to string
     *
     * @return string
     */
    public function __toString() {
        return json_encode($this->data);
    }

    /**
     * Validate value object
     *
     * @throws \InvalidArgumentException If validation fails
     */
    abstract protected function validate();
}

/**
 * Abstract Repository
 */
abstract class Abstract_Repository implements Repository_Interface {
    /**
     * Entity class name
     *
     * @var string
     */
    protected $entity_class;

    /**
     * Constructor
     *
     * @param string $entity_class Entity class name
     */
    public function __construct($entity_class) {
        $this->entity_class = $entity_class;
    }

    /**
     * Create entity instance
     *
     * @param array $data Entity data
     * @return Entity_Interface
     */
    protected function create_entity($data) {
        $entity = new $this->entity_class();
        
        if (isset($data['id'])) {
            $entity->set_id($data['id']);
            unset($data['id']);
        }

        foreach ($data as $key => $value) {
            $entity->set($key, $value);
        }

        return $entity;
    }

    /**
     * Convert entity to data array
     *
     * @param Entity_Interface $entity Entity instance
     * @return array
     */
    protected function entity_to_data(Entity_Interface $entity) {
        return $entity->to_array();
    }
}

/**
 * Abstract Service
 */
abstract class Abstract_Service implements Service_Interface {
    /**
     * Service dependencies
     *
     * @var array
     */
    protected $dependencies = array();

    /**
     * Constructor
     *
     * @param array $dependencies Service dependencies
     */
    public function __construct($dependencies = array()) {
        $this->dependencies = $dependencies;
    }

    /**
     * Get dependency
     *
     * @param string $name Dependency name
     * @return mixed
     * @throws \Exception If dependency not found
     */
    protected function get_dependency($name) {
        if (!isset($this->dependencies[$name])) {
            throw new \Exception("Dependency '{$name}' not found.");
        }

        return $this->dependencies[$name];
    }

    /**
     * Validate input data
     *
     * @param array $data Input data
     * @return bool
     * @throws \InvalidArgumentException If validation fails
     */
    abstract protected function validate_input($data);
}

/**
 * Abstract Specification
 */
abstract class Abstract_Specification implements Specification_Interface {
    /**
     * Combine with AND logic
     *
     * @param Specification_Interface $other Other specification
     * @return Specification_Interface
     */
    public function and_specification(Specification_Interface $other) {
        return new And_Specification($this, $other);
    }

    /**
     * Combine with OR logic
     *
     * @param Specification_Interface $other Other specification
     * @return Specification_Interface
     */
    public function or_specification(Specification_Interface $other) {
        return new Or_Specification($this, $other);
    }

    /**
     * Negate specification
     *
     * @return Specification_Interface
     */
    public function not_specification() {
        return new Not_Specification($this);
    }
}

/**
 * AND Specification
 */
class And_Specification extends Abstract_Specification {
    /**
     * Left specification
     *
     * @var Specification_Interface
     */
    private $left;

    /**
     * Right specification
     *
     * @var Specification_Interface
     */
    private $right;

    /**
     * Constructor
     *
     * @param Specification_Interface $left Left specification
     * @param Specification_Interface $right Right specification
     */
    public function __construct(Specification_Interface $left, Specification_Interface $right) {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * Check if candidate satisfies specification
     *
     * @param mixed $candidate Candidate to check
     * @return bool
     */
    public function is_satisfied_by($candidate) {
        return $this->left->is_satisfied_by($candidate) && $this->right->is_satisfied_by($candidate);
    }
}

/**
 * OR Specification
 */
class Or_Specification extends Abstract_Specification {
    /**
     * Left specification
     *
     * @var Specification_Interface
     */
    private $left;

    /**
     * Right specification
     *
     * @var Specification_Interface
     */
    private $right;

    /**
     * Constructor
     *
     * @param Specification_Interface $left Left specification
     * @param Specification_Interface $right Right specification
     */
    public function __construct(Specification_Interface $left, Specification_Interface $right) {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * Check if candidate satisfies specification
     *
     * @param mixed $candidate Candidate to check
     * @return bool
     */
    public function is_satisfied_by($candidate) {
        return $this->left->is_satisfied_by($candidate) || $this->right->is_satisfied_by($candidate);
    }
}

/**
 * NOT Specification
 */
class Not_Specification extends Abstract_Specification {
    /**
     * Wrapped specification
     *
     * @var Specification_Interface
     */
    private $specification;

    /**
     * Constructor
     *
     * @param Specification_Interface $specification Specification to negate
     */
    public function __construct(Specification_Interface $specification) {
        $this->specification = $specification;
    }

    /**
     * Check if candidate satisfies specification
     *
     * @param mixed $candidate Candidate to check
     * @return bool
     */
    public function is_satisfied_by($candidate) {
        return !$this->specification->is_satisfied_by($candidate);
    }
}