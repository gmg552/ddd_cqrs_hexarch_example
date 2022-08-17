<?php

namespace Qalis\Shared\Domain\Email;

class SystemEmailValueObject {

    private EmailAddressValueObject $recipient;
    private string $recipientName;
    private string $subject;
    private string $body;
    private array $attachments;
    private array $ccs;
    private array $bccs;
    private bool $isHtml;
    private EmailCharset $charset;

    public function __construct(
        EmailAddressValueObject $recipient,
        string $recipientName,
        string $subject,
        string $body,
        array $attachments,
        array $ccs,
        array $bccs,
        bool $isHtml,
        EmailCharset $charset
    )
    {
        $this->recipient = $recipient;
        $this->recipientName = $recipientName;
        $this->subject = $subject;
        $this->body = $body;
        $this->attachments = $attachments;
        $this->ccs = $ccs;
        $this->bccs = $bccs;
        $this->isHtml = $isHtml;
        $this->charset = $charset;
    }

    /**
     * @return EmailAddressValueObject
     */
    public function recipient(): EmailAddressValueObject
    {
        return $this->recipient;
    }

    /**
     * @param SystemEmailValueObject $recipient
     */
    public function updateRecipient(SystemEmailValueObject $recipient): void
    {
        $this->recipient = $recipient;
    }

    /**
     * @return string
     */
    public function recipientName(): string
    {
        return $this->recipientName;
    }

    /**
     * @param string $recipientName
     */
    public function updateRecipientName(string $recipientName): void
    {
        $this->recipientName = $recipientName;
    }

    /**
     * @return string
     */
    public function subject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function updateSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function body(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function updateBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return array
     */
    public function attachments(): array
    {
        return $this->attachments;
    }

    /**
     * @param array $attachments
     */
    public function updateAttachments(array $attachments): void
    {
        $this->attachments = $attachments;
    }

    /**
     * @return array
     */
    public function ccs(): array
    {
        return $this->ccs;
    }

    /**
     * @param array $ccs
     */
    public function updateCcs(array $ccs): void
    {
        $this->ccs = $ccs;
    }

    /**
     * @return array
     */
    public function bccs(): array
    {
        return $this->bccs;
    }

    /**
     * @param array $bccs
     */
    public function updateBccs(array $bccs): void
    {
        $this->bccs = $bccs;
    }

    /**
     * @return bool
     */
    public function isHtml(): bool
    {
        return $this->isHtml;
    }

    /**
     * @param bool $isHtml
     */
    public function updateIsHtml(bool $isHtml): void
    {
        $this->isHtml = $isHtml;
    }

    /**
     * @return EmailCharset
     */
    public function charset(): EmailCharset
    {
        return $this->charset;
    }

    /**
     * @param EmailCharset $charset
     */
    public function updateCharset(EmailCharset $charset): void
    {
        $this->charset = $charset;
    }

}
