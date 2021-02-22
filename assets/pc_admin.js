jQuery(document).ready(function ($) {

    // add comparison tabs
    $('body').on('click', 'button.sbwc-pc-add-data', function (e) {
        e.preventDefault();

        var html = '<div class="sbwc-pc-input-outer-cont">';
        html += '<div class="sbwc-pc-input-cont">';
        html += '<label for="sbwc_pc_data_title">Data title</label>';
        html += '<input type="text" name="sbwc_pc_data_title[]" class="sbwc_pc_data_title">';
        html += '</div> <div class="sbwc-pc-input-cont">';
        html += '<label for="sbwc_pc_data_content">Data content</label>';
        html += '<input type="text" name="sbwc_pc_data_content[]" class="sbwc_pc_data_content">';
        html += '<div class="sbwc-pc-add-rem-cont">';
        html += '<button class="sbwc-pc-add-data" title="Add">+</button> ';
        html += '<button class="sbwc-pc-rem-data" title="Remove">-</button>';
        html += '</div>';
        html += '</div >';
        html += '</div >';

        $(html).insertBefore('button#sbwc-pc-save-data');

    });

    // remove comparison tabs
    $('body').on('click', 'button.sbwc-pc-rem-data', function (e) {
        e.preventDefault();
        $(this).parent().parent().parent().remove();
    });

    // save data
    $('#sbwc-pc-save-data').click(function (e) {
        e.preventDefault();

        // data titles
        var data_titles = [];
        $('.sbwc_pc_data_title').each(function (index, element) {
            data_titles.push($(this).val());
        });

        // data content
        var data_content = [];
        $('.sbwc_pc_data_content').each(function (index, element) {
            data_content.push($(this).val());
        });

        // send
        var data = {
            'action': 'sbwc_pc_save_pc_info',
            'titles': data_titles,
            'content': data_content,
            'prod_id': $('#post_ID').val()
        };
        $.post(ajaxurl, data, function (response) {
            alert(response);
            location.reload();
        });

    });

});