$("button[type=submit]").click(function(e){
    if(e && e.preventDefault){
        e.preventDefault();
    }else{
        window.event.returnValue = false;
    }
    url =  $("form[name='post-form']")[0].action;
    if(!url){
        alert('action is null');
        return;
    }
    serialize_data = $("form[name='post-form']").serialize();
    $.ajax({
        type: 'post',
        url: url,
        data: serialize_data,
        dataType: "json",
        success: function(data) {
            if(data.status){
                if(data.redirect){
                    location.href=data.redirect;
                    return;
                }else{
                    alert(data.message);
                }
            }else{
                alert(data.message);
            }
        }
    });
});
$(".deleteBtn").click(function(e){
    var url = this.attr('action');
    if(url.length > 0){
        $.ajax({
            type: 'get',
            url: url,
            dataType: "json",
            success: function(data) {
                if(data.status){
                    if(data.redirect){
                        location.href=data.redirect;
                        return;
                    }else{
                        alert(data.message);
                    }
                }else{
                    alert(data.message);
                }
            }
        });
    }
});
