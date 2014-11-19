<?php

/**
 * TechDivision\Storage\GenericStackableTest
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

namespace TechDivision\Storage;

use TechDivision\Storage\Mock\MockContext;

/**
 * Test for generic stackable implementation.
 *
 * @category  Appserver
 * @package   TechDivision_Storage
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io
 */
class GenericStackableTest extends \PHPUnit_Framework_TestCase
{

    /**
     * A key to bind a value to the stackable.
     *
     * @var string
     */
    const KEY = 'key';

    /**
     * A value bound to the stackable for testing purposes.
     *
     * @var string
     */
    const VALUE = 'value';

    /**
     * The instance we want to test.
     *
     * @var \TechDivision\Storage\GenericStackable
     */
    protected $storage;

    /**
     * Initializes the instance we want to test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->storage = new GenericStackable();
    }

    /**
     * Test if the default session name is returned correctly.
     *
     * @return void
     */
    public function testSetAndGetValue()
    {

        // create a mock context
        $context = new MockContext();
        $context->injectStorage($this->storage);

        // set/get the a value
        $context->setValue(GenericStackableTest::KEY, GenericStackableTest::VALUE);
        $this->assertSame(GenericStackableTest::VALUE, $context->getValue(GenericStackableTest::KEY));
    }
}
