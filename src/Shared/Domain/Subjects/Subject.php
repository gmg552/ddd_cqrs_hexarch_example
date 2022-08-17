<?php

namespace Qalis\Shared\Domain\Subjects;

use Qalis\Shared\Domain\Cities\CityId;
use Qalis\Shared\Domain\Countries\CountryId;
use Qalis\Shared\Domain\Email\EmailAddressValueObject;
use Qalis\Shared\Domain\Provinces\ProvinceId;
use Qalis\Shared\Domain\ValueObjects\PhoneNumber;
use Qalis\Shared\Domain\ValueObjects\URL;

class Subject
{
    public const MALE = 'male';
    public const FEMALE = 'female';
    public const OTHER_GENDER = 'other';

    private SubjectId $id;
    private SubjectName $name;
    private ?SubjectSurname $surname1;
    private ?SubjectSurname $surname2;
    private ?SubjectIdentifier $identifier;
    private ?SubjectGender $gender;
    private ?SubjectAddress $address;
    private ?SubjectPostalCode $postalCode;
    private ?EmailAddressValueObject $email;
    private ?PhoneNumber $phoneNumber1;
    private ?PhoneNumber $phoneNumber2;
    private ?PhoneNumber $fax;
    private ?URL $web;
    private ?SubjectCityName $cityName;
    private ?SubjectProvinceName $provinceName;
    private ?CountryId $countryId;
    private ?ProvinceId $provinceId;
    private ?CityId $cityId;

    public function __construct(
        SubjectId $id,
        SubjectName $name,
        ?SubjectSurname $surname1 = null,
        ?SubjectSurname $surname2 = null,
        ?SubjectIdentifier $identifier = null,
        ?SubjectGender $gender = null,
        ?SubjectAddress $address = null,
        ?SubjectPostalCode $postalCode = null,
        ?EmailAddressValueObject $email = null,
        ?PhoneNumber $phoneNumber1 = null,
        ?PhoneNumber $phoneNumber2 = null,
        ?PhoneNumber $fax = null,
        ?URL $web = null,
        ?SubjectCityName $cityName = null,
        ?SubjectProvinceName $provinceName = null,
        ?CountryId $countryId = null,
        ?ProvinceId $provinceId = null,
        ?CityId $cityId = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname1 = $surname1;
        $this->surname2 = $surname2;
        $this->identifier = $identifier;
        $this->gender = $gender;
        $this->address = $address;
        $this->postalCode = $postalCode;
        $this->email = $email;
        $this->phoneNumber1 = $phoneNumber1;
        $this->phoneNumber2 = $phoneNumber2;
        $this->fax = $fax;
        $this->web = $web;
        $this->cityName = $cityName;
        $this->provinceName = $provinceName;
        $this->countryId = $countryId;
        $this->provinceId = $provinceId;
        $this->cityId = $cityId;
    }

    static public function fromPrimitives(
        string $id,
        string $name,
        ?string $surname1 = null,
        ?string $surname2 = null,
        ?string $identifier = null,
        ?string $gender = null,
        ?string $address = null,
        ?string $postalCode = null,
        ?string $email = null,
        ?string $phoneNumber1 = null,
        ?string $phoneNumber2 = null,
        ?string $fax = null,
        ?string $web = null,
        ?string $cityName = null,
        ?string $provinceName = null,
        ?string $countryId = null,
        ?string $provinceId = null,
        ?string $cityId = null
    ): Subject {
        return new Subject(
            new SubjectId($id),
            new SubjectName($name),
            $surname1 ? new SubjectSurname($surname1) : null,
            $surname2 ? new SubjectSurname($surname2) : null,
            $identifier ? new SubjectIdentifier($identifier) : null,
            $gender ? new SubjectGender($gender) : null,
            $address ? new SubjectAddress($address) : null,
            $postalCode ? new SubjectPostalCode($postalCode) : null,
            $email ? new EmailAddressValueObject($email) : null,
            $phoneNumber1 ? new PhoneNumber($phoneNumber1) : null,
            $phoneNumber2 ? new PhoneNumber($phoneNumber2) : null,
            $fax ? new PhoneNumber($fax) : null,
            $web ? new URL($web) : null,
            $cityName ? new SubjectCityName($cityName) : null,
            $provinceName ? new SubjectProvinceName($provinceName) : null,
            $countryId ? new CountryId($countryId) : null,
            $provinceId ? new ProvinceId($provinceId) : null,
            $cityId ? new CityId($cityId) : null
        );
    }

    /**
     * @return SubjectId
     */
    public function id(): SubjectId
    {
        return $this->id;
    }

    /**
     * @return SubjectName
     */
    public function name(): SubjectName
    {
        return $this->name;
    }

    /**
     * @param SubjectName $name
     */
    public function updateName(SubjectName $name): void
    {
        $this->name = $name;
    }

    /**
     * @return SubjectSurname|null
     */
    public function surname1(): ?SubjectSurname
    {
        return $this->surname1;
    }

    /**
     * @param SubjectSurname|null $surname1
     */
    public function updateSurname1(?SubjectSurname $surname1): void
    {
        $this->surname1 = $surname1;
    }

    /**
     * @return SubjectSurname|null
     */
    public function surname2(): ?SubjectSurname
    {
        return $this->surname2;
    }

    /**
     * @param SubjectSurname|null $surname2
     */
    public function updateSurname2(?SubjectSurname $surname2): void
    {
        $this->surname2 = $surname2;
    }

    /**
     * @return SubjectIdentifier|null
     */
    public function identifier(): ?SubjectIdentifier
    {
        return $this->identifier;
    }

    /**
     * @param SubjectIdentifier|null $identifier
     */
    public function updateIdentifier(?SubjectIdentifier $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * @return SubjectGender|null
     */
    public function gender(): ?SubjectGender
    {
        return $this->gender;
    }

    /**
     * @param SubjectGender|null $gender
     */
    public function updateGender(?SubjectGender $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * @return SubjectAddress|null
     */
    public function address(): ?SubjectAddress
    {
        return $this->address;
    }

    /**
     * @param SubjectAddress|null $address
     */
    public function updateAddress(?SubjectAddress $address): void
    {
        $this->address = $address;
    }

    /**
     * @return SubjectPostalCode|null
     */
    public function postalCode(): ?SubjectPostalCode
    {
        return $this->postalCode;
    }

    /**
     * @param SubjectPostalCode|null $postalCode
     */
    public function updatePostalCode(?SubjectPostalCode $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return EmailAddressValueObject|null
     */
    public function email(): ?EmailAddressValueObject
    {
        return $this->email;
    }

    /**
     * @param EmailAddressValueObject|null $email
     */
    public function updateEmail(?EmailAddressValueObject $email): void
    {
        $this->email = $email;
    }

    /**
     * @return PhoneNumber|null
     */
    public function phoneNumber1(): ?PhoneNumber
    {
        return $this->phoneNumber1;
    }

    /**
     * @param PhoneNumber|null $phoneNumber1
     */
    public function updatePhoneNumber1(?PhoneNumber $phoneNumber1): void
    {
        $this->phoneNumber1 = $phoneNumber1;
    }

    /**
     * @return PhoneNumber|null
     */
    public function phoneNumber2(): ?PhoneNumber
    {
        return $this->phoneNumber2;
    }

    /**
     * @param PhoneNumber|null $phoneNumber2
     */
    public function updatePhoneNumber2(?PhoneNumber $phoneNumber2): void
    {
        $this->phoneNumber2 = $phoneNumber2;
    }

    /**
     * @return PhoneNumber|null
     */
    public function fax(): ?PhoneNumber
    {
        return $this->fax;
    }

    /**
     * @param PhoneNumber|null $fax
     */
    public function updateFax(?PhoneNumber $fax): void
    {
        $this->fax = $fax;
    }

    /**
     * @return URL|null
     */
    public function web(): ?URL
    {
        return $this->web;
    }

    /**
     * @param URL|null $web
     */
    public function updateWeb(?URL $web): void
    {
        $this->web = $web;
    }

    /**
     * @return SubjectCityName|null
     */
    public function cityName(): ?SubjectCityName
    {
        return $this->cityName;
    }

    /**
     * @param SubjectCityName|null $cityName
     */
    public function updateCityName(?SubjectCityName $cityName): void
    {
        $this->cityName = $cityName;
    }

    /**
     * @return SubjectProvinceName|null
     */
    public function provinceName(): ?SubjectProvinceName
    {
        return $this->provinceName;
    }

    /**
     * @param SubjectProvinceName|null $provinceName
     */
    public function updateProvinceName(?SubjectProvinceName $provinceName): void
    {
        $this->provinceName = $provinceName;
    }

    /**
     * @return CountryId|null
     */
    public function countryId(): ?CountryId
    {
        return $this->countryId;
    }

    /**
     * @param CountryId|null $countryId
     */
    public function updateCountryId(?CountryId $countryId): void
    {
        $this->countryId = $countryId;
    }

    /**
     * @return ProvinceId|null
     */
    public function provinceId(): ?ProvinceId
    {
        return $this->provinceId;
    }

    /**
     * @param ProvinceId|null $provinceId
     */
    public function updateProvinceId(?ProvinceId $provinceId): void
    {
        $this->provinceId = $provinceId;
    }

    /**
     * @return CityId|null
     */
    public function cityId(): ?CityId
    {
        return $this->cityId;
    }

    /**
     * @param CityId|null $cityId
     */
    public function updateCityId(?CityId $cityId): void
    {
        $this->cityId = $cityId;
    }


}
