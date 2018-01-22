<table class="table table-bordered" style="margin-top: 50px">
    <thead>
    <th>Category Name</th>
    <th>Category Type</th>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td></td>
        </tr>
    </tbody>
    <script type="application/javascript">
        jQuery(document).ready(function () {

            jQuery.ajax({
                url: "<?= base_url()?>api/v1/categories",
                type: "GET",
                contentType: 'application/json',
                success: function(resultData) {
                    //here is your json.
                    // process it

                },
                error : function(error) {
                },

                timeout: 120000
            });
        });

    </script>
</table>