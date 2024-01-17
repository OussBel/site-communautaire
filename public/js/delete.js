jQuery(document).ready(function(){
    var wrapper = $('.tags');

    wrapper.on('click','.js-remove-item', function(e){
        e.preventDefault()
        console.log('delete')

        $(this).closest('.js-item').remove()

    })
});