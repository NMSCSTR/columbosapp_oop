document.addEventListener('DOMContentLoaded', function() {
    // Handle Copy functionality
    document.querySelectorAll('.copy-announcement').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const announcementDiv = this.closest('.flex-1');
            const content = announcementDiv.querySelector('p').textContent.split(':')[1].trim(); // Get only the content after the colon
            
            // Create a temporary textarea to copy the text
            const textarea = document.createElement('textarea');
            textarea.value = content;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);

            // Show SweetAlert notification
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Announcement content has been copied to clipboard',
                timer: 1500,
                showConfirmButton: false,
                position: 'top-end',
                toast: true
            });
        });
    });

    // Handle Delete functionality
    document.querySelectorAll('.delete-announcement').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const announcementId = this.getAttribute('data-id');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this announcement?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `../../controllers/adminController/deleteAnnouncement.php?id=${announcementId}`;
                }
            });
        });
    });
}); 