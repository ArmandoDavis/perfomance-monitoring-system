@extends('layouts.backend.app')
@section('title', __('label.user_logs_profile'))
@section('content')
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs nav-border-top nav-border-top-primary mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link fw-medium active" data-bs-toggle="tab" href="#general_tab" role="tab" aria-selected="true">
                        {{__('label.general')}}
                    </a>
                </li>
            </ul>

            <div class="tab-content text-muted">
                <div class="tab-pane active show" id="general_tab" role="tabpanel">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-end flex-wrap">
                                <a href="{{ route('backend.setting.menu') }}" class="btn btn-sm btn-dark mb-2">
                                    <i class="ri-close align-bottom me-2 text-muted"></i> {{ __('label.close') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    @include('pages.backend.access.user_logs.profile.includes.general_info')
                </div>
            </div>
        </div>
    </div>
@endsection

