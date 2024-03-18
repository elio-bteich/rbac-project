@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mb-4">Roles et permissions</h1>
        <a href="{{ route('users.index') }}" class="btn btn-primary">Gestion Utilisateurs</a>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Roles</div>
                <div class="card-body">
                    <div id="roleTree" class="jstree">
                        <!-- Roles tree will be dynamically loaded here -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Permissions</span>
                </div>
                <div class="card-body scrollable-box">
                    <div id="permissionListContainer">
                        <!-- The permissions list will be dynamically loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header">
                List d'utilisateurs
            </div>
            <div class="card-body" id="users-list">
                <!-- Users list will be dynamically loaded here -->
            </div>
        </div>
    </div>

    @include('roles.add-role-modal')

    @include('roles.modify-permissions-modal')

@endsection

@section('script')

    <script>
        $(function () {

            $('#roleTree').jstree({
                'core': {
                    'data': {!! json_encode($rolesTree) !!},
                    'icons': {
                        'default': '{!! asset('images/role.jpg') !!}'
                    }
                },
                'plugins': ['contextmenu'],
                'contextmenu': {
                    items: function (node) {
                        return {
                            add: {
                                label: "Add Role",
                                action: function () {
                                    showAddRoleModal(node.id);
                                }
                            },
                            modifyPermission: {
                                label: "Modify Permissions",
                                action: function () {
                                    if (node.parent === "#") {
                                        displayError("Cannot update root node")
                                    } else {
                                        showModifyPermissionModal(node.id);
                                    }
                                }
                            },
                            delete: {
                                label: "Delete",
                                action: function () {
                                    if (node.parent === "#") {
                                        displayError("Cannot delete root node")
                                    } else {
                                        showDeleteDialog(node.id);
                                    }
                                }
                            }
                        };
                    }
                }
            });

            $(document).on('click', '.modal [data-dismiss="modal"]', function () {
                $(this).parents('.modal').modal('hide');
            });

            $('#addRoleForm').submit(function (e) {
                e.preventDefault();
                var formData = $(this).serialize();

                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                $.ajax({
                    method: 'POST',
                    url: '/roles',
                    data: formData,
                    success: function (response) {
                        $('#addChildRoleModal').modal('hide');
                        refreshTreeWithNewData(response);
                        displaySuccess('Role has been added successfully.')
                    },
                    error: function (xhr) {
                        $('#addChildRoleModal').modal('hide');
                        if (xhr.status === 422) {
                            let validationErrors = xhr.responseJSON.errors;
                            for (let field in validationErrors) {
                                if (validationErrors.hasOwnProperty(field)) {
                                    displayError(validationErrors[field].join(', '));
                                }
                            }
                        } else {
                            displayError('An error has occurred');
                        }
                    }
                });

            });

            $(document).on('click', '#toggle-status', function () {
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
                                $('#toggle-status').text('Deactivate');
                            } else {
                                $('#toggle-status').text('Activate');
                            }
                            $(this).data('current-status', response.new_status);
                        } else {
                            displayError('An error has occured!')
                        }
                    },
                    error: function () {
                        displayError('An error has occured')
                    }
                });
            });

            let clickCount = 0;
            let clickTimeout;

            // Event handler for clicking on a role in the tree
            $(document).on('click', '.jstree-anchor', function (e) {

                let roleId = $(this).closest('.jstree-node').attr('id');
                clickCount++;
                clearTimeout(clickTimeout);

                clickTimeout = setTimeout(function() {
                    if (clickCount === 1) {
                        showLoadingOverlay()
                        // Fetch and display the permissions associated with the clicked role
                        $.ajax({
                            method: 'GET',
                            url: '/roles/' + roleId + "/permissions",
                            success: function (response) {
                                $('#permissionListContainer').html(response);
                            },
                            error: function (xhr) {
                                displayError("An error has occurred");
                            }
                        });

                        // Fetch and display the users associated with the clicked role
                        $.ajax({
                            method: 'GET',
                            url: '/roles/' + roleId + "/users",
                            success: function (response) {
                                $('#users-list').html(response);
                                hideLoadingOverlay()
                            },
                            error: function (xhr) {
                                hideLoadingOverlay()
                                displayError("An error occurred while fetching users");
                            }
                        });

                    } else {
                        $('#roleTree').jstree(true).deselect_all();
                        $('#users-list').html('');
                        $('#permissionListContainer').html('');
                    }
                    clickCount = 0;
                }, 300); // Set the delay time (in milliseconds)

            });

            function deleteRole(roleId) {
                $.ajax({
                    method: 'DELETE',
                    url: '/roles/' + roleId,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            refreshTreeWithNewData(response.success);
                            displaySuccess('Role has been deleted successfully.')
                        } else if (response.error) {
                            displayError(response.error)
                        }
                    }
                });
            }

            function openModifyPermissionModal(roleId) {
                $.ajax({
                    method: 'GET',
                    url: '/roles/' + roleId + '/modify-permissions',
                    success: function (response) {
                        $('#modify-permissions-form').html(response);
                        $('#modifyPermissionModal').modal('show')
                    },
                    error: function (xhr) {
                        displayError("An error has occurred");
                    }
                });
            }

            function refreshTreeWithNewData(newData) {
                $('#roleTree').jstree(true).settings.core.data = newData;
                $('#roleTree').jstree("refresh");
            }

            function showAddRoleModal(roleId) {
                $.ajax({
                    method: 'GET',
                    url: '/roles/' + roleId + '/canAddSubRole',
                    success: function (canAdd) {
                        if (canAdd) {
                            $('#parentRoleID').val(roleId);
                            $('#addChildRoleModal').modal('show');
                        } else {
                            displayError("You don't have the authorization to add a sub role to this role.")
                        }
                    },
                    error: function (xhr) {
                        displayError("An error has occurred");
                    }
                })
            }

            function showDeleteDialog(roleId) {
                $.ajax({
                    method: 'GET',
                    url: '/roles/' + roleId + '/canDeleteRole',
                    success: function (canDelete) {
                        if (canDelete) {
                            if (confirm("Are you sure you want to delete this role and its children?")) {
                                deleteRole(roleId);
                            }
                        } else {
                            displayError("You don't have the authorization to delete this role.")
                        }
                    },
                    error: function (xhr) {
                        displayError("An error has occurred");
                    }
                })
            }

            function showModifyPermissionModal(roleId) {
                $.ajax({
                    method: 'GET',
                    url: '/roles/' + roleId + '/canModifyRolePermissions',
                    success: function (canModify) {
                        if (canModify) {
                            openModifyPermissionModal(roleId)
                        } else {
                            displayError("You don't have the authorization to modify the permissions of this role.")
                        }
                    },
                    error: function (xhr) {
                        displayError("An error has occurred");
                    }
                })
            }
        });

    </script>
@endsection


@section('style')
    <style>

        #contextMenu {
            position: absolute;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 9999;
        }

        #contextMenu ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        #contextMenu li {
            padding: 8px 12px;
            cursor: pointer;
        }

        #contextMenu li:hover {
            background-color: #f5f5f5;
        }

        .modal-header .close {
            padding: 0;
            margin: 0;
            background: none;
            border: none;
            font-size: 24px;
            color: #ff0000;
        }

        .modal-header .close:hover {
            color: #990000;
        }

        .scrollable-box {
            max-height: 50%;
            overflow-y: auto;
        }

        .card-footer {
            position: sticky;
            bottom: 0;
            padding: 10px;
            background-color: white;
        }

        .card-body {
            height: 220px;
            overflow: auto;
            background-color: #ffffff;
        }

        #users-list {
            height: 370px;
            overflow-y: auto;
        }
    </style>
@endsection
