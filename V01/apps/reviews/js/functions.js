$(document).ready(function() {
    $(".edit-btn").on("click", function() {
        var $row = $(this).closest("tr");
        var $showCell = $row.find("td.show-cell");
        var currentValue = $showCell.data("value") == 1 ? "1" : "0";

        $showCell.html(
            '<select class="form-control show-select">' +
                '<option value="1"' + (currentValue === "1" ? " selected" : "") + '>Si</option>' +
                '<option value="0"' + (currentValue === "0" ? " selected" : "") + '>No</option>' +
            '</select>'
        );

        $(this).hide();
        $row.find(".save-btn").show();
    });

    $(".save-btn").on("click", function() {
        var $row = $(this).closest("tr");
        var $showCell = $row.find("td.show-cell");
        var reviewId = $row.data("review-id");
        var newValue = $showCell.find("select.show-select").val();

        $.ajax({
            url: BASE_URL + '/V01/apps/reviews/proccess/actions.php',
            type: 'POST',
            data: {
                ac: 'save',
                id: reviewId,
                show_review: newValue
            },
            success: function(response) {
                var showValue = (newValue === "1") ? 1 : 0;
                $showCell.attr("data-value", showValue);
                $showCell.html(
                    '<span class="badge ' + (showValue === 1 ? 'badge-success' : 'badge-danger') + '">' +
                        (showValue === 1 ? 'Si' : 'No') +
                    '</span>'
                );
            },
            error: function() {
                alert('No se pudo guardar el cambio. Intente de nuevo.');
            }
        });

        $(this).hide();
        $row.find(".edit-btn").show();
    });
});
