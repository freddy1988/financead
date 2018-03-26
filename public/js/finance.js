$(document).ready(()=>{
    initTabs($(".tabs-component"));
});

function initTabs(element){
    element = $(element);
    let data = element.data();
    let tabs = element.find(".tabs-heading > a[href]");
    let activeTab = (data&&data.active !== undefined)?$(tabs[Number(data.active)]):null;
    element.find(".tabs-body > div").css({display:"none"});
    if(activeTab !== undefined && activeTab !== null){
        element.find(".tabs-body > " + activeTab.attr("href")).css({display:"flex"});
        activeTab.addClass("active");
    }
    tabs.on("click",e=>{
        if(e)
            e.preventDefault();
        let target = $(e.target);
        element.find(".tabs-body > " + activeTab.attr("href")).css({display:"none"});
        element.find(".tabs-body > " + target.attr("href")).css({display:"flex"});
        activeTab.removeClass("active");
        target.addClass("active");
        activeTab = target;
    });
}