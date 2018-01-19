<?php

class Hanoi
{
    private $steps = 0;
    private $difficulty = 0;
    private $bricks = [];
    private $error_message = '';
    
    public function __construct($steps, $difficulty, $bricks)
    {
        $this->steps = $steps;
        $this->difficulty = $difficulty;
        $this->bricks = $bricks;
    }

    public function tryMove($from_stack_id, $to_stack_id, $brick_id)
    {
        for ($i = 0; $i < $brick_id; $i++) {
            if ($this->bricks[$i] == $to_stack_id) {
                $this->error_message = '只能放小盤子在大盤子上';
                return false;
            }
        }
        for ($i = 0; $i < $brick_id; $i++) {
            if ($this->bricks[$i] == $from_stack_id) {
                $this->error_message = '只能移動最上層盤子';
                return false;
            }
        }
        $this->move($to_stack_id, $brick_id);
        return true;
    }

    public function undo($from_stack_id, $to_stack_id, $brick_id)
    {
        $this->bricks[$brick_id] = $from_stack_id;
        $this->steps--;
    }

    public function complete()
    {
        foreach ($this->bricks as $brick) {
            if ($brick != 2) {
                return false;
            }
        }
        return true;
    }

    public function getsteps()
    {
        return $this->steps;
    }

    public function getBricks()
    {
        return $this->bricks;
    }

    public function getErrorMessage()
    {
        return $this->error_message;
    }

    private function move($to_stack_id, $brick_id)
    {
        $this->bricks[$brick_id] = $to_stack_id;
        $this->steps++;
    }
}
