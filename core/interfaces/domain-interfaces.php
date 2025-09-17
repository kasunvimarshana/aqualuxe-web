<?php
/**
 * Clean Architecture - Domain Layer Interfaces
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Domain\Interfaces;

defined('ABSPATH') || exit;

/**
 * Repository Interface
 */
interface Repository_Interface {
    /**
     * Find entity by ID
     *
     * @param int $id Entity ID
     * @return mixed
     */
    public function find_by_id($id);

    /**
     * Find all entities
     *
     * @param array $criteria Search criteria
     * @return array
     */
    public function find_all($criteria = array());

    /**
     * Save entity
     *
     * @param mixed $entity Entity to save
     * @return mixed
     */
    public function save($entity);

    /**
     * Delete entity
     *
     * @param mixed $entity Entity to delete
     * @return bool
     */
    public function delete($entity);
}

/**
 * Service Interface
 */
interface Service_Interface {
    /**
     * Execute service operation
     *
     * @param array $data Input data
     * @return mixed
     */
    public function execute($data);
}

/**
 * Event Interface
 */
interface Event_Interface {
    /**
     * Get event name
     *
     * @return string
     */
    public function get_name();

    /**
     * Get event data
     *
     * @return array
     */
    public function get_data();

    /**
     * Get event timestamp
     *
     * @return int
     */
    public function get_timestamp();
}

/**
 * Entity Interface
 */
interface Entity_Interface {
    /**
     * Get entity ID
     *
     * @return mixed
     */
    public function get_id();

    /**
     * Set entity ID
     *
     * @param mixed $id Entity ID
     */
    public function set_id($id);

    /**
     * Convert to array
     *
     * @return array
     */
    public function to_array();

    /**
     * Validate entity
     *
     * @return bool
     */
    public function is_valid();
}

/**
 * Value Object Interface
 */
interface Value_Object_Interface {
    /**
     * Check equality with another value object
     *
     * @param Value_Object_Interface $other Other value object
     * @return bool
     */
    public function equals(Value_Object_Interface $other);

    /**
     * Convert to string
     *
     * @return string
     */
    public function __toString();
}

/**
 * Specification Interface
 */
interface Specification_Interface {
    /**
     * Check if candidate satisfies specification
     *
     * @param mixed $candidate Candidate to check
     * @return bool
     */
    public function is_satisfied_by($candidate);

    /**
     * Combine with AND logic
     *
     * @param Specification_Interface $other Other specification
     * @return Specification_Interface
     */
    public function and_specification(Specification_Interface $other);

    /**
     * Combine with OR logic
     *
     * @param Specification_Interface $other Other specification
     * @return Specification_Interface
     */
    public function or_specification(Specification_Interface $other);

    /**
     * Negate specification
     *
     * @return Specification_Interface
     */
    public function not_specification();
}