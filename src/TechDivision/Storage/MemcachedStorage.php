<?php

/**
 * TechDivision\Storage\MemcachedStorage
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
 * @package   TechDivision_ApplicationServer
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io
 */

namespace TechDivision\Storage;

/**
 * Class MemcachedStorage
 *
 * @category  Appserver
 * @package   TechDivision_Storage
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.appserver.io
 */
class MemcachedStorage extends AbstractStorage
{

    /**
     * (non-PHPdoc)
     *
     * @return void
     * @see TechDivision\Storage\AbstractStorage::init();
     */
    public function init()
    {
        // inject the \Memcached storage
        $this->injectStorage(new \Memcached(__CLASS__));
        // initialize the storage
        $serverList = $this->storage->getServerList();
        if (empty($serverList)) {
            $this->storage->setOption(\Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
            foreach ($this->getServers() as $server) {
                list ($host, $port, $weight) = $server;
                $this->storage->addServer($host, $port, $weight);
            }
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @param string  $entryIdentifier Something which identifies the data - depends on concrete cache
     * @param mixed   $data            The data to cache - also depends on the concrete cache implementation
     * @param array   $tags            Tags to associate with this cache entry
     * @param integer $lifetime        Lifetime of this cache entry in seconds. If NULL is specified,
     *                                 the default lifetime is used. "0" means unlimited lifetime.
     *
     * @return void
     * @see \TechDivision\Storage\StorageInterface::set()
     */
    public function set($entryIdentifier, $data, array $tags = array(), $lifetime = null)
    {
        // create a unique cache key and add the passed value to the storage
        $cacheKey = $this->getIdentifier() . $entryIdentifier;
        $this->storage->set($cacheKey, $data, $lifetime);

        // if tags has been set, tag the data additionally
        foreach ($tags as $tag) {
            $tagData = $this->get($this->getIdentifier() . $tag);
            if (is_array($tagData) && in_array($cacheKey, $tagData, true) === true) {
                // do nothing here
            } elseif (is_array($tagData) && in_array($cacheKey, $tagData, true) === false) {
                $tagData[] = $cacheKey;
            } else {
                $tagData = array(
                    $cacheKey
                );
            }
            $this->storage->set($tag, $tagData);
        }
    }

    /**
     * (non-PHPdoc)
     *
     * @param string $entryIdentifier Something which identifies the cache entry - depends on concrete cache
     *
     * @return mixed
     * @see \TechDivision\Storage\StorageInterface::get()
     */
    public function get($entryIdentifier)
    {
        return $this->storage->get($entryIdentifier);
    }

    /**
     * (non-PHPdoc)
     *
     * @param string $entryIdentifier An identifier specifying the cache entry
     *
     * @return boolean TRUE if such an entry exists, FALSE if not
     * @see \TechDivision\Storage\StorageInterface::remove()
     */
    public function remove($entryIdentifier)
    {
        $this->storage->delete($this->getIdentifier() . $entryIdentifier);
    }

    /**
     * (non-PHPdoc)
     *
     * @return array
     * @see \TechDivision\Storage\StorageInterface::getAllKeys()
     */
    public function getAllKeys()
    {
        $this->storage->getAllKeys();
    }
}
