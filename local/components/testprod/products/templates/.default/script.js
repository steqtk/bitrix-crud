jQuery(document).ready(function () {
    jQuery('#send').click(function (e) {
        e.preventDefault();
        jQuery.ajax({
            url: "/local/components/testprod/products/ajax.php",
            type: 'post',
            dataType: 'html',
            data: $('#form').serialize(),
            success: function(result){
                $('#product').html(result);
                $("#form")[0].reset();
            }
        });
    });
});
