<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Contact US Lists" parent="Contact Us" child="List" />
    <!-- /.content-header -->

    <!-- /.content-Main -->
    <!-- /.card-header -->
    <div class="card-body">
        {{ $dataTable->table(['class' => 'table table-bordered table-striped']) }}
    </div>

    <!-- /.card-body -->
    </div>
    <!-- /.card -->
    <!-- /#updateModal -->
    <x-partials.contactUs_modal />
    <!-- /#updateModal -->
    <!-- /.content -->
    <!-- Custom Js contents -->
    @push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        //delete Custom Exception
        function delete_exception(element, user_id) {
            var url = "{{ route('admin.contact-us.destroy', ':id') }}";
            url = url.replace(':id', user_id);
            console.log(url);

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success mx-1',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        data: {
                            user_id: user_id,
                        },
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            if (data.success) {
                                window.LaravelDataTables["contactUs-table"].ajax.reload();
                            }
                        },
                        error: function(error) {
                            if (error.status ==
                                422) { // when status code is 422, it's a validation issue

                            }
                            console.log('debug error here');
                        }
                    })
                    swalWithBootstrapButtons.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your imaginary file is safe :)',
                        'error'
                    )
                }
            })
        }
        if (@json(session('success_create'))) {

            toastr.success('You have successfuly replied.')
        }

        var contactusId; // Declare the variable to store contactus_id
        $(document).ready(function() {
            // Display contacts Detail in popup modal
            $('#contactUs-table').on('click', '#update_user', function() {
                var user_id = $(this).data('user_id');
                var url = "{{ route('admin.contact-us.show', ':id') }}";
                url = url.replace(':id', user_id);

                console.log("iiiiiiiiiiiii");
                console.log(user_id);

                // AJAX request to get contact details
                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: 'json',
                    success: function(response) {

                        // Store the contactus_id in the variable
                        contactusId = response.id;
                        // Display contact details in the modal
                        $('#contactus_id').html(response.contactus_id);
                        $('#message').html('<strong>Question:</strong> '+response.message+'<hr style="border: 2px solid #8a8ad0;">');

                        
                        var allReplies = response.messages;
                        $('#replied_message').html('')
                        for (let i = 0; i < allReplies.length; i++) {
                          
                            $('#replied_message').append('<strong>Reply '+(i+1)+'</strong>: <p>' + allReplies[i].message + '</p>');

                        }
 

                        $('#update_modal').modal('show');

                    }
                });
            });

            // Submit the form and send repliedMessage to the server
            $('#replay_form').on('submit', function(e) {
                e.preventDefault(); // Prevent the form from submitting normally

                // Get the value of the textarea
                var repliedMessage = $('#repliedMessage').val();
                var url = "{{ route('admin.contact-message.storeReply') }}"; // Use the store route
                // Send the data to the server using Ajax, including contactusId
                $.ajax({
                    url: url,
                    method: "POST",
                    data: {
                        repliedMessage: repliedMessage,
                        contactusId: contactusId,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#update_modal').modal('toggle');
                        $('#repliedMessage').val('')
                        // window.LaravelDataTables["contactUs-table"].ajax.reload();
                        toastr.success('You have successfuly replied.')
                    },
                    error: function(error) {
                        // Handle errors if any
                        console.error(error);
                    }
                });
            });
        });
    </script>

    @endpush
    <!-- Custom Js contents -->
</x-layout>