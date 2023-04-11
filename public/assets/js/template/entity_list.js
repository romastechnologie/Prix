"use strict";

// Class definition
var KTDatatablesServerSide = function () {
    // Shared variables
    var table;
    var dt;
    var entityName;
    // Private functions
    var initDatatable = function () {
        if(tableau != null)
        {
            let columns = [];
            entityName = tableau.dataset.entityName;
            const viewColumns = tableau.dataset.tableColumns.split(';');
            let cpt = 0;
            for (let i = 0; i < viewColumns.length; i++) {
                if(!viewColumns[i].includes('#'))
                    columns.push({data: viewColumns[i]});
                else
                {
                    cpt++;
                    const pos = viewColumns[i].indexOf("#");
                    let newCol = viewColumns[i].slice(pos + 1);
                    let tab = newCol.split('#');
                    let all = "";
                    for (let j = 0; j < tab.length; j++) {
                        all += tab[j].toLowerCase() + (j !== (tab.length-1) ? "_" : "");
                    }
                    all += '_' + cpt;
                    columns.push({data: all});
                }
            }
            columns.push({data: null});
            dt = $(tableau).DataTable({
                searchDelay: 500,
                processing: true,
                serverSide: true,
                order: [[0, 'desc']],
                stateSave: true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child input[type="checkbox"]',
                    className: 'row-selected'
                },
                ajax: {
                    url: tableau.dataset.url,
                    data: {champs: viewColumns},
                },
                columns: columns,
                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        render: function (data) {
                            return `
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input kt_entity_checkbox" type="checkbox" value="${data}" />
                            </div>`;
                        }
                    },
                    {
                        targets: -1,
                        data: null,
                        orderable: false,
                        className: 'text-end',
                        render: function (data, type, row) {
                            return data.action;
                        },
                    },
                ],
                language: {
                    emptyTable: "<span style='font-style:italic'>Aucun enregistrement trouvé.</span>",
                },
                // Add data-filter attribute
                createdRow: function (row, data, dataIndex) {
                    /*$(row).find('td:eq(4)').attr('data-filter', data.CreditCardType);*/
                }
            });
            datatable = dt;
            table = dt.$;

            // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
            dt.on('draw', function () {
                initToggleToolbar();
                toggleToolbars();
                handleEditRows();
                handleDeleteRows();
                KTMenu.createInstances();
            });
        }
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector('[data-kt-entity-table-filter="search"]');
        if(filterSearch != null)
        {
            filterSearch.addEventListener('keyup', function (e) {
                dt.search(e.target.value).draw();
            });
        }
    }

    // Edit entity
    var handleEditRows = () => {
        // Select all delete buttons
        const editButtons = document.querySelectorAll('[data-kt-entity-table-filter="edit_row"]');
        const container = document.querySelector('[data-call-form="ajax"]');
        if(container != null)
        {
            editButtons.forEach(d => {
                // Delete button on click
                d.addEventListener('click', function (e) {
                    e.preventDefault();
                    // Select parent row
                    const parent = e.target.closest('tr');
                    /*const table = parent.closest('table');
                    const entityField = parent.querySelectorAll('td')[2].innerText;*/
                    $.ajax({
                        type: "GET",
                        url: e.target.dataset.url,
                        dataType: "json",
                        beforeSend: function () {
                            const loadingEl = document.createElement("div");
                            document.body.prepend(loadingEl);
                            loadingEl.classList.add("page-loader");
                            loadingEl.classList.add("flex-column");
                            loadingEl.classList.add("bg-dark");
                            loadingEl.classList.add("bg-opacity-25");
                            loadingEl.innerHTML = `
                                            <span class="spinner-border text-primary" role="status"></span>
                                            <span class="fs-6 fw-semibold mt-5" style="color:#ffffff">Chargement...</span>
                                        `;
                            document.body.classList.add('page-loading');
                            document.body.setAttribute('data-kt-app-page-loading', "on")
                        },
                        success: function(data){
                            $(container).html(data.edit);
                            const form = container.querySelector('form');
                            form.querySelectorAll('select').forEach(s => {
                                $(s).select2();
                            });
                            const btn = container.querySelector('button[type="submit"]');
                            btn.addEventListener("click", function(e){
                                e.preventDefault();
                                Addandedit($(form), container, data.new);
                            });
                            KTWizard.init();
                            KTAll.init();
                        },
                        complete: function () {
                            document.body.classList.remove('page-loading');
                            document.body.removeAttribute('data-kt-app-page-loading');
                        }
                    });
                })
            });
        }
    }

    // Delete entity
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = document.querySelectorAll('[data-kt-entity-table-filter="delete_row"]');
        if(deleteButtons != null && tableau != null)
        {
            deleteButtons.forEach(d => {
                // Delete button on click
                d.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Select parent row
                    const parent = e.target.closest('tr');
                    const table = parent.closest('table');

                    // Get customer name
                    const entityField = parent.querySelectorAll('td')[2].innerText;
                    $.ajax({
                        type: "POST",
                        url: tableau.dataset.url,
                        data: {id: e.target.dataset.id},
                        dataType: "json",
                        success: function(data){
                            e.target.dataset.token = data.token;
                        }
                    });
                    // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                    Swal.fire({
                        text: "Etes-vous sûr de vouloir supprimer " + entityField + "?",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Oui, supprimer!",
                        cancelButtonText: "Non, annuler",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                    }).then(function (result) {
                        if (result.value) {
                            // Simulate delete request -- for demo purpose only
                            Swal.fire({
                                text: "Suppression de " + entityField,
                                icon: "info",
                                buttonsStyling: false,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(function () {
                                Swal.fire({
                                    text: "Vous avez supprimé " + entityField + "!.",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    }
                                }).then(function () {
                                    // delete row data from server and re-draw datatable
                                    $.ajax({
                                        type: "GET",
                                        url: e.target.dataset.url,
                                        data: {token: e.target.dataset.token},
                                        dataType: "json",
                                        success: function(data){
                                            dt.row($(parent)).remove().draw();
                                        }
                                    });
                                    /*datatable.row($(parent)).remove().draw();*/
                                    //dt.draw();
                                });
                            });
                        } else if (result.dismiss === 'cancel') {
                            Swal.fire({
                                text: entityField + " n'a pas été supprimé.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            });
                        }
                    });
                })
            });
        }
    }

    // Init toggle toolbar
    var initToggleToolbar = function () {
        // Toggle selected action toolbar
        // Select all checkboxes
        if(tableau != null)
        {
            const checkboxes = container.querySelectorAll('.kt_entity_checkbox');

            // Select elements
            const deleteSelected = document.querySelector('[data-kt-entity-table-select="delete_selected"]');

            // Toggle delete selected toolbar
            checkboxes.forEach(c => {
                // Checkbox on click event
                c.addEventListener('click', function () {
                    setTimeout(function () {
                        toggleToolbars();
                    }, 50);
                });
            });

            // Deleted selected rows
            deleteSelected.addEventListener('click', function () {
                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Etes-vous sûr de vouloir supprimer les " + entityName + "(s) sélectionné(s)?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    showLoaderOnConfirm: true,
                    confirmButtonText: "Oui, supprimer!",
                    cancelButtonText: "Non, annuler",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    },
                }).then(function (result) {
                    if (result.value) {
                        // Simulate delete request -- for demo purpose only
                        Swal.fire({
                            text: "Suppression des " + entityName + "(s) sélectionnés",
                            icon: "info",
                            buttonsStyling: false,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(function () {
                            Swal.fire({
                                text: "Vous avez supprimé tous les " + entityName + "(s) sélectionné(s)!.",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            }).then(function () {
                                // delete row data from server and re-draw datatable
                                dt.draw();
                            });

                            // Remove header checked box
                            const headerCheckbox = container.querySelectorAll('.kt_entity_checkbox')[0];
                            headerCheckbox.checked = false;
                        });
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: "Les " + entityName + "(s) sélectionné(s) n'ont pas été supprimés.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        });
                    }
                });
            });
        }
    }

    // Toggle toolbars
    var toggleToolbars = function () {
        if(tableau != null)
        {
            // Define variables
            const toolbarBase = document.querySelector('[data-kt-entity-table-toolbar="base"]');
            const toolbarSelected = document.querySelector('[data-kt-entity-table-toolbar="selected"]');
            const selectedCount = document.querySelector('[data-kt-entity-table-select="selected_count"]');

            // Select refreshed checkbox DOM elements
            const allCheckboxes = tableau.querySelectorAll('tbody .kt_entity_checkbox');

            // Detect checkboxes state & count
            let checkedState = false;
            let count = 0;

            // Count checked boxes
            allCheckboxes.forEach(c => {
                if (c.checked) {
                    checkedState = true;
                    count++;
                }
            });

            // Toggle toolbars
            if (checkedState) {
                selectedCount.innerHTML = count;
                toolbarBase.classList.add('d-none');
                toolbarSelected.classList.remove('d-none');
            } else {
                toolbarBase.classList.remove('d-none');
                toolbarSelected.classList.add('d-none');
            }
        }
    }

    // Public methods
    return {
        init: function () {
            initDatatable();
            handleSearchDatatable();
            initToggleToolbar();
            handleEditRows();
            handleDeleteRows();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesServerSide.init();
});