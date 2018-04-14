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
