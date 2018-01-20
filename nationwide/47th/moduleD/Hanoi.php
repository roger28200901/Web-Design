<?php

class Hanoi
{
    private $steps = 0;
    private $difficulty = 0;
    private $bricks = [];
    private $error_message = '';
    private $moves = [];
    
    public function __construct($steps, $difficulty, $bricks, $moves = [])
    {
        $this->steps = $steps;
        $this->difficulty = $difficulty;
        $this->bricks = $bricks;
        $this->moves = $moves;
    }

    public function init()
    {
        $this->steps = 0;
        for ($i = 0; $i < $this->difficulty; $i++) {
            $this->bricks[$i] = 0;
        }
        $this->error_message = '';
        $this->moves = [];
    }

    public function tryMove($from_stack_id, $to_stack_id, $brick_id, $interval)
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
        $this->move($from_stack_id, $to_stack_id, $brick_id);
        $this->steps++;
        $this->putMove($from_stack_id, $to_stack_id, $brick_id, $interval);
        return true;
    }

    public function undo()
    {
        $move = array_pop($this->moves);
        $from_stack_id = $move->from_stack_id - 1;
        $to_stack_id = $move->to_stack_id - 1;
        $brick_id = $move->brick_id - 1;
        $this->move($to_stack_id, $from_stack_id, $brick_id);
        $this->steps--;
    }

    public function auto()
    {
        $this->hanoi(0, 1, 2, $this->difficulty - 1);
    }

    public function repeat()
    {
        $this->steps = 0;
        for ($i = 0; $i < $this->difficulty; $i++) {
            $this->bricks[$i] = 0;
        }
        $this->error_message = '';
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

    public function getsteps() { return $this->steps; }
    public function getBricks() { return $this->bricks; }
    public function getErrorMessage() { return $this->error_message; }
    public function getMoves() { return $this->moves; }

    private function move($from_stack_id, $to_stack_id, $brick_id)
    {
        $this->bricks[$brick_id] = $to_stack_id;
    }

    private function hanoi($from_stack_id, $temp_stack_id, $to_stack_id, $level)
    {
        if (1 > $level) {
            $this->putMove($from_stack_id, $to_stack_id, $level);
        } else {
            $this->hanoi($from_stack_id, $to_stack_id, $temp_stack_id, $level - 1);
            $this->putMove($from_stack_id, $to_stack_id, $level);
            $this->hanoi($temp_stack_id, $from_stack_id, $to_stack_id, $level - 1);
        }
    }

    private function putMove($from_stack_id, $to_stack_id, $brick_id, $interval = 0.5)
    {
        $this->moves[] = [
            'from_stack_id' => $from_stack_id + 1,
            'to_stack_id' => $to_stack_id + 1,
            'brick_id' => $brick_id + 1,
            'interval' => $interval,
        ];
    }
}
