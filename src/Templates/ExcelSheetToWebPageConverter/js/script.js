//when the document loaded all the html and css
$(document).ready(function () {
    //when the submit button is pressed
    $('#excelSheetUploadForm').on('submit', function (e) {
        //stop the standard form from sending it to the server we want to do this manually
        e.preventDefault();
        //make a form data object
        const data = new FormData();
        //get the file from the form
        const files = $('#excelSheet')[0].files;
        //check if there is a file
        if (files.length > 0) {
            //put the file into the formdata object
            data.append('excelSheet', files[0])
            //use ajax to send data to the server
            $.ajax({
                url: '/',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                success: function (data) {
                    //todo: error handling
                    // if (data['error'])
                    // {
                    //     $('#excelSheet')
                    // }
                    $('body').html(data)
                }
            });
        }
    })
});