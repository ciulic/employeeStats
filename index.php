<?php include('setup.php');?>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" href="<?php echo URL_HTTP;?>css/kendo.common.min.css" />
        <link rel="stylesheet" href="<?php echo URL_HTTP;?>css/kendo.default.min.css" />
        <link rel="stylesheet" href="<?php echo URL_HTTP;?>css/kendo.dataviz.min.css" />
        <link rel="stylesheet" href="<?php echo URL_HTTP;?>css/kendo.dataviz.default.min.css" />
        <link rel="stylesheet" href="<?php echo URL_HTTP;?>css/main.css" />

        <script type="text/javascript" src="<?php echo URL_HTTP;?>js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo URL_HTTP;?>js/kendo.all.min.js"></script>
        <script type="text/javascript">
            var URL_HTTP = '<?php echo URL_HTTP;?>';
        </script>
    </head>
    <body>
        <button id="employeeContainer">Add Employee</button>
        <div id="grid"></div>
        <div id="window">
            <form id="entryForm">
                <input type="hidden" name="EID" id="EID" value="">
                <table>
                    <tr>
                        <td>Date</td>
                    </tr>
                    <tr>
                        <td><input type="text" name="date" id="datePicker" value="" /></td>
                    </tr>
                    <tr>
                        <td>Enter Time</td>
                    </tr>
                    <tr>
                        <td><input type="text" name="enterTime" id="enter" class="timePicker" value="" /></td>
                    </tr>
                    <tr>
                        <td>Leave Time</td>
                    </tr>
                    <tr>
                        <td><input type="text" name="leaveTime" id="leave" class="timePicker" value="" /></td>
                    </tr>
                    <tr>
                        <td><button id="saveEntry">SAVE</button></td>
                    </tr>
                </table>
            </form>
        </div>
        <div id="Ewindow">
            <form id="employeeForm">
                <table>
                    <tr>
                        <td>First Name</td>
                    </tr>
                    <tr>
                        <td><input type="text" name="firstName" id="firstName" value="" /></td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                    </tr>
                    <tr>
                        <td><input type="text" name="lastName" id="lastName" value="" /></td>
                    </tr>
                    <tr>
                        <td>Title</td>
                    </tr>
                    <tr>
                        <td><input type="text" name="title" id="title" value="" /></td>
                    </tr>
                    <tr>
                        <td><button id="saveEmployee">SAVE</button></td>
                    </tr>
                </table>
            </form>
        </div>
        <script type="text/x-kendo-template" id="template">
            <div class="tabstrip">
                <ul>
                    <li class="k-state-active">Statistics</li>
                </ul>
                <div>
                    <div class="statistics">
                        <button class="entryContainer" eid="0">Add Entry</button>
                    </div>
                </div>
            </div>
        </script>
        
        <script type="text/javascript">
            $(document).ready(function () {
                var element, subElement = null;
                element = $("#grid").kendoGrid({
                    dataSource: {
                        type: "json",
                        transport: {
                            read: URL_HTTP + 'employees.php',
                            update: URL_HTTP + 'employees.php'
                        },
                        schema:{
                            data: "rows"
                        },
                        pageSize: 20,
                        serverPaging: false,
                        serverSorting: false
                    },
                    height: 550,
                    sortable: true,
                    pageable: false,
                    detailTemplate: kendo.template($("#template").html()),
                    detailInit: detailInit,
                    dataBound: function () {
                        //this.expandRow(this.tbody.find("tr.k-master-row").first());
                    },
                    columns: [{
                            field: "employeeID",
                            title: "ID",
                            width: "50px"
                        }, {
                            field: "firstName",
                            title: "First Name",
                            width: "120px"
                        }, {
                            field: "lastName",
                            title: "Last Name",
                            width: "120px"
                        }, {
                            field: "title",
                            title: "Title",
                        }
                    ],
                    detailExpand: function(e) {
                        if (typeof Window !== 'undefined') {
                            Window.data("kendoWindow").close();
                        }
                    },
                    detailCollapse: function(e) {
                        if (typeof Window !== 'undefined') {
                            Window.data("kendoWindow").close();
                        }
                    }
                });
                
                var Window = $("#window");
                Window.kendoWindow({
                    title: 'Add New Entry',
                    visible: false,
                    width: '300px',
                    height: '250px',
                    actions: [
                        'Close'
                    ]
                });
                
                var lastClickedEntryContainer;
                $(document).on('click', '.entryContainer', function() {
                    var EID = $(this).attr('eid');
                    
                    $('#EID').val(EID);
                    lastClickedEntryContainer = $(this);
                    
                    var currentDate = new Date();
                    Window.find('#datePicker').val(currentDate.getFullYear() + '-' + (currentDate.getMonth() + 1) + '-' + currentDate.getUTCDate());
                    Window.data("kendoWindow").open().center();
                });
                
                $('#datePicker').kendoDatePicker({
                    format: "yyyy-MM-dd",
                });
                
                $('.timePicker').kendoTimePicker({
                    format: "HH:mm"
                });
                
                $(document).on('click', '#saveEntry', function(e) {
                    e.preventDefault();
                    
                    var inputs = $('#entryForm input');
                    var inputsValues = [];
                    var _continue = true;
                    
                    $.each(inputs, function() {
                        if ($(this).val() == '') {
                            _continue = false;
                            $(this).css({
                                'border': '1px solid red'
                            });
                        } else {
                            inputsValues[$(this).attr('id')] = $(this).val();
                            
                            $(this).css({
                                'border': '1px solid gray'
                            });
                        }
                    });
                    
                    var enterTime = new Date('01/01/2015 ' + inputsValues['enter'] + ':00');
                    var leaveTime = new Date('01/01/2015 ' + inputsValues['leave'] + ':00');
                    
                    if (enterTime > leaveTime) {
                        _continue = false;
                        
                        $('#enter, #leave').css({
                            border: '1px solid red'
                        });
                    }
                    
                    if (_continue) {
                        var grid = lastClickedEntryContainer.closest(".statistics").data("kendoGrid");
                        
                        $.post(URL_HTTP + 'statistics.php', $('#entryForm').serialize(), function(response) {
                            if (response.error) {
                                
                            } else if (response.success) {
                                grid.dataSource.insert({
                                    date: response.success.date,
                                    hours: response.success.hours,
                                    enter: response.success.enterTime,
                                    leave: response.success.leaveTime
                                });
                                
                                Window.data("kendoWindow").close();
                            }
                        });
                    }
                });
                
                var EWindow = $("#Ewindow");
                EWindow.kendoWindow({
                    modal: true,
                    title: 'Add New Employee',
                    visible: false,
                    width: '300px',
                    height: '250px',
                    actions: [
                        'Close'
                    ]
                });
                
                $('#employeeContainer').on('click', function() {
                    $('#employeeForm input').val('');
                    
                    EWindow.data("kendoWindow").open().center();
                });
                
                $(document).on('click', '#saveEmployee', function(e) {
                    e.preventDefault();
                    
                    var inputs = $('#employeeForm input');
                    var inputsValues = [];
                    var _continue = true;
                    
                    $.each(inputs, function() {
                        if ($(this).val() == '') {
                            _continue = false;
                            $(this).css({
                                'border': '1px solid red'
                            });
                        } else {
                            inputsValues[$(this).attr('id')] = $(this).val();
                            
                            $(this).css({
                                'border': '1px solid gray'
                            });
                        }
                    });
                    
                    if (_continue) {
                        var grid = $('#grid').data("kendoGrid");
                        
                        $.post(URL_HTTP + 'employees.php', $('#employeeForm').serialize(), function(response) {
                            if (response.error) {
                                
                            } else if (response.success) {
                                grid.dataSource.insert({
                                    employeeID: response.success.employeeID,
                                    firstName: response.success.firstName,
                                    lastName: response.success.lastName,
                                    title: response.success.title
                                });
                                
                                EWindow.data("kendoWindow").close();
                            }
                        });
                    }
                })
            });
        </script>
        
        <script type="text/javascript" src="<?php echo URL_HTTP;?>js/functions.js"></script>
    </body>
</html>