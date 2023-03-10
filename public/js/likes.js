$(document).ready(function () {

    $('i').click(function () {
        let publicationId = $(this).find('i');
        const recipeId = this.dataset.recipeId;
        $(this).addClass('clicked');
        if ($(this).hasClass('liked')) {
            $(this).removeClass('liked').addClass('not-liked');
        } else {
            $(this).removeClass('not-liked').addClass('liked');
        }

        $.ajax({
            url: 'http://127.0.0.1:8000/api/likes/recipes/' + recipeId,
            type: 'post',
            success: function (data) {
                console.log(data)
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    });

    $('.recipeImg').hover(function() {
        $(this).find('i').css('display', 'flex');
    }, function() {
        $(this).find('i').css('display', 'none');
        $(this).find('.fa-solid.fa-heart').removeClass('clicked');
    });
});