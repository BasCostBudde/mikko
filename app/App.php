<?php

namespace App;

class App
{

    // NOTE dit wordt in php8 een stuk korter :)
    private $filesystem;
    private $parameter;
    private $formatter;
    private $schedule;

    private $messages = [];

    public function __construct(
        FileSystem $filesystem,
        Parameter $parameter,
        Formatter $formatter,
        PaySchedule $schedule
    ) {
        $this->filesystem = $filesystem;
        $this->parameter = $parameter;
        $this->formatter = $formatter;
        $this->schedule = $schedule;
    }

    public function process($date)
    {
        $filename = 'out.csv';
        if ($this->parameter->get('1')) {
            $filename = $this->parameter->get('1');
        }
        if ($this->filesystem->exists($filename)) {
            $this->messages[] = "$filename already exists.\n";
            return(1);
        }
        if (!$this->filesystem->creatable($filename)) {
            $this->messages[] = "$filename cannot be created.\n";
            return(1);
        }
        $out = $this->schedule->get($date);        
        if ($this->filesystem->write($filename, $this->formatter->format($out))) {
            return 0;
        }
        return 1;
    }

    public function messages()
    {
        return $this->messages;
    }

}
