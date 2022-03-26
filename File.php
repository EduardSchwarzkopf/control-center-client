<?php

class File
{
    private bool $exist = false;
    private string $relative_location = '';
    private string $absolute_location = '';
    private string $human_size = '';
    private string $date = '';
    private int $filetime = 0;
    private int $bytes = 0;
    private int $hours = 0;

    function __construct(string $location)
    {

        $this->location = $location;

        $this->exist = file_exists($location);

        if ($this->exist) {
            $this->bytes = filesize($location);
            $this->human_size = FileUtils::HumanFileSize($this->bytes);
            $this->date = FileUtils::GetModificationDate($location);
            $this->hours = FileUtils::GetAgeHours($this->date);
            $this->filetime = filemtime($location);
        }
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
        return get_object_vars($this);
    }
}
