
$(document).ready(function() {
    $('#commentForm').on('submit', function(e) {
        e.preventDefault();

        var commentContent = $('#comment_content').val()
        var slug = $("input[name='slug']").val()

        $.ajax({
            url: '/figure/'.slug, // Replace this with your Symfony route for adding comments
            type: 'POST',
            data: { content: commentContent }, // Send the comment content as data
            success: function(newComment) {
                // Assuming the server responds with the newly created comment data in JSON format

                // Update the UI by appending the new comment
                $('#commentsContainer').append('<div>' + newComment.content + '</div>');

                // Clear the input field after adding the comment
                $('#commentContent').val('');
            },
            error: function(error) {
                // Handle error if adding the comment fails
            }
        });
    })
})