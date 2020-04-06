$('.carousel-item').first().addClass('active');

$('.datepicker').datepicker({
    format: 'yyyy/mm/dd',
    startDate: '-3d'
});

$('.file-upload').file_upload();