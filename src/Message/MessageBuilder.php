<?php

namespace Driplet\Message;

use Driplet\Exception\DripletException;

/**
 * Builder class for Driplet messages.
 */
class MessageBuilder
{
    protected array $message;
    protected array $target = [
        'include' => [],
        'exclude' => [],
    ];
    protected ?array $currentTargets = null;
    protected ?string $topic = null;

    /**
     * Creates a new message builder instance.
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * Sets the message.
     */
    public function setMessage(array $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Gets the builder for include targets.
     */
    public function include(): self
    {
        return $this->forInclude();
    }

    /**
     * Gets the builder for exclude targets.
     */
    public function exclude(): self
    {
        return $this->forExclude();
    }

    /**
     * Sets a target value.
     */
    public function setTarget(string $key, mixed $value): self
    {
        $value = is_array($value) ? $value : [$value];
        if (isset($this->currentTargets[$key])) {
            $this->currentTargets[$key] = array_unique(array_merge($this->currentTargets[$key], $value));
        } else {
            $this->currentTargets[$key] = $value;
        }
        return $this;
    }

    /**
     * Sets the topic for the message.
     */
    public function setTopic(string $topic): self
    {
        $this->topic = $topic;
        return $this;
    }

    /**
     * Helper to set the current target array reference.
     */
    protected function forInclude(): self
    {
        $this->currentTargets = &$this->target['include'];
        return $this;
    }

    /**
     * Helper to set the current target array reference.
     */
    protected function forExclude(): self
    {
        $this->currentTargets = &$this->target['exclude'];
        return $this;
    }

    /**
     * Generates a random nonce.
     */
    protected function generateNonce(): string
    {
        return bin2hex(random_bytes(16));
    }

    /**
     * Builds and returns the message array.
     *
     * @throws DripletException
     */
    public function build(): array
    {
        if (!isset($this->message)) {
            throw new DripletException('Message content is required');
        }

        if ($this->topic === null) {
            throw new DripletException('Topic is required for broadcasting messages');
        }

        $target = [];
        if (!empty($this->target['include'])) {
            $target['include'] = $this->target['include'];
        }
        if (!empty($this->target['exclude'])) {
            $target['exclude'] = $this->target['exclude'];
        }

        return [
            'nonce' => $this->generateNonce(),
            'timestamp' => time(),
            'message' => $this->message,
            'target' => $target,
            'topic' => $this->topic,
        ];
    }
}
