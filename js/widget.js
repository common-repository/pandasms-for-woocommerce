jQuery(document).ready(function (){

    jQuery('.sms_durum_sorgula').click(function(){

        jQuery(".pandasms-gonderim-sorgulama-sonuc").html("<p>SMS durum bilgisi sorgulanıyor...</p>");

        var gonderim_no = jQuery(this).attr("gonderim_no");

        var data = {
            'action':'pandasms_rapor_sorgula_callback',
            'gonderim_no':gonderim_no,
            'pandasms_rapor_sorgula_nonce':pandaAjax.pandasms_sms_detail_nonce
        };


        jQuery.post(pandaAjax.url, data, function(response){

            jQuery(".pandasms-gonderim-sorgulama-sonuc").html(response);

        });

    });

});

