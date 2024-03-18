@extends('layouts.app')

@section('content')
    <div class="file-explorer">
        <div class="file-explorer-sidebar">
            <div class="create-folder mb-4 d-flex">
                <button class="btn btn-primary float-start add-folder-btn">
                    <i class="fas fa-plus"></i> Ajouter Dossier
                </button>
            </div>
            <div class="folder-list">
                @if(count($folders) > 0)
                    @foreach($folders as $folder)
                        <div class="folder-item">
                            <a class="nav-link folder-tab"
                               id="folder_tab_{{ $folder->id }}"
                               href="#"
                               data-toggle="tab"
                               role="tab"
                               data-folder-id="{{ $folder->id }}">
                                <i class="fas fa-folder"></i>
                                {{ $folder->name }}
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="file-explorer-content">
            <div class="folder-content">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Contacts</span>
                                    <button id="contactsAddButton" class="btn btn-sm btn-primary">Ajouter</button>
                                </div>
                                <div class="mt-3 input-group">
                                    <input type="text" id="search-input" class="form-control" placeholder="Rechercher par nom, mail, ou numéro de téléphone...">
                                    <div class="input-group-append">
                                        <button id="clear-search" class="btn btn-sm btn-outline-secondary" style="display: none;"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body fixed-card-body" id="contacts-list">
                                <!-- Contacts table will be loaded dynamically here -->
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactInfoModalLabel" aria-hidden="true">
                    <!-- Modal will we loaded dynamically here -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>

        $(document).ready(function () {

            paginationLength = 11
            selectedFolder = $('.folder-tab:first').text().trim();


            clickDefaultFolder()

            // Initial load when the page loads
            loadContacts(selectedSearchTerm, selectedPage, paginationLength, selectedFolder);

            // Show/hide "X" button and clear search input when clicked
            $('#search-input').on('input', function() {
                updateSelectedSearchTerm(($(this).val()));
            });

            $(document).on('click', '.folder-tab', function (event) {
                event.preventDefault();

                // Remove the 'active' class from all folder tabs
                $('.folder-tab').removeClass('active');

                // Add the 'active' class to the clicked folder tab
                $(this).addClass('active');

                updateSelectedFolder($(this).data('folder-id'));
                updateSelectedPage(1);
                loadContacts(selectedSearchTerm, selectedPage, paginationLength, selectedFolder);
            });

            $(document).on('click', '.add-folder-btn', function () {
                addFolderModal()
            })

            $(document).on('change', '#belongsToStructure', function() {
                if ($(this).prop('checked')) {
                    $('#structureDropdown').show();
                } else {
                    $('#structureDropdown').hide();
                }
            });

            $(document).on('click', '#contactsAddButton', function() {

                addContactModal()

            })

            function updateSelectedFolder(newFolder) {
                selectedFolder = newFolder;
                $(document).trigger('selectedFolderChanged', newFolder);
            }

            function clickDefaultFolder() {
                showLoadingOverlay()
                setTimeout(function () {
                    $('.folder-tab:first').click();
                    hideLoadingOverlay()
                }, 0.5)
            }
        });


    </script>
@endsection

@section('style')

    <style>

        .file-explorer {
            display: flex;
            height: 80vh;
            border: 1px solid #ddd;
            background-color: #f5f5f5;
        }

        .file-explorer-sidebar {
            width: 20%;
            padding: 20px;
            background-color: #fff;
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        .folder-list {
            list-style: none;
            padding: 0;
        }

        .folder-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            cursor: pointer;
        }

        .folder-item i {
            margin-right: 10px;
        }

        .file-explorer-content {
            flex: 1;
            padding: 20px;
            background-color: #fff;
            box-shadow: -2px 0 4px rgba(0, 0, 0, 0.1);
        }

        .create-folder {
            margin-top: 20px;
            text-align: center;
        }

        .create-folder button {
            border-radius: 100px;
        }

        .fixed-card-body {
            height: 610px;
            overflow-y: auto;
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

        .info-label {
            flex: 1;
            font-weight: 600;
        }

        .info-value {
            flex: 2;
        }

        .input-group {
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

        .contact-details {
            width: 100%;
        }

    </style>

@endsection