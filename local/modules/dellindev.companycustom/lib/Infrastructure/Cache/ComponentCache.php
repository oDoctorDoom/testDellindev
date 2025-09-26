<?php
declare(strict_types=1);

namespace DellinDev\CompanyCustom\Infrastructure\Cache;

use Bitrix\Main\Data\Cache;

final class ComponentCache
{
    /**
     * @param callable():array $producer
     * @return array
     */
    public function remember(string $id, string $dir, callable $producer, int $ttl = 60): array
    {
        $cache = Cache::createInstance();
        if ($cache->initCache($ttl, $id, $dir)) {
            $vars = $cache->getVars();
            return is_array($vars) ? $vars : [];
        }
        $cache->startDataCache();
        $data = $producer();
        $cache->endDataCache($data);
        return is_array($data) ? $data : [];
    }
}
