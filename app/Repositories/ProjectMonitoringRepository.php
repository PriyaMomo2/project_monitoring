<?php

namespace App\Repositories;

use App\Interfaces\ProjectMonitoringInterface;
use App\Models\ProjectMonitoring;

class ProjectMonitoringRepository implements ProjectMonitoringInterface
{
    public function getAll()
    {
        return ProjectMonitoring::get();
    }

    public function getById($id)
    {
        return ProjectMonitoring::find($id);
    }

    public function store($data)
    {
        return ProjectMonitoring::create($data);
    }

    public function edit($id, $data)
    {
        return ProjectMonitoring::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return ProjectMonitoring::destroy($id);
    }

}
