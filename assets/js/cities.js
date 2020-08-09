import '../css/city.scss';

document.addEventListener('DOMContentLoaded', function() {
    var id, userRating;

    id = 1;

    while (userRating = document.querySelector('.js-wind-direction-' + id)) {
        var windDirectionInDegrees = userRating.dataset.degrees - 90;

        $("#arrow-image-" + id).css({'transform': 'rotate(' + windDirectionInDegrees + 'deg)'});
        id++;
    }
});
