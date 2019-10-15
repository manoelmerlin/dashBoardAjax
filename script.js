$(document).ready(() => {

	$('#documentacao').on('click', () => {
     //  $('#pagina').load('documentacao.html')

        /*  $.get('documentacao.html', data => {
            $('#pagina').html(data)
        })*/

        $.post('documentacao.html', data => {
            $('#pagina').html(data)
        })

    })

    /*
    $('#suporte').on('click', () => {
       $('#pagina').load('suporte.html')
    })

    $.get('documentacao.html', data => {
        $('#pagina').html(data)
    })*/


    $('#competencia').on('change', e => {

        let competencia = $(e.target).val()
        console.log(competencia)


        $.ajax({
            type: 'GET',
            url: 'app.php',
            data: `competencia=${competencia}`,
            dataType: 'json',
            success: dados => {
                $('#numeroVendas').html(dados.numeroDeVendas)
                $('#totalVendas').html(dados.totalDeVendas)

            },
            error:  erro => {console.log(erro)}

        })
    })

})