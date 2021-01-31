$(document).ready(function () {
    $('#excelSheetUploadForm').on('submit', function (e) {
        e.preventDefault();
        const data = new FormData();
        const files = $('#excelSheet')[0].files;
        if (files.length > 0) {
            data.append('excelSheet', files[0])
            $.ajax({
                url: '/',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                success: function (data) {
                    if (data['error'])
                    {
                        $('#excelSheet')
                    }
                    $('body').html(data)
                }
            });
        }
    })
});