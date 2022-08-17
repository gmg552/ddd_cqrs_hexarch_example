<?php

namespace Qalis\Shared\Domain\PrivacyLevels;

use Qalis\Shared\Domain\Services\ServiceId;
use Qalis\Shared\Domain\Subjects\SubjectId;
use Qalis\Shared\Domain\ValueObjects\DateTimePeriod;

final class PrivacyLevel {

    private PrivacyLevelId $id;
    private SubjectId $subjectId;
    private ServiceId $serviceId;
    private DateTimePeriod $period;

    public function __construct(
        PrivacyLevelId $id,
        SubjectId $subjectId,
        ServiceId $serviceId,
        DateTimePeriod $period
    )
    {
        $this->id = $id;
        $this->subjectId = $subjectId;
        $this->serviceId = $serviceId;
        $this->period = $period;
    }

    /**
     * @param DateTimePeriod $period
     */
    public function updatePeriod(DateTimePeriod $period): void
    {
        $this->period = $period;
    }

    /**
     * @return PrivacyLevelId
     */
    public function id(): PrivacyLevelId
    {
        return $this->id;
    }

    /**
     * @return SubjectId
     */
    public function subjectId(): SubjectId
    {
        return $this->subjectId;
    }

    /**
     * @return ServiceId
     */
    public function serviceId(): ServiceId
    {
        return $this->serviceId;
    }

    /**
     * @return DateTimePeriod
     */
    public function period(): DateTimePeriod
    {
        return $this->period;
    }

}
