<?php

namespace Qalis\Shared\Domain\Email\SendFromSystem;

use Qalis\Shared\Domain\Email\SystemEmailValueObject;

interface EmailSender {

    public const SSL = 'ssl';

    public function sendSystemEmail(SystemEmailValueObject $email) : void;

}
