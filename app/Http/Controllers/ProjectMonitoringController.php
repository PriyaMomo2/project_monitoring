<?php

namespace App\Http\Controllers;
use App\Repositories\ProjectMonitoringRepository;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProjectMonitoringController extends Controller
{
    private ProjectMonitoringRepository $repository;
    protected $data = array();

    public function __construct(
        ProjectMonitoringRepository $repository,
    ) {
        $this->repository = $repository;
        $this->data['title'] = ' Project Monitoring';
        $this->data['view_directory'] = "web.views";    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ref = $this->data;
        return view($this->data['view_directory'] . '.index', compact('ref'));
    }

        public function datas(Request $request)
        {
            if ($request->ajax()) {
                try {
                    $data = $this->repository->getAll();
                } catch (Exception $e) {
                    dd($e);
                }
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '<form method="POST" action="' . route('project-monitoring.destroy', encrypt($row["id"])) . '">
                                            ' . method_field("DELETE") . '
                                            ' . csrf_field() . '
                                            <button type="button" id="deleteRow" data-message="' . $row["family_card_number"] . '" class="btn bg-gradient-danger btn-tooltip show-alert-delete-box" data-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                                            <a href="' . route("project-monitoring.edit", encrypt($row["id"])) . '" class="btn bg-gradient-success btn-tooltip"><i class="bi bi-pencil-square"></i></a>
                                        </form>';
                        return $btn;
                    })
                    ->addColumn('project_leader', function ($row) {
                        $imageUrl = Storage::url($row["project_leader_image"]);
                        $image = '<img src="' . $imageUrl . '" alt="Leader Image" style="width: 35px; height: 35px; border-radius: 50%; margin-right: 10px;">';
                        $nameEmail = '<div>
                                        <strong>' . $row["project_leader_name"] . '</strong><br>
                                        <span style="font-size: 0.9em; color: #6c757d;">' . $row["project_leader_email"] . '</span>
                                      </div>';
                        $content = '<div style="display: flex; align-items: center;">' . $image . $nameEmail . '</div>';
                        return $content;
                    })                    
                    ->rawColumns(['action', 'progress', 'project_leader'])
                    ->editColumn('start_date', function ($data) {
                        $formattedDate = Carbon::parse($data['start_date'])->translatedFormat('d M Y');
                        return $formattedDate;
                    })
                    ->editColumn('end_date', function ($data) {
                        $formattedDate = Carbon::parse($data['end_date'])->translatedFormat('d M Y');
                        return $formattedDate;
                    })
                    ->editColumn('progress', function ($data) {
                        $progressValue = $data['progress'];

                        $progressColor = $progressValue == 100 ? '#28a745' : '#3717ec';
                        return '
                            <div class="progress-bar-container" style="display: flex; align-items: center;">
                                <div class="progress-bar" style="width: 60%; background-color: #f3f3f3; border-radius: 4px; overflow: hidden; margin-right: 10px;">
                                    <div class="progress-bar-fill" style="height: 8px; background-color: ' . $progressColor . '; width: ' . $progressValue . '%;"></div>
                                </div>
                                <span style="font-weight: bold;">' . $progressValue . '%</span>
                            </div>';

                    })                                       
                    ->make(true);
            } elseif ($request->expectsJson()) {
                try {
                    $data = $this->repository->getAll();
                    return response()->json($data);
                } catch (Exception $e) {
                    dd($e);
                }
            }
        }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $ref = $this->data;
        $ref["url"] = route("project-monitoring.store");
        $ref["new"] = true;
        return view($this->data['view_directory'] . '.form', compact(
            'ref',
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "project_name" => ['required'],
            "client" => ['required'],
            "project_leader_image" => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            "project_leader_name" => ['required'],
            "project_leader_email" => ['required'],
            "start_date" => ['required', 'date'],
            "end_date" => ['required', 'date'],
            "progress" => ['required'],
        ], [], [
            'project_name' => 'Project Name',
            'client' => 'Client',
            'project_leader_image' => 'Project Leader Image',
            'project_leader_name' => 'Project Leader Name',
            'project_leader_email' => 'Project Leader Email',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'progress' => 'Progres',
        ]);
        $data['id'] = 'PM-' . Str::uuid();
        
        try {
            $project_leader_image = $request->file('project_leader_image')->store('project_leaders', 'public');
            $data["project_leader_image"] = $project_leader_image;
            $this->repository->store($data);
            return redirect()->route('project-monitoring.index')->with('success', 'Berhasil Menambah data ' . $data["project_name"]);
            
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi kesalahan saat menyimpan data")->withInput($request->input);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $id = decrypt($id);
        $data = $this->repository->getById($id);
        $ref = $this->data;
        $ref["url"] = route("project-monitoring.update", $id);
        $ref["new"] = false;
        return view($this->data['view_directory'] . '.form', compact('ref', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            "project_name" => ['required'],
            "client" => ['required'],
            "project_leader_image" => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            "project_leader_name" => ['required'],
            "project_leader_email" => ['required'],
            "start_date" => ['required'],
            "end_date" => ['required', 'after_or_equal:start_date'],
            "progress" => ['required', 'integer', 'min:0', 'max:100', 'digits_between:1,3'],
        ], [], [
            'project_name' => 'Project Name',
            'client' => 'Client',
            'project_leader_image' => 'Project Leader Image',
            'project_leader_name' => 'Project Leader Name',
            'project_leader_email' => 'Project Leader Email',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'progress' => 'Progres',
        ]);
        $data['updated_at'] = now();

        try {
            $existingData = $this->repository->getById($id);

            if ($request->hasFile('project_leader_image')) {
                if ($existingData->project_leader_image) {
                    Storage::disk('public')->delete($existingData->project_leader_image);
                }
                
                $project_leader_image = $request->file('project_leader_image')->store('project_leaders', 'public');
                $data["project_leader_image"] = $project_leader_image;
            }
            $this->repository->edit($id, $data);
            return redirect()->route('project-monitoring.index')->with('success', 'Berhasil Mengubah ' . $data["project_name"]);
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi kesalahan saat mengubah data")->withInput($request->input);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $id = decrypt($id);
        try {
            $data = $this->repository->getById($id);
            if ($data) {
                $project_leader_image = $data->project_leader_image;

                if ($project_leader_image) {
                    Storage::disk('public')->delete($project_leader_image);
                }
                $this->repository->destroy($id);
                return back()->with('success', 'Data berhasil di hapus');
            } else {
                return back()->with('error', 'Data tidak ditemukan');
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat menghapus data");
        }
    }
}
