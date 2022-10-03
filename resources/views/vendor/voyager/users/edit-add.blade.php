@extends('voyager::master')

@section('page_title', __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
@stop

@php
    $countries = TCG\Voyager\Models\Country::all();
    // $states = TCG\Voyager\Models\State::all();
    // $cities = TCG\Voyager\Models\City::all();
@endphp

@section('content')
    <div class="page-content container-fluid">
        <form class="form-edit-add" role="form"
              action="@if(!is_null($dataTypeContent->getKey())){{ route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) }}@else{{ route('voyager.'.$dataType->slug.'.store') }}@endif"
              method="POST" enctype="multipart/form-data" autocomplete="off">
            <!-- PUT Method if we are editing -->
            @if(isset($dataTypeContent->id))
                {{ method_field("PUT") }}
            @endif
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-bordered">
                    {{-- <div class="panel"> --}}
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="panel-body">
                            <div class="form-group">
                                <label for="display_name">Display Name</label>
                                <input type="text" class="form-control" id="display_name" name="display_name" placeholder="{{ __('voyager::generic.name') }}"
                                       value="{{ old('display_name', $dataTypeContent->display_name ?? '') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="name">{{ __('voyager::generic.name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('voyager::generic.name') }}"
                                       value="{{ old('name', $dataTypeContent->name ?? '') }}">
                            </div>

                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                                       value="{{ old('username', $dataTypeContent->username ?? '') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="email">{{ __('voyager::generic.email') }}</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('voyager::generic.email') }}"
                                       value="{{ old('email', $dataTypeContent->email ?? '') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="password">{{ __('voyager::generic.password') }}</label>
                                @if(isset($dataTypeContent->password))
                                    <br>
                                    <small>{{ __('voyager::profile.password_hint') }}</small>
                                @endif
                                <input type="password" class="form-control" id="password" name="password" value="" autocomplete="new-password" {{ isset($dataTypeContent->password) ? '' : 'required' }}>
                            </div>

                            @can('editRoles', $dataTypeContent)
                                <div class="form-group">
                                    <label for="default_role">{{ __('voyager::profile.role_default') }}</label>
                                    @php
                                        $dataTypeRows = $dataType->{(isset($dataTypeContent->id) ? 'editRows' : 'addRows' )};

                                        $row     = $dataTypeRows->where('field', 'user_belongsto_role_relationship')->first();
                                        $options = $row->details;
                                    @endphp
                                    @include('voyager::formfields.relationship')
                                </div>
                                <div class="form-group">
                                    <label for="additional_roles">{{ __('voyager::profile.roles_additional') }}</label>
                                    @php
                                        $row     = $dataTypeRows->where('field', 'user_belongstomany_role_relationship')->first();
                                        $options = $row->details;
                                    @endphp
                                    @include('voyager::formfields.relationship')
                                </div>
                            @endcan
                            @php
                            if (isset($dataTypeContent->locale)) {
                                $selected_locale = $dataTypeContent->locale;
                            } else {
                                $selected_locale = config('app.locale', 'en');
                            }
                            @endphp

                            <div class="form-group">
                                <label for="linkedin">Date Of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                    value="{{ old('date_of_birth', $dataTypeContent->date_of_birth) }}">
                            </div>

                            <div class="form-group">
                                <label for="locale">{{ __('voyager::generic.locale') }}</label>
                                <select class="form-control select2" id="locale" name="locale">
                                    @foreach (Voyager::getLocales() as $locale)
                                    <option value="{{ $locale }}"
                                    {{ ($locale == $selected_locale ? 'selected' : '') }}>{{ $locale }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="Address"
                                       value="{{ old('address', $dataTypeContent->address ?? '') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="country_id">Country</label>
                                <select class="form-control select2" id="country_id" name="country_id" data-id="{{ $dataTypeContent->country_id ?? '0' }}">
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->id }}"
                                    {{ ($country->id == $dataTypeContent->country_id ? 'selected' : '') }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="state_id">State</label>
                                <select class="form-control select2" id="state_id" name="state_id" data-id="{{ $dataTypeContent->state_id ?? '0' }}">
                                    {{-- @foreach ($states as $state)
                                    <option value="{{ $state->id }}"
                                    {{ ($state->id == $dataTypeContent->state_id ? 'selected' : '') }}>{{ $state->name }}</option>
                                    @endforeach --}}
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="city_id">city</label>
                                <select class="form-control select2" id="city_id" name="city_id" data-id="{{ $dataTypeContent->city_id ?? '0' }}">
                                    {{-- @foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                    {{ ($city->id == $dataTypeContent->city_id ? 'selected' : '') }}>{{ $city->name }}</option>
                                    @endforeach --}}
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control select2" id="status" name="status" data-id="{{ $dataTypeContent->status ?? '1' }}">
    <option value="1" {{ ( isset($dataTypeContent->status) && $dataTypeContent->status == 1) ? 'selected' : '' }} >Active</option>
                                    <option value="0" {{ (isset($dataTypeContent->status) && $dataTypeContent->status == 0) ? 'selected' : '' }} >Deactive</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="trophies">Trophies</label>
                                <input type="number" class="form-control" id="trophies" name="trophies" placeholder="Trophies"
                                       value="{{ old('trophies', $dataTypeContent->trophies ?? '0') }}">
                            </div>

                            <div class="form-group">
                                <label for="facebook">Facebook URL</label>
                                <input type="url" class="form-control" id="facebook" name="facebook" placeholder="Facebook"
                                       value="{{ old('facebook', $dataTypeContent->facebook) }}">
                            </div>

                            <div class="form-group">
                                <label for="twitter">Twitter URL</label>
                                <input type="url" class="form-control" id="twitter" name="twitter" placeholder="Twitter"
                                       value="{{ old('twitter', $dataTypeContent->twitter) }}">
                            </div>

                            <div class="form-group">
                                <label for="instagram">Instagram URL</label>
                                <input type="url" class="form-control" id="instagram" name="instagram" placeholder="Instagram"
                                       value="{{ old('instagram', $dataTypeContent->instagram) }}">
                            </div>

                            <div class="form-group">
                                <label for="linkedin">Linkedin URL</label>
                                <input type="url" class="form-control" id="linkedin" name="linkedin" placeholder="Linkedin"
                                       value="{{ old('linkedin', $dataTypeContent->linkedin) }}">
                            </div>

                            <div class="form-group">
                                <label for="linkedin">Bio</label>
                                <textarea class="form-control" id="bio" name="bio" placeholder="Bio">{{ old('bio', $dataTypeContent->bio) }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel panel panel-bordered panel-warning">
                        <div class="panel-body">
                            <div class="form-group">
                                @if(isset($dataTypeContent->avatar))
                                    <img src="{{ filter_var($dataTypeContent->avatar, FILTER_VALIDATE_URL) ? $dataTypeContent->avatar : Voyager::image( $dataTypeContent->avatar ) }}" style="width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;" />
                                @endif
                                <input type="file" data-name="avatar" name="avatar">
                            </div>
                        </div>
                    </div>

                    {{-- <div class="panel panel panel-bordered panel-warning">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="image">{{ 'Profile Image' }}</label>
                                @if(isset($dataTypeContent->image))
                                    <img src="{{ filter_var($dataTypeContent->image, FILTER_VALIDATE_URL) ? $dataTypeContent->image : Voyager::image( $dataTypeContent->image ) }}" style="width:200px; height:auto; clear:both; display:block; padding:2px; border:1px solid #ddd; margin-bottom:10px;" />
                                @endif
                                <input type="file" data-name="image" name="image">
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>

            <button type="submit" class="btn btn-primary pull-right save">
                {{ __('voyager::generic.save') }}
            </button>
        </form>

        <iframe id="form_target" name="form_target" style="display:none"></iframe>
        <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post" enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden">
            {{ csrf_field() }}
            <input name="image" id="upload_file" type="file" onchange="$('#my_form').submit();this.value='';">
            <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
        </form>
    </div>
@stop

@section('javascript')
    <script>
        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            var selectedCountry = $("#country_id").data('id');
            var selectedState = $("#state_id").data('id');
            var selectedCity = $("#city_id").data('id');

            setStates(selectedCountry);
            setCities(selectedState);
            
            setTimeout(() => {
                $('#state_id option[value="'+selectedState+'"]').attr("selected", true);
                $('#city_id option[value="'+selectedCity+'"]').attr("selected", true);
                $('.select2').select2();
            }, 1000);
            
        });

        $("select#country_id").change(function(){
            var selectedCountry = $("#country_id option:selected").val();
            setStates(selectedCountry);
        });

        $("select#state_id").change(function(){
            var selectedState = $("#state_id option:selected").val();
            setCities(selectedState);
        });

        function setStates(id) {
            
            $.get('{{ url("/getStates/") }}/'+id, function(data, status){
                
                var data1 = $.map(data, function (obj) {
                    obj.text = obj.text || obj.name;
                    return obj;
                });

                $('select#state_id').select2().empty();
                $('select#state_id').select2();
                $('select#state_id').select2({ data: data1 });
            });
        }

        function setCities(id) {
            
            $.get('{{ url("/getCities/") }}/'+id, function(data, status){
                
                var data1 = $.map(data, function (obj) {
                    obj.text = obj.text || obj.name;
                    return obj;
                });

                $('select#city_id').select2().empty();
                $('select#city_id').select2();
                $('select#city_id').select2({ data: data1 });
            });
        }

    </script>
@stop
