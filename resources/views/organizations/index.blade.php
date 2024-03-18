@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h1>Structures</h1>
                <input type="text" id="organization-search-input" class="form-control" placeholder="Search organizations">
            </div>
            <div class="card-body">
                <button id="organizationsAddButton" class="btn btn-outline-primary mt-2">
                    <i class="fas fa-plus"></i> Ajouter
                </button>
                <div id="organizations-list">
                    <!-- Organizations will be dynamically loaded here -->
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')

    <script>

        $(document).ready(function () {

            let selectedSearchTerm = '';
            let selectedPage = 1;

            loadOrganizations(selectedSearchTerm, selectedPage);

            $('#organizationsAddButton').on('click', function (e) {
                addOrganizationModal();
            });

            $('#organization-search-input').on('input', function() {
                updateSelectedSearchTerm($(this).val());
            });

            // Pagination links event
            $(document).on('click', '#pagination-container a', function(event) {
                event.preventDefault();
                updateSelectedPage($(this).attr('href').split('page=')[1]);
                loadOrganizations(selectedSearchTerm, selectedPage);
            });

            $(document).on('searchTermChanged', function(event, newTerm) {
                if (newTerm.length > 0) {
                    $('#clear-search').show(); // Show the "X" button
                } else {
                    $('#clear-search').hide(); // Hide the "X" button
                }
                loadOrganizations(selectedSearchTerm, selectedPage);
            });

            $(document).on('click', '.edit-organization', function(e) {
                let orgId = $(this).data('org-id');
                editOrganizationModal(orgId)
            })

            $(document).on('click', '.delete-organization', function(e) {
                let orgId = $(this).data('org-id');
                deleteOrganizationModal(orgId)
            })

            $(document).on('selectedPageChanged', function(event, newPage) {
                loadOrganizations(selectedSearchTerm, selectedPage);
            });

            $(document).on('input', '#city', function(e) {
                formatCity(e.target);
            });

            function addOrganizationModal(formData=null, validationErrors=null) {
                $.ajax({
                    method: 'GET',
                    url: "{{ route('organizations.create') }}",
                    success: function(response) {
                        Swal.fire({
                            title: 'Ajouter Structure',
                            html: response,
                            showCancelButton: true,
                            confirmButtonText: 'Enregister',
                            cancelButtonText: 'Annuler',
                            customClass: 'custom-alert',
                            preConfirm: function() {
                                addOrganization();
                            },
                            didOpen: function() {
                                if (formData) {
                                    const formDataObj = parseFormData(formData);
                                    fillInputValues(formDataObj);
                                    displayValidationErrors(validationErrors);
                                }
                            }
                        });
                    },
                    error: function(xhr) {
                        displayError(xhr.statusText);
                    }
                });
            }

            function deleteOrganizationModal(organizationId) {
                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: 'Vous ne pourrez pas récupérer cette structure!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, supprimez-la !',
                    cancelButtonText: 'Annuler',
                    preConfirm: function () {
                        deleteOrganization(organizationId)
                    }
                });
            }

            function editOrganizationModal(organizationId, formData=null, validationErrors=null) {
                $.ajax({
                    method: 'GET',
                    url: "{{ route('organizations.edit', ['organization' => '__organizationId__']) }}".replace('__organizationId__', organizationId),
                    success: function(response) {
                        Swal.fire({
                            title: 'Modifier Structure',
                            html: response.html,
                            showCancelButton: true,
                            confirmButtonText: 'Enregister',
                            cancelButtonText: 'Annuler',
                            customClass: 'custom-alert',
                            preConfirm: function () {
                                let formData = $('#edit-organization-form').serialize();
                                editOrganization(organizationId, formData);
                            },
                            didOpen: function() {
                                if (formData) {
                                    const formDataObj = parseFormData(formData);
                                    fillInputValues(formDataObj);
                                    displayValidationErrors(validationErrors);
                                } else {
                                    fillInputValues(response.formData);
                                }
                            }
                        });
                    },
                    error: function(xhr) {
                        displayError(xhr.statusText)
                    }
                });
            }

            function editOrganization(organizationId, formData) {
                $.ajax({
                    method: 'PUT',
                    url: "{{ route('organizations.update', ['organization' => '__organizationId__']) }}".replace('__organizationId__', organizationId),
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            displaySuccess(response.success);
                            loadOrganizations(selectedSearchTerm, selectedPage);
                        } else if (response.inputError) {
                            editOrganizationModal(organizationId, formData, response.messages);
                        }
                    },
                    error: function(xhr) {
                        displayError(xhr.statusText);
                    }
                });
            }

            function addOrganization() {
                let formData = $('#add-organization-form').serialize();

                $.ajax({
                    method: 'POST',
                    url: "{{ route('organizations.store') }}",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            displaySuccess(response.success);
                            loadOrganizations(selectedSearchTerm, selectedPage);
                        } else if (response.inputError) {
                            addOrganizationModal(formData, response.messages);
                        }
                    },
                    error: function(xhr) {
                        displayError(xhr.statusText);
                    }
                });
            }

            function deleteOrganization(organizationId, force=false) {
                let forceString = force ? '1' : '0';
                $.ajax({
                    url: "{{ route('organizations.destroy', ['organization' => '__organizationId__', 'force' => '__force__']) }}".replace('__organizationId__', organizationId)
                        .replace('__force__', forceString),
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            displaySuccess(response.success)
                            loadOrganizations(selectedSearchTerm, selectedPage)
                        } else if (response.constraintError) {
                            promptForceDelete(organizationId, response.constraintError)
                        }
                    },
                    error: function(xhr) {
                        displayError(xhr.statusText)
                    }
                });
            }


            function loadOrganizations(searchTerm, page) {
                $.ajax({
                    method: 'GET',
                    url: '/organizations/search',
                    data: {
                        search: searchTerm,
                        page: page
                    },
                    success: function(response) {
                        $('#organizations-list').html(response.organizationsHtml);
                        $('#pagination-container').html(response.paginationHtml);
                    },
                    error: function(xhr) {
                        displayError(xhr.statusText);
                    }
                });
            }

            function updateSelectedSearchTerm(newTerm) {
                selectedSearchTerm = newTerm;
                $(document).trigger('searchTermChanged', newTerm);
            }

            function updateSelectedPage(newPage) {
                selectedPage = newPage;
                $(document).trigger('selectedPageChanged', newPage);
            }

            // Function to fill input values from form data
            function fillInputValues(formDataObj) {
                $.each(formDataObj, function (field, value) {
                    if (value !== null) {
                        $('#' + field).val(value);
                    }
                })
            }

            function formatCity(inputElement) {
                let inputString = inputElement.value;

                inputElement.value = inputString.toLowerCase().replace(/(^|-)\S/g, function (match) {
                    return match.toUpperCase();
                }).replace(/-+/g, "-");
            }

            function promptForceDelete(organizationId, errorMessage) {
                Swal.fire({
                    title: errorMessage,
                    text: 'Voulez vous supprimer la structure avec tout ses contacts!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, supprimez-les !',
                    cancelButtonText: 'Annuler',
                    preConfirm: function () {
                        deleteOrganization(organizationId, true)
                    }
                });
            }

        });

    </script>

@endsection

@section('style')

    <style>

        #pagination-container {
            position: absolute;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
        }

        #name-col {
            width: 15%;
        }

        #email-col {
            width: 20%;
        }

        #phone-num-col {
            width: 20%;
        }

        #address-col {
            width: 30%;
        }

        #actions-col {
            width: 15%
        }

        .card-header {
            position: relative;
        }

        .card-body {
            background-color: #ffffff;
        }

        #organization-search-input {
            position: absolute;
            width: 50%;
            right: 0;
            margin-right: 20px;
        }

    </style>

@endsection