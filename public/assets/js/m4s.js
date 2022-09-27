$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    const lang = getCookie('locale');

    if ($(".datatable")) {
        $(".datatable").each(function (e) {
            $($('.datatable thead tr').get(e))
                .addClass('filters')
                .appendTo($('.datatable thead').get(e));
            $($('.datatable').get(e)).DataTable({
                orderCellsTop: true,
                searching: true,
                fixedHeader: true,
                responsive: true,
                dom: 'Blfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'btnexcell',
                    text: ' <i class="fas fa-file-excel"></i>',
                    titleAttr: 'Excel'
                }],
                "pageLength": 50,
                language: {
                    url: '/assets/DataTables/i18n/' + lang + '.json'
                },
                initComplete: function () {
                    var api = this.api();
                    // for each column
                    api
                        .columns()
                        .eq(0)
                        .each(function (colIdx) {
                            //Set the header cell to contain the input element
                            var cell = $($('.datatable').get(e)).find('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                            // // console.dir($('.filters th'));
                            var title = $(cell).text();
                            $(cell).html('<input class="form-control" type="text" placeholder="' + title + '" />');

                            // On every keypress
                            $('input',
                                $($('.datatable').get(e)).find('.filters th').eq($(api.column(colIdx).header()).index())
                            )
                                .off('keyup change')
                                .on('keyup change', function (e) {
                                    e.stopPropagation();

                                    // Get the search value
                                    $(this).attr('title', $(this).val());
                                    var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                    var cursorPosition = this.selectionStart;
                                    // Search the column for that value
                                    api
                                        .column(colIdx)
                                        .search(
                                            this.value != ''
                                                ? regexr.replace('{search}', '(((' + this.value + ')))')
                                                : '',
                                            this.value != '',
                                            this.value == ''
                                        )
                                        .draw();

                                    $(this)
                                        .focus()[0]
                                        .setSelectionRange(cursorPosition, cursorPosition);
                                });
                        });
                }
            });

        });
    }

    if ($('.inventorytable')) {
        $(".inventorytable").each(function (e) {
            $($('.inventorytable thead tr').get(e))
                .addClass('filters')
                .appendTo($('.inventorytable thead').get(e));
            $($('.inventorytable').get(e)).DataTable({
                orderCellsTop: true,
                searching: true,
                fixedHeader: true,
                responsive: true,
                dom: 'Blfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'btnexcell',
                    text: ' <i class="fas fa-file-excel"></i>',
                    titleAttr: 'Excel'
                }],
                columnDefs: [{
                    orderable: false,
                    targets: [0, 8],
                }],
                "pageLength": 50,
                language: {
                    url: '/assets/DataTables/i18n/' + lang + '.json'
                },
                initComplete: function () {
                    var api = this.api();
                    // for each column
                    api
                        .columns()
                        .eq(0)
                        .each(function (colIdx) {
                            //Set the header cell to contain the input element
                            var cell = $($('.inventorytable').get(e)).find('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                            // // console.dir($('.filters th'));
                            var title = $(cell).text();
                            $(cell).html('<input class="form-control" type="text" placeholder="' + title + '" />');

                            // On every keypress
                            $('input',
                                $($('.inventorytable').get(e)).find('.filters th').eq($(api.column(colIdx).header()).index())
                            )
                                .off('keyup change')
                                .on('keyup change', function (e) {
                                    e.stopPropagation();

                                    // Get the search value
                                    $(this).attr('title', $(this).val());
                                    var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                    var cursorPosition = this.selectionStart;
                                    // Search the column for that value
                                    api
                                        .column(colIdx)
                                        .search(
                                            this.value != ''
                                                ? regexr.replace('{search}', '(((' + this.value + ')))')
                                                : '',
                                            this.value != '',
                                            this.value == ''
                                        )
                                        .draw();

                                    $(this)
                                        .focus()[0]
                                        .setSelectionRange(cursorPosition, cursorPosition);
                                });
                        });
                }
            });

        });
    }

    if ($('.customertable')) {
        $(".customertable").each(function (e) {
            $($('.customertable thead tr').get(e))
                .addClass('filters')
                .appendTo($('.customertable thead').get(e));
            $($('.customertable').get(e)).DataTable({
                orderCellsTop: true,
                searching: true,
                fixedHeader: true,
                responsive: true,
                dom: 'Blfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'btnexcell',
                    text: ' <i class="fas fa-file-excel"></i>',
                    titleAttr: 'Excel'
                }],
                columnDefs: [{
                    orderable: false,
                    searchable: false,
                    targets: [0, 6],
                }],
                "pageLength": 50,
                language: {
                    url: '/assets/DataTables/i18n/' + lang + '.json'
                },
                initComplete: function () {
                    var api = this.api();
                    // for each column
                    api
                        .columns()
                        .eq(0)
                        .each(function (colIdx) {
                            //Set the header cell to contain the input element
                            var cell = $($('.customertable').get(e)).find('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                            // // console.dir($('.filters th'));
                            var title = $(cell).text();
                            $(cell).html('<input class="form-control" type="text" placeholder="' + title + '" />');

                            // On every keypress
                            $('input',
                                $($('.customertable').get(e)).find('.filters th').eq($(api.column(colIdx).header()).index())
                            )
                                .off('keyup change')
                                .on('keyup change', function (e) {
                                    e.stopPropagation();

                                    // Get the search value
                                    $(this).attr('title', $(this).val());
                                    var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                    var cursorPosition = this.selectionStart;
                                    // Search the column for that value
                                    api
                                        .column(colIdx)
                                        .search(
                                            this.value != ''
                                                ? regexr.replace('{search}', '(((' + this.value + ')))')
                                                : '',
                                            this.value != '',
                                            this.value == ''
                                        )
                                        .draw();

                                    $(this)
                                        .focus()[0]
                                        .setSelectionRange(cursorPosition, cursorPosition);
                                });
                        });
                }
            });

        });
    }

    if ($('.fieldservicetable')) {
        $(".fieldservicetable").each(function (e) {
            $($('.fieldservicetable thead tr').get(e))
                .addClass('filters')
                .appendTo($('.fieldservicetable thead').get(e));
            $($('.fieldservicetable').get(e)).DataTable({
                orderCellsTop: true,
                searching: true,
                fixedHeader: true,
                responsive: true,
                dom: 'Blfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'btnexcell',
                    text: ' <i class="fas fa-file-excel"></i>',
                    titleAttr: 'Excel'
                }],
                "pageLength": 50,
                language: {
                    url: '/assets/DataTables/i18n/' + lang + '.json'
                },
                "columnDefs": [{"targets": 6, "type": "date-eu"}, {"targets": 7, "type": "date-eu"}],
                order: [6, 'desc'],
                initComplete: function () {
                    var api = this.api();
                    // for each column
                    api
                        .columns()
                        .eq(0)
                        .each(function (colIdx) {
                            //Set the header cell to contain the input element
                            var cell = $($('.fieldservicetable').get(e)).find('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                            // // console.dir($('.filters th'));
                            var title = $(cell).text();
                            $(cell).html('<input class="form-control" type="text" placeholder="' + title + '" />');

                            // On every keypress
                            $('input',
                                $($('.fieldservicetable').get(e)).find('.filters th').eq($(api.column(colIdx).header()).index())
                            )
                                .off('keyup change')
                                .on('keyup change', function (e) {
                                    e.stopPropagation();

                                    // Get the search value
                                    $(this).attr('title', $(this).val());
                                    var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                    var cursorPosition = this.selectionStart;
                                    // Search the column for that value
                                    api
                                        .column(colIdx)
                                        .search(
                                            this.value != ''
                                                ? regexr.replace('{search}', '(((' + this.value + ')))')
                                                : '',
                                            this.value != '',
                                            this.value == ''
                                        )
                                        .draw();

                                    $(this)
                                        .focus()[0]
                                        .setSelectionRange(cursorPosition, cursorPosition);
                                });
                        });
                }
            });
        });
    }

    if ($("#hireshoptable")) {
        $('#hireshoptable thead tr')
            .addClass('filters')
            .appendTo('#hireshoptable thead');

        var table = $('#hireshoptable').DataTable({
            orderCellsTop: true,
            searching: true,
            fixedHeader: true,
            responsive: true,
            "pageLength": 50,
            language: {
                url: '/assets/DataTables/i18n/' + lang + '.json'
            },
            "columnDefs": [{"targets": 2, "type": "date-eu"}],
            order: [2, 'desc'],
            initComplete: function () {
                var api = this.api();

                //for each column
                api
                    .columns()
                    .eq(0)
                    .each(function (colIdx) {
                        // Set the header cell to contain the input element
                        var cell = $('#hireshoptable .filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();
                        $(cell).html('<input class="form-control" type="text" placeholder="' + title + '" />');

                        // On every keypress in this input
                        $(
                            'input',
                            $('#hireshoptable .filters th').eq($(api.column(colIdx).header()).index())
                        )
                            .off('keyup change')
                            .on('keyup change', function (e) {
                                e.stopPropagation();

                                // Get the search value
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                var cursorPosition = this.selectionStart;
                                // Search the column for that value
                                api
                                    .column(colIdx)
                                    .search(
                                        this.value != ''
                                            ? regexr.replace('{search}', '(((' + this.value + ')))')
                                            : '',
                                        this.value != '',
                                        this.value == ''
                                    )
                                    .draw();

                                $(this)
                                    .focus()[0]
                                    .setSelectionRange(cursorPosition, cursorPosition);
                            });
                    });
            }
        });
    }

    if ($('#deliveryTable')) {
        $("#deliveryTable").each(function (e) {
            $($('#deliveryTable thead tr').get(e))
                .addClass('filters')
                .appendTo($('#deliveryTable thead').get(e));
            $($('#deliveryTable').get(e)).DataTable({
                orderCellsTop: true,
                searching: true,
                fixedHeader: true,
                responsive: true,
                dom: 'Blfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'btnexcell',
                    text: ' <i class="fas fa-file-excel"></i>',
                    titleAttr: 'Excel'
                }],
                "pageLength": 50,
                language: {
                    url: '/assets/DataTables/i18n/' + lang + '.json'
                },
                "columnDefs": [{"targets": 4, "type": "date-eu"}],
                order: [4, 'desc'],
                initComplete: function () {
                    var api = this.api();
                    // for each column
                    api
                        .columns()
                        .eq(0)
                        .each(function (colIdx) {
                            //Set the header cell to contain the input element
                            var cell = $($('#deliveryTable').get(e)).find('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                            // // console.dir($('.filters th'));
                            var title = $(cell).text();
                            $(cell).html('<input class="form-control" type="text" placeholder="' + title + '" />');

                            // On every keypress
                            $('input',
                                $($('#deliveryTable').get(e)).find('.filters th').eq($(api.column(colIdx).header()).index())
                            )
                                .off('keyup change')
                                .on('keyup change', function (e) {
                                    e.stopPropagation();

                                    // Get the search value
                                    $(this).attr('title', $(this).val());
                                    var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                    var cursorPosition = this.selectionStart;
                                    // Search the column for that value
                                    api
                                        .column(colIdx)
                                        .search(
                                            this.value != ''
                                                ? regexr.replace('{search}', '(((' + this.value + ')))')
                                                : '',
                                            this.value != '',
                                            this.value == ''
                                        )
                                        .draw();

                                    $(this)
                                        .focus()[0]
                                        .setSelectionRange(cursorPosition, cursorPosition);
                                });
                        });
                }
            });

        });
    }

    if ($('#extraDevicesTable')) {
        $("#extraDevicesTable").each(function (e) {
            $($('#extraDevicesTable thead tr').get(e))
                .addClass('filters')
                .appendTo($('#extraDevicesTable thead').get(e));
            $($('#extraDevicesTable').get(e)).DataTable({
                orderCellsTop: true,
                searching: true,
                fixedHeader: true,
                responsive: true,
                dom: 'Blfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'btnexcell',
                    text: ' <i class="fas fa-file-excel"></i>',
                    titleAttr: 'Excel'
                }],
                "pageLength": 50,
                language: {
                    url: '/assets/DataTables/i18n/' + lang + '.json'
                },
                // "columnDefs" : [{"targets":4, "type":"date-eu"}],
                // order: [4, 'desc'],
                initComplete: function () {
                    var api = this.api();
                    // for each column
                    api
                        .columns()
                        .eq(0)
                        .each(function (colIdx) {
                            //Set the header cell to contain the input element
                            var cell = $($('#extraDevicesTable').get(e)).find('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                            // // console.dir($('.filters th'));
                            var title = $(cell).text();
                            $(cell).html('<input class="form-control" type="text" placeholder="' + title + '" />');

                            // On every keypress
                            $('input',
                                $($('#extraDevicesTable').get(e)).find('.filters th').eq($(api.column(colIdx).header()).index())
                            )
                                .off('keyup change')
                                .on('keyup change', function (e) {
                                    e.stopPropagation();

                                    // Get the search value
                                    $(this).attr('title', $(this).val());
                                    var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                    var cursorPosition = this.selectionStart;
                                    // Search the column for that value
                                    api
                                        .column(colIdx)
                                        .search(
                                            this.value != ''
                                                ? regexr.replace('{search}', '(((' + this.value + ')))')
                                                : '',
                                            this.value != '',
                                            this.value == ''
                                        )
                                        .draw();

                                    $(this)
                                        .focus()[0]
                                        .setSelectionRange(cursorPosition, cursorPosition);
                                });
                        });
                }
            });

        });
    }

    if ($('#searchResultTable')) {
        $("#searchResultTable").each(function (e) {
            $($('#searchResultTable thead tr').get(e))
                .addClass('filters')
                .appendTo($('#searchResultTable thead').get(e));
            $($('#searchResultTable').get(e)).DataTable({
                orderCellsTop: true,
                searching: false,
                fixedHeader: true,
                responsive: true,
                dom: 'Blfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'btnexcell',
                    text: ' <i class="fas fa-file-excel"></i>',
                    titleAttr: 'Excel'
                }],
                "pageLength": 50,
                language: {
                    url: '/assets/DataTables/i18n/' + lang + '.json'
                },
                initComplete: function () {
                    var api = this.api();
                    // for each column
                    api
                        .columns()
                        .eq(0)
                        .each(function (colIdx) {
                            //Set the header cell to contain the input element
                            var cell = $($('#searchResultTable').get(e)).find('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                            // // console.dir($('.filters th'));
                            var title = $(cell).text();
                            $(cell).html('<input class="form-control" type="text" placeholder="' + title + '" />');

                            // On every keypress
                            $('input',
                                $($('#searchResultTable').get(e)).find('.filters th').eq($(api.column(colIdx).header()).index())
                            )
                                .off('keyup change')
                                .on('keyup change', function (e) {
                                    e.stopPropagation();

                                    // Get the search value
                                    $(this).attr('title', $(this).val());
                                    var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                    var cursorPosition = this.selectionStart;
                                    // Search the column for that value
                                    api
                                        .column(colIdx)
                                        .search(
                                            this.value != ''
                                                ? regexr.replace('{search}', '(((' + this.value + ')))')
                                                : '',
                                            this.value != '',
                                            this.value == ''
                                        )
                                        .draw();

                                    $(this)
                                        .focus()[0]
                                        .setSelectionRange(cursorPosition, cursorPosition);
                                });
                        });
                }
            });

        });
    }

    if ($("#buyTable")) {
        $('#buyTable thead tr')
            .addClass('filters')
            .appendTo('#buyTable thead');

        var table = $('#buyTable').DataTable({
            orderCellsTop: true,
            searching: true,
            fixedHeader: true,
            responsive: true,
            dom: 'Blfrtip',
            buttons: [{
                extend: 'excelHtml5',
                className: 'btnexcell',
                text: ' <i class="fas fa-file-excel"></i>',
                titleAttr: 'Excel'
            }],
            "pageLength": 50,
            language: {
                url: '/assets/DataTables/i18n/' + lang + '.json'
            },
            initComplete: function () {
                var api = this.api();

                //for each column
                api
                    .columns()
                    .eq(0)
                    .each(function (colIdx) {
                        // Set the header cell to contain the input element
                        var cell = $('#buyTable .filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();
                        $(cell).html('<input class="form-control" type="text" placeholder="' + title + '" />');

                        // On every keypress in this input
                        $(
                            'input',
                            $('#buyTable .filters th').eq($(api.column(colIdx).header()).index())
                        )
                            .off('keyup change')
                            .on('keyup change', function (e) {
                                e.stopPropagation();

                                // Get the search value
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                var cursorPosition = this.selectionStart;
                                // Search the column for that value
                                api
                                    .column(colIdx)
                                    .search(
                                        this.value != ''
                                            ? regexr.replace('{search}', '(((' + this.value + ')))')
                                            : '',
                                        this.value != '',
                                        this.value == ''
                                    )
                                    .draw();

                                $(this)
                                    .focus()[0]
                                    .setSelectionRange(cursorPosition, cursorPosition);
                            });
                    });
            }
        });
    }

    if ($("#rentTable")) {
        $('#rentTable thead tr')
            .addClass('filters')
            .appendTo('#rentTable thead');

        var table = $('#rentTable').DataTable({
            orderCellsTop: true,
            searching: true,
            fixedHeader: true,
            responsive: true,
            dom: 'Blfrtip',
            buttons: [{
                extend: 'excelHtml5',
                className: 'btnexcell',
                text: ' <i class="fas fa-file-excel"></i>',
                titleAttr: 'Excel'
            }],
            "pageLength": 50,
            language: {
                url: '/assets/DataTables/i18n/' + lang + '.json'
            },
            initComplete: function () {
                var api = this.api();

                //for each column
                api
                    .columns()
                    .eq(0)
                    .each(function (colIdx) {
                        // Set the header cell to contain the input element
                        var cell = $('#rentTable .filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();
                        $(cell).html('<input class="form-control" type="text" placeholder="' + title + '" />');

                        // On every keypress in this input
                        $(
                            'input',
                            $('#rentTable .filters th').eq($(api.column(colIdx).header()).index())
                        )
                            .off('keyup change')
                            .on('keyup change', function (e) {
                                e.stopPropagation();

                                // Get the search value
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                var cursorPosition = this.selectionStart;
                                // Search the column for that value
                                api
                                    .column(colIdx)
                                    .search(
                                        this.value != ''
                                            ? regexr.replace('{search}', '(((' + this.value + ')))')
                                            : '',
                                        this.value != '',
                                        this.value == ''
                                    )
                                    .draw();

                                $(this)
                                    .focus()[0]
                                    .setSelectionRange(cursorPosition, cursorPosition);
                            });
                    });
            }
        });
    }

    if ($("#bothTable")) {
        $('#bothTable thead tr')
            .addClass('filters')
            .appendTo('#bothTable thead');

        var table = $('#bothTable').DataTable({
            orderCellsTop: true,
            searching: true,
            fixedHeader: true,
            responsive: true,
            dom: 'Blfrtip',
            buttons: [{
                extend: 'excelHtml5',
                className: 'btnexcell',
                text: ' <i class="fas fa-file-excel"></i>',
                titleAttr: 'Excel'
            }],
            "pageLength": 50,
            language: {
                url: '/assets/DataTables/i18n/' + lang + '.json'
            },
            initComplete: function () {
                var api = this.api();

                //for each column
                api
                    .columns()
                    .eq(0)
                    .each(function (colIdx) {
                        // Set the header cell to contain the input element
                        var cell = $('#bothTable .filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();
                        $(cell).html('<input class="form-control" type="text" placeholder="' + title + '" />');

                        // On every keypress in this input
                        $(
                            'input',
                            $('#bothTable .filters th').eq($(api.column(colIdx).header()).index())
                        )
                            .off('keyup change')
                            .on('keyup change', function (e) {
                                e.stopPropagation();

                                // Get the search value
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                var cursorPosition = this.selectionStart;
                                // Search the column for that value
                                api
                                    .column(colIdx)
                                    .search(
                                        this.value != ''
                                            ? regexr.replace('{search}', '(((' + this.value + ')))')
                                            : '',
                                        this.value != '',
                                        this.value == ''
                                    )
                                    .draw();

                                $(this)
                                    .focus()[0]
                                    .setSelectionRange(cursorPosition, cursorPosition);
                            });
                    });
            }
        });
    }

    if ($("#skuTable")) {
        $('#skuTable thead tr')
            .addClass('filters')
            .appendTo('#skuTable thead');

        var table = $('#skuTable').DataTable({
            orderCellsTop: true,
            searching: true,
            fixedHeader: true,
            responsive: true,
            dom: 'Blfrtip',
            buttons: [{
                extend: 'excelHtml5',
                className: 'btnexcell',
                text: ' <i class="fas fa-file-excel"></i>',
                titleAttr: 'Excel'
            }],
            "pageLength": 50,
            language: {
                url: '/assets/DataTables/i18n/' + lang + '.json'
            },
            initComplete: function () {
                var api = this.api();

                //for each column
                api
                    .columns()
                    .eq(0)
                    .each(function (colIdx) {
                        // Set the header cell to contain the input element
                        var cell = $('#skuTable .filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();
                        $(cell).html('<input class="form-control" type="text" placeholder="' + title + '" />');

                        // On every keypress in this input
                        $(
                            'input',
                            $('#skuTable .filters th').eq($(api.column(colIdx).header()).index())
                        )
                            .off('keyup change')
                            .on('keyup change', function (e) {
                                e.stopPropagation();

                                // Get the search value
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})'; //$(this).parents('th').find('select').val();

                                var cursorPosition = this.selectionStart;
                                // Search the column for that value
                                api
                                    .column(colIdx)
                                    .search(
                                        this.value != ''
                                            ? regexr.replace('{search}', '(((' + this.value + ')))')
                                            : '',
                                        this.value != '',
                                        this.value == ''
                                    )
                                    .draw();

                                $(this)
                                    .focus()[0]
                                    .setSelectionRange(cursorPosition, cursorPosition);
                            });
                    });
            }
        });
    }

    var academic_year = getCookie('academic_year');
    if (academic_year != "") {
        // Nothing to do if the academic_year cookie is set
    } else {
        var date = new Date();
        // if(date <= Date.parse('30/06/2022') && date >= Date.parse('01/07/2021')) {
        //     console.log('ici');
        //     setCookie('academic_year', ( (date.getFullYear()) + "-" + date.getFullYear() + 1), 1);
        // }
        if (date.getMonth() >= 7) {
            setCookie('academic_year', ((date.getFullYear()) + "-" + (date.getFullYear() + 1)), 1);
        } else {
            setCookie('academic_year', ((date.getFullYear() - 1) + "-" + date.getFullYear()), 1);
        }
        // console.log("academic_year need to be set");
    }

    if ($('.select-institution')) {
        $('.selectpicker').selectpicker({
            container: 'container',
            dropdownAlignRight: true
        })
        $('.select-institution').on('changed.bs.select', function (e, clickedIndex, newValue, oldValue) {
            // check if value is undifined or not
            if (this.value !== undefined) {
                var id = this.value.split("/");
                // console.log(id[id.length - 1]);
                if (this.value.includes('institutionlocation/')) {
                    setInstitutionLocationCookie(id[id.length - 1]);
                    removeInstitutionCookie();
                }
                if (this.value.includes('institution/')) {
                    setInstitutionCookie(id[id.length - 1]);
                    removeInstitutionLocationCookie();
                }
                location.href = window.location.origin + this.value;
                $('.selectpicker').selectpicker({
                    container: 'container',
                    dropdownAlignRight: true
                })
            }
        });
        if (getCookie('location_id') != '') {
            $('select[name=selValue]').val('/institutionlocation/' + getCookie('location_id'));
        }
        if (getCookie('institution_id') != '') {
            $('select[name=selValue]').val('/institution/' + getCookie('institution_id'));
        }
        $('.selectpicker').selectpicker('refresh');
    }

    if ($("#devicesAssignedCheckAll")) {
        $("#devicesAssignedCheckAll").change(function () {
            $("#dataWithUserTable td input:checkbox").each(function (e) {
                $($("#dataWithUserTable td input:checkbox").get(e)).prop('checked', $("#devicesAssignedCheckAll").is(":checked"));
            });
        });
    }

    if ($("#devicesNotAssignedCheckAll")) {
        $("#devicesNotAssignedCheckAll").change(function () {
            $("#dataWithoutUserTable td input:checkbox").each(function (e) {
                $($("#dataWithoutUserTable td input:checkbox").get(e)).prop('checked', $("#devicesNotAssignedCheckAll").is(":checked"));
            });
        });
    }

    if ($("#nolocationDevicesCheckAll")) {
        $("#nolocationDevicesCheckAll").change(function () {
            $("#nolocationDevicesTable td input:checkbox").each(function (e) {
                $($("#nolocationDevicesTable td input:checkbox").get(e)).prop('checked', $("#nolocationDevicesCheckAll").is(":checked"));
            });
        });
    }

    if ($("#extraDevicesCheckAll")) {
        $("#extraDevicesCheckAll").change(function () {
            $("#extraDevicesTable td input:checkbox").each(function (e) {
                $($("#extraDevicesTable td input:checkbox").get(e)).prop('checked', $("#extraDevicesCheckAll").is(":checked"));
            });
        });
    }

    if ($("#customerTableCheckAll")) {
        $("#customerTableCheckAll").change(function () {
            $("#customerTable td input:checkbox").each(function (e) {
                $($("#customerTable td input:checkbox").get(e)).prop('checked', $("#customerTableCheckAll").is(":checked"));
            });
        });
    }

    if ($("#customerNoLocationTableCheckAll")) {
        $("#customerNoLocationTableCheckAll").change(function () {
            $("#customerNoLocationTable td input:checkbox").each(function (e) {
                $($("#customerNoLocationTable td input:checkbox").get(e)).prop('checked', $("#customerNoLocationTableCheckAll").is(":checked"));
            });
        });
    }

    if ($('.custom-file input')) {
        console.log("found id");
        $('.custom-file input').change(function (e) {
            if (e.target.files.length) {
                $(this).next('.custom-file-label').html(e.target.files[0].name);
            }
        });
    }

});

function getSelect(sel) {
    setCookie("academic_year", sel.value, 1);
    location.reload();
}

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function setInstitutionCookie(schoolId) {
    setCookie("institution_id", schoolId, 1);
}

function removeInstitutionCookie() {
    setCookie("institution_id", "", 0);
}

function setInstitutionLocationCookie(locationid) {
    setCookie('location_id', locationid, 1);
}

function removeInstitutionLocationCookie() {
    setCookie('location_id', "", 0);
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function copyToClipboard(value) {
    navigator.clipboard.writeText(value).then(function () {
        console.log('Async: Copying to clipboard was successful!');
    }, function (err) {
        console.error('Async: Could not copy text: ', err);
    });
}

