function addUrl()
{
    var new_div = document.createElement('div');
    new_div.classList.add("form-group");
    new_div.innerHTML = '<label class="col-sm-2 control-label no-padding-right">' +
            '<label for="site_url" class="required">URL</label>' +
            '<span class="required">*</span>' +
        '</label>' +
        '<div class="col-sm-10">' +
            '<input type="text" class="input-xxlarge" name="url[]" required="required" maxlength="255"> ' +
            '<a class="red" href="#" onclick="removeUrl(this);">' +
                '<i class="icon-trash bigger-130"></i>' +
            '</a>' +
        '</div>';
    document.getElementById('url-form').appendChild(new_div);
}

function removeUrl(e)
{
    e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
}



function getChanges() {
    var ids = [];
    $('input[name="idx[]"]').each(function () {
        ids.push(parseInt($(this).val()));
    });
    $.ajax({
        type: "POST",
        url: Routing.generate("withdraw_site_get_changes"),
        data: {
            'ids': ids
        },
        dataType: "json",
    }).done(function (data) {
        setChanges(data);
    }).always(function () {
        setTimeout(function () {
            getChanges();
        }, 3000);
    });

}

function addSiteInstantly() {
    url = $("#url-field").val();
    $("#url-field, #url-btn").attr("disabled", "disabled");
    $.ajax({
        type: "POST",
        url: Routing.generate("withdraw_site_new"),
        data: {
            'url[]': url
        },
        dataType: "json",
    }).done(function (data) {
        newSite(data);
    }).always(function () {
        $("#url-field, #url-btn").removeAttr("disabled");
        $("#url-field").val('').focus();
    });
}


function setChanges(data) {
    for (i = 0; i < data.length; i++) {
        id = data[i].id;
        $('td[data-url="' + id + '"]').html(data[i].url);
        $('td[data-status="' + id + '"]').html(data[i].status);
        $('td[data-title="' + id + '"]').html(data[i].title);
        $('td[data-ex-links-count="' + id + '"]').html(data[i].ex_links_count);
        $('td[data-ga-is-exist="' + id + '"]').html(data[i].ga_is_exist);
    }
}



function newSite(data) {
    newUrl = $('#url-row').html()
        .replace(/replace-with-id/g, data.id)
        .replace(/replace-with-url/g, data.url)
        .replace(/replace-with-createdAt/g, data.createdAt);
    $('.gridForm tbody tr:first').before('<tr>'+newUrl+'</tr>');
}
