function detailInit(e) {
    var detailRow = e.detailRow;
    
    detailRow.find(".tabstrip").kendoTabStrip({
        animation: {
            open: {effects: "fadeIn"}
        }
    });
    
    detailRow.find('.entryContainer').attr('eid', e.data.employeeID);
    
    detailRow.find(".statistics").kendoGrid({
        dataSource: {
            transport: {
                read: {
                    url: URL_HTTP + 'statistics.php?EID=' + e.data.employeeID,
                    type: "json",
                }
            },
            schema: {
                model: {
                    fields: {
                        date: {type: "date",},
                        hours: {type: "number"},
                        enter: {type: "string"},
                        leave: {type: "string"}
                    }
                },
                parse: function(response) {
                    var items = [];
                    
                    if (typeof response.dates !== 'undefined' && Object.keys(response.dates).length > 0) {
                        
                        for (var i in response.dates[0]) {
                            var dateItem = response.dates[0][i];
                            
                            for (var j in dateItem.entries) {
                                var timeItem = dateItem.entries[j];

                                var item = {
                                    date: dateItem.date,
                                    hours: dateItem.hours,
                                    enter: timeItem.enterTime,
                                    leave: timeItem.leaveTime
                                };
                                items.push(item);
                            }
                        }
                    }
                    
                    return items;
                }
            }
        },
        sortable: true,
        scrollable: false,
        pageable: false,
        columns: [
            {
                field: "date", 
                title: "Date", 
                width: "100px", 
                format: "{0:dd MMM yyyy}",
            }, {
                field: "hours", 
                title: "Hours No.", 
                width: "50px"
            }, {
                field: "enter", 
                title: "Enter Time", 
                width: "50px"
            }, {
                field: "leave", 
                title: "Leave Time", 
                width: "50px"
            },
        ]
    });
}