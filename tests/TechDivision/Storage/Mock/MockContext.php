<?php

/**
 * TechDivision\Storage\Mock\MockContext
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @category  Appserver
 * @package   TechDivision_Storage
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io
 */

namespace TechDivision\Storage\Mock;

use TechDivision\Storage\GenericStackable;

/**
 * Test for the default session settings implementation.
 *
 * @category  Appserver
 * @package   TechDivision_Storage
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io
 */
class MockContext extends GenericStackable
{

    /**
     * The storage we use to store data.
     *
     * @var \TechDivision\Storage\GenericStackable
     */
    protected $storage;

    /**
     * Injects the storage we use to store data.
     *
     * @param \TechDivision\Storage\GenericStackable $storage The storage we want to use
     *
     * @return void
     */
    public function injectStorage(GenericStackable $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Initialize the context with the passed key/value pair.
     *
     * @param string $key   The key to bind the value to the context
     * @param string $value The value to be bound
     *
     * @return void
     */
    public function setValue($key, $value)
    {
        $this->storage[$key] = $value;
    }

    /**
     * Returns the value bound to the context used the passed key.
     *
     * @param string $key The key of the bounded value
     *
     * @return string The value bound to the context
     */
    public function getValue($key)
    {
        return $this->storage[$key];
    }
}
