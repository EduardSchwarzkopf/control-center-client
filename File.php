<?php

class File
{
    protected bool $exist = false;
    protected string $name = '';
    protected string $location = '';
    protected string $human_size = '';
    protected string $date = '';
    protected int $filetime = 0;
    protected int $bytes = 0;
    protected int $hours = 0;

    function __construct(string $location = '')
    {

        $this->exist = file_exists($location);
        if ($this->exist == false) {
            return $this;
        }

        $this->location = $location;
        $this->name = basename($location);
        $this->bytes = filesize($location);
        $this->human_size = FileUtils::HumanFileSize($this->bytes);
        $this->date = FileUtils::GetModificationDate($location);
        $this->hours = FileUtils::GetAgeHours($this->date);
        $this->filetime = filemtime($location);
    }

    public function Name(): string
    {
        return $this->name;
    }

    public function Location(): string
    {
        return $this->location;
    }

    public function Exist(): bool
    {
        return $this->exist;
    }

    public function Date(): string
    {
        return $this->date;
    }

    public function Filetime(): int
    {
        return $this->filetime;
    }

    public function Bytes(): int
    {
        return $this->bytes;
    }

    public function Hours(): int
    {
        return $this->hours;
    }

    public function HumanSize(): string
    {
        return $this->human_size;
    }

    public function ToArray()
    {
        if ($this->exist == false) {
            return [];
        }

        $vars = get_object_vars($this);
        unset($vars['exist']);

        return $vars;
    }
}
