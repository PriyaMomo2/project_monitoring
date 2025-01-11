@extends('web.layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@php
    $isEditMode = isset($data); 
@endphp

@push('header_script')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('web/css/style.css') }}" rel="stylesheet" />
@endpush

@section('content')
    @include('web.layouts.navbars.topnav', ['title' => Str::upper($ref['title'])])
    <div class="container-fluid py-4">
        <form method="POST" action="{{ $ref['url'] }}" enctype="multipart/form-data">
            @if ($isEditMode)
                @method('PUT')
            @endif
            @csrf
            <div class="row">
                <!-- Card 1 -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="text-bold">Informasi Proyek</h6>
                        </div>
                        <div class="card-body">
                            <!-- Project Name -->
                            <div class="mb-4">
                                <label class="form-label">Project Name</label>
                                <input id="project_name" name="project_name" class="form-control bg-white" type="text"
                                    placeholder="Enter the Project Name"
                                    value="{{ old('project_name', $isEditMode ? $data['project_name'] : '') }}">
                            </div>
                            <!-- Client -->
                            <div class="mb-4">
                                <label class="form-label">Client</label>
                                <input id="client" name="client" class="form-control bg-white" type="text"
                                    placeholder="Enter the Client"
                                    value="{{ old('client', $isEditMode ? $data['client'] : '') }}">
                            </div>
                            <!-- Project Leader -->
                            <div class="mb-4">
                                <label class="form-label">Project Leader</label>
                                <div class="d-flex align-items-start">
                                    <!-- Input untuk unggah gambar -->
                                    <div class="me-3">
                                        <input 
                                            type="file" 
                                            class="form-control bg-white" 
                                            id="project_leader_image" 
                                            name="project_leader_image" 
                                            accept="image/*" 
                                            onchange="previewImage(event)">
                                        <input 
                                            type="hidden" 
                                            name="project_leader_image_url" 
                                            id="project_leader_image_url" 
                                            value="{{ old('project_leader_image_url', isset($data['project_leader_image']) ? asset('storage/' . $data['project_leader_image']) : '') }}">
                                    </div>

                                    <!-- Gambar preview -->
                                    <img 
                                        id="project_leader_preview" 
                                        src="{{ old('project_leader_image_url', isset($data['project_leader_image']) ? asset('storage/' . $data['project_leader_image']) : '#') }}" 
                                        alt="Preview Image" 
                                        style="display: {{ old('project_leader_image_url', isset($data['project_leader_image'])) ? 'block' : 'none' }}; 
                                            width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                                </div>
                                <p class="m-1">
                                    <i class="text-danger">* Gambar wajib berupa jpg, jpeg, atau png dan ukuran maksimal 2MB</i>
                                </p>

                                <!-- Input untuk nama -->
                                <div class="mt-3">
                                    <label class="form-label">Leader's Name</label>
                                    <input 
                                        id="project_leader_name" 
                                        name="project_leader_name" 
                                        class="form-control bg-white" 
                                        type="text" 
                                        placeholder="Enter the Project Leader's Name" 
                                        value="{{ old('project_leader_name', $isEditMode ? $data['project_leader_name'] : '') }}">
                                </div>

                                <!-- Input untuk email -->
                                <div class="mt-3">
                                    <label class="form-label">Leader's Email</label>
                                    <input 
                                        id="project_leader_email" 
                                        name="project_leader_email" 
                                        class="form-control bg-white" 
                                        type="email" 
                                        placeholder="Enter the Project Leader's Email" 
                                        value="{{ old('project_leader_email', $isEditMode ? $data['project_leader_email'] : '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="text-bold">Detail Date & Progress</h6>
                        </div>
                        <div class="card-body">
                            <!-- Start Date -->
                            <div class="mb-4">
                                <label class="form-label">Start Date</label>
                                <input id="start_date" name="start_date" class="form-control bg-white datepicker" type="text"
                                    placeholder="Enter the Start Date"
                                    value="{{ old('start_date', $isEditMode ? $data['start_date'] : '') }}">
                            </div>
                            <!-- End Date -->
                            <div class="mb-4">
                                <label class="form-label">End Date</label>
                                <input id="end_date" name="end_date" class="form-control bg-white datepicker" type="text"
                                    placeholder="Enter the End Date"
                                    value="{{ old('end_date', $isEditMode ? $data['end_date'] : '') }}">
                            </div>
                            <!-- Progress -->
                            <div class="mb-4">
                                <label class="form-label">Progress (%)</label>
                                <input 
                                    id="progress" 
                                    name="progress" 
                                    class="form-control bg-white" 
                                    type="number" 
                                    min="0" 
                                    max="100" 
                                    oninput="if (this.value > 100) this.value = 100"
                                    placeholder="Enter the Progress Percentage"
                                    value="{{ old('progress', $isEditMode ? $data['progress'] : '') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Action Buttons -->
            <div class="text-end">
                <a href="{{ route('project-monitoring.index') }}" class="btn bg-gradient-danger me-2">Batal</a>
                <button type="submit" class="btn bg-gradient-info">Simpan</button>
            </div>
        </form>
    </div>
@endsection

@push('footer_script')
    <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script>

        // date
        if (document.querySelector(".datepicker")) {
            flatpickr(".datepicker", {
                altInput: true,
                locale: "id",
                altFormat: "j F Y",
                dateFormat: "Y-m-d"
            });
        }
        $('.datepicker').css('background-color', 'white');

        // progress
        document.getElementById('progres').addEventListener('input', function (e) {
            if (this.value.length > 3) {
                this.value = this.value.slice(0, 3);
            }

            if (this.value < 0) this.value = 0;
            if (this.value > 100) this.value = 100;
        });

        // image
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('project_leader_preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        }
    </script>
@endpush
