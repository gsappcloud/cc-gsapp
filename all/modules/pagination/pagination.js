$(function()    {
    var estimate = function()   {
        var words   =   parseInt($('#pagination-count').text() );
        var total   =   parseInt($('#edit-body').val().length );
        var pages   =   Math.ceil( total / (words * 6) );
        var page    =   (pages > 1) ? " pages" : " page";
        $('#pagination-guess').text(pages + page);
    }
    
    $('#edit-body').blur(estimate);
    $('#edit-body').focus(estimate);
    estimate();
});