@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Structure</h5>
                    </div>
                    <div class="card-body">
                        <div class="organization-info">
                            <p><strong>Nom:</strong> {{ $organization->name }}</p>
                            <p><strong>Email:</strong> {{ $organization->email }}</p>
                            <p><strong>Numéro de téléphone:</strong> {{ $organization->phone_number }}</p>
                            <p><strong>Adresse:</strong> {{ $organization->address->street }}, {{ $organization->address->postal_code }} {{ $organization->address->city }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header row mx-0 justify-content-between align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">Contacts</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="search-bar">
                                <input type="text" id="search-input" class="form-control" placeholder="Search by name, email, or phone number">
                                <div class="input-group-append">
                                    <button id="clear-search" class="btn btn-sm btn-outline-secondary" style="display: none"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="contacts-list">
                        <!-- Organization contacts will be loaded dynamically here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>

        $(document).ready(function () {

            organizationId = {{ $organization->id }}
            paginationLength = 10

            loadContacts(selectedSearchTerm, selectedPage, paginationLength, selectedFolder, organizationId);

            $(document).on('selectedPageChanged', function(event, newPage) {
                loadContacts(selectedSearchTerm, selectedPage, paginationLength, selectedFolder, organizationId);
            });

            $(document).on('selectedSearchTermChanged', function(event, newTerm) {
                if (newTerm.length > 0) {
                    $('#clear-search').show(); // Show the "X" button
                } else {
                    $('#clear-search').hide(); // Hide the "X" button
                }
                loadContacts(selectedSearchTerm, selectedPage, paginationLength, selectedFolder, organizationId);
            });

            $('#search-input').on('input', function() {
                updateSelectedSearchTerm(($(this).val()));
            });
        })

    </script>

@endsection

@section('style')
    <style>
        .organization-info p {
            margin-bottom: 5px;
        }

        .search-bar {
            position: relative;
        }

        #clear-search {
            z-index: 999;
            border: none;
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
        }

        #contacts-list {
            position: relative;
            height: 534px;
        }

        .card-body {
            position: relative;
        }

        #pagination-container {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }

    </style>
@endsection
