<?php

namespace Qalis\Shared\Infrastructure\APM;

use Qalis\Shared\Domain\ConfigProvider;

trait APMTrait {

    private ConfigProvider $configProvider;

    public function __construct(ConfigProvider $configProvider)
    {
        $this->configProvider = $configProvider;
        $this->configProvider->enableErrorMonitor(false);
    }

    public function __destruct()
    {
        $this->configProvider->enableErrorMonitor(true);
    }

}
