<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Notifications" parent="Notifications" child="List"  />

    @foreach ($notificationsList as $notification)
    <div class="card mb-2 mx-1 mx-md-4" >
        <div class="card-body ">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="fas fa-bell text-primary mr-3" style="font-size: 1.5rem;"></i> {{-- Add your notification icon --}}
                    <div>
                        <h5 class="card-title ">{{ $notification->title }}</h5>
                        <p class="card-text">
                            {{ $notification->created_at->diffForHumans() }} {{-- Display time difference --}}
                        </p>
                    </div>
                </div>
                <a class='btn btn-sm mr-2 mr-md-5' onclick="delete_row(this, '{{ $notification->id }}')" role="button">
                    <i class='text-danger fas fa-trash'></i></a>

            </div>
        </div>
    </div>
@endforeach

@push("scripts")

<script>
 function delete_row(element, row_id) {
            var url = "{{ route('admin.notification.destroy', ':id') }}";
            url = url.replace(':id', row_id);
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
                            row_id: row_id,
                        },
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            if (data.success) {
                               // rferesh  this page
                               var deletedCard = $(element).closest('.card');
        deletedCard.fadeOut(300, function() {
            // Remove the element after fade out animation
            deletedCard.remove();
        });
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
</script>


@endpush


</x-layout>
