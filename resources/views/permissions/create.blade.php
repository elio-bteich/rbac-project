@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Create Permission') }}</div>

                <form method="POST" action="{{ route('permissions.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">{{ __('Permission Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')

                                @include('alerts.error-message')

                            @enderror
                        </div>

                        <div class="form-group my-2">
                            <label for="target_model">{{ __('Target Model') }}</label>
                            <select id="target_model" class="form-control @error('target_model') is-invalid @enderror" name="target_model" required>
                                <option value="" selected disabled>Selectionne le model cible</option>
                                @foreach($permissionTargets as $permissionTarget)
                                    <option value="{{ $permissionTarget->id }}">{{ $permissionTarget->name }}</option>
                                @endforeach
                            </select>

                            @error('target_model')

                                @include('alerts.error-message')

                            @enderror
                        </div>

                        <div class="form-group mb-2">
                            <label for="location">{{ __('Folder') }}</label>
                            <select id="location" class="form-control @error('location') is-invalid @enderror" name="location" required>
                                <option value="" selected disabled>Selectionne la localisation</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>

                            @error('location')

                                @include('alerts.error-message')

                            @enderror
                        </div>

                </div>
                <div class="card-footer d-flex justify-content-start" style="background-color: #f0f0f0;">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Create') }}
                    </button>
                    <a href="{{ route('admin.home') }}" class="btn btn-secondary mx-2">
                        {{ __('Cancel') }}
                    </a>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
