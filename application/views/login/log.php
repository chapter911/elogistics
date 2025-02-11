<div class="card mb-6">
    <div class="card-header header-elements">
        <h3 class="mb-0 me-2">Daftar Login</h3>
    </div>
    <div class="collapse show">
        <div class="row">
            <div class="card-datatable p-10 text-nowrap">
                <table id="mytable" class="table">
                    <thead>
                        <tr style="background-color: #008B8B">
                            <th style="text-align: center; color: white">NO</th>
                            <th style="text-align: center; color: white">USERNAME</th>
                            <th style="text-align: center; color: white">LOGIN DATE</th>
                            <th style="text-align: center; color: white">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($log as $d) { ?>
                        <tr>
                            <td style="text-align: center;"><?= $i . "."; ?></td>
                            <td style="text-align: center;"><?= html_escape($d->username); ?></td>
                            <td style="text-align: center;"><?= html_escape($d->login_date); ?></td>
                            <td style="text-align: center;">
                                <?= html_escape($d->is_logged_in) == 1 ? "<span class='badge bg-primary'>success</span>" : "<span class='badge bg-danger'>failed</span>" ;?>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
var table;

$(document).ready(function() {
    table = $("#mytable").DataTable({
        "scrollX": true,
        "fixedHeader": {
            "header": true,
            "headerOffset": $('.layout-navbar').height() + 15
        },
        "pageLength": 10,
    });
});
</script>