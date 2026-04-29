$(document).ready(function () {
    var currentGfgStep, nextGfgStep, previousGfgStep;
    var opacity;

    var steps = $("fieldset").length;
    console.log("the number of steps", steps);

    setProgressBar(current);
    let small_device_view = $("div#small_device_view");

    if (screen.width < 768 || window.width < 768) {
        for (let index = 0; index < steps; index++) {
            list_contents.push($("#progressbar li").eq(index).html());
        }
        //remove all of li contents
        $("#progressbar li").html("");

        console.log("---------------------------->"+current);
        small_device_view.html(list_contents[current-1]);
    }

    $(".next-step").click(function () {
        /////////////////////////////////////////////////////////
        var issue_id = $(this).data('issue_id');
        var kpi_id = $(this).data('next_kpi_id');
        var url = "/admin/issues/" + issue_id;
        // url = url.replace(':id', issue_id);

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success mx-1',
                confirmButton: 'btn btn-success mx-1',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You need to be more aware of the effect of this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX request
                $.ajax({
                    url: url,
                    type: "PATCH",
                    data: {
                        'kpi_id': kpi_id
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log('success');
                        if (data.success) {
                            console.log('success');
                            console.log(data);
                            // $('#update_modal').modal('toggle');
                            // window.LaravelDataTables["users-table"].ajax.reload();
                            // toastr.success('You have successfuly updated a user.')
                        }
                    },
                    error: function (error) {
                        console.log('error');
                    }
                })


                currentGfgStep = $(this).parent();
                nextGfgStep = $(this).parent().next();
                let li_index = $("fieldset").index(nextGfgStep);

                //activate when pressed and for small device eject the content
                $("#progressbar li").eq(li_index).addClass("active");

                if (screen.width < 768 || window.width < 768) {
                    small_device_view.html(list_contents[li_index]);
                    console.log(li_index);
                }

                nextGfgStep.show();
                currentGfgStep.animate(
                    { opacity: 0 },
                    {
                        step: function (now) {
                            opacity = 1 - now;

                            currentGfgStep.css({
                                display: "none",
                                position: "relative",
                            });
                            nextGfgStep.css({ opacity: opacity });
                        },
                        duration: 500,
                    }
                );
                setProgressBar(++current);
                // swalWithBootstrapButtons.fire(
                //     'Updated!',
                //     'Your kpi has been updated.',
                //     'success'
                // )
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                // swalWithBootstrapButtons.fire(
                //     'Cancelled',
                //     "No change :)",
                //     'error'
                // )
            }
        })
        // ////////////////////////////////////////////////////////

    });

    $(".previous-step").click(function () {

        var issue_id = $(this).data('issue_id');
        var kpi_id = $(this).data('next_kpi_id');
        var url = "/admin/issues/" + issue_id;
        // url = url.replace(':id', issue_id);

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success mx-1',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You need to be more aware of the effect of this action",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX request
                $.ajax({
                    url: url,
                    type: "PATCH",
                    data: {
                        'kpi_id': kpi_id
                    },
                    dataType: 'json',
                    success: function (data) {
                        console.log('success');
                        if (data.success) {
                            console.log('success');
                            console.log(data);
                            // $('#update_modal').modal('toggle');
                            // window.LaravelDataTables["users-table"].ajax.reload();
                            // toastr.success('You have successfuly updated a user.')
                        }
                    },
                    error: function (error) {
                        console.log('error');
                    }
                })


                currentGfgStep = $(this).parent();
                previousGfgStep = $(this).parent().prev();

                let li_index = $("fieldset").index(currentGfgStep);

                $("#progressbar li").eq(li_index).removeClass("active");

                if (screen.width < 768 || window.width < 768) {
                    small_device_view.html(list_contents[li_index - 1]);
                    console.log(li_index);
                }

                if (li_index == 1) {
                    small_device_view.html(list_contents[0]);
                }
                previousGfgStep.show();

                currentGfgStep.animate(
                    { opacity: 0 },
                    {
                        step: function (now) {
                            opacity = 1 - now;

                            currentGfgStep.css({
                                display: "none",
                                position: "relative",
                            });
                            previousGfgStep.css({ opacity: opacity });
                        },
                        duration: 500,
                    }
                );
                setProgressBar(--current);

                // swalWithBootstrapButtons.fire(
                //     'Updated!',
                //     'Your kpi has been updated..',
                //     'success'
                // )
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                // swalWithBootstrapButtons.fire(
                //     'Cancelled',
                //     "Your imaginary file is safe :)",
                //     'error'
                // )
            }
        })
        ////////////////////////////////////////////////////////////////////
    });

    function setProgressBar(currentStep) {
        var percent = parseFloat(100 / steps) * current;
        percent = percent.toFixed();
        // console.log(percent, current, currentStep);
        $("#kpi_progress").css("width", percent + "%");
    }

    $(".submit").click(function () {
        return false;
    });
});
