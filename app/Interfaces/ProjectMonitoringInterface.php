<?php

namespace App\Interfaces;

interface ProjectMonitoringInterface
{
    public function getAll();
    public function getById($id);
    public function store($data);
    public function edit($id, $data);
    public function destroy($id);
}
