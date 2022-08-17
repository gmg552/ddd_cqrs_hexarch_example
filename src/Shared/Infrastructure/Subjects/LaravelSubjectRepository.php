<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Subjects;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Qalis\Shared\Domain\Countries\CountryId;
use Qalis\Shared\Domain\Criteria\Criteria;
use Qalis\Shared\Domain\Subjects\Subject;
use Qalis\Shared\Domain\Subjects\SubjectAddress;
use Qalis\Shared\Domain\Subjects\SubjectCityName;
use Qalis\Shared\Domain\Subjects\SubjectGender;
use Qalis\Shared\Domain\Subjects\SubjectId;
use Qalis\Shared\Domain\Subjects\SubjectIdentifier;
use Qalis\Shared\Domain\Subjects\SubjectName;
use Qalis\Shared\Domain\Subjects\SubjectPostalCode;
use Qalis\Shared\Domain\Subjects\SubjectProvinceName;
use Qalis\Shared\Domain\Subjects\SubjectSurname;
use Qalis\Shared\Domain\Email\EmailAddressValueObject;
use Qalis\Shared\Domain\ValueObjects\PhoneNumber;
use Qalis\Shared\Domain\ValueObjects\URL;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Subject as SubjectEloquent;
use Qalis\Shared\Domain\Subjects\SubjectRepository;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\CriteriaToQueryBuilderMapper;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\QueryBuilderUtils;
use Qalis\Shared\Infrastructure\Persistence\Uuid2Id;
use stdClass;
use function Lambdish\Phunctional\map;

class LaravelSubjectRepository implements SubjectRepository
{

    public function delete(SubjectId $subjectId): void
    {
        SubjectEloquent::whereUuid($subjectId->value())->forceDelete();
    }

    public function searchByCriteria(Criteria $criteria) : array {
        $queryBuilderWithCriteria = $this->searchQueryBuilder($criteria);
        return map($this->queryBuilderRecordToOperatorCode(), $queryBuilderWithCriteria->get());
    }

    private function searchQueryBuilder(Criteria $criteria) : Builder {

        $query = QueryBuilderUtils::notDeleted(DB::table('subjects')
            ->leftJoin('countries', 'countries.id', '=', 'subjects.country_id'))
            ->selectRaw('lower(hex(subjects.uuid)) as subjectId, lower(hex(countries.uuid)) as countryId, subjects.*');

        (new CriteriaToQueryBuilderMapper($criteria, $query))->apply();

        return $query;
    }

    private function queryBuilderRecordToOperatorCode() : callable {
        return static fn(stdClass $subject) => new Subject(
            new SubjectId($subject->subjectId),
            new SubjectName($subject->name),
            $subject->surname1 ? new SubjectSurname($subject->surname1) : null,
            $subject->surname2 ? new SubjectSurname($subject->surname2) : null,
            $subject->identifier ? new SubjectIdentifier($subject->identifier) : null,
            $subject->gender ? new SubjectGender($subject->gender) : null,
            $subject->address ? new SubjectAddress($subject->address) : null,
            $subject->postal_code ? new SubjectPostalCode($subject->postal_code) : null,
            $subject->email ? new EmailAddressValueObject($subject->email) : null,
            $subject->phone_number ? new PhoneNumber($subject->phone_number) : null,
            $subject->phone_number2 ? new PhoneNumber($subject->phone_number2) : null,
            $subject->fax ? new PhoneNumber($subject->fax) : null,
            $subject->web ? new URL($subject->web) : null,
            $subject->city_name ? new SubjectCityName($subject->city_name) : null,
            $subject->province_name ? new SubjectProvinceName($subject->province_name) : null,
            $subject->countryId ? new CountryId($subject->countryId) : null
        );
    }

    public function save(Subject $subject): void
    {
        SubjectEloquent::updateOrCreate(
            [
                'uuid' => $subject->id()->binValue()
            ],
            [
                'name' => $subject->name()->value(),
                'surname1' => $subject->surname1() ? $subject->surname1()->value() : null,
                'surname2' => $subject->surname2() ? $subject->surname2()->value() : null,
                'identifier' => $subject->identifier() ? $subject->identifier()->value() : null,
                'gender' => $subject->gender() ? $subject->gender()->value() : null,
                'address' => $subject->address() ? $subject->address()->value() : null,
                'postal_code' => $subject->postalCode() ? $subject->postalCode()->value() : null,
                'email' => $subject->email() ? $subject->email()->value() : null,
                'phone_number' => $subject->phoneNumber1() ? $subject->phoneNumber1()->value() : null,
                'phone_number2' => $subject->phoneNumber2() ? $subject->phoneNumber2()->value() : null,
                'fax' => $subject->fax() ? $subject->fax()->value() : null,
                'web' => $subject->web() ? $subject->web()->value() : null,
                'city_name' => $subject->cityName() ? $subject->cityName()->value() : null,
                'province_name' => $subject->provinceName() ? $subject->provinceName()->value() : null,
                'country_id' => $subject->countryId() ? Uuid2Id::resolve('countries', $subject->countryId()->value()) : null,
                'province_id' => $subject->provinceId() ? Uuid2Id::resolve('provinces', $subject->provinceId()->value()) : null,
                'city_id' => $subject->cityId() ? Uuid2Id::resolve('cities', $subject->cityId()->value()) : null,
            ]
        );
    }
}
