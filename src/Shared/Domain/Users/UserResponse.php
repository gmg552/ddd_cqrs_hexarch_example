<?php

namespace Qalis\Shared\Domain\Users;

final class UserResponse {

    private string $id;
    private string $name;
    private string $email;
    private ?string $surname1;
    private ?string $surname2;

    public function __construct(
        string $id,
        string $name,
        string $email,
        ?string $surname1 = null,
        ?string $surname2 = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->surname1 = $surname1;
        $this->surname2 = $surname2;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'surname1' => $this->surname1,
            'surname2' => $this->surname2
        ];
    }

    public function fullName(): string {
        return $this->name . ' '. $this->surname1 . ' ' . $this->surname2;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function surname1(): ?string
    {
        return $this->surname1;
    }

    /**
     * @return string|null
     */
    public function surname2(): ?string
    {
        return $this->surname2;
    }




}
