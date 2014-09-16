<?php

class blog extends controller
{
    public function index()
    {
        $this->archive();
    }

    public function archive()
    {
        echo 'list';
    }

    public function show()
    {
        echo 'show';
    }

}
?>

