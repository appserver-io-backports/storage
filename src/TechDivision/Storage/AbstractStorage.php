<?php
/**
 * TechDivision\Storage\AbstractStorage
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

/**
 * A abstract storage implementation.
 *
 * @category  Appserver
 * @package   TechDivision_Storage
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io
 */
abstract class AbstractStorage implements StorageInterface
{

    /**
     * Register the trait that provides basic storage functionality.
     *
     * @var \Trait
     */
    use StorageTrait;

    /**
     * A storage backend, a \Stackable for example.
     *
     * @var mixed
     */
    protected $storage;

    /**
     * Passes the configuration and initializes the storage.
     *
     * The identifier will be set after the init() function has been invoked, so it'll overwrite the one
     * specified in the configuration if set.
     *
     * @param string $identifier Unique identifier for the cache storage
     *
     * @return void
     */
    public function __construct($identifier = null)
    {
        $this->init();
        $this->flush();
        if ($identifier != null) {
            $this->identifier = $identifier;
        }
    }

    /**
     * Restores the storage after the instance has been recovered
     * from sleep.
     *
     * @return void
     */
    public function __wakeup()
    {
        $this->init();
    }

    /**
     * Initializes the storage when the instance is constructed and the
     * __wakeup() method is invoked.
     *
     * @return void
     */
    abstract public function init();

    /**
     * (non-PHPdoc)
     *
     * @return void
     * @see \TechDivision\Storage\StorageInterface::collectGarbage()
     */
    public function collectGarbage()
    {
        // nothing to do here, because gc is handled by memcache
    }

    /**
     * (non-PHPdoc)
     *
     * @param string $tag The tag to search for
     *
     * @return array An array with the identifier (key) and content (value) of all matching entries. An empty array if no entries matched
     * @see \TechDivision\Storage\StorageInterface::getByTag()
     */
    public function getByTag($tag)
    {
        return $this->get($this->getIdentifier() . $tag);
    }

    /**
     * (non-PHPdoc)
     *
     * @param string $entryIdentifier An identifier specifying the cache entry
     *
     * @return boolean TRUE if such an entry exists, FALSE if not
     * @see \TechDivision\Storage\StorageInterface::has()
     */
    public function has($entryIdentifier)
    {
        if ($this->get($this->getIdentifier() . $entryIdentifier) !== false) {
            return true;
        }
        return false;
    }

    /**
     * Injects the storage instance to use.
     *
     * @param mixed $storage The storge instance to use
     *
     * @return void
     */
    public function injectStorage($storage)
    {
        $this->storage = $storage;
    }

    /**
     * (non-PHPdoc)
     *
     * @return mixed The storage object itself
     * @see \TechDivision\Storage\StorageInterface::getStorage()
     */
    public function getStorage()
    {
        return $this->storage;
    }
}
