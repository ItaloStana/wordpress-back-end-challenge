jQuery(document).ready(function($) {
    $('.favoritar-button').on('click', function(e) {
        e.preventDefault();

        var post_id = $(this).data('post-id');
        var isFavoritado = $(this).hasClass('favoritado');

        var method = isFavoritado ? 'DELETE' : 'POST';

        $.ajax({
            url: '/wp-json/favoritar/v1/post/',
            method: method,
            data: { post_id: post_id },
            success: function(response) {
                if (method === 'POST') {
                    $(this).addClass('favoritado').text('Desfavoritar');
                } else {
                    $(this).removeClass('favoritado').text('Favoritar');
                }
            }.bind(this),
            error: function(response) {
                console.error('Erro:', response);
            }
        });
    });
});
