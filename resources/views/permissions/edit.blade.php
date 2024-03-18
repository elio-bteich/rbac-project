@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Edit Permission') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('permissions.update', $permission->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">{{ __('Permission Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $permission->name) }}" required autocomplete="name" autofocus>

                            @error('name')

                                @include('alerts.error-message')

                            @enderror
                        </div>

                        <div class="form-group my-3">
                            <label for="target_model">{{ __('Target Model') }}</label>
                            <select id="target_model" class="form-control @error('target_model') is-invalid @enderror" name="target_model" required>

                                @foreach($permissionTargets as $permissionTarget)
                                    <option value="{{ $permissionTarget->id }}" @if($permission->target_type === $permissionTarget->type) selected @endif>{{ $permissionTarget->name }}</option>
                                @endforeach

                            </select>

                            @error('target_model')

                                @include('alerts.error-message')

                            @enderror

                        </div>

                        <div class="form-group mb-1">
                            <label for="location">{{ __('Folder') }}</label>
                            <select id="location" class="form-control @error('location') is-invalid @enderror" name="location" required>

                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" @if($permission->typeable->location->name === $location->name) selected @endif>
                                        {{ $location->name }}</option>
                                @endforeach

                            </select>

                            @error('location')

                                @include('alerts.error-message')

                            @enderror
                        </div>


                        <div class="card-footer d-flex justify-content-start" style="background-color: #f0f0f0;">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Update') }}
                            </button>
                            <a href="{{ route('admin.home') }}" class="btn btn-secondary mx-2">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
