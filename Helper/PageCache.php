<?php

namespace Hevelop\CacheInvalidate\Helper;

use Magento\Framework\Cache\InvalidateLogger;

/**
 * Class PageCache
 * @package Hevelop\CacheInvalidate\Helper
 */
class PageCache
{
    /**
     * @var \Magento\PageCache\Model\Cache\Server
     */
    private $cacheServer;

    /**
     * @var \Magento\CacheInvalidate\Model\SocketFactory
     */
    private $socketAdapterFactory;

    /**
     * @var InvalidateLogger
     */
    private $logger;

    /**
     * Constructor
     *
     * @param \Magento\PageCache\Model\Cache\Server $cacheServer
     * @param \Magento\CacheInvalidate\Model\SocketFactory $socketAdapterFactory
     * @param InvalidateLogger $logger
     */
    public function __construct(
        \Magento\PageCache\Model\Cache\Server $cacheServer,
        \Magento\CacheInvalidate\Model\SocketFactory $socketAdapterFactory,
        InvalidateLogger $logger
    ) {
        $this->cacheServer = $cacheServer;
        $this->socketAdapterFactory = $socketAdapterFactory;
        $this->logger = $logger;
    }

    /**
     * Send curl purge request
     * to invalidate cache by path
     *
     * @param $path
     * @return bool Return true if successful; otherwise return false
     */
    public function sendPurgeRequestByUrl($path)
    {
        $socketAdapter = $this->socketAdapterFactory->create();
        $servers = $this->cacheServer->getUris();
        $socketAdapter->setOptions(['timeout' => 10]);
        foreach ($servers as $server) {
            $server->setPath($path);
            $headers['Host'] = $server->getHost();
            try {
                $socketAdapter->connect($server->getHost(), $server->getPort());
                $socketAdapter->write(
                    'PURGE',
                    $server,
                    '1.1'
                );
                $socketAdapter->read();
                $socketAdapter->close();
            } catch (\Exception $e) {
                $this->logger->critical($e->getMessage(), compact('server'));
                return false;
            }
        }

        $this->logger->execute(compact('servers'));
        return true;
    }
}