@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Gestion Utilisateurs</h1>
        @if(hasAnySubRole())
        <a href="{{ route('users.create') }}" class="btn btn-primary">Créer Utilisateur</a>
        @endif
    </div>

    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste d'utilisateurs</h5>
            <form action="{{ route('users.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" id="search-input" class="form-control" placeholder="Rechercher par nom ou par mail..." value="{{ request('search') }}">
                </div>
            </form>
        </div>
        <div class="card-body" id="user-list">
            <!-- Search results and pagination will be loaded here using AJAX -->
        </div>
    </div>

@endsection

@section('script')

    <script>

        $(document).ready(function() {
            // Initial load when the page loads
            loadUsers('', 1);

            // Search input event
            $('#search-input').on('keyup', function() {
                let searchTerm = $(this).val();
                loadUsers(searchTerm, 1); // Load first page of results
            });

            $(document).on('click', '.dropdown-toggle', function(e) {
                // Hide all other dropdown menus
                $('.dropdown-menu').not($(this).next('.dropdown-menu')).dropdown('hide');

                // Toggle the clicked dropdown menu
                $(this).next('.dropdown-menu').dropdown('toggle');
            });

            // Handle click on change-role button
            $(document).on('click', '.change-role', function() {
                let userId = $(this).data('user-id');
                let roleId = $(this).data('role-id');
                let roleName = $(this).data('role-name');
                let userName = $(this).closest('tr').find('td:first-child').text();

                if (confirm('Etes vous sure de vouloir changer le role de "' + userName + " à " + roleName + '"?')) {

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    let dropdownMenu = $(this).closest('.dropdown-menu');
                    let dropdownToggle = dropdownMenu.prev('.dropdown-toggle');

                    $.ajax({
                        type: 'PATCH',
                        url: '/users/' + userId + '/update-role',
                        data: { role_id: roleId },
                        success: function(response) {
                            // Update the displayed role on success
                            dropdownToggle.text(response.role_name);
                            // Close the dropdown
                            dropdownMenu.dropdown('hide');
                            $(this).closest('.dropdown-menu').prev('.dropdown-toggle').text(response.role_name);
                        },
                        error: function(xhr) {
                            console.error('Error updating role:', xhr.statusText);
                        }
                    });
                }
            });

            // Pagination links event
            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                loadUsers($('#search-input').val(), page);
            });

            $(document).on('click', '.toggle-status', function (e) {
                const userId = $(this).data('user-id');
                const currentStatus = $(this).data('current-status');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '{{ route('users.toggle-status') }}',
                    data: { user_id: userId, current_status: currentStatus },
                    success: function (response) {

                        if (response.status === 'success') {
                            // Update button text based on new status
                            if (response.new_status === 1) {
                                $(e.target).text('Deactivate')
                                $(e.target).closest('tr').children('.user-status').text('Active')
                            } else {
                                $(e.target).text('Activate')
                                $(e.target).closest('tr').children('.user-status').text('Inactive')
                            }
                            $(this).data('current-status', response.new_status);
                        } else {
                            displayError('Une erreur est survenue!')
                        }
                    },
                    error: function () {
                        displayError('Une erreur est survenue!')
                    }
                });
            });
        });

        function loadUsers(searchTerm, page) {
            $.ajax({
                method: 'GET',
                url: '/users/search',
                data: {
                    search: searchTerm,
                    page: page
                },
                success: function(response) {
                    $('#user-list').html(response);
                },
                error: function(xhr) {
                    console.error('Error de chargement:', xhr.statusText);
                }
            });
        }


    </script>

@endsection

@section('style')

    <style>

        .card-body {
            position: relative;
        }

        #user-list {
            height: 640px;
            overflow-y: auto;
        }

        #pagination-container {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .custom-dropdown-menu {
            transform: translate(0, 30px) !important;
        }

    </style>

@endsection