<?php

namespace Qalis\Certification\Audits\Domain\CheckTimestampOverlaps;

use Qalis\Certification\Audits\Domain\AuditReadRepository;

class AuditTimestampOverlapsChecker {

    private AuditReadRepository $auditReadRepository;

    public function __construct(AuditReadRepository $auditReadRepository)
    {
        $this->auditReadRepository = $auditReadRepository;
    }

    public function __invoke(CheckTimestampOverlapsRequest $request, string $auditId): CheckTimestampOverlapsResponse
    {
        $savedTimestamps = $this->auditReadRepository->searchTimestampsForValidationByAudit($auditId);
        $newTimestamps = $this->auditReadRepository->completeTimestampsForValidation($request);
        $timestampsToValidate = $this->getUniqueTimestamps($savedTimestamps, $newTimestamps);
        return $this->getOverlaps($timestampsToValidate);
    }

//    private function getIdsFromRequest(CheckTimestampOverlapsRequest $request): array {
//        $response = [];
//        foreach($request->toArray() as $item) {
//            array_push($response, $item['id']);
//        }
//        return $response;
//    }

    private function getOverlaps($timestampsToValidate) {
        $checkTimestampOverlapsResponse = new CheckTimestampOverlapsResponse();
        $groupedTimestamps = $this->getGroupedOverlapsByAuditorAndDate($timestampsToValidate);
        foreach($groupedTimestamps as $auditorId => $auditors) {
            foreach($auditors as $dates) {
                $checkTimestampOverlapResponse = new CheckTimestampOverlapResponse();
                foreach($dates as $index => $searchTimestampsForValidationResponse) {
                    $this->checkOverlaps($checkTimestampOverlapResponse, $dates, $index, $searchTimestampsForValidationResponse);
                }
                if (!$checkTimestampOverlapResponse->isEmpty()) $checkTimestampOverlapsResponse->add($checkTimestampOverlapResponse);
            }
        }
        return $checkTimestampOverlapsResponse;
    }

    private function checkOverlaps(&$checkTimestampOverlapResponse, array $dates, $index, $searchTimestampsForValidationResponse) {
        //Si solo hay una fecha o ninguna, no existe solapamiento
        if (count($dates) > 1) {
            foreach($dates as $indexLoop => $dateLoop) {
                if ($index !== $indexLoop) {
                    if ($this->startTimeIsBetweenTime($dateLoop->startTime(), $dateLoop->endTime(), $searchTimestampsForValidationResponse->startTime()) ||
                    $this->endTimeIsBetweenTime($dateLoop->startTime(), $dateLoop->endTime(), $searchTimestampsForValidationResponse->endTime())) {
                        $checkTimestampOverlapResponse->addOverlap(
                            $searchTimestampsForValidationResponse->auditorFullName(),
                            $searchTimestampsForValidationResponse->date()->format("Y-m-d"),
                            $searchTimestampsForValidationResponse->startTime(),
                            $searchTimestampsForValidationResponse->endTime(),
                            $searchTimestampsForValidationResponse->checklistTemplateName(),
                            $searchTimestampsForValidationResponse->auditedItemCode()
                        );
                        $checkTimestampOverlapResponse->addOverlap(
                            $dateLoop->auditorFullName(),
                            $dateLoop->date()->format("Y-m-d"),
                            $dateLoop->startTime(),
                            $dateLoop->endTime(),
                            $dateLoop->checklistTemplateName(),
                            $dateLoop->auditedItemCode()
                        );
                    }
                }
            }
        }
    }

    /**
     * @param $startTime
     * @param $endTime
     * @param $startTimeToCheck
     * @return bool
     * Se permite que una fecha de inicio coincida con una de fin
     */
    private function startTimeIsBetweenTime($startTime, $endTime, $startTimeToCheck) {
        return ((strtotime($startTimeToCheck) >= strtotime($startTime)) && (strtotime($startTimeToCheck) < strtotime($endTime)));
    }

    /**
     * @param $startTime
     * @param $endTime
     * @param $endTimeToCheck
     * @return bool
     * Se permite que una fecha de fin, coincida con una de inicio
     */
    private function endTimeIsBetweenTime($startTime, $endTime, $endTimeToCheck) {
        return ((strtotime($endTimeToCheck) > strtotime($startTime)) && (strtotime($endTimeToCheck) <= strtotime($endTime)));
    }

    private function getGroupedOverlapsByAuditorAndDate($timestampsToValidate): array {
        $groupedTimestamps = [];
        foreach($timestampsToValidate as $timestampToValidate) {
            if (!isset($groupedTimestamps[$timestampToValidate->auditorId()])) $groupedTimestamps[$timestampToValidate->auditorId()] = [];
            if (!isset($groupedTimestamps[$timestampToValidate->auditorId()][$timestampToValidate->date()->format('Y-m-d')])) $groupedTimestamps[$timestampToValidate->auditorId()][$timestampToValidate->date()->format('Y-m-d')] = [];
            array_push($groupedTimestamps[$timestampToValidate->auditorId()][$timestampToValidate->date()->format('Y-m-d')], $timestampToValidate);
        }
        return $groupedTimestamps;
    }

    private function getUniqueTimestamps($savedTimestamps, $newTimestamps) {

        $response = [];
        foreach($savedTimestamps as $savedTimestamp) {
            if (!array_key_exists($savedTimestamp->id(), $response)) {
                $response[$savedTimestamp->id()] = $savedTimestamp;
            }
        }

        //Macha si ya existía ya que viene de la API
        foreach($newTimestamps as $newTimestamp) {
            $response[$newTimestamp->id()] = $newTimestamp;
        }

        return array_values($response);

    }

}
