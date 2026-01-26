@extends('layouts.backend.app')
@section('title', __('label.user.add_staff'))
@include('includes.assets.select2_assets')

@section('content')
    {!! Html::formOpen(['route' => 'backend.users.staff.store', 'autocomplete' => 'off','method' => 'post', 'name' => 'create', 'class' => 'needs-validation' ,'novalidate', 'enctype'=>"multipart/form-data"]) !!}
    {{ Html::hidden('action_type', 1, []) }}
    {{ Html::hidden('today', getTodayDate(), []) }}

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <div class="form-group row mb-2">
                        <div class="col-xxl-3 col-md-6">
                            <div>
                                {{ Html::labels('name', __('label.name'), ['class' =>'required_asterik form-label']) }}
                                {{ Html::texts('name', null, ['class'=>'form-control form-control-sm required', 'id' => 'name', 'placeholder' => '', 'autocomplete' => 'off']) }}
                                {!! $errors->first('name', '<span class="badge rounded-pill bg-danger text-white">:message</span>') !!}
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-xxl-3 col-md-6">
                            <div class="form-group">
                                {{ Html::labels('phone', __('label.phone'), ['class' =>'required_asterik form-label']) }}
                                {{ Html::texts('phone', null, ['class'=>'form-control form-control-sm required', 'id' => 'phone', 'placeholder' => '', 'autocomplete' => 'off']) }}
                                {!! $errors->first('phone', '<span class="badge rounded-pill bg-danger text-white">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <div class="col-xxl-3 col-md-6">
                            <div>
                                {{ Html::labels('email', __('label.email'), ['class' =>'required_asterik form-label']) }}
                                {{ Html::texts('email', null, ['class'=>'form-control form-control-sm required', 'id' => 'email', 'placeholder' => '', 'autocomplete' => 'off']) }}
                                {!! $errors->first('email', '<span class="badge rounded-pill bg-danger text-white">:message</span>') !!}
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-xxl-3 col-md-6">
                            <div class="form-group">
                                {{ Html::labels('username', __('label.user.username'), ['class' =>'form-label']) }}
                                {{ Html::texts('username', null, ['class'=>'form-control form-control-sm', 'id' => 'username', 'placeholder' => '', 'autocomplete' => 'off']) }}
                                {!! $errors->first('username', '<span class="badge rounded-pill bg-danger text-white">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <div class="col-xxl-3 col-md-6">
                            <div class="form-group">
                                {{ Html::labels('role', __('label.role'), ['class' =>'required_asterik form-label']) }}
                                {{ Html::selectMultiple('roles', $roles->pluck('display_name', 'id'), [], [
                                    'class' => 'select2 form-control required',
                                    'id' => 'roles',
                                    'placeholder' => __('label.roles.select_roles'),
                                    'autocomplete' => 'off'
                                ]) }}
                                {!! $errors->first('role', '<span class="badge rounded-pill bg-danger text-white">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                {{ Html::checkbox('is_active', 1, true, ['class' => 'form-check-input', 'id' => 'is_active']) }}
                                {{ Html::labels('is_active', __('label.is_active'), ['class' => 'form-check-label']) }}
                                {!! $errors->first('is_active', '<span class="text-danger small text-white">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="element-form">
                                <div class="form-group d-flex justify-content-end gap-2">
                                    {{ link_to_route('backend.users.staff.index',trans('buttons.general.cancel'),[],['id'=> 'cancel', 'class' => 'btn btn-dark cancel_button', ]) }}
                                    {{ Html::submits(trans('buttons.general.submit'), ['class' => 'btn btn-primary', 'type'=>'submit', 'style' => 'border-radius: 5px;',  'id' => 'submit_btn']) }}
                                    <label id="submit_label"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!!  Html::formClose()  !!}
@endsection

@push('scripts')
    <script>
        pleaseWaitSubmitButton("submit_btn","submit_label","{{ trans('label.please_wait') }}",2);
        $('body').on('submit', 'form[name=create]', function(e) {
            e.preventDefault();
            /*Codes Here*/
            pleaseWaitSubmitButton("submit_btn","submit_label","{{ trans('label.please_wait') }}",1);
            this.submit();
        });

        $(".select2").select2();
    </script>
@endpush
