<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Marsoins Extranet') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.9/dist/sweetalert2.min.css">

    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.9/dist/sweetalert2.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('style')
    <style>
        .table {
            --bs-table-bg: transparent; /* Or your desired background color */
        }

        .swal2-styled.swal2-confirm {
            background-color: #0d6efd;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #fff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .swal2-html-container {
            overflow: unset;
        }

        .custom-alert {
            min-width: 600px;
            text-align: left;
        }

        .custom-alert label {
            float: left;
        }

        body {
            background-color: #f5f5f5;
        }

        .contact-row:hover {
            background-color: rgba(0,0,0,0.2);
            cursor: pointer;
        }

        .contact-row, tr{
            border-color: rgba(0,0,0,0.8);
        }

        .custom-width-name {
            width: 20%;
        }

        .custom-width-email {
            width: 25%;
        }

        .custom-width-pers-number {
            width: 20%;
        }

        .custom-width-organization-name {
            width: 20%;
        }

        .custom-width-job {
            width: 15%
        }

        .contact-info {
            padding: 20px;
            border: 1px solid #e2e2e2;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .contact-info-item {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            text-align: left;
        }

    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a href="/">
                    <img width="120px" height="50px" alt="A Vos Soins Extranet" class="custom-logo" src="{{ asset('/images/avossoins-logo.png') }}">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">

                        <!-- Authentication Links -->
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('organizations.index') }}">
                                    <i class="fas fa-building"></i>
                                    Structures
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('contacts.index') }}">
                                    <i class="fas fa-address-book"></i>
                                    Contacts
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.home') }}">
                                    <i class="fas fa-cogs"></i>
                                    Admin
                                </a>
                            </li>

                            <li class="nav-item dropdown" style="margin-left: 20px">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user-circle"></i>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">

                <div class="loading-overlay">
                    <div class="spinner"></div>
                </div>

                @include('alerts.session-success-alert')

                @include('alerts.session-error-alert')

                @include('alerts.alert-success')

                @include('alerts.alert-error')

                @yield('content')
            </div>
        </main>

    </div>
    @yield('script')
<script>

    let organizationId = null;
    let selectedSearchTerm = '';
    let selectedPage = 1;
    let paginationLength = 12
    let selectedFolder = null

    $(document).on('click', '.contact-row', function() {

        let contactId = $(this).data('contact-id');

        showContactModal(contactId)
    });

    $(document).on('click', '#pagination-container a', function(event) {
        event.preventDefault();
        updateSelectedPage($(this).attr('href').split('page=')[1]);
        loadContacts(selectedSearchTerm, selectedPage, paginationLength, selectedFolder, organizationId);
    });

    $('#clear-search').on('click', function() {
        $('#search-input').val('');
        $('#clear-search').hide();
        updateSelectedSearchTerm('');
        updateSelectedPage(1);
        loadContacts(selectedSearchTerm, selectedPage, paginationLength, selectedFolder, organizationId);
    });

    $(document).on('searchTermChanged', function(event, newTerm) {
        if (newTerm.length > 0) {
            $('#clear-search').show(); // Show "X" button
        } else {
            $('#clear-search').hide(); // Hide "X" button
        }
        loadContacts(newTerm, selectedPage, paginationLength, selectedFolder, organizationId);
    });

    function updateSelectedSearchTerm(newTerm) {
        selectedSearchTerm = newTerm;
        $(document).trigger('searchTermChanged', newTerm);
    }

    function updateSelectedPage(newPage) {
        selectedPage = newPage;
        $(document).trigger('selectedPageChanged', newPage);
    }

    function displayError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: message,
        })
    }

    function displaySuccess(message) {
        Swal.fire({
            icon: 'success',
            title: message,
            showConfirmButton: false,
            timer: 1500
        })
    }

    function loadContacts(searchTerm, page, paginationLength, folder=null, organizationId=null) {
        $.ajax({
            method: 'GET',
            url: '/contacts/search',
            data: {
                search: searchTerm,
                page: page,
                folder: folder,
                organizationId: organizationId,
                paginationLength: paginationLength
            },
            success: function(response) {
                $('#contacts-list').html(response.contactsHtml);
                $('#pagination-container').html(response.paginationHtml);
            },
            error: function(xhr) {
                displayError(xhr.statusText)
            }
        });
    }

    function addContactModal(formData=null, validationErrors=null) {
        $.ajax({
            method: 'GET',
            url: "{{ route('contacts.create') }}",
            success: function(response) {
                Swal.fire({
                    title: 'Create Contact',
                    html: response,
                    showCancelButton: true,
                    confirmButtonText: 'Save Changes',
                    cancelButtonText: 'Cancel',
                    customClass: 'custom-alert',
                    preConfirm: function () {
                        addContact()
                    },
                    didOpen: function() {
                        if (formData) {
                            const formDataObj = parseFormData(formData);
                            fillContactInputValues(formDataObj);
                            displayValidationErrors(validationErrors);
                        }
                    }
                })
            },
            error: function(xhr) {
                displayError(xhr.statusText)
            }
        });
    }

    function addFolderModal(formData=null, validationErrors=null) {
        $.ajax({
            method: 'GET',
            url: "{{ route('folders.create') }}",
            success: function(response) {
                Swal.fire({
                    title: 'Create Folder',
                    html: response,
                    showCancelButton: true,
                    confirmButtonText: 'Save Changes',
                    cancelButtonText: 'Cancel',
                    customClass: 'custom-alert',
                    preConfirm: function () {
                        addFolder()
                    },
                    didOpen: function() {
                        if (formData) {
                            const formDataObj = parseFormData(formData);
                            fillContactInputValues(formDataObj);
                            displayValidationErrors(validationErrors);
                        }
                    }
                })
            },
            error: function(xhr) {
                displayError(xhr.statusText)
            }
        });
    }

    function addFolder() {
        let formData = $('#add-folder-form').serialize();

        $.ajax({
            method: 'POST',
            url: "{{ route('folders.store') }}",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {

                if (response.success) {

                    displaySuccess(response.success);
                    loadContacts(selectedSearchTerm, selectedPage, selectedFolder);

                } else if (response.permissionError) {

                    displayError(reponse.permissionError)

                } else if (response.inputError) {

                    addFolderModal(formData, response.messages)

                }
            },
            error: function(xhr) {
                displayError(xhr.statusText);
            }
        });
    }


    function showContactModal(contactId) {
        $.ajax({
            method: 'GET',
            url: "{{ route('contacts.render-contact-view', ['contact' => '__contactId__']) }}".replace('__contactId__', contactId),
            success: function(response) {
                Swal.fire({
                    title: 'Contact Information',
                    html: response,
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Edit',
                    denyButtonText: `Delete`,
                    customClass: 'custom-alert',
                }).then((result) => {
                    if (result.isConfirmed) {

                        editContactModal(contactId)

                    } else if (result.isDenied) {

                        deleteContactModal(contactId)

                    }
                })
            },
            error: function(xhr) {
                displayError(xhr.statusText)
            }
        });
    }

    function editContactModal(contactId, formData=null, validationErrors=null) {
        $.ajax({
            method: 'GET',
            url: "{{ route('contacts.render-contact-edit', ['contact' => '__contactId__']) }}".replace('__contactId__', contactId),
            success: function(response) {
                Swal.fire({
                    title: 'Edit Contact',
                    html: response.html,
                    showCancelButton: true,
                    confirmButtonText: 'Save Changes',
                    cancelButtonText: 'Cancel',
                    customClass: 'custom-alert',
                    preConfirm: function () {
                        let formData = $('#edit-contact-form').serialize();
                        editContact(contactId, formData);
                    },
                    didOpen: function() {
                        if (formData) {
                            const formDataObj = parseFormData(formData);
                            fillContactInputValues(formDataObj);
                            displayValidationErrors(validationErrors);
                        } else {
                            fillContactInputValues(response.formData);
                        }
                    }
                });
            },
            error: function(xhr) {
                displayError(xhr.statusText)
            }
        });
    }

    function editContact(contactId, formData) {
        $.ajax({
            method: 'PUT',
            url: "{{ route('contacts.update', ['contact' => '__contactId__']) }}".replace('__contactId__', contactId),
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    displaySuccess(response.success);
                    loadContacts(selectedSearchTerm, selectedPage, paginationLength, selectedFolder, organizationId);
                } else if (response.permissionError) {
                    displayError(response.permissionError);
                } else if (response.inputError) {
                    editContactModal(contactId, formData, response.messages);
                }
            },
            error: function(xhr) {
                displayError(xhr.statusText);
            }
        });
    }

    // Function to fill input values from form data
    function fillContactInputValues(formDataObj) {
        $.each(formDataObj, function (field, value) {
            if (field === 'belongsToStructure') {
                if (value === 'on') {
                    $('#belongsToStructure').prop('checked', true);
                    $('#structureDropdown').show();
                }
            } else if (field === 'folder_ids[]') {
                if (Array.isArray(value)) {
                    value.forEach(item => {
                        $('input[name="' + field + '"][value="' + item + '"]').prop('checked', true);
                    });
                } else if (value !== null) {
                    $('input[name="' + field + '"][value="' + value + '"]').prop('checked', true);
                }
            } else {
                if (value !== null) {
                    $('#' + field).val(value);
                }
            }
        });
    }

    function addContact() {
        let formData = $('#add-contact-form').serialize();

        $.ajax({
            method: 'POST',
            url: "{{ route('contacts.store') }}",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {

                if (response.success) {

                    displaySuccess(response.success);
                    loadContacts(selectedSearchTerm, selectedPage, selectedFolder);

                } else if (response.permissionError) {

                    displayError(reponse.permissionError)

                } else if (response.inputError) {

                    addContactModal(formData, response.messages)

                }
            },
            error: function(xhr) {
                displayError(xhr.statusText);
            }
        });
    }



    function deleteContact(contactId) {
        $.ajax({
            url: "{{ route('contacts.destroy', ['contact' => '__contactId__']) }}".replace('__contactId__', contactId),
            type: 'POST',
            data: {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                displaySuccess(response.message)
                loadContacts(selectedSearchTerm, selectedPage, paginationLength, selectedFolder)
            },
            error: function(xhr) {
                displayError(xhr.statusText)
            }
        });
    }

    function deleteContactModal(contactId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this contact!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            preConfirm: function () {
                deleteContact(contactId)
            }
        });
    }

    // Show the loading overlay
    function showLoadingOverlay() {
        $('.loading-overlay').fadeIn();
    }

    // Hide the loading overlay
    function hideLoadingOverlay() {
        $('.loading-overlay').fadeOut();
    }

    // Function to parse form data into an object
    function parseFormData(formData) {
        const formDataObj = {};
        formData.split('&').forEach(pair => {
            const [key, value] = pair.split('=');
            const decodedKey = decodeURIComponent(key);
            const decodedValue = decodeURIComponent(value);

            if (!formDataObj.hasOwnProperty(decodedKey)) {
                formDataObj[decodedKey] = decodedValue;
            } else {
                if (!Array.isArray(formDataObj[decodedKey])) {
                    formDataObj[decodedKey] = [formDataObj[decodedKey]];
                }
                formDataObj[decodedKey].push(decodedValue);
            }
        });
        return formDataObj;
    }

    // Function to display validation errors
    function displayValidationErrors(validationErrors) {
        $.each(validationErrors, function(field, errors) {
            $('#' + field).addClass('is-invalid');
            $.each(errors, function(_, error) {
                $('#' + field).after('<div class="invalid-feedback">' + error + '</div>');
            });
        });
    }

</script>
</body>
</html>
