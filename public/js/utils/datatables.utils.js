$.fn.dataTable.ext.search.push((settings, data) => {
    let dataItem = $(".filter-bar > button.active").data();
    if (!!dataItem && (dataItem.filterEval !== undefined || ( dataItem.filterValue !== undefined && dataItem.filterColumn !== undefined )))
        if(dataItem.filterEval !== undefined)
            return eval(dataItem.filterEval);
        else if(dataItem.filterValue !== undefined)
            return data[dataItem.filterColumn] === dataItem.filterValue;
    return true;
});


function addBtnFilter(topElement, tableApi, filters) {
    $(topElement).find(".dataTables_length").css({"margin-right": "auto"});
    let filterBar = $(topElement).find(".filter-bar");
    filterBar.addClass("btn-group btn-group-sm");
    filterBar.append('<button class="btn btn-filter active">All Entries</button>');
    filters.forEach(filter => {
        let btn = $('<button class="btn btn-filter">'+filter.title+'</button>');
        btn.attr({
            "data-filter-column": (filter.column!==undefined)?filter.column:null,
            "data-filter-value": (filter.value!==undefined)?filter.value:null,
            "data-filter-eval": (filter.eval!==undefined)?filter.eval:null
        });
        filterBar.append(btn);
    });
    let btns = filterBar.find(".btn-filter");
    btns.on('click', e => {
        let target = $(e.target);
        if (target.hasClass("active"))
            return;
        btns.removeClass("active");
        target.addClass("active");
        tableApi.draw();
    });
}