import '../css/city.scss';

document.addEventListener('DOMContentLoaded', function() {
    var userRating = document.querySelector('.js-wind-direction');
    var windDirectionInDegrees = userRating.dataset.degrees - 90;

    $("#arrow-image").css({'transform': 'rotate(' + windDirectionInDegrees + 'deg)'});
});
