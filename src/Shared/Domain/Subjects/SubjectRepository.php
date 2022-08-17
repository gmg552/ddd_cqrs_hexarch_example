<?php

namespace Qalis\Shared\Domain\Subjects;

use Qalis\Shared\Domain\Criteria\Criteria;

interface SubjectRepository {
    public function save(Subject $subject): void;
    public function searchByCriteria(Criteria $criteria) : array;
    public function delete(SubjectId $subjectId): void;
}
