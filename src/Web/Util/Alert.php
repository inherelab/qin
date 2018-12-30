<?php
/**
 * Created by PhpStorm.
 * User: Inhere
 * Date: 2018/6/1 0001
 * Time: 21:12
 */

namespace Qin\Web\Util;

/**
 * Class Alert
 * @package Qin\Web\Util
 */
class Alert
{
    // alert style
    const LIGHT = 'light';
    const DARK = 'dark';
    const INFO = 'info';
    const SUCCESS = 'success';
    const PRIMARY = 'primary';
    const WARN = 'warning';
    const WARNING = 'warning';
    const ERROR = 'danger';
    const DANGER = 'danger';
    const SECONDARY = 'secondary';

    /**
     * @var string
     */
    public $type = 'info';

    /**
     * @var string
     */
    public $title = 'Notice!';

    /**
     * @var string
     */
    public $message = '';

    /**
     * @var array
     */
    public $closeBtn = true;

    /**
     * @param array $config
     * @return Alert
     */
    public static function create(array $config = []): self
    {
        return new self($config);
    }

    /**
     * Alert constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        foreach ($config as $name => $value) {
            if (\method_exists($this, $name)) {
                $this->$name($value);
            } elseif (\property_exists($this, $name)) {
                $this->$name = $value;
            }
        }
    }

    /**
     * @param $type
     * @return $this
     */
    public function type(string $type): self
    {
        $this->type = $type;
        $this->title = \ucfirst($type) . '!';

        return $this;
    }

    /**
     * @param $msg
     * @return $this
     */
    public function msg(string $msg): self
    {
        $this->message = $msg;

        return $this;
    }

    /**
     * @param $title
     * @return $this
     */
    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        // add a new alert message
        return [
            'type' => $this->type ?: 'info', // info success primary warning danger dark
            'title' => $this->title ?: 'Info!',
            'msg' => $this->message,
            'closeBtn' => (bool)$this->closeBtn
        ];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->all();
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->message === '';
    }

    /**
     * @return Alert
     */
    public function reset(): self
    {
        $this->type = 'info';
        $this->title = 'Notice!';
        $this->message = '';

        return $this;
    }
}
